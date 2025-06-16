CREATE OR REPLACE FUNCTION public.get_user_password(_name text)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
DECLARE
    user_password TEXT;
BEGIN
    SELECT password INTO user_password FROM users WHERE name = _name;
    RETURN user_password;
END;
$function$