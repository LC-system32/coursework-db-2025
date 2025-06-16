CREATE OR REPLACE FUNCTION public.get_all_table_names()
 RETURNS TABLE(name text, comment text)
 LANGUAGE plpgsql
AS $function$
BEGIN
  RETURN QUERY
  SELECT
    c.relname::text AS name,
    obj_description(c.oid, 'pg_class') AS comment
  FROM pg_class c
  JOIN pg_namespace n ON n.oid = c.relnamespace
  WHERE
    c.relkind = 'r'
    AND n.nspname = 'public'
    AND c.relname NOT LIKE 'pg_%'
    AND c.relname NOT LIKE 'sql_%';
END;
$function$
