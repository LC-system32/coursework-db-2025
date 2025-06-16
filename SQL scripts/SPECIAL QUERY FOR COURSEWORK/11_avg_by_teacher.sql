-- Середній бал по кожному вчителю
SELECT teacher_id, AVG(score) FROM submissions GROUP BY teacher_id;
