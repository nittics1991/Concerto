\encoding UTF-8;

BEGIN;

----------------------------------------------------------------------------------------------------
--
----------------------------------------------------------------------------------------------------

-- カラム追加 変更 削除
ALTER TABLE public.mst_skill 
	ADD COLUMN cd_parent text DEFAULT ''
	,ALTER update SET NOT NULL
	,ALTER editor SET NOT NULL
	,ALTER cd_skill SET NOT NULL
	,ALTER nm_skill SET NOT NULL
	,ALTER dt_yukou SET NOT NULL
    ,DROP nm_biko
;

-- コメント
COMMENT ON COLUMN public.mst_skill.cd_parent IS '親';



--RENAMEは個別に
ALTER TABLE public.setubi_inf
    RENAME kb_setubi TO cd_group
;  
ALTER TABLE public.setubi_inf
    RENAME nm_maker TO nm_bunrui
;


-- 制約はNOT VALID後にVALIDATE CONSTRAINT
ALTER TABLE public.setubi_yoyaku
    ADD CONSTRAINT setubi_yoyaku_cd_setubi_foreign
        FOREIGN KEY(cd_setubi)
            REFERENCES public.setubi_inf(cd_setubi)
            ON UPDATE CASCADE
            ON DELETE CASCADE
            NOT VALID
;

ALTER TABLE public.setubi_yoyaku
    VALIDATE CONSTRAINT setubi_yoyaku_cd_setubi_foreign
;


--CHECK制約の例
ALTER TABLE public.mst_skill 
	ADD CONSTRAINT mst_skill_check_skill 
		CHECK (cd_skill ~ '^\d{10}$')
	, ADD CONSTRAINT mst_skill_check_parent 
		CHECK (cd_parent = '' OR cd_parent ~ '^\d{10}$')
    NOT VALID
;



-- COMMIT;

