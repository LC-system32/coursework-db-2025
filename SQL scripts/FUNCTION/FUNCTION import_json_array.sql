CREATE OR REPLACE FUNCTION public.import_json_array(target_table text, data jsonb)
 RETURNS void
 LANGUAGE plpgsql
AS $function$
DECLARE
    elem           jsonb;
    -- загальні змінні
    cols           text;
    vals           text;
    dyn_sql        text;
    -- для participants
    v_full_name    text;
    v_school       text;
    v_class        text;
    v_teacher      text;
    v_school_id    integer;
    v_class_id     integer;
    v_teacher_id   integer;
    -- для teachers
    v_teacher_name text;
    -- для submissions
    v_participant  text;
    v_test         text;
    v_language     text;
    v_score        integer;
    v_submitted    text;
    v_participant_id integer;
    v_test_id      integer;
    v_language_id  integer;
    v_verdict_code text;
    v_verdict_id   integer;
BEGIN
  FOR elem IN SELECT * FROM jsonb_array_elements(data) LOOP

    IF target_table = 'participants' THEN
      v_full_name := elem ->> 'full_name';
      v_school    := elem ->> 'school';
      v_class     := elem ->> 'class';
      v_teacher   := elem ->> 'teacher';

      SELECT id INTO v_school_id  FROM schools  WHERE name       = v_school    LIMIT 1;
      SELECT id INTO v_class_id   FROM classes  WHERE grade      = v_class     LIMIT 1;
      SELECT id INTO v_teacher_id FROM teachers WHERE full_name  = v_teacher   LIMIT 1;
      IF v_school_id IS NULL OR v_class_id IS NULL OR v_teacher_id IS NULL THEN
        RAISE EXCEPTION 'Невірні дані для participants: % / % / %', v_school, v_class, v_teacher;
      END IF;

      INSERT INTO participants(full_name, school_id, class_id, teacher_id)
      VALUES (v_full_name, v_school_id, v_class_id, v_teacher_id);

    ELSIF target_table = 'teachers' THEN
      v_teacher_name := elem ->> 'full_name';
      v_school       := elem ->> 'school';

      SELECT id INTO v_school_id FROM schools WHERE name = v_school LIMIT 1;
      IF v_school_id IS NULL THEN
        RAISE EXCEPTION 'Невірна школа для teachers: %', v_school;
      END IF;

      INSERT INTO teachers(full_name, school_id)
      VALUES (v_teacher_name, v_school_id);

    ELSIF target_table = 'submissions' THEN
      v_participant := elem ->> 'participant';
      v_test        := elem ->> 'test';
      v_language    := elem ->> 'language';
      v_score       := (elem ->> 'score')::integer;
      v_submitted   := elem ->> 'submitted_at';
      v_verdict_code:= elem ->> 'verdict';

      SELECT id INTO v_participant_id FROM participants WHERE full_name = v_participant LIMIT 1;
      SELECT id INTO v_test_id        FROM tests        WHERE name      = v_test        LIMIT 1;
      SELECT id INTO v_language_id    FROM languages    WHERE name      = v_language    LIMIT 1;
      SELECT id INTO v_verdict_id     FROM verdicts     WHERE code      = v_verdict_code LIMIT 1;
      IF v_participant_id IS NULL OR v_test_id IS NULL OR v_language_id IS NULL THEN
        RAISE EXCEPTION 'Невірні дані для submissions: % / % / %', v_participant, v_test, v_language;
      END IF;

      INSERT INTO submissions(
        participant_id,
        test_id,
        language_id,
        score,
        submitted_at,
        verdict_id
      )
      VALUES (
        v_participant_id,
        v_test_id,
        v_language_id,
        v_score,
        TO_DATE(v_submitted, 'DD.MM.YYYY'),
        v_verdict_id
      );

    ELSE
      -- для інших таблиць формуємо динамічний INSERT
      cols := (
        SELECT string_agg(quote_ident(k), ', ')
        FROM jsonb_object_keys(elem) AS k
      );
      vals := (
        SELECT string_agg(quote_literal(elem ->> k), ', ')
        FROM jsonb_object_keys(elem) AS k
      );
      dyn_sql := format('INSERT INTO %I (%s) VALUES (%s)', target_table, cols, vals);
      EXECUTE dyn_sql;
    END IF;

  END LOOP;
END;
$function$
