CREATE OR REPLACE FUNCTION public.filter_teachers(p_full_name text DEFAULT NULL::text, p_school text DEFAULT NULL::text)
 RETURNS TABLE(id integer, name text, school text)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY
    SELECT
        tv.id,
        tv.name,
        tv.school
    FROM public.teachers_view tv
    WHERE
        (p_full_name IS NULL OR tv.name ILIKE '%' || p_full_name || '%') AND
        (p_school IS NULL OR tv.school ILIKE '%' || p_school || '%');
END;
$function$
