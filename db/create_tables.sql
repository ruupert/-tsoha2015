SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

CREATE OR REPLACE PROCEDURAL LANGUAGE plpgsql;


SET search_path = public, pg_catalog;

CREATE FUNCTION trigger_create_initial_timestamp() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.created_at = now();
   NEW.modified_at = now();
   NEW.pw_hash = md5(NEW.created_at::text||NEW.pw_hash);
   RETURN NEW;
END;
$$;

CREATE FUNCTION trigger_update_modified_timestamp() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.updated_at = now();
   RETURN NEW;
END;
$$;

SET default_tablespace = '';

SET default_with_oids = false;


CREATE TABLE movie (
    id integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    modified_at timestamp without time zone NOT NULL,
    name text NOT NULL,
    description text NOT NULL,
    image bytea,
    duration integer NOT NULL
);

CREATE SEQUENCE movie_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE movie_id_seq OWNED BY movie.id;

CREATE TABLE reservation (
    id integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    modified_at timestamp without time zone NOT NULL,
    user_id integer NOT NULL,
    timetable_id integer NOT NULL,
    quantity integer NOT NULL
);

CREATE SEQUENCE reservation_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE reservation_id_seq OWNED BY reservation.id;

CREATE TABLE theater (
    id integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    modified_at timestamp without time zone NOT NULL,
    name text NOT NULL,
    description text NOT NULL,
    image bytea,
    seats integer NOT NULL
);

COMMENT ON COLUMN theater.id IS 'avain';

CREATE SEQUENCE theater_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE theater_id_seq OWNED BY theater.id;

CREATE TABLE timetable (
    id integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    modified_at timestamp without time zone NOT NULL,
    start_at timestamp without time zone NOT NULL,
    movie_id integer NOT NULL,
    theater_id integer NOT NULL
);

CREATE SEQUENCE timetable_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE timetable_id_seq OWNED BY timetable.id;

CREATE TABLE users (
    id integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    modified_at timestamp without time zone NOT NULL,
    admin boolean DEFAULT false NOT NULL,
    username text NOT NULL,
    pw_hash text NOT NULL,
    name text NOT NULL,
    lastname text NOT NULL
);

CREATE SEQUENCE user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE user_id_seq OWNED BY users.id;

ALTER TABLE ONLY movie ALTER COLUMN id SET DEFAULT nextval('movie_id_seq'::regclass);

ALTER TABLE ONLY reservation ALTER COLUMN id SET DEFAULT nextval('reservation_id_seq'::regclass);

ALTER TABLE ONLY theater ALTER COLUMN id SET DEFAULT nextval('theater_id_seq'::regclass);

ALTER TABLE ONLY timetable ALTER COLUMN id SET DEFAULT nextval('timetable_id_seq'::regclass);

ALTER TABLE ONLY users ALTER COLUMN id SET DEFAULT nextval('user_id_seq'::regclass);

SELECT pg_catalog.setval('movie_id_seq', 1, false);

SELECT pg_catalog.setval('reservation_id_seq', 1, false);

SELECT pg_catalog.setval('theater_id_seq', 1, false);

SELECT pg_catalog.setval('timetable_id_seq', 1, false);

SELECT pg_catalog.setval('user_id_seq', 13, true);

ALTER TABLE ONLY movie
    ADD CONSTRAINT pk_movie PRIMARY KEY (id);

ALTER TABLE ONLY reservation
    ADD CONSTRAINT pk_reservation PRIMARY KEY (id);

ALTER TABLE ONLY theater
    ADD CONSTRAINT pk_theater PRIMARY KEY (id);

ALTER TABLE ONLY timetable
    ADD CONSTRAINT pk_timetable PRIMARY KEY (id);

ALTER TABLE ONLY users
    ADD CONSTRAINT pk_user PRIMARY KEY (id);

ALTER TABLE ONLY users
    ADD CONSTRAINT unique_username UNIQUE (username);

CREATE TRIGGER trigger_create_movie_timestamp
    BEFORE INSERT ON movie
    FOR EACH ROW
    EXECUTE PROCEDURE trigger_create_initial_timestamp();

CREATE TRIGGER trigger_create_reservation_timestamp
    BEFORE INSERT ON reservation
    FOR EACH ROW
    EXECUTE PROCEDURE trigger_create_initial_timestamp();

CREATE TRIGGER trigger_create_theater_timestamp
    BEFORE INSERT ON theater
    FOR EACH ROW
    EXECUTE PROCEDURE trigger_create_initial_timestamp();

CREATE TRIGGER trigger_create_timetable_timestamp
    BEFORE INSERT ON timetable
    FOR EACH ROW
    EXECUTE PROCEDURE trigger_create_initial_timestamp();

CREATE TRIGGER trigger_create_user_timestamp
    BEFORE INSERT ON users
    FOR EACH ROW
    EXECUTE PROCEDURE trigger_create_initial_timestamp();

CREATE TRIGGER trigger_update_movie_timestamp
    BEFORE UPDATE ON movie
    FOR EACH ROW
    EXECUTE PROCEDURE trigger_update_modified_timestamp();

CREATE TRIGGER trigger_update_reservation_timestamp
    BEFORE UPDATE ON reservation
    FOR EACH ROW
    EXECUTE PROCEDURE trigger_update_modified_timestamp();

CREATE TRIGGER trigger_update_theater_timestamp
    BEFORE UPDATE ON theater
    FOR EACH ROW
    EXECUTE PROCEDURE trigger_update_modified_timestamp();

CREATE TRIGGER trigger_update_timetable_timestamp
    BEFORE UPDATE ON timetable
    FOR EACH ROW
    EXECUTE PROCEDURE trigger_update_modified_timestamp();

CREATE TRIGGER trigger_update_user_timestamp
    BEFORE UPDATE ON users
    FOR EACH ROW
    EXECUTE PROCEDURE trigger_update_modified_timestamp();

ALTER TABLE ONLY timetable
    ADD CONSTRAINT fk_movie FOREIGN KEY (movie_id) REFERENCES movie(id);

ALTER TABLE ONLY timetable
    ADD CONSTRAINT fk_theater FOREIGN KEY (theater_id) REFERENCES theater(id);

ALTER TABLE ONLY reservation
    ADD CONSTRAINT fk_timetable FOREIGN KEY (timetable_id) REFERENCES timetable(id);

ALTER TABLE ONLY reservation
    ADD CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES users(id);

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;
