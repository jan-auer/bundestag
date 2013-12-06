CREATE TABLE election
(
  election_id SERIAL PRIMARY KEY,
  number      INTEGER NOT NULL,
  date        DATE    NOT NULL
);

CREATE TABLE voter
(
  voter_id    SERIAL PRIMARY KEY,
  name        TEXT    NOT NULL,
  birthday    DATE    NOT NULL,
  election_id INTEGER NOT NULL REFERENCES election (election_id) ON DELETE CASCADE
);

CREATE TABLE state
(
  state_id    SERIAL PRIMARY KEY,
  number      INTEGER NOT NULL,
  name        TEXT    NOT NULL,
  population  INTEGER NOT NULL,
  election_id INTEGER NOT NULL REFERENCES election (election_id) ON DELETE CASCADE
);

CREATE TABLE party
(
  party_id                SERIAL PRIMARY KEY,
  name                    TEXT    NOT NULL,
  abbreviation            TEXT,
  formation_date          DATE,
  members                 INTEGER,
  color                   TEXT,
  minority_representation BOOLEAN NOT NULL
);

CREATE TABLE state_list
(
  state_list_id SERIAL PRIMARY KEY,
  state_id      INTEGER NOT NULL REFERENCES state (state_id) ON DELETE CASCADE,
  party_id      INTEGER NOT NULL REFERENCES party (party_id) ON DELETE CASCADE
);

CREATE TABLE candidate
(
  candidate_id SERIAL PRIMARY KEY,
  name         TEXT NOT NULL,
  birthday     DATE,
  party_id     INTEGER REFERENCES party (party_id)
);

CREATE TABLE state_candidacy
(
  candidate_id  INTEGER PRIMARY KEY REFERENCES candidate (candidate_id) ON DELETE CASCADE,
  state_list_id INTEGER NOT NULL REFERENCES state_list (state_list_id) ON DELETE CASCADE,
  position      INTEGER NOT NULL
);

CREATE TABLE constituency
(
  constituency_id SERIAL PRIMARY KEY,
  number          INTEGER NOT NULL,
  name            TEXT,
  population  INTEGER NOT NULL,
  state_id        INTEGER NOT NULL REFERENCES state (state_id) ON DELETE CASCADE
);

CREATE TABLE constituency_candidacy
(
  candidate_id    INTEGER PRIMARY KEY REFERENCES candidate (candidate_id) ON DELETE CASCADE,
  constituency_id INTEGER NOT NULL REFERENCES constituency (constituency_id) ON DELETE CASCADE
);

CREATE TABLE first_result
(
  first_result_id SERIAL PRIMARY KEY,
  candidate_id    INTEGER NOT NULL REFERENCES constituency_candidacy (candidate_id)
);

CREATE TABLE second_result
(
  second_result_id SERIAL PRIMARY KEY,
  state_list_id    INTEGER NOT NULL REFERENCES state_list (state_list_id),
  constituency_id  INTEGER NOT NULL REFERENCES constituency (constituency_id)
);

CREATE TABLE aggregated_first_result
(
  aggregated_first_result_id SERIAL PRIMARY KEY,
  candidate_id               INTEGER NOT NULL REFERENCES constituency_candidacy (candidate_id),
  count                      INTEGER
);

CREATE TABLE aggregated_second_result
(
  aggregated_second_result_id SERIAL PRIMARY KEY,
  state_list_id               INTEGER NOT NULL REFERENCES state_list (state_list_id),
  constituency_id             INTEGER NOT NULL REFERENCES constituency (constituency_id),
  count                       INTEGER
);
