-- Тести з середнім балом вище 80
SELECT test_id, AVG(score) FROM submissions GROUP BY test_id HAVING AVG(score) > 80;
