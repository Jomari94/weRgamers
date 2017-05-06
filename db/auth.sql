--
-- PostgreSQL database dump
--

-- Dumped from database version 9.6.2
-- Dumped by pg_dump version 9.6.2

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: auth_assignment; Type: TABLE; Schema: public; Owner: wergamers
--

CREATE TABLE auth_assignment (
    item_name character varying(64) NOT NULL,
    user_id character varying(64) NOT NULL,
    created_at integer
);


ALTER TABLE auth_assignment OWNER TO wergamers;

--
-- Name: auth_item; Type: TABLE; Schema: public; Owner: wergamers
--

CREATE TABLE auth_item (
    name character varying(64) NOT NULL,
    type smallint NOT NULL,
    description text,
    rule_name character varying(64),
    data text,
    created_at integer,
    updated_at integer
);


ALTER TABLE auth_item OWNER TO wergamers;

--
-- Name: auth_item_child; Type: TABLE; Schema: public; Owner: wergamers
--

CREATE TABLE auth_item_child (
    parent character varying(64) NOT NULL,
    child character varying(64) NOT NULL
);


ALTER TABLE auth_item_child OWNER TO wergamers;

--
-- Name: auth_rule; Type: TABLE; Schema: public; Owner: wergamers
--

CREATE TABLE auth_rule (
    name character varying(64) NOT NULL,
    data text,
    created_at integer,
    updated_at integer
);


ALTER TABLE auth_rule OWNER TO wergamers;


--
-- Data for Name: auth_item; Type: TABLE DATA; Schema: public; Owner: wergamers
--

COPY auth_item (name, type, description, rule_name, data, created_at, updated_at) FROM stdin;
manageRequests	2	Gestiona las solicitudes de unión al grupo	isAdmin	\N	1494020830	1494020830
givePermissions	2	Da permisos de admin a otros miembros del grupo	isAdmin	\N	1494021671	1494021671
banMembers	2	Expulsa miembros del grupo	isAdmin	\N	1494021789	1494021789
manageEvents	2	Crea o elimina eventos	isAdmin	\N	1494021830	1494021830
viewChat	2	Ver el chat del grupo	isMember	\N	1494016129	1494021990
leaveGroup	2	Deja el grupo	isMember	\N	1494022594	1494022594
User	1	Usuario de la aplicación	\N	\N	1494016152	1494022824
\.


--
-- Data for Name: auth_item_child; Type: TABLE DATA; Schema: public; Owner: wergamers
--

COPY auth_item_child (parent, child) FROM stdin;
User	viewChat
User	manageRequests
User	givePermissions
User	banMembers
User	manageEvents
User	leaveGroup
\.


--
-- Data for Name: auth_rule; Type: TABLE DATA; Schema: public; Owner: wergamers
--

COPY auth_rule (name, data, created_at, updated_at) FROM stdin;
isMember	O:19:"app\\rbac\\MemberRule":3:{s:4:"name";s:8:"isMember";s:9:"createdAt";i:1494016105;s:9:"updatedAt";i:1494016105;}	1494016105	1494016105
isAdmin	O:18:"app\\rbac\\AdminRule":3:{s:4:"name";s:7:"isAdmin";s:9:"createdAt";i:1494020315;s:9:"updatedAt";i:1494020315;}	1494020315	1494020315
\.


--
-- Name: auth_assignment auth_assignment_pkey; Type: CONSTRAINT; Schema: public; Owner: wergamers
--

ALTER TABLE ONLY auth_assignment
    ADD CONSTRAINT auth_assignment_pkey PRIMARY KEY (item_name, user_id);


--
-- Name: auth_item_child auth_item_child_pkey; Type: CONSTRAINT; Schema: public; Owner: wergamers
--

ALTER TABLE ONLY auth_item_child
    ADD CONSTRAINT auth_item_child_pkey PRIMARY KEY (parent, child);


--
-- Name: auth_item auth_item_pkey; Type: CONSTRAINT; Schema: public; Owner: wergamers
--

ALTER TABLE ONLY auth_item
    ADD CONSTRAINT auth_item_pkey PRIMARY KEY (name);


--
-- Name: auth_rule auth_rule_pkey; Type: CONSTRAINT; Schema: public; Owner: wergamers
--

ALTER TABLE ONLY auth_rule
    ADD CONSTRAINT auth_rule_pkey PRIMARY KEY (name);


--
-- Name: idx-auth_item-type; Type: INDEX; Schema: public; Owner: wergamers
--

CREATE INDEX "idx-auth_item-type" ON auth_item USING btree (type);


--
-- Name: auth_assignment auth_assignment_item_name_fkey; Type: FK CONSTRAINT; Schema: public; Owner: wergamers
--

ALTER TABLE ONLY auth_assignment
    ADD CONSTRAINT auth_assignment_item_name_fkey FOREIGN KEY (item_name) REFERENCES auth_item(name) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: auth_item_child auth_item_child_child_fkey; Type: FK CONSTRAINT; Schema: public; Owner: wergamers
--

ALTER TABLE ONLY auth_item_child
    ADD CONSTRAINT auth_item_child_child_fkey FOREIGN KEY (child) REFERENCES auth_item(name) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: auth_item_child auth_item_child_parent_fkey; Type: FK CONSTRAINT; Schema: public; Owner: wergamers
--

ALTER TABLE ONLY auth_item_child
    ADD CONSTRAINT auth_item_child_parent_fkey FOREIGN KEY (parent) REFERENCES auth_item(name) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: auth_item auth_item_rule_name_fkey; Type: FK CONSTRAINT; Schema: public; Owner: wergamers
--

ALTER TABLE ONLY auth_item
    ADD CONSTRAINT auth_item_rule_name_fkey FOREIGN KEY (rule_name) REFERENCES auth_rule(name) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- PostgreSQL database dump complete
--
