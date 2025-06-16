CREATE OR REPLACE FUNCTION public.filter_submissions(p_full_name text, p_language text, p_score_min integer, p_score_max integer, p_date_from date, p_date_to date, p_has_score text)
 RETURNS TABLE(id integer, participant text, language text, score integer, submitted_at text)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY
    SELECT
        sv.id,
        sv.participant,
        sv.language::TEXT,
        sv.score,

        TO_CHAR(sv.submitted_at::DATE, 'YYYY-MM-DD')::TEXT AS submitted_at
    FROM public.submissions_view sv
    WHERE
        (p_full_name IS NULL OR sv.participant ILIKE '%' || p_full_name || '%') AND
        (p_language IS NULL OR sv.language = p_language) AND
        (p_score_min IS NULL OR sv.score >= p_score_min) AND
        (p_score_max IS NULL OR sv.score <= p_score_max) AND
        (
            p_date_from IS NULL OR
            p_date_to IS NULL OR
            sv.submitted_at::DATE BETWEEN p_date_from AND p_date_to
        ) AND
        (
            p_has_score IS NULL OR
            (p_has_score = 'yes' AND sv.score IS NOT NULL) OR
            (p_has_score = 'no' AND sv.score IS NULL)
        );
END;
$function$
