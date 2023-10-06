\encoding UTF-8;

BEGIN;

\echo '-----CREATE TABLE public.claim_inf';

CREATE TABLE public.claim_inf (
	no_claim TEXT PRIMARY KEY,
	
	kb_nendo TEXT NOT NULL DEFAULT '',
	no_seq SMALLINT NOT NULL DEFAULT 0,
	
	cd_bumon TEXT NOT NULL DEFAULT '',
	cd_tanto TEXT NOT NULL DEFAULT '',
	
	nm_site TEXT NOT NULL DEFAULT '',
	dt_hassei TEXT NOT NULL DEFAULT '',
	nm_renraku TEXT NOT NULL DEFAULT '',
	
	nm_bunrui TEXT NOT NULL DEFAULT '',
	
	kb_syubetu TEXT NOT NULL DEFAULT '',
	kb_jyudaido TEXT NOT NULL DEFAULT '',
	kb_gennin TEXT NOT NULL DEFAULT '',
	
	dt_kaisyu TEXT NOT NULL DEFAULT '',
	nm_kaisyu TEXT NOT NULL DEFAULT '',
	
	nm_doc TEXT NOT NULL DEFAULT '',
	nm_program TEXT NOT NULL DEFAULT '', --rename nm_pro

	dt_kakunin TEXT NOT NULL DEFAULT '',
	nm_kakunin TEXT NOT NULL DEFAULT '',
	
	kb_hiyou TEXT NOT NULL DEFAULT '',
	
	no_hosyu TEXT NOT NULL DEFAULT '',
	
	yn_keihi INTEGER NOT NULL DEFAULT 0,
	yn_tanka SMALLINT NOT NULL DEFAULT 0,
	tm_cyokka DOUBLE PRECISION NOT NULL DEFAULT 0,
	
	nm_mondai TEXT NOT NULL DEFAULT '',
	nm_keika TEXT NOT NULL DEFAULT '', --string_agg(multi rows)
	nm_genin TEXT NOT NULL DEFAULT '',
	nm_taisaku TEXT NOT NULL DEFAULT '',
	nm_saihatu TEXT NOT NULL DEFAULT '',
	
	nm_biko TEXT NOT NULL DEFAULT '',
	
	ins_date TEXT NOT NULL DEFAULT '',
	up_date TEXT NOT NULL DEFAULT '', --new
	
	dt_end TEXT NOT NULL DEFAULT '',	--rename kb_sts create from dt_kakunin|XXXXX
	
	--only old data
	nm_system TEXT NOT NULL DEFAULT '', --not use

	--only old data, new system use to mail_inf
	nm_tyosa TEXT NOT NULL DEFAULT '', --not use
	nm_syonin TEXT NOT NULL DEFAULT '', --not use

	--only old data move to claim_doc delete??
	nm_file TEXT NOT NULL DEFAULT ''
)
;


\echo '-----COMMENT ON TABLE public.claim_inf';

COMMENT ON TABLE public.claim_inf IS 'クレーム情報';

