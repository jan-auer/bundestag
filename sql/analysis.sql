-- Q1
-- See view party_state_seats in seat_distribution.sql
-- ===================================================================
-- Q2
CREATE OR REPLACE VIEW bundestag_candidates (state_id, constituency_id, candidate_id, party_id, directCandidate) AS (
  SELECT state_id, constituency_id, candidate_id, party_id, 1
  FROM elected_candidates ec
    JOIN candidate USING (candidate_id)
    JOIN constituency_candidacy USING (candidate_id)
    JOIN constituency c USING(constituency_id)
    JOIN state USING (state_id)
  WHERE candidate_id IN (SELECT candidate_id
                         FROM constituency_winners)
  UNION ALL
  SELECT  sl.state_id, 0, candidate_id, party_id, 0
  FROM elected_candidates
    JOIN state_candidacy USING (candidate_id)
    JOIN state_list sl USING (state_list_id)
  WHERE candidate_id NOT IN (SELECT candidate_id
                             FROM constituency_winners)
);
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
    NATURAL JOIN state_list
    JOIN (SELECT constituency_id, sum(count) AS totalvotes
     FROM aggregated_second_result
     GROUP BY constituency_id) total USING (constituency_id)
  GROUP BY party_id, constituency_id, total.totalvotes
);

-- Q3.4
CREATE OR REPLACE VIEW constituency_votes_history (oldDate, newDate, constituency_name, party_abbreviation, oldAbsoluteVotes, oldTotalVotes, newAbsoluteVotes, newTotalVotes) AS (
    WITH constituency_election (date, constituency_name, party_abbreviation, absoluteVotes, totalVotes) AS (
        SELECT date, c.name, p.abbreviation, absoluteVotes, totalVotes
        FROM constituency c JOIN constituency_votes USING (constituency_id)
          JOIN election USING (election_id)
          JOIN party p USING (party_id)
    )
    SELECT old.date, new.date, old.constituency_name, old.party_abbreviation, old.absoluteVotes, old.totalVotes, new.absoluteVotes, new.totalVotes
    FROM constituency_election old, constituency_election new
    WHERE old.constituency_name = new.constituency_name AND old.date < new.date AND
          old.party_abbreviation = new.party_abbreviation
);
-- ===================================================================
-- Q4
WITH constituency_winners_second_votes (election_id, constituency_id, party_id) AS (
    SELECT election_id, constituency_id, party_id
    FROM aggregated_second_result r1
      JOIN state_list USING (state_list_id)
      JOIN party USING (party_id)
    WHERE NOT EXISTS(SELECT *
                     FROM aggregated_second_result r2
                     WHERE r1.constituency_id = r2.constituency_id AND r1.count < r2.count)
), constituency_winners_first_votes (election_id, constituency_id, party_id) AS (
    SELECT election_id, constituency_id, party_id
    FROM constituency_winners
      NATURAL JOIN candidate
)

SELECT election_id, constituency_id, fv.party_id AS firstVotePartyId, sv.party_id AS secondVotePartyId
FROM constituency_winners_first_votes fv JOIN constituency_winners_second_votes sv
  USING (election_id, constituency_id);
-- ===================================================================
-- Q6 USE RANKING FOR TOP X:  SELECT * FROM top_close_constituency_candidates WHERE ranking <=10;
CREATE OR REPLACE VIEW close_constituency_winners (election_id, party_id, constituency_id, candidate_id, ranking) AS (
    WITH constituency_second_winners (constituency_id, candidate_id, count) AS (
        SELECT constituency_id, candidate_id, count FROM (
                                                           SELECT constituency_id, candidate_id, count, row_number() OVER (PARTITION BY constituency_id ORDER BY count DESC) AS ranking
                                                           FROM candidate_results) t
        WHERE t.ranking=2
    ), constituency_winners_votes_diff (constituency_id, candidate_id, party_id, votes_diff) AS (
        SELECT w.constituency_id, w.candidate_id, party_id, w.count - sw.count
        FROM constituency_winners w JOIN candidate USING (candidate_id)
          JOIN constituency_second_winners sw
          USING (constituency_id)
    )

    SELECT election_id, party_id, constituency_id, candidate_id, ranking
    FROM (SELECT election_id, party_id, constituency_id, candidate_id, row_number() OVER (PARTITION BY party_id ORDER BY votes_diff DESC) AS ranking
          FROM party
            JOIN constituency_winners_votes_diff USING (party_id)) t
);


CREATE OR REPLACE VIEW close_constituency_loosers (election_id, party_id, constituency_id, candidate_id, ranking) AS (

    WITH constituency_loosers (constituency_id, candidate_id, count) AS (
        SELECT constituency_id, candidate_id, count
        FROM candidate_results r1
        WHERE candidate_id NOT IN (
          SELECT candidate_id
          FROM constituency_winners
        )
    ), constituency_loosers_votes_diff (constituency_id, candidate_id, party_id, votes_diff) AS (
        SELECT constituency_id, cl.candidate_id, party_id, (cw.count - cl.count)
        FROM constituency_loosers cl JOIN candidate USING (candidate_id)
          JOIN constituency_winners cw
          USING (constituency_id)
    )

    SELECT election_id, party_id, constituency_id, candidate_id, ranking
    FROM (SELECT election_id, party_id, constituency_id, candidate_id, row_number() OVER (PARTITION BY party_id ORDER BY votes_diff DESC) AS ranking
          FROM party
            JOIN constituency_loosers_votes_diff USING (party_id)) t
    ORDER BY ranking
);

CREATE OR REPLACE VIEW top_close_constituency_candidates (election_id, party_id, constituency_id, candidate_id, ranking, type) AS (
    SELECT *, 'W'
    FROM close_constituency_winners
  UNION ALL
    SELECT *, 'L'
    FROM close_constituency_loosers
    WHERE (party_id) NOT IN (SELECT party_id
                                            FROM close_constituency_winners)
);
