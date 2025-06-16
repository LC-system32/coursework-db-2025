CREATE OR REPLACE FUNCTION validate_schools()
RETURNS TRIGGER AS $$
BEGIN
  IF TRIM(NEW.name) IS NULL OR TRIM(NEW.name) = '' THEN
    RAISE EXCEPTION 'School name cannot be empty';
  END IF;
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION validate_classes()
RETURNS TRIGGER AS $$
BEGIN
  IF TRIM(NEW.grade) IS NULL OR TRIM(NEW.grade) = '' THEN
    RAISE EXCEPTION 'Class grade cannot be empty';
  END IF;

  IF LENGTH(NEW.grade) > 10 THEN
    RAISE EXCEPTION 'Class grade too long';
  END IF;

  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION validate_languages()
RETURNS TRIGGER AS $$
BEGIN
  IF TRIM(NEW.name) IS NULL OR TRIM(NEW.name) = '' THEN
    RAISE EXCEPTION 'Language name cannot be empty';
  END IF;

  IF LENGTH(NEW.name) > 20 THEN
    RAISE EXCEPTION 'Language name too long';
  END IF;

  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION validate_tests()
RETURNS TRIGGER AS $$
BEGIN
  IF TRIM(NEW.name) IS NULL OR TRIM(NEW.name) = '' THEN
    RAISE EXCEPTION 'Test name cannot be empty';
  END IF;

  IF TRIM(NEW.description) IS NULL OR TRIM(NEW.description) = '' THEN
    RAISE EXCEPTION 'Test description cannot be empty';
  END IF;

  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION validate_verdicts()
RETURNS TRIGGER AS $$
BEGIN
  IF TRIM(NEW.code) IS NULL OR TRIM(NEW.code) = '' THEN
    RAISE EXCEPTION 'Verdict code cannot be empty';
  END IF;

  IF LENGTH(NEW.code) > 10 THEN
    RAISE EXCEPTION 'Verdict code too long';
  END IF;

  IF TRIM(NEW.description) IS NULL OR TRIM(NEW.description) = '' THEN
    RAISE EXCEPTION 'Verdict description cannot be empty';
  END IF;

  RETURN NEW;
END;
$$ LANGUAGE plpgsql;