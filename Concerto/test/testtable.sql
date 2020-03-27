
----------------------------------------------------------------------------------------------------
BEGIN;

CREATE SCHEMA IF NOT EXISTS test;

COMMENT ON SCHEMA test IS ' use test';

GRANT USAGE 
    ON SCHEMA test TO concerto;

COMMIT;

----------------------------------------------------------------------------------------------------
BEGIN;

CREATE TABLE test._modeldb 
	(b_data text
	, i_data integer
	, f_data double precision
	, d_data double precision
	, s_data text
	, t_data text
	);

COMMENT ON TABLE test._modeldb IS 'UnitTest用';

GRANT SELECT, INSERT, UPDATE, DELETE, TRUNCATE
	ON TABLE test._modeldb TO concerto;


COMMIT;

----------------------------------------------------------------------------------------------------
BEGIN;

CREATE TABLE test._modeldbcacher 
	(b_data text
	, i_data integer
	, f_data double precision
	, d_data double precision
	, s_data text
	, t_data text
	);

COMMENT ON TABLE test._modeldbcacher IS 'UnitTest用';

GRANT SELECT, INSERT, UPDATE, DELETE, TRUNCATE
	ON TABLE test._modeldbcacher TO concerto;

COMMIT;

----------------------------------------------------------------------------------------------------
BEGIN;

CREATE TABLE test._modeldbtree 
	(cd_id text
	, cd_parent text
	, no_data integer
	, nm_data text
	);

COMMENT ON TABLE test._modeldbtree IS 'UnitTest用';

GRANT SELECT, INSERT, UPDATE, DELETE, TRUNCATE
	ON TABLE test._modeldbtree TO concerto;


COMMIT;

----------------------------------------------------------------------------------------------------
BEGIN;

CREATE TABLE test._pdocache 
	(key text primary key
	, value text NOT NULL default ''
	, expire_at timestamp NOT NULL
	);

COMMENT ON TABLE test._pdocache IS 'UnitTest用';

GRANT SELECT, INSERT, UPDATE, DELETE, TRUNCATE
	ON TABLE test._pdocache TO concerto;

COMMIT;


