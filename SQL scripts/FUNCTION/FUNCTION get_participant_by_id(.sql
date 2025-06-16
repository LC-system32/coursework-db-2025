CREATE OR REPLACE FUNCTION public.get_participant_by_id(p_id integer)
 RETURNS TABLE(participant_name text, school text, class character varying, teacher_name text, submission_id integer, test_name text, language character varying, score integer, submitted_at text, verdict_code character varying, verdict_description text)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY
    SELECT 
        p.full_name AS participant_name,
        s.name AS school,
        c.grade AS class,
        t.full_name AS teacher_name,
        sub.id AS submission_id,
        tst.name AS test_name,
        l.name AS language,
        sub.score,
        TO_CHAR(sub.submitted_at, 'DD.MM.YYYY') AS submitted_at,
        v.code AS verdict_code,
        v.description AS verdict_description
    FROM participants p
    LEFT JOIN schools s ON p.school_id = s.id
    LEFT JOIN classes c ON p.class_id = c.id
    LEFT JOIN teachers t ON p.teacher_id = t.id
    INNER JOIN submissions sub ON p.id = sub.participant_id
    INNER JOIN tests tst ON sub.test_id = tst.id
    LEFT JOIN languages l ON sub.language_id = l.id
    LEFT JOIN verdicts v ON sub.verdict_id = v.id
    WHERE p.id = p_id;
END;
$function$
