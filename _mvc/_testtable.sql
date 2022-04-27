\encoding SJIS;

BEGIN;

----------------------------------------------------------------------------------------------------
--
----------------------------------------------------------------------------------------------------

CREATE SCHEMA IF NOT EXISTS test;

----------------------------------------------------------------------------------------------------
--
----------------------------------------------------------------------------------------------------
CREATE TABLE test._modeldb 
	(b_data text
	, i_data integer
	, f_data double precision
	, d_data double precision
	, s_data text
	, t_data text
	);

COMMENT ON TABLE test._modeldb IS 'UnitTest用';

----------------------------------------------------------------------------------------------------
--
----------------------------------------------------------------------------------------------------
CREATE TABLE test._modeldbcacher 
	(b_data text
	, i_data integer
	, f_data double precision
	, d_data double precision
	, s_data text
	, t_data text
	);

COMMENT ON TABLE test._modeldbcacher IS 'UnitTest用';


----------------------------------------------------------------------------------------------------
--
----------------------------------------------------------------------------------------------------
CREATE TABLE test._modeldbUpsert
	(b_data text
	, i_data integer primary key
	, f_data double precision
	, d_data double precision
	, s_data text
	, t_data text
	);

COMMENT ON TABLE test._modeldbUpsert IS 'UnitTest用';

----------------------------------------------------------------------------------------------------
--
----------------------------------------------------------------------------------------------------
CREATE TABLE test._modeldbtree 
	(cd_id text
	, cd_parent text
	, no_data integer
	, nm_data text
	);

COMMENT ON TABLE test._modeldbtree IS 'UnitTest用';

----------------------------------------------------------------------------------------------------
--
----------------------------------------------------------------------------------------------------
CREATE TABLE test._pdocache (
    key text PRIMARY KEY NOT NULL DEFAULT '',
    value text NOT NULL DEFAULT '',
    expire_at text NOT NULL DEFAULT ''
	);

COMMENT ON TABLE test._pdocache IS 'UnitTest用';

----------------------------------------------------------------------------------------------------
--
----------------------------------------------------------------------------------------------------

GRANT USAGE
    ON SCHEMA test TO concerto;

GRANT SELECT, INSERT, UPDATE, DELETE, TRUNCATE
	ON ALL TABLES IN SCHEMA test TO concerto;

----------------------------------------------------------------------------------------------------
--
----------------------------------------------------------------------------------------------------

COMMIT;

