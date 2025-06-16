-- Учасники з балами вище середнього
SELECT full_name FROM participants
WHERE id IN (
  SELECT participant_id FROM submissions 
  WHERE score > (SELECT AVG(score) FROM submissions)
);
