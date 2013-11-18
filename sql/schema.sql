CREATE TABLE election
(
  id     SERIAL PRIMARY KEY,
  number INTEGER NOT NULL,
  date   DATE    NOT NULL
);

CREATE TABLE voter
(
  id          SERIAL PRIMARY KEY,
  name        TEXT    NOT NULL,
  birthday    DATE    NOT NULL,
  election_id INTEGER NOT NULL REFERENCES election (id) ON DELETE CASCADE
);

CREATE TABLE state
(
  id          SERIAL PRIMARY KEY,
  number      INTEGER NOT NULL,
  name        TEXT    NOT NULL,
  population  INTEGER NOT NULL,
  election_id INTEGER NOT NULL REFERENCES election (id) ON DELETE CASCADE
);

CREATE TABLE party
(
  id                      SERIAL PRIMARY KEY,
  name                    TEXT    NOT NULL,
  abbreviation            TEXT,
  formation_date          DATE,
  members                 INTEGER,
  color                   TEXT,
  minority_representation BOOLEAN NOT NULL
);

CREATE TABLE state_list
(
  id       SERIAL PRIMARY KEY,
  state_id INTEGER NOT NULL REFERENCES state (id) ON DELETE CASCADE,
  party_id INTEGER NOT NULL REFERENCES party (id) ON DELETE CASCADE
);

CREATE TABLE candidate
(
  id       SERIAL PRIMARY KEY,
  name     TEXT NOT NULL,
  birthday DATE,
  party_id INTEGER REFERENCES party (id)
);

CREATE TABLE state_candidacy
(
  candidate_id  INTEGER PRIMARY KEY REFERENCES candidate (id) ON DELETE CASCADE,
  state_list_id INTEGER NOT NULL REFERENCES state_list (id) ON DELETE CASCADE,
  position      INTEGER NOT NULL
);

CREATE TABLE constituency
(
  id       SERIAL PRIMARY KEY,
  number   INTEGER NOT NULL,
  name     TEXT,
  state_id INTEGER NOT NULL REFERENCES state (id) ON DELETE CASCADE
);

CREATE TABLE constituency_candidacy
(
  candidate_id    INTEGER PRIMARY KEY REFERENCES candidate (id) ON DELETE CASCADE,
  constituency_id INTEGER NOT NULL REFERENCES constituency (id) ON DELETE CASCADE
);

CREATE TABLE first_result
(
  id           SERIAL PRIMARY KEY,
  candidate_id INTEGER NOT NULL REFERENCES constituency_candidacy (candidate_id)
);

CREATE TABLE second_result
(
  id              SERIAL PRIMARY KEY,
  state_list_id   INTEGER NOT NULL REFERENCES state_list (id),
  constituency_id INTEGER NOT NULL REFERENCES constituency (id)
);
CREATE TABLE aggregated_first_result
(
  id           SERIAL PRIMARY KEY,
  candidate_id INTEGER NOT NULL REFERENCES constituency_candidacy (candidate_id),
  count        INTEGER
);

CREATE TABLE aggregated_second_result
(
  id              SERIAL PRIMARY KEY,
  state_list_id   INTEGER NOT NULL REFERENCES state_list (id),
  constituency_id INTEGER NOT NULL REFERENCES constituency (id),
  count           INTEGER
);
