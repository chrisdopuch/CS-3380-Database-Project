/*This is the SQL file that creates our database. It also creates some test values*/

DROP TABLE IF EXISTS database.sessions;
DROP TABLE IF EXISTS database.experiments;
DROP TABLE IF EXISTS database.experimenters;
DROP TABLE IF EXISTS database.locations;
DROP TABLE IF EXISTS database.participants;
DROP TABLE IF EXISTS database.users;
DROP TABLE IF EXISTS database.emails;
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
phone_number 	VARCHAR(14),
ethnicity		CHAR(30), /*proper nouns, stored with capitals*/
gender			CHAR(7),
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

CREATE TABLE emails (
id				SERIAL PRIMARY KEY,
send_by			TIMESTAMP,
recipient		VARCHAR(50),
subject			VARCHAR(50),
text			VARCHAR(500)
);

/*
Insert test data into database for use by developers
Users are easy enough to be added through registration, and edited on my account page
same goes for sessions soon
*/
INSERT INTO experiments (expid, payment, name, requirements) VALUES( 1, 150, 'experiment','none');
INSERT INTO experiments (expid, payment, name, requirements) VALUES( 2, 100, 'experiment 1', 'none');
INSERT INTO experiments (expid, payment, name, requirements) VALUES( 3, 50, 'experiment 2', 'none');
INSERT INTO locations (room, building) VALUES(101, 'Student Center');
INSERT INTO locations (room, building) VALUES(102, 'McAlester');
INSERT INTO users VALUES('test_user1', 'password_hash', 'salt', 'experimenter', 'email@mail.com');
INSERT INTO users VALUES('test_user2', 'password_hash', 'salt', 'experimenter', 'email@mail.com');
INSERT INTO participants (first_name, middle_name, last_name, address, phone_number, ethnicity, gender, age, education, contact_again, username) VALUES('fname', 'mname', 'lname', 'address', 555-555-5555, 'ethnicity', 'male', 21, 16, 'false', 'test_user1');
INSERT INTO experimenters (first_name, middle_name, last_name, username) VALUES('fname', 'mname', 'lname', 'test_user2');
INSERT INTO sessions (session_date, start_time, end_time, lid, eid, expid, pid) VALUES(CURRENT_DATE, CURRENT_TIME, CURRENT_TIME, 1, 1 , 1, 1);
INSERT INTO sessions (session_date, start_time, end_time, lid, eid, expid, pid) VALUES(CURRENT_DATE, CURRENT_TIME, CURRENT_TIME, 2, 1, 2, 1);
INSERT INTO emails (send_by, recipient, subject, text) VALUES(CURRENT_TIMESTAMP, 'cdp73@mail.missouri.edu', 'test email', 'This is a test email sent from babbage');



