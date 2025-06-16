-- Школи з великою кількістю учасників
SELECT sch.name, COUNT(p.id) FROM schools sch
INNER JOIN participants p ON sch.id = p.school_id
GROUP BY sch.name
HAVING COUNT(p.id) > 5;
