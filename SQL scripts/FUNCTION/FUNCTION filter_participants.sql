CREATE OR REPLACE FUNCTION public.filter_participants(p_full_name character varying DEFAULT NULL::character varying, p_school character varying DEFAULT NULL::character varying, p_class character varying DEFAULT NULL::character varying, p_teacher_full_name character varying DEFAULT NULL::character varying)
 RETURNS TABLE(id integer, name character varying, school character varying, class character varying, teacher character varying)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY
    SELECT
        pv.id,
        pv.name::varchar,
        pv.school::varchar,
        pv.class::varchar,
        pv.teacher::varchar
    FROM participants_view pv
    WHERE
        (p_full_name IS NULL OR pv.name ILIKE '%' || p_full_name || '%') AND
        (p_school IS NULL OR pv.school ILIKE '%' || p_school || '%') AND
        (p_class IS NULL OR pv.class ILIKE '%' || p_class || '%') AND
        (p_teacher_full_name IS NULL OR pv.teacher ILIKE '%' || p_teacher_full_name || '%');
END;
$function$
