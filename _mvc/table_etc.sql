\encoding UTF8;

-- OIDのCAST例
SELECT A.oid, relname, relnamespace::regnamespace, relowner::regrole
FROM pg_class A
JOIN pg_namespace B
	ON B.oid = A.relnamespace 
WHERE relnamespace = 'public'::regnamespace
LIMIT 100
;

-- 指定schemaのpg_classテーブルのみ取得
SELECT B.*
FROM information_schema.tables A
JOIN pg_class B
	ON B.relname = table_name
WHERE A.table_schema = 'public'
LIMIT 50
;

-- 指定schemaのテーブルコメント一覧
SELECT B.relname
	, C.description
FROM information_schema.tables A
JOIN pg_class B
	ON B.relname = table_name
JOIN pg_description C
	ON C.objoid = B.oid
WHERE A.table_schema = 'public'
	AND C.objsubid = 0
ORDER BY A.table_name
;

-- 指定テーブルの列コメント
-- ダメ

SELECT C.attnum
	, C.attname  
	, D.description
FROM information_schema.tables A
JOIN pg_class B
	ON B.relname = table_name
JOIN pg_attribute C
	ON C.attrelid = B.oid
JOIN pg_description D
	ON D.objsubid = C.attnum 
WHERE A.table_schema = 'public'
	AND A.table_name = 'cyuban_inf'
ORDER BY C.attnum
LIMIT 50

