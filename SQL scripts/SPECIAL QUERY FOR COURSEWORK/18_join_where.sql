-- Тести, в яких отримано ≥90 балів
SELECT t.name, s.score FROM tests t
INNER JOIN submissions s ON t.id = s.test_id
WHERE s.score >= 90;
