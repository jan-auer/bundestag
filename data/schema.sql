CREATE TABLE election 
(
	id 		SERIAL PRIMARY KEY,
	number 	INTEGER NOT NULL,
	date 	DATE
);

CREATE TABLE voter 
(
	id 			SERIAL PRIMARY KEY,
	name 		VARCHAR,
	birthday 	DATE,
	election_id INTEGER NOT NULL REFERENCES election(id) on delete cascade on update cascade
);

CREATE TABLE state
(
	id 			SERIAL PRIMARY KEY,
	name 		VARCHAR,
	population 	INTEGER,
	election_id INTEGER NOT NULL REFERENCES election(id) on delete cascade on update cascade
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
	state_id	INTEGER NOT NULL REFERENCES state(id) on delete cascade on update cascade,
	party_id	INTEGER NOT NULL REFERENCES party(id) on delete cascade on update cascade
);

CREATE TABLE candidate
(
	id 		 SERIAL PRIMARY KEY,
	name 	 VARCHAR,
	birthday DATE,
	party_id INTEGER REFERENCES party(id)
);

CREATE TABLE state_candidacy
(
	state_list_id	INTEGER NOT NULL REFERENCES state_list(id) on delete cascade on update cascade,
	candidate_id	INTEGER NOT NULL REFERENCES candidate(id) on delete cascade on update cascade,
	position 		INTEGER NOT NULL,
	
	PRIMARY KEY (state_list_id, candidate_id)
);

CREATE TABLE constituency
(
	id 			SERIAL PRIMARY KEY,
	number 		INTEGER NOT NULL,
	name 		VARCHAR,
	state_id	INTEGER NOT NULL REFERENCES state(id) on delete cascade on update cascade
);

CREATE TABLE constituency_candidacy
(
	id 				SERIAL PRIMARY KEY,
	constituency_id INTEGER NOT NULL REFERENCES constituency(id) on delete cascade on update cascade,
	candidate_id 	INTEGER NOT NULL REFERENCES candidate(id),
	
	unique(constituency_id, candidate_id)
);

CREATE TABLE site
(
	id 	 			SERIAL PRIMARY KEY,
	name 			VARCHAR,
	constituency_id INTEGER NOT NULL REFERENCES constituency(id) on delete cascade on update cascade
);

CREATE TABLE result_type
(
	id 	 SERIAL PRIMARY KEY,
	name VARCHAR
);

CREATE TABLE result
(
	id				SERIAL PRIMARY KEY,
	result_type_id	INTEGER NOT NULL REFERENCES result_type(id),
	site_id			INTEGER NOT NULL REFERENCES site(id)
);

CREATE TABLE first_result
(
	id 							SERIAL PRIMARY KEY,
	result_id 				  INTEGER NOT NULL REFERENCES result(id),
	constituency_candidacy_id INTEGER NOT NULL REFERENCES constituency_candidacy(id)
);

CREATE TABLE second_result
(
	id 				SERIAL PRIMARY KEY,
	result_id 	  	INTEGER NOT NULL REFERENCES result(id),
	state_list_id 	INTEGER NOT NULL REFERENCES state_list(id)
);