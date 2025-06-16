-- Усі учасники, навіть без результатів
SELECT p.full_name, s.score FROM submissions s
RIGHT JOIN participants p ON s.participant_id = p.id;
