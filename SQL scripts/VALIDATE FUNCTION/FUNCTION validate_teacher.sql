CREATE OR REPLACE FUNCTION public.validate_teacher()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
BEGIN
    
    IF NEW.full_name IS NULL
       OR NEW.school_id IS NULL
    THEN
        RAISE EXCEPTION 'Усі поля є обов’язковими';
    END IF;

    
    IF NEW.full_name ~ '[0-9]' THEN
        RAISE EXCEPTION 'Ім’я не повинно містити цифр';
    END IF;

    
    PERFORM 1
      FROM schools
     WHERE id = NEW.school_id;
    IF NOT FOUND THEN
        RAISE EXCEPTION 'school_id % не існує', NEW.school_id;
    END IF;

    
    IF EXISTS (
        SELECT 1
          FROM teachers t
         WHERE t.full_name = NEW.full_name
           AND t.school_id = NEW.school_id
           AND (TG_OP = 'INSERT' OR t.id <> NEW.id)
    ) THEN
        RAISE EXCEPTION 'Такий вчитель уже існує';
    END IF;

    RETURN NEW;
END;
$function$
