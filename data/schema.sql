CREATE TABLE election
(
  id     SERIAL PRIMARY KEY,
  number INTEGER NOT NULL,
  date   DATE
);

CREATE TABLE voter
(
  id          SERIAL PRIMARY KEY,
  name        TEXT,
  birthday    DATE,
  election_id INTEGER NOT NULL REFERENCES election (id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE state
(
  id          SERIAL PRIMARY KEY,
  name        TEXT,
  population  INTEGER,
  election_id INTEGER NOT NULL REFERENCES election (id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE party
(
  id                      SERIAL PRIMARY KEY,
  name                    TEXT,
  abbreviation            TEXT,
  formation_date          DATE,
  members                 INTEGER,
  color                   TEXT,
  minority_representation BOOLEAN
);

CREATE TABLE state_list
(
  id       SERIAL PRIMARY KEY,
  state_id INTEGER NOT NULL REFERENCES state (id) ON DELETE CASCADE ON UPDATE CASCADE,
  party_id INTEGER NOT NULL REFERENCES party (id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE candidate
(
  id       SERIAL PRIMARY KEY,
  name     TEXT,
  birthday DATE,
  party_id INTEGER REFERENCES party (id)
);

CREATE TABLE state_candidacy
(
  state_list_id INTEGER NOT NULL REFERENCES state_list (id) ON DELETE CASCADE ON UPDATE CASCADE,
  candidate_id  INTEGER NOT NULL REFERENCES candidate (id) ON DELETE CASCADE ON UPDATE CASCADE,
  position      INTEGER NOT NULL,

  PRIMARY KEY (state_list_id, candidate_id)
);

CREATE TABLE constituency
(
  id       SERIAL PRIMARY KEY,
  number   INTEGER NOT NULL,
  name     TEXT,
  state_id INTEGER NOT NULL REFERENCES state (id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE constituency_candidacy
(
  id              SERIAL PRIMARY KEY,
  constituency_id INTEGER NOT NULL REFERENCES constituency (id) ON DELETE CASCADE ON UPDATE CASCADE,
  candidate_id    INTEGER NOT NULL REFERENCES candidate (id),

  UNIQUE (constituency_id, candidate_id)
);

CREATE TABLE site
(
  id              SERIAL PRIMARY KEY,
  name            TEXT,
  constituency_id INTEGER NOT NULL REFERENCES constituency (id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE result_type
(
  id   SERIAL PRIMARY KEY,
  name TEXT
);

CREATE TABLE result
(
  id             SERIAL PRIMARY KEY,
  result_type_id INTEGER NOT NULL REFERENCES result_type (id),
  site_id        INTEGER NOT NULL REFERENCES site (id)
);

CREATE TABLE first_result
(
  id                        SERIAL PRIMARY KEY,
  result_id                 INTEGER NOT NULL REFERENCES result (id),
  constituency_candidacy_id INTEGER NOT NULL REFERENCES constituency_candidacy (id)
);

CREATE TABLE second_result
(
  id            SERIAL PRIMARY KEY,
  result_id     INTEGER NOT NULL REFERENCES result (id),
  state_list_id INTEGER NOT NULL REFERENCES state_list (id)
);
