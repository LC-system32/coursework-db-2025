CREATE OR REPLACE FUNCTION public.get_participants_analytics()
 RETURNS jsonb
 LANGUAGE sql
AS $function$
SELECT jsonb_build_object(
  -- 1. Загальна кількість учасників
  'general', (
    SELECT row_to_json(g)
    FROM (
      SELECT
        COUNT(*) AS total_participants
      FROM participants
    ) g
  ),

  -- 2. Top-5 учасників за середнім балом
  'top5_by_avg_score', (
    SELECT jsonb_agg(p)
    FROM (
      SELECT
        p.full_name,
        ROUND(AVG(s.score), 2) AS avg_score
      FROM participants p
      JOIN submissions s ON s.participant_id = p.id
      GROUP BY p.id, p.full_name
      ORDER BY avg_score DESC
      LIMIT 5
    ) p
  ),

  -- 3. Top-5 учасників за максимальним балом
  'top5_by_max_score', (
    SELECT jsonb_agg(p)
    FROM (
      SELECT
        p.full_name,
        MAX(s.score) AS max_score
      FROM participants p
      JOIN submissions s ON s.participant_id = p.id
      GROUP BY p.id, p.full_name
      ORDER BY max_score DESC
      LIMIT 5
    ) p
  ),

  -- 4. Найпопулярніша мова програмування серед учасників
  'most_popular_language', (
    SELECT row_to_json(l)
    FROM (
      SELECT
        lg.name AS language,
        COUNT(*) AS uses
      FROM submissions s
      JOIN languages lg ON lg.id = s.language_id
      GROUP BY lg.name
      ORDER BY uses DESC
      LIMIT 1
    ) l
  )

);
$function$
