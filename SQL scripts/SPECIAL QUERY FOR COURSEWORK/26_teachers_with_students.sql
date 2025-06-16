-- Вчителі, які мають учнів
SELECT * FROM teachers WHERE id IN (SELECT teacher_id FROM participants);
