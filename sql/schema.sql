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
CREATE INDEX voter_election_id ON voter USING HASH (election_id);

CREATE TABLE state
(
  state_id    SERIAL PRIMARY KEY,
  number      INTEGER NOT NULL,
  name        TEXT    NOT NULL,
  population  INTEGER NOT NULL,
  election_id INTEGER NOT NULL REFERENCES election (election_id) ON DELETE CASCADE
);
CREATE INDEX state_election_id ON state USING HASH (election_id);

CREATE TABLE party
(
  party_id     SERIAL PRIMARY KEY,
  name         TEXT    NOT NULL,
  abbreviation TEXT,
  color        TEXT,
  election_id  INTEGER NOT NULL REFERENCES election (election_id) ON DELETE CASCADE
);
CREATE INDEX party_election_id ON party USING HASH (election_id);

CREATE TABLE state_list
(
  state_list_id SERIAL PRIMARY KEY,
  state_id      INTEGER NOT NULL REFERENCES state (state_id) ON DELETE CASCADE,
  party_id      INTEGER NOT NULL REFERENCES party (party_id) ON DELETE CASCADE
);
CREATE INDEX state_list_state_id ON state_list USING HASH (state_id);
CREATE INDEX state_list_party_id ON state_list USING HASH (party_id);

CREATE TABLE candidate
(
  candidate_id SERIAL PRIMARY KEY,
  name         TEXT    NOT NULL,
  birthday     DATE,
  party_id     INTEGER REFERENCES party (party_id),
  election_id  INTEGER NOT NULL REFERENCES election (election_id) ON DELETE CASCADE
);
CREATE INDEX candidate_party_id ON candidate USING HASH (party_id);
CREATE INDEX candidate_election_id ON candidate USING HASH (election_id);


CREATE TABLE state_candidacy
(
  candidate_id  INTEGER PRIMARY KEY REFERENCES candidate (candidate_id) ON DELETE CASCADE,
  state_list_id INTEGER NOT NULL REFERENCES state_list (state_list_id) ON DELETE CASCADE,
  position      INTEGER NOT NULL
);
CREATE INDEX state_candidate_state_list_id ON state_candidacy USING HASH (state_list_id);


CREATE TABLE constituency
(
  constituency_id SERIAL PRIMARY KEY,
  number          INTEGER NOT NULL,
  name            TEXT,
  electives       INTEGER NOT NULL,
  state_id        INTEGER NOT NULL REFERENCES state (state_id) ON DELETE CASCADE,
  election_id     INTEGER NOT NULL REFERENCES election (election_id) ON DELETE CASCADE
);
CREATE INDEX constituency_state_id ON constituency USING HASH (state_id);
CREATE INDEX constituency_election_id ON constituency USING HASH (election_id);


CREATE TABLE constituency_candidacy
(
  candidate_id    INTEGER PRIMARY KEY REFERENCES candidate (candidate_id) ON DELETE CASCADE,
  constituency_id INTEGER NOT NULL REFERENCES constituency (constituency_id) ON DELETE CASCADE
);
CREATE INDEX constituency_candidacy_candidate_id ON constituency_candidacy USING HASH (candidate_id);
CREATE INDEX constituency_candidacy_constituency_id ON constituency_candidacy USING HASH (constituency_id);


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
--makes queries slower?
--CREATE INDEX aggregated_first_result_candidate_id ON aggregated_first_result USING HASH (candidate_id);


CREATE TABLE aggregated_second_result
(
  aggregated_second_result_id SERIAL PRIMARY KEY,
  state_list_id               INTEGER NOT NULL REFERENCES state_list (state_list_id),
  constituency_id             INTEGER NOT NULL REFERENCES constituency (constituency_id),
  count                       INTEGER
);
--makes queries slower?
--CREATE INDEX aggregated_second_result_state_list_id ON aggregated_second_result USING HASH (state_list_id);
--CREATE INDEX aggregated_second_result_constituency_id ON aggregated_second_result USING HASH (constituency_id);

