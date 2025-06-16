-- ----------------------------------------------------------------------------
-- 1. Довідкові таблиці
-- ----------------------------------------------------------------------------

CREATE TABLE schools (
  id   SERIAL PRIMARY KEY,
  name TEXT NOT NULL UNIQUE
);

CREATE TABLE classes (
  id    SERIAL PRIMARY KEY,
  grade VARCHAR(10) NOT NULL UNIQUE
);

CREATE TABLE languages (
  id   SERIAL PRIMARY KEY,
  name VARCHAR(20) NOT NULL UNIQUE,
);

CREATE TABLE tests (
  id          SERIAL PRIMARY KEY,
  name        TEXT    NOT NULL UNIQUE,
  description TEXT NOT NULL,
  languages_id  INTEGER REFERENCES languages(id) ON DELETE SET NULL
);

CREATE TABLE verdicts (
  id          SERIAL PRIMARY KEY,
  code        VARCHAR(10) NOT NULL UNIQUE,
  description TEXT        NOT NULL
);

-- ----------------------------------------------------------------------------
-- 2. Основні сутності з ON DELETE
-- ----------------------------------------------------------------------------

CREATE TABLE teachers (
  id         SERIAL PRIMARY KEY,
  full_name  TEXT    NOT NULL,
  school_id  INTEGER REFERENCES schools(id) ON DELETE SET NULL,
  CONSTRAINT uq_teachers_name_school UNIQUE(full_name, school_id)
);

CREATE TABLE participants (
  id            SERIAL PRIMARY KEY,
  full_name     TEXT    NOT NULL,
  class_id      INTEGER REFERENCES classes(id)   ON DELETE SET NULL,
  school_id     INTEGER REFERENCES schools(id)   ON DELETE SET NULL,
  teacher_id    INTEGER REFERENCES teachers(id)  ON DELETE SET NULL,
  CONSTRAINT uq_participants_name_school_class UNIQUE(full_name, school_id, class_id)
);

CREATE TABLE submissions (
  id             SERIAL PRIMARY KEY,
  participant_id INTEGER NOT NULL REFERENCES participants(id) ON DELETE CASCADE,
  test_id        INTEGER NOT NULL REFERENCES tests(id)        ON DELETE CASCADE,
  language_id    INTEGER NOT NULL REFERENCES languages(id)    ON DELETE SET NULL,
  score          INTEGER NOT NULL CHECK (score >= 0),
  code_text      TEXT        NOT NULL,
  submitted_at   DATE NOT NULL DEFAULT '2001-01-01',
  verdict_id     SMALLINT   REFERENCES verdicts(id)           ON DELETE SET NULL,
  CONSTRAINT uq_submissions_unique_run UNIQUE(participant_id, test_id, language_id, submitted_at)
);

-- ----------------------------------------------------------------------------
-- 3. Індекси
-- ----------------------------------------------------------------------------

CREATE INDEX idx_participants_school     ON participants(school_id);
CREATE INDEX idx_participants_class      ON participants(class_id);
CREATE INDEX idx_participants_teacher    ON participants(teacher_id);
CREATE INDEX idx_submissions_participant ON submissions(participant_id);
CREATE INDEX idx_submissions_test        ON submissions(test_id);
CREATE INDEX idx_submissions_language    ON submissions(language_id);
CREATE INDEX idx_submissions_verdict     ON submissions(verdict_id);
