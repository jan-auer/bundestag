--
-- TABLE election
--
CREATE TABLE election
(
  election_id SERIAL PRIMARY KEY,
  number      INTEGER NOT NULL,
  date        DATE    NOT NULL
);

--
-- TABLE state: One separate state entry for each election
--
CREATE TABLE state
(
  state_id    SERIAL PRIMARY KEY,
  number      INTEGER NOT NULL,
  name        TEXT    NOT NULL,
  population  INTEGER NOT NULL,
  election_id INTEGER NOT NULL REFERENCES election (election_id) ON DELETE CASCADE
);

-- Speed up foreign key access.
CREATE INDEX state_election_id ON state USING HASH (election_id);

--
-- TABLE party: One separate party entry for each election
--
CREATE TABLE party
(
  party_id     SERIAL PRIMARY KEY,
  name         TEXT    NOT NULL,
  abbreviation TEXT,
  color        TEXT,
  election_id  INTEGER NOT NULL REFERENCES election (election_id) ON DELETE CASCADE
);

-- Speed up foreign key access.
CREATE INDEX party_election_id ON party USING HASH (election_id);
-- Speed up scans for a particular abbreviation used for election comparisons.
CREATE INDEX party_abbreviation ON party USING HASH (abbreviation);

--
-- TABLE state_list: Connection between states and parties
--
CREATE TABLE state_list
(
  state_list_id SERIAL PRIMARY KEY,
  state_id      INTEGER NOT NULL REFERENCES state (state_id) ON DELETE CASCADE,
  party_id      INTEGER NOT NULL REFERENCES party (party_id) ON DELETE CASCADE
);

-- Speed up foreign key access.
CREATE INDEX state_list_state_id ON state_list USING HASH (state_id);
-- Speed up foreign key access.
CREATE INDEX state_list_party_id ON state_list USING HASH (party_id);

--
-- TABLE candidate: One separate candidate entry for each election.
-- This includes both direct and list candidates.
--
CREATE TABLE candidate
(
  candidate_id SERIAL PRIMARY KEY,
  name         TEXT    NOT NULL,
  birthday     DATE,
  party_id     INTEGER REFERENCES party (party_id),
  election_id  INTEGER NOT NULL REFERENCES election (election_id) ON DELETE CASCADE
);

-- Speed up foreign key access.
CREATE INDEX candidate_party_id ON candidate USING HASH (party_id);
-- Speed up foreign key access.
CREATE INDEX candidate_election_id ON candidate USING HASH (election_id);

--
-- TABLE state_candidacy: Connection between candidate and state_list.
--
CREATE TABLE state_candidacy
(
  candidate_id  INTEGER PRIMARY KEY REFERENCES candidate (candidate_id) ON DELETE CASCADE,
  state_list_id INTEGER NOT NULL REFERENCES state_list (state_list_id) ON DELETE CASCADE,
  position      INTEGER NOT NULL
);

-- Speed up foreign key access and ordering by position within a list.
CREATE INDEX state_candidacy_state_list_position ON state_candidacy USING BTREE (state_list_id, position);

--
-- TABLE constituency: One separate entry for each election.
--
CREATE TABLE constituency
(
  constituency_id SERIAL PRIMARY KEY,
  number          INTEGER NOT NULL,
  name            TEXT,
  electives       INTEGER NOT NULL,
  state_id        INTEGER NOT NULL REFERENCES state (state_id) ON DELETE CASCADE,
  election_id     INTEGER NOT NULL REFERENCES election (election_id) ON DELETE CASCADE
);

-- Speed up foreign key access.
CREATE INDEX constituency_state_id ON constituency USING HASH (state_id);
-- Speed up foreign key access.
CREATE INDEX constituency_election_id ON constituency USING HASH (election_id);

--
-- TABLE constituency_candidacy: Direct candidates for each constituency.
--
CREATE TABLE constituency_candidacy
(
  candidate_id    INTEGER PRIMARY KEY REFERENCES candidate (candidate_id) ON DELETE CASCADE,
  constituency_id INTEGER NOT NULL REFERENCES constituency (constituency_id) ON DELETE CASCADE
);

-- Speed up foreign key access.
CREATE INDEX constituency_candidacy_constituency_id ON constituency_candidacy USING HASH (constituency_id);

--
-- TABLE first_result: Contains single votes to direct candidates.
--
CREATE TABLE first_result
(
  first_result_id SERIAL PRIMARY KEY,
  candidate_id    INTEGER NOT NULL REFERENCES constituency_candidacy (candidate_id)
);

--
-- TABLE second_result: Contains single votes to state lists.
--
CREATE TABLE second_result
(
  second_result_id SERIAL PRIMARY KEY,
  state_list_id    INTEGER NOT NULL REFERENCES state_list (state_list_id),
  constituency_id  INTEGER NOT NULL REFERENCES constituency (constituency_id)
);

--
-- TABLE aggregated_first_result: Contains an aggregated count of votes for all direct candidates.
--
CREATE TABLE aggregated_first_result
(
  aggregated_first_result_id SERIAL PRIMARY KEY,
  candidate_id               INTEGER NOT NULL REFERENCES constituency_candidacy (candidate_id),
  count                      INTEGER
);

-- An additional column "constituency_id" with a btree index on (constituency_id, count) could speed up
-- finding the constituency winner.

--
-- TABLE aggregated_second_result: Contains an aggregated count of votes for all state lists.
--
CREATE TABLE aggregated_second_result
(
  aggregated_second_result_id SERIAL PRIMARY KEY,
  state_list_id               INTEGER NOT NULL REFERENCES state_list (state_list_id),
  constituency_id             INTEGER NOT NULL REFERENCES constituency (constituency_id),
  count                       INTEGER
);

-- An additional column "state_id" with a btree index on (state_id, count) could speed up
-- ordering parties in the seat distribution process.

--
-- TABLE voter
--
CREATE TABLE voter
(
  voter_id        SERIAL PRIMARY KEY,
  identity_number TEXT    NOT NULL,
  hash            TEXT    NOT NULL,
  constituency_id INTEGER NOT NULL REFERENCES constituency (constituency_id) ON DELETE CASCADE,
  voted           BOOLEAN,
  election_id     INTEGER NOT NULL REFERENCES election (election_id) ON DELETE CASCADE
);


-- Prevent duplicate voters per election.
CREATE UNIQUE INDEX voter_election_id ON voter (identity_number, election_id);
-- Speed up voter lookup by hash.
CREATE INDEX voter_hash ON voter USING HASH (hash);
