CREATE OR REPLACE FUNCTION public.get_submissions_analytics()
 RETURNS jsonb
 LANGUAGE sql
AS $function$
SELECT jsonb_build_object(
  'general', (
    SELECT row_to_json(t)
    FROM (
      SELECT
        (SELECT COUNT(*)            FROM participants)                                 AS participants_count,
        (SELECT COUNT(*)            FROM submissions)                                 AS attempts_count,
        COALESCE((SELECT AVG(score) FROM submissions), 0)::numeric(5,2)               AS average_score,
        COALESCE((SELECT MAX(score) FROM submissions), 0)                             AS max_score
    ) AS t
  ),
  'languages', (
    SELECT jsonb_agg(row_to_json(t))
    FROM (
      SELECT l.name   AS language,
             COUNT(*) AS total
        FROM submissions s
        JOIN languages l ON s.language_id = l.id
       GROUP BY l.name
       ORDER BY total DESC
    ) AS t
  ),
  'classes', (
    SELECT jsonb_agg(row_to_json(t))
    FROM (
      SELECT c.grade            AS class,
             COUNT(DISTINCT p.id) AS total
        FROM participants p
        JOIN classes c ON p.class_id = c.id
       GROUP BY c.grade
       ORDER BY total DESC
    ) AS t
  ),
  'dynamics', (
    SELECT jsonb_agg(row_to_json(t))
    FROM (
      SELECT submitted_at AS submission_date,
             COUNT(*)      AS total
        FROM submissions
       GROUP BY submitted_at
       ORDER BY submitted_at
    ) AS t
  ),
'top_participants', (
    SELECT jsonb_agg(row_to_json(t))
    FROM (
      SELECT p.full_name,
             MAX(s.score) AS max_score
        FROM submissions s
        JOIN participants p ON s.participant_id = p.id
       GROUP BY p.full_name
       HAVING MAX(s.score) > 0  -- Вибираємо учасників з максимальним балом більше 50
       ORDER BY max_score DESC
       LIMIT 5
    ) AS t
  )
);
$function$
