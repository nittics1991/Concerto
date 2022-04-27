--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = test, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: _modeldb; Type: TABLE; Schema: test; Owner: postgres; Tablespace: 
--

CREATE TABLE _modeldb (
    b_data boolean,
    i_data integer,
    f_data real,
    d_data double precision,
    s_data text,
    t_data timestamp with time zone
);


ALTER TABLE test._modeldb OWNER TO postgres;

--
-- Data for Name: _modeldb; Type: TABLE DATA; Schema: test; Owner: postgres
--

COPY _modeldb (b_data, i_data, f_data, d_data, s_data, t_data) FROM stdin;
t	10	20.0200005	30.030000000000001	STRING	2014-12-01 00:00:00+09
f	-10	-20.0200005	-30.030000000000001	文字列	2014-12-15 00:00:00+09
t	0	-0	\N	\N	\N
t	100	-2	\N	\N	\N
t	200	-4	\N	\N	\N
t	300	-6	\N	\N	\N
t	400	-8	\N	\N	\N
t	500	-10	\N	\N	\N
t	600	-12	\N	\N	\N
t	700	-14	\N	\N	\N
t	800	-16	\N	\N	\N
t	900	-18	\N	\N	\N
\.


--
-- PostgreSQL database dump complete
--

