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
    SELECT constituency_id, greatest(frv.votes, srv.votes) / electives :: REAL,
      greatest(frv.votes, srv.votes), electives
    FROM first_result_votes frv
      JOIN second_result_votes srv
      USING (constituency_id)
      JOIN constituency USING (constituency_id)
);

-- Query 3.2: Compute all elected direct candidates (constituency winners)

  -- See view constituency_winners in seat_distribution.sql

-- Query 3.3: Compute the relative and absolute number of votes for each party.

CREATE OR REPLACE VIEW constituency_votes (party_id, constituency_id, absoluteVotes, percentualVotes, totalVotes) AS (
    WITH total (constituency_id, total_votes) AS (
        SELECT constituency_id, sum(votes)
        FROM constituency_party_votes
        GROUP BY constituency_id
    )
    SELECT party_id, constituency_id, SUM(votes), SUM(votes) / total_votes :: REAL, total_votes
    FROM constituency_party_votes
      JOIN total USING (constituency_id)
    GROUP BY party_id, constituency_id, total_votes
);

-- Query 3.4: Compare election results to the previous election.

CREATE OR REPLACE VIEW constituency_votes_history (olddate, newdate, constituency_name, party_abbreviation, oldabsolutevotes, oldtotalvotes, newabsolutevotes, newtotalvotes) AS (
    WITH constituency_election (date, constituency_name, party_abbreviation, absolutevotes, totalvotes) AS (
        SELECT date, c.name, p.abbreviation, absolutevotes, totalvotes
        FROM constituency c JOIN constituency_votes USING (constituency_id)
          JOIN election USING (election_id)
          JOIN party p USING (party_id)
    )
    SELECT old.date, new.date, old.constituency_name, old.party_abbreviation, old.absolutevotes,
           old.totalvotes, new.absolutevotes, new.totalvotes
    FROM constituency_election old, constituency_election new
    WHERE old.constituency_name = new.constituency_name AND old.date < new.date AND
          old.party_abbreviation = new.party_abbreviation
);

-- Query 4: Compute the best party for each constituency (first and second results).

CREATE OR REPLACE VIEW constituency_winner_parties (election_id, constituency_id, firstvotepartyid, secondvotepartyid) AS (
    WITH constituency_winners_second_votes (election_id, constituency_id, party_id) AS (
        SELECT election_id, constituency_id, party_id
        FROM aggregated_second_result r1
          JOIN state_list USING (state_list_id)
          JOIN party USING (party_id)
        WHERE NOT EXISTS(
            SELECT *
            FROM aggregated_second_result r2
            WHERE r1.constituency_id = r2.constituency_id AND r1.count < r2.count
        )
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

CREATE OR REPLACE VIEW top_close_constituency_candidates (election_id, party_id, constituency_id, candidate_id, ranking, type) AS (
    WITH candidate_ranking (election_id, party_id, constituency_id, candidate_id, count, ranking) AS (
        SELECT election_id, party_id, constituency_id, candidate_id, count,
          row_number() OVER (PARTITION BY constituency_id ORDER BY count DESC)
        FROM candidate_results
          JOIN candidate USING (candidate_id)
    ),
    constituency_winners_diff (election_id, party_id, constituency_id, candidate_id, diff) AS (
        SELECT election_id, w.party_id, constituency_id, w.candidate_id, w.count - sw.count
        FROM candidate_ranking w
          JOIN candidate_ranking sw USING (election_id, constituency_id)
        WHERE w.ranking = 1 AND sw.ranking = 2
    ),
    constituency_losers_diff (election_id, party_id, constituency_id, candidate_id, diff) AS (
        SELECT election_id, l.party_id, constituency_id, l.candidate_id, w.count - l.count
        FROM candidate_ranking w
          JOIN candidate_ranking l USING (election_id, constituency_id)
        WHERE w.ranking = 1 AND l.ranking > 1
    ),
    close_constituency_winners (election_id, party_id, constituency_id, candidate_id, ranking) AS (
        SELECT election_id, party_id, constituency_id, candidate_id,
          row_number() OVER (PARTITION BY party_id ORDER BY diff ASC)
        FROM constituency_winners_diff
    ),
    close_constituency_losers (election_id, party_id, constituency_id, candidate_id, ranking) AS (
        SELECT election_id, party_id, constituency_id, candidate_id,
          row_number() OVER (PARTITION BY party_id ORDER BY diff ASC)
        FROM constituency_losers_diff
    )
    SELECT *, 'W'
    FROM close_constituency_winners
    UNION ALL
    SELECT *, 'L'
    FROM close_constituency_losers
    WHERE party_id NOT IN (SELECT DISTINCT party_id FROM close_constituency_winners)
    ORDER BY ranking ASC
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
