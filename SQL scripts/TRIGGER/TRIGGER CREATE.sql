CREATE TRIGGER trg_validate_schools
BEFORE INSERT OR UPDATE ON schools
FOR EACH ROW EXECUTE FUNCTION validate_schools();

CREATE TRIGGER trg_validate_classes
BEFORE INSERT OR UPDATE ON classes
FOR EACH ROW EXECUTE FUNCTION validate_classes();

CREATE TRIGGER trg_validate_languages
BEFORE INSERT OR UPDATE ON languages
FOR EACH ROW EXECUTE FUNCTION validate_languages();

CREATE TRIGGER trg_validate_tests
BEFORE INSERT OR UPDATE ON tests
FOR EACH ROW EXECUTE FUNCTION validate_tests();

CREATE TRIGGER trg_validate_verdicts
BEFORE INSERT OR UPDATE ON verdicts
FOR EACH ROW EXECUTE FUNCTION validate_verdicts();

CREATE TRIGGER trg_validate_teachers
BEFORE INSERT OR UPDATE ON teachers
FOR EACH ROW EXECUTE FUNCTION validate_teachers();

CREATE TRIGGER trg_validate_participants
BEFORE INSERT OR UPDATE ON participants
FOR EACH ROW EXECUTE FUNCTION validate_participants();

CREATE TRIGGER trg_validate_submissions
BEFORE INSERT OR UPDATE ON submissions
FOR EACH ROW EXECUTE FUNCTION validate_submissions();