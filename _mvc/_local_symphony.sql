\encoding UTF-8;

BEGIN;


----------------------------------------------------------------------------------------------------
-- oracle_fdw
----------------------------------------------------------------------------------------------------

CREATE EXTENSION IF NOT EXISTS oracle_fdw;

CREATE SERVER IF NOT EXISTS symphony
    FOREIGN DATA WRAPPER oracle_fdw
    OPTIONS (dbserver '//133.199.131.132:2525/ITCA');

CREATE USER MAPPING IF NOT EXISTS 
    FOR CURRENT_USER
    SERVER symphony
	OPTIONS (user 'ITC_USER', password 'ITC_201304');

CREATE USER MAPPING IF NOT EXISTS 
    FOR concerto
    SERVER symphony
	OPTIONS (user 'ITC_USER', password 'ITC_201304');

GRANT USAGE ON FOREIGN SERVER symphony TO postgres;
GRANT USAGE ON FOREIGN SERVER symphony TO concerto;


----------------------------------------------------------------------------------------------------
-- orafdw schema
----------------------------------------------------------------------------------------------------

CREATE SCHEMA IF NOT EXISTS orafdw;

IMPORT FOREIGN SCHEMA "ITC_IS"
    LIMIT TO (
        employee_view
        ,han_hanyo_view
        ,itc_faz_hikiate_view
        ,itc_faz_hoju_view
        ,itc_faz_nyuko_syukko_view
        ,itc_faz_zaiko_hist_view
        ,itc_gen_kansei_shiwake_view
        ,itc_han_uriage_shiwake_view
        ,itc_nai_kinmu_view
        ,itc_pur_order_seikyu_view
        ,juchu_toke
        ,kansei_buka_view
        ,tmal0010
        ,tmal0030
        ,tmal0160
        ,tpal0010
        ,tpal0011
        ,tpal0020
        ,tpal0030
        ,tpal0110
        ,tpal0210
        ,tpal0220
        ,tpal0810
        ,tpal0820
        ,tsal0010
        ,tsal0020
        ,tsal0050 
    )
    FROM SERVER symphony
    INTO orafdw;


GRANT USAGE ON SCHEMA orafdw TO concerto;

GRANT SELECT ON ALL TABLES IN SCHEMA orafdw TO concerto;

--ROLLBACK;
COMMIT;



