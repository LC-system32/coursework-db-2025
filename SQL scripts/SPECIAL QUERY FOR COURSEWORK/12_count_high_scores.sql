-- Кількість високих балів (≥90) по кожному тесту
SELECT test_id, COUNT(*) FROM submissions WHERE score >= 90 GROUP BY test_id;
