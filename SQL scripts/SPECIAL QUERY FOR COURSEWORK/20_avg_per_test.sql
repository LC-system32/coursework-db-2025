-- Середній бал по тестах
SELECT t.name, AVG(s.score) FROM tests t
INNER JOIN submissions s ON t.id = s.test_id
GROUP BY t.name;
