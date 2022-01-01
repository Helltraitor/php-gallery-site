<?php

declare(strict_types=1);

require_once __DIR__ . '/../mvc/models/connection.php';

use Models\Connection;

throw new CompileError('This module not for import. Comment this line and execute this script instead.');

$user_table_creation_query =
'
-- Table: public.user

CREATE TABLE public."user"
(
    id bigserial NOT NULL,
    name character varying(64) COLLATE pg_catalog."default" NOT NULL,
    email character varying(320) COLLATE pg_catalog."default" NOT NULL,
    password character varying(256) COLLATE pg_catalog."default" NOT NULL,
    CONSTRAINT primary_key_id PRIMARY KEY (id),
    CONSTRAINT unique_email UNIQUE (email)
)

TABLESPACE pg_default;

ALTER TABLE public."user"
    OWNER to "PHP";
-- Index: email_index


CREATE UNIQUE INDEX email_index
    ON public."user" USING btree
    (email COLLATE pg_catalog."default" ASC NULLS LAST)
    TABLESPACE pg_default;
-- Index: password_index


CREATE INDEX password_index
    ON public."user" USING btree
    (password COLLATE pg_catalog."default" ASC NULLS LAST)
    TABLESPACE pg_default;
';

$auth_table_creation_query =
'
-- Table: public.auth

CREATE TABLE public.auth
(
    id integer NOT NULL,
    uuid character varying(128) COLLATE pg_catalog."default" NOT NULL,
    expire bigint NOT NULL,
    CONSTRAINT auth_pkey PRIMARY KEY (uuid),
    CONSTRAINT auth_id_fkey FOREIGN KEY (id)
        REFERENCES public."user" (id) MATCH FULL
        ON UPDATE CASCADE
        ON DELETE CASCADE
)

TABLESPACE pg_default;

ALTER TABLE public.auth
    OWNER to "PHP";
';

$image_table_creation_query =
'
-- Table: public.image

CREATE TABLE public.image
(
    id bigserial NOT NULL,
    "user" integer NOT NULL,
    rating integer NOT NULL,
    rated integer NOT NULL,
    description character varying COLLATE pg_catalog."default" NOT NULL,
    rated_users integer[] NOT NULL,
    CONSTRAINT image_pkey PRIMARY KEY (id),
    CONSTRAINT image_user_fkey FOREIGN KEY ("user")
        REFERENCES public."user" (id) MATCH FULL
        ON UPDATE CASCADE
        ON DELETE CASCADE
)

TABLESPACE pg_default;

ALTER TABLE public.image
    OWNER to "PHP";

-- Trigger: DURFU

CREATE FUNCTION delete_user_from_rated_users()
    RETURNS TRIGGER AS $dufru$
BEGIN
	UPDATE public.image
	SET	rated_users=array_remove(rated_users, CAST(OLD.id AS INTEGER))
	WHERE OLD.id = ANY(rated_users);
	RETURN OLD;
END
$dufru$ LANGUAGE plpgsql;

CREATE TRIGGER trg_del_user BEFORE DELETE ON public."user" FOR EACH ROW EXECUTE PROCEDURE delete_user_from_rated_users() 
';

foreach (
    [
        $user_table_creation_query,
        $auth_table_creation_query,
        $image_table_creation_query
    ] as $query
) {
    Connection::get()->exec($query);
}