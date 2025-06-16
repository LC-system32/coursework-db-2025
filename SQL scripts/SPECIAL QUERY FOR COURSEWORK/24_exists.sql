-- Учасники з хоча б одним результатом
SELECT * FROM participants p
WHERE EXISTS (SELECT 1 FROM submissions s WHERE s.participant_id = p.id);