COMMENT ON COLUMN public.claim_inf.no_claim IS 'クレーム番号';
COMMENT ON COLUMN public.claim_inf.kb_nendo IS '年度';
COMMENT ON COLUMN public.claim_inf.no_seq IS '番号';
COMMENT ON COLUMN public.claim_inf.cd_bumon IS '部門コード';
COMMENT ON COLUMN public.claim_inf.cd_tanto IS '担当者';
COMMENT ON COLUMN public.claim_inf.nm_site IS '	サイト／システム';
COMMENT ON COLUMN public.claim_inf.dt_hassei IS '発生日';
COMMENT ON COLUMN public.claim_inf.nm_renraku IS '連絡ルート';
COMMENT ON COLUMN public.claim_inf.nm_bunrui IS '分類';
COMMENT ON COLUMN public.claim_inf.kb_syubetu IS '0:トラブル 1:要望 2:問合せ 3:その他';
COMMENT ON COLUMN public.claim_inf.kb_jyudaido IS 'A:外部仕様 B:内部仕様 C:ソフトウェア設計 
D:ソースコード E:マニュアル F:誤修正
G:テストケース Hハードウェア
W:誤操作･誤運用 X:保留･不明･他';
COMMENT ON COLUMN public.claim_inf.kb_gennin IS '0:重大 1:重要 2:軽微 3外見的';
COMMENT ON COLUMN public.claim_inf.dt_kaisyu IS '改修日';
COMMENT ON COLUMN public.claim_inf.nm_kaisyu IS '改修者';
COMMENT ON COLUMN public.claim_inf.nm_doc IS '改修図書';
COMMENT ON COLUMN public.claim_inf.nm_program IS '改修プログラム';
COMMENT ON COLUMN public.claim_inf.dt_kakunin IS '完了確認日';
COMMENT ON COLUMN public.claim_inf.nm_kakunin IS '完了確認者';
COMMENT ON COLUMN public.claim_inf.kb_hiyou IS '0:有償 1:無償';
COMMENT ON COLUMN public.claim_inf.no_hosyu IS '	補修発番';
COMMENT ON COLUMN public.claim_inf.yn_keihi IS '経費';
COMMENT ON COLUMN public.claim_inf.yn_tanka IS '直課単価';
COMMENT ON COLUMN public.claim_inf.tm_cyokka IS '直課時間';
COMMENT ON COLUMN public.claim_inf.nm_mondai IS '問題点';
COMMENT ON COLUMN public.claim_inf.nm_keika IS '経過';
COMMENT ON COLUMN public.claim_inf.nm_genin IS '原因';
COMMENT ON COLUMN public.claim_inf.nm_taisaku IS '対策';
COMMENT ON COLUMN public.claim_inf.nm_saihatu IS '再発防止';
COMMENT ON COLUMN public.claim_inf.nm_biko IS '備考';
COMMENT ON COLUMN public.claim_inf.ins_date IS '登録日';
COMMENT ON COLUMN public.claim_inf.up_date IS '更新日';
COMMENT ON COLUMN public.claim_inf.dt_end IS '完了日';
COMMENT ON COLUMN public.claim_inf.nm_system IS 'クレームグループ ';
COMMENT ON COLUMN public.claim_inf.nm_tyosa IS '調査者';
COMMENT ON COLUMN public.claim_inf.nm_syonin IS '承認者';
COMMENT ON COLUMN public.claim_inf.nm_file IS '資料URL';


\echo '-----INSERT INTO public.claim_inf';

INSERT INTO public.claim_inf (
no_claim
,kb_nendo
,no_seq
,cd_bumon
,cd_tanto
,nm_site
,dt_hassei
,nm_renraku
,nm_bunrui
,kb_syubetu
,kb_jyudaido
,kb_gennin
,dt_kaisyu
,nm_kaisyu
,nm_doc
,nm_program
,dt_kakunin
,nm_kakunin
,kb_hiyou
,no_hosyu
,yn_keihi
,yn_tanka
,CAST (tm_cyokka AS DOUBLE PRECISION)
,nm_mondai
,nm_keika
,nm_genin
,nm_taisaku
,nm_saihatu
,nm_biko
,ins_date
,up_date
,dt_end
,nm_system
,nm_tyosa
,nm_syonin
,nm_file
)
SELECT
	'CLM' || SUBSTR(A.kb_nendo, 3) ||
		LPAD(A.no_seq::TEXT, 3, '0')
		AS no_claim
	,A.kb_nendo
	,A.no_seq
	,A.kb_group AS cd_bumon
	,A.cd_tanto
	,A.nm_site
	,A.dt_hassei
	,A.nm_renraku
	,A.nm_bunrui
	,A.kb_syubetu
	,A.kb_jyudaido
	,A.kb_gennin
	,A.dt_kaisyu
	,A.nm_kaisyu
	,A.nm_doc
	,A.nm_pro AS nm_program
	,A.dt_kakunin
	,A.nm_kakunin
	,A.kb_hiyou
	,A.no_hosyu
	,A.yn_keihi
	,A.yn_tanka
	,A.tm_kousu AS tm_cyokka
	,COALESCE(B.nm_mondai, '')
	,COALESCE(C.nm_keika, '')
	,COALESCE(D.nm_genin, '')
	,COALESCE(E.nm_taisaku, '')
	,COALESCE(F.nm_saihatu, '')
	,A.nm_biko
	,A.ins_date
	,CASE WHEN A.dt_kakunin = '' THEN A.ins_date
		ELSE A.dt_kakunin
		END AS up_date
	,CASE WHEN A.kb_sts = '1' THEN  A.dt_kakunin
		ELSE ''
		END AS dt_end
	,A.nm_system
	,A.nm_tyosa
	,A.nm_syonin
	,CASE WHEN A.nm_file ~ '^file:\\\\' THEN A.nm_file
		ELSE ''
		END AS nm_file 
