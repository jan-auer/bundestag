-- Q1
-- See view party_state_seats in seat_distribution.sql
-- ===================================================================
-- Q2
-- See view elected_candidates in seat_distribution.sql
-- ===================================================================
-- Q3.1
CREATE OR REPLACE VIEW constituency_turnout (constituency_id, turnout, voters, electives) AS (
    WITH first_result_votes (constituency_id, votes) AS (
        SELECT constituency_id, SUM(count)
        FROM aggregated_first_result
          JOIN constituency_candidacy USING (candidate_id)
        GROUP BY constituency_id
    ), second_result_votes (constituency_id, votes) AS (
        SELECT constituency_id, SUM(count)
        FROM aggregated_second_result
        GROUP BY constituency_id
    )

    SELECT constituency_id, greatest(frv.votes, srv.votes) / electives :: REAL, greatest(frv.votes, srv.votes), electives
    FROM first_result_votes frv
      JOIN second_result_votes srv
      USING (constituency_id)
      JOIN constituency USING (constituency_id)
);

-- Q3.2
-- See view constituency_winners in seat_distribution.sql

-- Q3.3
CREATE OR REPLACE VIEW constituency_votes (party_id, constituency_id, absoluteVotes, percentualVotes, totalVotes) AS (
  SELECT party_id, constituency_id, SUM(votes), SUM(votes) / total.totalvotes :: REAL, total.totalvotes
  FROM constituency_party_votes
    NATURAL JOIN state_list,
    (SELECT sum(count) AS totalvotes
     FROM aggregated_second_result) total
  GROUP BY party_id, constituency_id, total.totalvotes
);

-- Q3.4
CREATE OR REPLACE VIEW constituency_votes_history (oldDate, newDate, constituency_name, party_abbreviation, oldAbsoluteVotes, oldTotalVotes, newAbsoluteVotes, newTotalVotes) AS (
    WITH constituency_election (date, constituency_name, party_abbreviation, absoluteVotes, totalVotes) AS (
        SELECT date, c.name, p.abbreviation, absoluteVotes, totalVotes
        FROM constituency c JOIN constituency_votes USING (constituency_id)
          JOIN election USING (election_id)
          JOIN party p
          USING (party_id)
    )
    SELECT old.date, new.date, old.constituency_name, old.party_abbreviation, old.absoluteVotes, old.totalVotes, new.absoluteVotes, new.totalVotes
    FROM constituency_election old, constituency_election new
    WHERE old.constituency_name = new.constituency_name AND old.date < new.date AND
          old.party_abbreviation = new.party_abbreviation
);

SELECT * FROM constituency_votes_history;