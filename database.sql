DROP TABLE IF EXISTS database.sessions;
DROP TABLE IF EXISTS database.experiments;
DROP TABLE IF EXISTS database.experimenters;
DROP TABLE IF EXISTS database.locations;
DROP TABLE IF EXISTS database.participants;
DROP TABLE IF EXISTS database.users;
DROP SCHEMA IF EXISTS database;

CREATE SCHEMA database;
SET search_path TO database;

CREATE TABLE users (
username		VARCHAR(50) NOT NULL PRIMARY KEY,
pwhash			CHAR(40) NOT NULL,
salt			CHAR(40) NOT NULL,
user_type		CHAR(12) NOT NULL,
email			VARCHAR(50)
);

CREATE TABLE participants (
pid 			SERIAL PRIMARY KEY,
first_name		CHAR(50),
middle_name		CHAR(50),
last_name		CHAR(50),
address 		VARCHAR(100),
phone_number 	NUMERIC,
ethnicity		CHAR(20),
gender			CHAR(6),
age				NUMERIC,
education 		NUMERIC,
contact_again	BOOLEAN,
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
first_name		CHAR(50),
middle_name		CHAR(50),
last_name		CHAR(50),
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

/*
Insert test data into database for use by developers
Users are easy enough to be added through registration, and edited on my account page
same goes for sessions soon
*/
INSERT INTO experiments VALUES(1, 100, 'experiment 1', 'none');
INSERT INTO experiments VALUES(2, 50, 'experiment 2', 'none');
INSERT INTO locations VALUES(1, 101, 'Student Center');
INSERT INTO locations VALUES(2, 102, 'McAlester');
INSERT INTO users VALUES("test_user1", "password_hash", "salt", "experimenter", "email@mail.com");
INSERT INTO users VALUES("test_user2", "password_hash", "salt", "experimenter", "email@mail.com");
INSERT INTO participants VALUES(1, "address", 555-555-5555, "ethnicity", "gender", 21, 16, "test_user1");
INSERT INTO experimenters VALUES(1, "experimenter_person", "test_user2");
INSERT INTO sessions VALUES(1, curdate(), curtime(), curtime()+100, 1, 1 1, 1);
INSERT INTO sessions VALUES(2, curdate(), curtime(), curtime()+100, 2, 1 1, 1);



