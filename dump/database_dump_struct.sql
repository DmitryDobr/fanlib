--
-- PostgreSQL database dump
--

-- Dumped from database version 11.17
-- Dumped by pg_dump version 11.17

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: CHAPTER; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."CHAPTER" (
    chapter_id integer NOT NULL,
    work_id integer NOT NULL,
    chapter_text text,
    chapter_remark text,
    chapter_name text,
    chapter_number integer NOT NULL
);


ALTER TABLE public."CHAPTER" OWNER TO postgres;

--
-- Name: COLUMN "CHAPTER".work_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public."CHAPTER".work_id IS 'к какой работе относится глава';


--
-- Name: COLUMN "CHAPTER".chapter_text; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public."CHAPTER".chapter_text IS 'Текст главы';


--
-- Name: COLUMN "CHAPTER".chapter_remark; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public."CHAPTER".chapter_remark IS 'примечания к главе';


--
-- Name: COLUMN "CHAPTER".chapter_name; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public."CHAPTER".chapter_name IS 'Название главы';


--
-- Name: CHARACTER; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."CHARACTER" (
    character_id integer NOT NULL,
    about_character text,
    character_name text
);


ALTER TABLE public."CHARACTER" OWNER TO postgres;

--
-- Name: CHARACTER-TO-FANDOM; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."CHARACTER-TO-FANDOM" (
    "CHAR_FAND_id" integer NOT NULL,
    character_id integer NOT NULL,
    fandom_id integer NOT NULL
);


ALTER TABLE public."CHARACTER-TO-FANDOM" OWNER TO postgres;

--
-- Name: CHARACTER-TO-WORK; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."CHARACTER-TO-WORK" (
    "CHAR_WORK_id" integer NOT NULL,
    character_id integer NOT NULL,
    work_id integer NOT NULL
);


ALTER TABLE public."CHARACTER-TO-WORK" OWNER TO postgres;

--
-- Name: COLLECTION; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."COLLECTION" (
    collection_id integer NOT NULL,
    id_user integer NOT NULL,
    name text NOT NULL
);


ALTER TABLE public."COLLECTION" OWNER TO postgres;

--
-- Name: COLUMN "COLLECTION".name; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public."COLLECTION".name IS 'название коллекции пользователя';


--
-- Name: COLLECTION-TO-WORK; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."COLLECTION-TO-WORK" (
    "COLL_WORK_id" integer NOT NULL,
    id_collection integer NOT NULL,
    work_id integer NOT NULL
);


ALTER TABLE public."COLLECTION-TO-WORK" OWNER TO postgres;

--
-- Name: COMMENT; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."COMMENT" (
    comment_id integer NOT NULL,
    id_work integer NOT NULL,
    id_user integer NOT NULL,
    text text
);


ALTER TABLE public."COMMENT" OWNER TO postgres;

--
-- Name: FANDOM; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."FANDOM" (
    fandom_id integer NOT NULL,
    about_fandom text,
    author text,
    name text
);


ALTER TABLE public."FANDOM" OWNER TO postgres;

--
-- Name: COLUMN "FANDOM".about_fandom; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public."FANDOM".about_fandom IS 'про фандом';


--
-- Name: COLUMN "FANDOM".author; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public."FANDOM".author IS 'Оригинальный создатель произведения, по которому создан фандом';


--
-- Name: COLUMN "FANDOM".name; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public."FANDOM".name IS 'наименование фандома';


--
-- Name: USER; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."USER" (
    user_id integer NOT NULL,
    birth date,
    about text,
    email text NOT NULL,
    password text NOT NULL,
    nickname text NOT NULL
);


ALTER TABLE public."USER" OWNER TO postgres;

--
-- Name: WORK; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."WORK" (
    work_id integer NOT NULL,
    user_id integer NOT NULL,
    about text,
    update_time date NOT NULL,
    remark text,
    "WORK_STATUS" integer,
    "WorkName" text
);


ALTER TABLE public."WORK" OWNER TO postgres;

--
-- Name: COLUMN "WORK".work_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public."WORK".work_id IS 'id самой работы';


--
-- Name: COLUMN "WORK".user_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public."WORK".user_id IS 'id пользователя, который создал это';


--
-- Name: COLUMN "WORK".about; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public."WORK".about IS 'о работе';


--
-- Name: COLUMN "WORK".update_time; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public."WORK".update_time IS 'дата обновления';


--
-- Name: COLUMN "WORK".remark; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public."WORK".remark IS 'примечания';


--
-- Name: COLUMN "WORK"."WORK_STATUS"; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public."WORK"."WORK_STATUS" IS '0 - в работе. 1 - заморожен. 2 - завершен. 3 - скрыт';


