CREATE OR REPLACE FUNCTION public.get_teachers_analytics()
 RETURNS jsonb
 LANGUAGE sql
AS $function$
SELECT jsonb_build_object(
  'general', (
    SELECT row_to_json(t)
    FROM (
      SELECT
        COUNT(*) AS total_teachers,
        ROUND(AVG(p_count), 2) AS avg_participants_per_teacher
      FROM (
        SELECT COUNT(*) AS p_count
        FROM participants
        WHERE teacher_id IS NOT NULL
        GROUP BY teacher_id
      ) sub
    ) t
  ),

  'average_score_all_teachers', (
    SELECT ROUND(AVG(score), 2)
    FROM (
      SELECT AVG(s.score) AS score
      FROM teachers t
      JOIN participants p ON p.teacher_id = t.id
      JOIN submissions s ON s.participant_id = p.id
      GROUP BY t.id
    ) avg_scores
  ),

  'top_teachers_avg_score', (
    SELECT jsonb_agg(row_to_json(tt))
    FROM (
      SELECT
        t.full_name,
        ROUND(AVG(s.score), 2) AS avg_score
      FROM teachers t
      JOIN participants p ON p.teacher_id = t.id
      JOIN submissions s ON s.participant_id = p.id
      GROUP BY t.full_name
      ORDER BY avg_score DESC
      LIMIT 5
    ) tt
  ),

  'best_teacher', (
    SELECT row_to_json(bt)
    FROM (
      SELECT
        t.full_name,
        ROUND(AVG(s.score), 2) AS avg_score
      FROM teachers t
      JOIN participants p ON p.teacher_id = t.id
      JOIN submissions s ON s.participant_id = p.id
      GROUP BY t.id, t.full_name
      ORDER BY avg_score DESC
      LIMIT 1
    ) bt
  ),

  'teacher_activity_over_time', (
    SELECT jsonb_agg(row_to_json(ta))
    FROM (
      SELECT
        DATE_TRUNC('day', s.submitted_at)::date AS date,
        p.teacher_id,
        COUNT(*) AS submission_count,
        COUNT(DISTINCT s.participant_id) AS unique_students
      FROM submissions s
      JOIN participants p ON p.id = s.participant_id
      WHERE p.teacher_id IS NOT NULL
      GROUP BY DATE_TRUNC('day', s.submitted_at), p.teacher_id
      ORDER BY date, p.teacher_id
    ) ta
  )
);
$function$
