-- Query 1: Compute the seat distribution in the Bundestag.

  -- See view party_state_seats in seat_distribution.sql

-- Query 2: Compute all members in the Bundestag.

CREATE OR REPLACE VIEW bundestag_candidates (election_id, state_id, constituency_id, candidate_id, candidate_name, party_id, direct_candidate) AS (
    SELECT c.election_id, state_id, constituency_id, candidate_id, c.name, party_id, TRUE
    FROM elected_candidates ec
      JOIN candidate c USING (candidate_id)
      JOIN constituency_candidacy USING (candidate_id)
      JOIN constituency USING (constituency_id)
    WHERE direct_candidate = TRUE
    UNION ALL
    SELECT election_id, state_id, 0, candidate_id, name, party_id, FALSE
    FROM elected_candidates
      JOIN state_candidacy USING (candidate_id)
      JOIN state_list USING (state_list_id)
      JOIN candidate USING (candidate_id, party_id)
    WHERE direct_candidate = FALSE
);

-- Query 3.1: Compute the election turnout for each constituency.

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

-- Query 3.2: Compute all elected direct candidates (constituency winners)

  -- See view constituency_winners in seat_distribution.sql

-- Query 3.3: Compute the relative and absolute number of votes for each party.

CREATE OR REPLACE VIEW constituency_votes (party_id, constituency_id, absoluteVotes, percentualVotes, totalVotes) AS (
  SELECT cpv.party_id, cpv.constituency_id, SUM(votes), SUM(votes) / total.totalvotes :: REAL, total.totalvotes
  FROM constituency_party_votes cpv
    JOIN (SELECT constituency_id, sum(votes) AS totalvotes
          FROM constituency_party_votes
          GROUP BY constituency_id) total USING (constituency_id)
  GROUP BY party_id, constituency_id, total.totalvotes
);

-- Query 3.4: Compare election results to the previous election.

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

-- Query 4: Compute the best party for each constituency (first and second results).

CREATE OR REPLACE VIEW constituency_winner_parties (election_id, constituency_id, firstVotePartyId, secondVotePartyId) AS (
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

    SELECT election_id, constituency_id, fv.party_id, sv.party_id
    FROM constituency_winners_first_votes fv JOIN constituency_winners_second_votes sv
      USING (election_id, constituency_id)
);

-- Query 5: Show all overhead seats.

  -- See view state_party_seats in seat_distribution.sql

-- Query 6: Compute the closest winners or losers for each party.
-- Usage: Use "ranking" for top x:  SELECT * FROM top_close_constituency_candidates WHERE ranking <=10;

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

-- Query 7: Show election results without using aggregated data.
-- Some manual stuff required like rerunning the seat_distribution.sql and analysis.sql for restoring views which were deleted by cascading

/*DROP MATERIALIZED VIEW aggregated_first_result CASCADE;
DROP MATERIALIZED VIEW aggregated_second_result CASCADE;
CREATE VIEW aggregated_first_result (candidate_id, count) AS (
  SELECT candidate_id, COUNT(*) AS count
  FROM first_result
  GROUP BY candidate_id
);
CREATE VIEW aggregated_second_result (state_list_id, constituency_id, count) AS (
  SELECT state_list_id, constituency_id, COUNT(*) AS count
  FROM second_result
  GROUP BY state_list_id, constituency_id
);*/
--RERUN OF analysis.sql and seat_distribution.sql required (because of cascading)!!