--
-- Name: CHAPTER CHAPTER_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."CHAPTER"
    ADD CONSTRAINT "CHAPTER_pkey" PRIMARY KEY (chapter_id);


--
-- Name: CHARACTER-TO-FANDOM CHARACTER-TO-FANDOM_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."CHARACTER-TO-FANDOM"
    ADD CONSTRAINT "CHARACTER-TO-FANDOM_pkey" PRIMARY KEY ("CHAR_FAND_id");


--
-- Name: CHARACTER-TO-WORK CHARACTER-TO-WORK_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."CHARACTER-TO-WORK"
    ADD CONSTRAINT "CHARACTER-TO-WORK_pkey" PRIMARY KEY ("CHAR_WORK_id");


--
-- Name: CHARACTER CHARACTER_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."CHARACTER"
    ADD CONSTRAINT "CHARACTER_pkey" PRIMARY KEY (character_id);


--
-- Name: COLLECTION-TO-WORK COLLECTION-TO-WORK_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."COLLECTION-TO-WORK"
    ADD CONSTRAINT "COLLECTION-TO-WORK_pkey" PRIMARY KEY ("COLL_WORK_id");


--
-- Name: COLLECTION COLLECTION_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."COLLECTION"
    ADD CONSTRAINT "COLLECTION_pkey" PRIMARY KEY (collection_id);


--
-- Name: COMMENT COMMENT_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."COMMENT"
    ADD CONSTRAINT "COMMENT_pkey" PRIMARY KEY (comment_id);


--
-- Name: FANDOM FANDOM_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."FANDOM"
    ADD CONSTRAINT "FANDOM_pkey" PRIMARY KEY (fandom_id);


--
-- Name: USER USER_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."USER"
    ADD CONSTRAINT "USER_pkey" PRIMARY KEY (user_id);


--
-- Name: WORK WORK_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."WORK"
    ADD CONSTRAINT "WORK_pkey" PRIMARY KEY (work_id);


--
-- Name: CHAPTER CHAPTER_id_work_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."CHAPTER"
    ADD CONSTRAINT "CHAPTER_id_work_fkey" FOREIGN KEY (work_id) REFERENCES public."WORK"(work_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: CHARACTER-TO-FANDOM CHARACTER-TO-FANDOM_id_character_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."CHARACTER-TO-FANDOM"
    ADD CONSTRAINT "CHARACTER-TO-FANDOM_id_character_fkey" FOREIGN KEY (character_id) REFERENCES public."CHARACTER"(character_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: CHARACTER-TO-FANDOM CHARACTER-TO-FANDOM_id_fandom_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."CHARACTER-TO-FANDOM"
    ADD CONSTRAINT "CHARACTER-TO-FANDOM_id_fandom_fkey" FOREIGN KEY (fandom_id) REFERENCES public."FANDOM"(fandom_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: CHARACTER-TO-WORK CHARACTER-TO-WORK_id_character_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."CHARACTER-TO-WORK"
    ADD CONSTRAINT "CHARACTER-TO-WORK_id_character_fkey" FOREIGN KEY (character_id) REFERENCES public."CHARACTER"(character_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: CHARACTER-TO-WORK CHARACTER-TO-WORK_id_work_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."CHARACTER-TO-WORK"
    ADD CONSTRAINT "CHARACTER-TO-WORK_id_work_fkey" FOREIGN KEY (work_id) REFERENCES public."WORK"(work_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: COLLECTION-TO-WORK COLLECTION-TO-WORK_id_collection_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."COLLECTION-TO-WORK"
    ADD CONSTRAINT "COLLECTION-TO-WORK_id_collection_fkey" FOREIGN KEY (id_collection) REFERENCES public."COLLECTION"(collection_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: COLLECTION-TO-WORK COLLECTION-TO-WORK_id_work_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."COLLECTION-TO-WORK"
    ADD CONSTRAINT "COLLECTION-TO-WORK_id_work_fkey" FOREIGN KEY (work_id) REFERENCES public."WORK"(work_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: COLLECTION COLLECTION_id_user_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."COLLECTION"
    ADD CONSTRAINT "COLLECTION_id_user_fkey" FOREIGN KEY (id_user) REFERENCES public."USER"(user_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: COMMENT COMMENT_id_user_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."COMMENT"
    ADD CONSTRAINT "COMMENT_id_user_fkey" FOREIGN KEY (id_user) REFERENCES public."USER"(user_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: COMMENT COMMENT_id_work_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."COMMENT"
    ADD CONSTRAINT "COMMENT_id_work_fkey" FOREIGN KEY (id_work) REFERENCES public."WORK"(work_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: WORK WORK_id_user_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."WORK"
    ADD CONSTRAINT "WORK_id_user_fkey" FOREIGN KEY (user_id) REFERENCES public."USER"(user_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

