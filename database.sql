DROP TABLE IF EXISTS database.sessions;
DROP TABLE IF EXISTS database.experiments;
DROP TABLE IF EXISTS database.experimenters;
DROP TABLE IF EXISTS database.locations;
DROP TABLE IF EXISTS database.participants;
DROP TABLE IF EXISTS database.users;
DROP SCHEMA IF EXISTS database;

CREATE SCHEMA database;
SET search_path to database;

CREATE TABLE users (
username		VARCHAR(50) NOT NULL PRIMARY KEY,
pwhash			CHAR(40) NOT NULL,
salt			CHAR(40) NOT NULL,
user_type		CHAR(12) NOT NULL,
email			VARCHAR(50)
);

CREATE TABLE participants (
pid 			SERIAL PRIMARY KEY,
address 		VARCHAR,
phone_number 	NUMERIC,
ethnicity		CHAR,
gender			CHAR,
age				NUMERIC,
education 		NUMERIC,
username		VARCHAR REFERENCES database.users(username) ON DELETE CASCADE
);

CREATE TABLE experiments (
expid			SERIAL PRIMARY KEY,
payment			NUMERIC,
name			CHAR(50),
requirements	VARCHAR(500)
);

CREATE TABLE experimenters (
eid				SERIAL PRIMARY KEY,
name			CHAR(50),
username 		VARCHAR(50) REFERENCES database.users(username) ON DELETE CASCADE
);

CREATE TABLE locations (
lid				SERIAL PRIMARY KEY,
room			SMALLINT,
building		CHAR(50)
);

CREATE TABLE sessions (
sid 			SERIAL PRIMARY KEY,
session_date   	DATE NOT NULL,
start_time		TIME NOT NULL,
end_time		TIME NOT NULL,
lid				INTEGER REFERENCES database.locations(lid) ON DELETE CASCADE,
eid				INTEGER REFERENCES database.experimenters(eid) ON DELETE CASCADE,
expid			INTEGER REFERENCES database.experiments(expid) ON DELETE CASCADE,
pid				INTEGER REFERENCES database.participants(pid)
);