CREATE OR REPLACE FUNCTION public.validate_submission()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
BEGIN
    
    IF NEW.participant_id IS NULL
       OR NEW.test_id        IS NULL
       OR NEW.language_id    IS NULL
       OR NEW.score          IS NULL
       OR NEW.code_text      IS NULL
       OR NEW.submitted_at   IS NULL
    THEN
        RAISE EXCEPTION 'Усі поля обов’язкові';
    END IF;

    
    IF NEW.score < 0 OR NEW.score > 100 THEN
        RAISE EXCEPTION 'score повинен бути в межах від 0 до 100';
    END IF;

    
    PERFORM 1 FROM participants WHERE id = NEW.participant_id;
    IF NOT FOUND THEN
        RAISE EXCEPTION 'participant_id % не існує', NEW.participant_id;
    END IF;
    PERFORM 1 FROM tests WHERE id = NEW.test_id;
    IF NOT FOUND THEN
        RAISE EXCEPTION 'test_id % не існує', NEW.test_id;
    END IF;
    PERFORM 1 FROM languages WHERE id = NEW.language_id;
    IF NOT FOUND THEN
        RAISE EXCEPTION 'language_id % не існує', NEW.language_id;
    END IF;

    
    IF EXISTS (
        SELECT 1
          FROM submissions s
         WHERE s.participant_id = NEW.participant_id
           AND s.test_id        = NEW.test_id
           AND s.language_id    = NEW.language_id
           AND s.submitted_at   = NEW.submitted_at
           AND (TG_OP = 'INSERT' OR s.id <> NEW.id)
    ) THEN
        RAISE EXCEPTION 'Спроба з такими даними вже існує';
    END IF;

    RETURN NEW;
END;
$function$
