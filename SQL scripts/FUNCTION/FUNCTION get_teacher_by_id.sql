CREATE OR REPLACE FUNCTION public.get_teacher_by_id(t_id integer)
 RETURNS TABLE(submission_id integer, participant_full_name text, participant_class text, submission_language text, submission_score integer, submission_submitted_at text, teacher_full_name text, teacher_school text)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY
    SELECT 
        s.id,
        p.full_name,
        c.grade::text,
        l.name::text,
        s.score,
        TO_CHAR(s.submitted_at, 'DD.MM.YYYY'),
        t.full_name,
        sch.name::text
    FROM submissions s
    INNER JOIN participants p ON s.participant_id = p.id
    INNER JOIN teachers t ON p.teacher_id = t.id
    LEFT JOIN classes c ON p.class_id = c.id
    LEFT JOIN schools sch ON t.school_id = sch.id
    LEFT JOIN languages l ON s.language_id = l.id
    WHERE p.teacher_id = t_id;
END;
$function$
