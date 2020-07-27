CREATE DATABASE movies_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE movies_db;

CREATE TABLE movies (
id INT (11) AUTO_INCREMENT,
title VARCHAR (50) NOT NULL,
year VARCHAR (50) NOT NULL,
format varchar (50) NOT NULL,
PRIMARY KEY (id)
);

CREATE TABLE stars (
id INT (11) AUTO_INCREMENT,
name VARCHAR (50) NOT NULL,
PRIMARY KEY (id)
);

CREATE UNIQUE INDEX uidx_name
ON stars (name);

CREATE TABLE movies_stars (
movie_id INT (11) NOT NULL,
star_id INT (11) NOT NULL,
PRIMARY KEY (movie_id, star_id),
FOREIGN KEY (movie_id) REFERENCES movies (id)
ON DELETE CASCADE,
FOREIGN KEY (star_id) REFERENCES stars (id)
ON DELETE CASCADE
);

CREATE INDEX idx_movie_id
ON movies_stars (movie_id);

CREATE INDEX idx_star_id
ON movies_stars (star_id);