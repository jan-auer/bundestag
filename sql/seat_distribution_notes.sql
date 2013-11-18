
CREATE OR REPLACE VIEW StateVote (state_id, votes) AS (
  SELECT
    sl.state_id,
    SUM(sr.count)
  FROM Aggregated_Second_Result sr
    JOIN State_List sl
      ON sr.state_list_id = sl.id
    JOIN State s
      ON sl.state_id = s.id
  GROUP BY sl.state_id
);

CREATE OR REPLACE VIEW StatePartyVote (state_id, party_id, votes) AS (
  SELECT
    sl.state_id,
    sl.party_id,
    SUM(sr.count) / CAST(sv.votes AS DECIMAL)
  FROM StateVote sv
    JOIN State_List sl
      ON sl.state_id = sv.state_id
    JOIN Aggregated_Second_Result sr
      ON sr.state_list_id = sl.id
  GROUP BY sl.state_id, sl.party_id, sv.votes
  ORDER BY sl.state_id, sl.party_id
);

SELECT
  spv.state_id,
  ROUND(spv.votes * ss.seats)
FROM StatePartyVote spv
  JOIN StateSeats ss
    ON spv.state_id = ss.state_id;

--SELECT * FROM Party p JOIN State_List sl ON sl.party_id = p.id JOIN StateSeats ss ON ss.state_id = sl.state_id;

--SELECT   sv.state_id,   CAST(sv.votes AS DECIMAL) / cv.votes FROM StateVote sv, CountryVote cv



CREATE OR REPLACE VIEW CountryVote (votes) AS (
  SELECT
    SUM(sr.count)
  FROM Aggregated_Second_Result sr
);





--SELECT   spv.state_id,   ROUND(spv.votes * ss.seats) FROM StatePartyVote spv   JOIN StateSeats ss     ON spv.state_id = ss.state_id;


CREATE RECURSIVE VIEW StateSeatsScheper(election_id, population, divisor, seats) AS (
  SELECT
    s.election_id,
    C.population,
    C.population / 598,
    SUM(s.population / (C.population / 598))
  FROM Country C JOIN State s
      ON s.election_id = C.election_id
  GROUP BY s.election_id, C.population

  UNION ALL

  SELECT
    sss.election_id,
    sss.population,
    sss.divisor * (1 - 1 / 598),
    SUM(s.population / (sss.divisor * (1 - 1 / sss.population)))
  FROM StateSeatsScheper sss
    JOIN State s
      ON s.election_id = sss.election_id
  WHERE sss.seats < 598
  GROUP BY sss.election_id, sss.population, sss.divisor

);
--round!