FROM public.claim_kihon A
LEFT JOIN (
	SELECT kb_nendo, no_seq
		, TRIM(TRAILING CHR(10) || CHR(13) FROM  nm_mondai) AS nm_mondai
	FROM public.claim_mondai
	) B
	ON B.kb_nendo = A.kb_nendo
		AND B.no_seq = A.no_seq
LEFT JOIN (
	SELECT kb_nendo, no_seq
		, string_agg(
			TRIM(TRAILING CHR(10) || CHR(13) FROM  nm_keika),
			CHR(10)
			ORDER BY no_hist
		) AS nm_keika
	FROM public.claim_keika
	GROUP BY kb_nendo, no_seq
	) C
	ON C.kb_nendo = A.kb_nendo
		AND C.no_seq = A.no_seq
LEFT JOIN (
	SELECT kb_nendo, no_seq
		, TRIM(TRAILING CHR(10) || CHR(13) FROM  nm_genin) AS nm_genin
	FROM public.claim_genin
	) D
	ON D.kb_nendo = A.kb_nendo
		AND D.no_seq = A.no_seq
LEFT JOIN (
	SELECT kb_nendo, no_seq
		, TRIM(TRAILING CHR(10) || CHR(13) FROM  nm_taisaku) AS nm_taisaku
	FROM public.claim_taisaku
	) E
	ON E.kb_nendo = A.kb_nendo
		AND E.no_seq = A.no_seq
LEFT JOIN (
	SELECT kb_nendo, no_seq
		, TRIM(TRAILING CHR(10) || CHR(13) FROM  nm_saihatu) AS nm_saihatu
	FROM public.claim_saihatu
	) F
	ON F.kb_nendo = A.kb_nendo
		AND F.no_seq = A.no_seq
ORDER BY A.kb_nendo, A.no_seq
;


\echo '-----CREATE TABLE public.claim_doc';

CREATE TABLE public.claim_doc (
	no_claim TEXT PRIMARY KEY,
	no_seq SMALLINT NOT NULL DEFAULT 0,
	nm_file TEXT NOT NULL DEFAULT '',
	nm_file_inf TEXT NOT NULL DEFAULT ''
	,CONSTRAINT claim_doc_unique
		UNIQUE(no_claim, no_seq)
);


\echo '-----COMMENT ON TABLE public.claim_doc';

COMMENT ON TABLE public.claim_doc IS 'クレーム図書';

COMMENT ON COLUMN public.claim_doc.no_claim IS 'クレーム番号';
COMMENT ON COLUMN public.claim_doc.no_seq IS '番号';
COMMENT ON COLUMN public.claim_doc.nm_file IS '図書リンクURI';
COMMENT ON COLUMN public.claim_doc.nm_file_inf IS '図書説明';


\echo '-----INSERT INTO public.claim_doc';

INSERT INTO public.claim_doc (
	no_claim, no_seq, nm_file, nm_file_inf
)
SELECT
	no_claim
	,1
	,REGEXP_REPLACE(
		REGEXP_REPLACE(nm_file, '\\', '/', 'g'),
		'^file://',
		'file://///'
	)AS nm_file
	,''
FROM public.claim_inf
WHERE nm_file != ''
ORDER BY no_claim
;

\echo '-----ALTER TABLE public.claim_inf';

ALTER TABLE public.claim_inf
DROP nm_file
;




--cd_stsの部分どうする？


-- COMMIT;

