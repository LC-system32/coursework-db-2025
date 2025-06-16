-- Результати учасників
SELECT s.id, s.score, p.full_name FROM submissions s
INNER JOIN participants p ON s.participant_id = p.id;
