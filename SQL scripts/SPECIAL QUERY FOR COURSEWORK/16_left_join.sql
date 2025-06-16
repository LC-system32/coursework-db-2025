-- Усі вчителі, навіть без учнів чи результатів
SELECT t.full_name, s.score FROM teachers t
LEFT JOIN participants p ON t.id = p.teacher_id
LEFT JOIN submissions s ON p.id = s.participant_id;
