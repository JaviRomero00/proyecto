DROP TABLE IF EXISTS contactos CASCADE;
DROP TABLE IF EXISTS usuarios CASCADE;

CREATE TABLE contactos (
    id          bigserial    PRIMARY KEY,
    nombre      varchar(50)  NOT NULL,
    telefono    varchar(10)  NOT NULL UNIQUE

);

CREATE TABLE usuarios (
    id          bigserial   PRIMARY KEY,
    usuario     varchar(255) NOT NULL UNIQUE,
    contrasena  varchar(255) NOT NULL
);

INSERT INTO usuarios (usuario, contrasena)
     VALUES (md5('javi'), md5('javi'));
