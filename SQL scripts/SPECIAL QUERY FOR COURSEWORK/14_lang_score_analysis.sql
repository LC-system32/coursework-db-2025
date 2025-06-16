-- Аналіз результатів по мовах програмування
SELECT language_id, AVG(score) AS avg_score FROM submissions
WHERE score >= 50
GROUP BY language_id
HAVING AVG(score) > 60
ORDER BY avg_score DESC;
