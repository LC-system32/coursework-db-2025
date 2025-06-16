CREATE OR REPLACE FUNCTION public.get_submission_by_id(p_id integer)
 RETURNS TABLE(participant_full_name text, test_name text, test_description text, submission_language character varying, submission_code text, submission_score integer, submission_submitted_at text, verdict_code text, verdict_description text)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY
    SELECT
        p.full_name,                                                      -- TEXT
        t.name,                                                           -- TEXT
        t.description,                                                    -- TEXT
        l.name,                                                           -- VARCHAR(20)
        s.code_text,                                                      -- TEXT
        s.score ,                                            -- NUMERIC(5,2)
        TO_CHAR(s.submitted_at, 'DD.MM.YYYY')::TEXT,               -- TEXT
        COALESCE(v.code, '')::TEXT,                                        -- TEXT
        COALESCE(v.description, '')::TEXT                                  -- TEXT
    FROM submissions s
    LEFT JOIN participants p ON s.participant_id = p.id
    LEFT JOIN tests        t ON s.test_id        = t.id
    LEFT JOIN languages    l ON s.language_id    = l.id
    LEFT JOIN verdicts     v ON s.verdict_id     = v.id
    WHERE s.id = p_id;
END;
$function$
