-- Результати, вищі за деякі з певної мови
SELECT * FROM submissions
WHERE score > ANY (
  SELECT score FROM submissions WHERE language_id = 1
);
