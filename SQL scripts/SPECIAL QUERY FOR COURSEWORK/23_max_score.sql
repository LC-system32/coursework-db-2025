-- Найкращий результат
SELECT * FROM submissions WHERE score = (SELECT MAX(score) FROM submissions);
