CREATE TABLE election 
(
	id 		SERIAL PRIMARY KEY,
	number 	INTEGER,
	e_date 	DATE
);

CREATE TABLE voter 
(
	id 			SERIAL PRIMARY KEY,
	name 		VARCHAR,
	birthday 	DATE,
	election_id INTEGER NOT NULL REFERENCES election(id)
);

CREATE TABLE party
(
	id 						SERIAL PRIMARY KEY,
	name 					VARCHAR,
	abbreviation 			VARCHAR,
	formation_date 			DATE,
	members 				INTEGER,
	color 					VARCHAR,
	minority_representation BOOLEAN
);

CREATE TABLE state_list
(
	id 			SERIAL PRIMARY KEY,
	election_id	INTEGER NOT NULL REFERENCES election(id),
	party_id	INTEGER NOT NULL REFERENCES party(id)
);

CREATE TABLE candidate
(
	id 		 SERIAL PRIMARY KEY,
	name 	 VARCHAR,
	birthday DATE
);

CREATE TABLE state_candidate
(
	state_list_id	INTEGER NOT NULL REFERENCES state_list(id),
	candidate_id	INTEGER NOT NULL REFERENCES candidate(id),
	position 		INTEGER,
	PRIMARY KEY (state_list_id, candidate_id)
);

CREATE TABLE constituency_candidacy
(
	id 			 SERIAL PRIMARY KEY,
	election_id  INTEGER NOT NULL REFERENCES election(id),
	party_id	 INTEGER NULL REFERENCES party(id),
	candidate_id INTEGER NOT NULL REFERENCES candidate(id)
);

CREATE TABLE result_type
(
	id 	 SERIAL PRIMARY KEY,
	name VARCHAR
);

CREATE TABLE site
(
	id 	 SERIAL PRIMARY KEY,
	name VARCHAR
);

CREATE TABLE result
(
	id				SERIAL PRIMARY KEY,
	r_count			INTEGER,
	result_type_id	INTEGER NOT NULL REFERENCES result_type(id),
	site_id			INTEGER NOT NULL REFERENCES site(id)
);

CREATE TABLE first_result
(
	result_id 				  INTEGER NOT NULL REFERENCES result(id),
	constituency_candidacy_id INTEGER NOT NULL REFERENCES constituency_candidacy(id)
);

CREATE TABLE second_result
(
	result_id 	  	INTEGER NOT NULL REFERENCES result(id),
	state_list_id 	INTEGER NOT NULL REFERENCES state_list(id)
);

CREATE TABLE state
(
	id 			INTEGER,
	name 		VARCHAR,
	population 	INTEGER
);

CREATE TABLE constituency
(
	c_number 	INTEGER,
	name 		VARCHAR
);

CREATE TABLE district
(
	id 		INTEGER,
	name 	VARCHAR
);
