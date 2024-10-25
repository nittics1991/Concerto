\encoding UTF8;
/*
*	orafdwテーブル再作成
*
*
*
*
*/

CREATE OR REPLACE FUNCTION tmp.replace_orafdw(
) RETURNS VOID AS $$

DECLARE
	-- [[cd_tanto, nm_tanto, new_bumon],...]
	_comment_list TEXT[][] := '{
		
		
		
		
		
		
		
		{50117ITC,旧IKB02未定,IKA02},
		{50224ITC,旧SEF02未定,SSB02},
		{50305ITC,旧SFC02未定,SEC02},
		{50306ITC,旧SFF02未定,SSB02},
		{50307ITC,旧SFE02未定,SSB02},
		{50313ITC,旧SFB02_HW未定,SEB02},
		{50314ITC,旧SFB02_PLC未定,SEB02},
		{50315ITC,旧SFB02_SW未定,SEB02},
		{50316ITC,旧SFB02_その他未定,SEB02},
		{50402ITC,旧IEA02未定,IKA02}
	}';
	
	_i INTEGER;
	_cnt INTEGER;

BEGIN
	RAISE NOTICE '--- start mst_tanto ---';
		
	FOR _i IN 1 .. array_upper(_comment_list, 1)
	LOOP
		
		RAISE NOTICE '% %', _comment_list[_i][1], _comment_list[_i][2];
		
		
		
		
		
		COMMENT ON 
		
		
		
		
		UPDATE public.mst_tanto
		SET nm_tanto = _comment_list[_i][2]
			, cd_bumon = _comment_list[_i][3]
		WHERE cd_tanto = _comment_list[_i][1]
		;
		
		GET DIAGNOSTICS _cnt := ROW_COUNT;
		
		RAISE NOTICE '... %' ,_cnt;
	
	END LOOP;
	
	RAISE NOTICE '--- end mst_tanto ---';
	
END;
$$ LANGUAGE plpgsql;

BEGIN;

SELECT tmp.mst_tanto();

-- ROLLBACK;
-- COMMIT;













BEGIN;

IMPORT FOREIGN SCHEMA "ITC_IS" FROM SERVER symphony INTO orafdw;


COMMENT ON TABLE public.project_inf IS 'プロジェクト情報';




