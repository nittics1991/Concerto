\encoding UTF-8;

BEGIN;

----------------------------------------------------------------------------------------------------
--
----------------------------------------------------------------------------------------------------

CREATE TABLE tmp.login_cache (
	key TEXT PRIMARY KEY,
	value TEXT NOT NULL DEFAULT '',
	expire_at TIMESTAMP NOT NULL DEFAULT NOW()
);

--テーブルコメント
COMMENT ON TABLE tmp.login_cache IS 'ログインキャッシュ';

--カラムコメント
COMMENT ON COLUMN tmp.login_cache.key IS 'キー';
COMMENT ON COLUMN tmp.login_cache.value IS '値';
COMMENT ON COLUMN tmp.login_cache.expire_at IS '期限';


--権限追加
GRANT SELECT, INSERT, UPDATE, DELETE, TRUNCATE
	ON TABLE tmp.login_cache TO concerto;

GRANT SELECT
	ON TABLE tmp.login_cache TO reader;


-- COMMIT;

