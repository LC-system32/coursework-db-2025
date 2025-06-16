-- Тести учасників із балами < 60
SELECT p.full_name, t.name FROM participants p
JOIN submissions s ON p.id = s.participant_id
JOIN tests t ON s.test_id = t.id
WHERE p.id IN (
  SELECT participant_id FROM submissions WHERE score < 60
);
