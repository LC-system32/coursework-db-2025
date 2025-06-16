CREATE OR REPLACE FUNCTION public.validate_participant()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
BEGIN
    
    IF NEW.full_name    IS NULL
       OR NEW.school_id  IS NULL
       OR NEW.class_id   IS NULL
       OR NEW.teacher_id IS NULL
    THEN
        RAISE EXCEPTION 'Усі поля є обов’язковими';
    END IF;

    
    IF NEW.full_name ~ '[0-9]' THEN
        RAISE EXCEPTION 'Ім’я не повинно містити цифр';
    END IF;

    
    PERFORM 1 FROM schools   WHERE id = NEW.school_id;
    IF NOT FOUND THEN
        RAISE EXCEPTION 'school_id % не існує', NEW.school_id;
    END IF;

    PERFORM 1 FROM classes   WHERE id = NEW.class_id;
    IF NOT FOUND THEN
        RAISE EXCEPTION 'class_id % не існує', NEW.class_id;
    END IF;

    PERFORM 1 FROM teachers  WHERE id = NEW.teacher_id;
    IF NOT FOUND THEN
        RAISE EXCEPTION 'teacher_id % не існує', NEW.teacher_id;
    END IF;

    
    IF EXISTS (
        SELECT 1
          FROM participants p
         WHERE p.full_name  = NEW.full_name
           AND p.school_id  = NEW.school_id
           AND p.class_id   = NEW.class_id
           AND p.teacher_id = NEW.teacher_id
    ) THEN
        RAISE EXCEPTION 'Такий учасник уже існує';
    END IF;

    RETURN NEW;
END;
$function$
