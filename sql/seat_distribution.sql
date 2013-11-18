DROP VIEW IF EXISTS StateSeats CASCADE;
DROP VIEW IF EXISTS CountryVote CASCADE;
DROP VIEW IF EXISTS StateVote CASCADE;
DROP VIEW IF EXISTS StateQuota CASCADE;
DROP VIEW IF EXISTS Country CASCADE;

CREATE OR REPLACE VIEW Country AS (
  SELECT
    election_id,
    SUM(population) AS population
  FROM State
  GROUP BY election_id
);

CREATE OR REPLACE VIEW StateQuota(election_id, state_id, quota_of_seats) AS (
  SELECT
    s.election_id,
    s.id,
    CAST(s.population AS DECIMAL) / c.population
  FROM State s
    JOIN Country c
      ON s.election_id = c.election_id
);

CREATE OR REPLACE VIEW StateSeats(election_id, state_id, seats) AS (
  SELECT
    election_id,
    state_id,
    ROUND(quota_of_seats * 598)
  FROM StateQuota
);



