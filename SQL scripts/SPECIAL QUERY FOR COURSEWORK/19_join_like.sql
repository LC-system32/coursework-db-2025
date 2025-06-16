-- Тести учасників на ім’я Іван
SELECT p.full_name, t.name FROM participants p
INNER JOIN submissions s ON p.id = s.participant_id
INNER JOIN tests t ON s.test_id = t.id
WHERE p.full_name LIKE 'Іван%';
