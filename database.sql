DROP TABLE IF EXISTS database.participants;
DROP TABLE IF EXISTS database.sessions;
DROP TABLE IF EXISTS database.experiments;
DROP TABLE IF EXISTS database.experimenters;
DROP TABLE IF EXISTS database.locations;
DROP TABLE IF EXISTS database.users;

DROP SCHEMA IF EXISTS database;
CREATE SCHEMA database;
SET search_path to database;

CREATE TABLE participants (
pid 			SERIAL PRIMARY KEY,
address 		VARCHAR,
phone_number 	NUMERIC,
ethnicity		CHAR,
gender			CHAR,
age				NUMERIC,
education 		NUMERIC,
username		VARCHAR,
username REFERENCES database.users(username) ON DELETE CASCADE
);

CREATE TABLE sessions (
sid 			SERIAL PRIMARY KEY,
session_date   	DATE NOT NULL,
start_time		TIME NOT NULL,
end_time		TIME NOT NULL,
lid				NUMERIC,
eid				NUMERIC,
expid			NUMERIC,
pid				NUMERIC,
FOREIGN KEY (pid) REFERENCES database.participants(pid),
FOREIGN KEY (eid) REFERENCES database.experimenters(eid) ON DELETE CASCADE,
FOREIGN KEY (expid) REFERENCES database.experiments(expid) ON DELETE CASCADE,
FOREIGN KEY (lid) REFERENCES database.locations(lid) ON DELETE CASCADE
);

CREATE TABLE experiments (
expid			SERIAL PRIMARY KEY,
payment			NUMERIC,
name			CHAR,
requirements	VARCHAR,
);

CREATE TABLE experimenters (
eid				SERIAL PRIMARY KEY,
name			CHAR,
username		VARCHAR,
username REFERENCES database.users(username) ON DELETE CASCADE
);

CREATE TABLE locations (
lid				SERIAL PRIMARY KEY,
room			SMALLINT,
building		CHAR,
);

CREATE TABLE users (
username		VARCHAR NOT NULL PRIMARY KEY,
pwhash			VARCHAR NOT NULL,
salt			CHAR NOT NULL,
user_type		CHAR NOT NULL,
email			VARCHAR,
);