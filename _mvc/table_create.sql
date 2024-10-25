\encoding SJIS;

BEGIN;

----------------------------------------------------------------------------------------------------
--
----------------------------------------------------------------------------------------------------

--schema作成
CREATE SCHEMA tmp AUTHORIZATION postgres;

--テーブル作成
CREATE TABLE public.project_inf 
	(update text NOT NULL DEFAULT ''
	, editor text NOT NULL DEFAULT ''
	, no_project int2 NOT NULL DEFAULT 0
	, nm_project text NOT NULL DEFAULT ''
	, cd_tanto text  NOT NULL DEFAULT ''
	, dt_pkansei text NOT NULL DEFAULT ''
	, fg_kansei text NOT NULL DEFAULT ''
	,CONSTRAINT project_inf_unique
		UNIQUE (no_project)
	);

--テーブルコメント
COMMENT ON TABLE public.project_inf IS 'プロジェクト情報';

--カラムコメント
COMMENT ON COLUMN public.project_inf.update IS '更新日';
COMMENT ON COLUMN public.project_inf.editor IS '更新者';




--権限追加
GRANT SELECT, INSERT, UPDATE, DELETE, TRUNCATE
	ON TABLE public.wf_pmh TO concerto;

GRANT SELECT
	ON TABLE public.wf_pmh TO reader;

GRANT SELECT, INSERT, UPDATE, DELETE, TRUNCATE
	ON ALL TABLES IN SCHEMA mondaiten TO mondaiten;

GRANT USAGE
	ON SCHEMA public TO mondaiten;

GRANT SELECT, INSERT, UPDATE, DELETE, TRUNCATE
	ON ALL TABLES IN SCHEMA public TO concerto;

GRANT SELECT
	ON ALL TABLES IN SCHEMA public TO reader;


-- COMMIT;

