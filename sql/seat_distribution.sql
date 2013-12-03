DROP VIEW IF EXISTS state_seats CASCADE;
DROP VIEW IF EXISTS state_party_candidates CASCADE;

-- STEP 1: How many seats does each state get in the Bundestag?

CREATE OR REPLACE VIEW state_seats (state_id, seats) AS (
    WITH dhondt (state_id, rank) AS (
        SELECT id, row_number() OVER (PARTITION BY election_id ORDER BY population / (i - .5) DESC)
        FROM state, generate_series(1, 598) i
    )
    SELECT state_id, count(1)
    FROM dhondt
    WHERE rank <= 598
    GROUP BY state_id
);

-- STEP 2: Which parties have made it over the 5% threshold and what is the minimum
--         number of seats the parties get in each state?

CREATE OR REPLACE VIEW state_party_candidates (state_id, party_id, candidates) AS (
    WITH candidate_results (constituency_id, candidate_id, count) AS (
        SELECT constituency_id, candidate_id, count
        FROM aggregated_first_result
          NATURAL JOIN constituency_candidacy
    ), constituency_winners (constituency_id, candidate_id) AS (
        SELECT constituency_id, candidate_id
        FROM candidate_results r1
        WHERE NOT EXISTS (
            SELECT *
            FROM candidate_results r2
            WHERE r1.constituency_id = r2.constituency_id AND r1.count < r2.count
        )
    )
    SELECT state_id, party_id, count(1)
    FROM constituency_winners
      JOIN constituency ON constituency_id = constituency.id
      JOIN candidate ON candidate_id = candidate.id
    GROUP BY state_id, party_id
);

CREATE OR REPLACE VIEW state_party_votes (state_id, party_id, votes) AS (
    WITH party_candidates (party_id, candidate_count) AS (
        SELECT party_id, sum(candidates)
        FROM state_party_candidates
        GROUP BY party_id
    ), threshold (threshold) AS (
        SELECT 0.05 * sum(count)
        FROM aggregated_second_result
    ), valid_votes (party_id, votes) AS (
        SELECT party_id, sum(count) :: INT
        FROM aggregated_second_result
          JOIN state_list l ON state_list_id = l.id
          LEFT JOIN party_candidates USING (party_id)
          CROSS JOIN threshold
        GROUP BY threshold, party_id, candidate_count
        HAVING sum(count) >= threshold OR candidate_count >= 3
    )
    SELECT state_id, party_id, sum(count) :: INT AS votes
    FROM valid_votes
      JOIN state_list l USING (party_id)
      JOIN aggregated_second_result ON l.id = state_list_id
    GROUP BY state_id, party_id
);

CREATE OR REPLACE VIEW state_party_seats (state_id, party_id, seats) AS (
    WITH dhondt (state_id, seats, party_id, rank) AS (
        SELECT state_id, seats, party_id, row_number() OVER (PARTITION BY state_id ORDER BY votes / (i - .5) DESC)
        FROM state_seats
          NATURAL JOIN state_party_votes
          CROSS JOIN generate_series(1, seats) i
    ), proportional_seats (state_id, party_id, seats) AS (
        SELECT state_id, party_id, count(1)
        FROM dhondt
        WHERE rank <= seats
        GROUP BY state_id, party_id
    )
    SELECT state_id, party_id, greatest(seats, candidates)
    FROM proportional_seats
      LEFT JOIN state_party_candidates USING (state_id, party_id)
);

-- STEP 3: What is the full size of the Bundestag to retain both vote
--         proportions and minimum seats for each party?

CREATE OR REPLACE VIEW party_seats (party_id, seats, candidates) AS (
    WITH total_votes (v) AS (
        SELECT sum(votes) :: REAL
        FROM state_party_votes
    ), total_seats (s) AS (
        SELECT sum(seats) :: REAL
        FROM state_party_seats
    ), party_seats_votes (party_id, seats, votes) AS (
        SELECT party_id, sum(seats), sum(votes)
        FROM state_party_seats
          NATURAL JOIN state_party_votes
        GROUP BY party_id
    ), divisor (divisor) AS (
        SELECT votes / (seats - .49)
        FROM party_seats_votes, total_seats, total_votes
        ORDER BY seats / s - votes / v DESC
        LIMIT 1
    ), party_candidates (party_id, candidates) AS (
        SELECT party_id, sum(candidates) :: INT
        FROM state_party_candidates
        GROUP BY party_id
    )
    SELECT party_id, round(votes / divisor) :: INT, coalesce(candidates, 0)
    FROM divisor, party_seats_votes
      LEFT JOIN party_candidates using (party_id)
);

-- STEP 4: How many seats does each party get for its state lists?

CREATE OR REPLACE VIEW party_state_seats (party_id, state_id, seats) AS (
    WITH dhondt (party_id, state_id, num, rank) AS (
        SELECT party_id, state_id, votes / (i - .5),
          row_number() OVER (PARTITION BY party_id, state_id ORDER BY votes / (i - .5) DESC)
        FROM party_seats
          NATURAL JOIN state_party_votes
          CROSS JOIN generate_series(1, seats) i
    ), selected (party_id, state_id, rank) AS (
        SELECT party_id, state_id, row_number() OVER (PARTITION BY party_id ORDER BY num DESC)
        FROM dhondt
          LEFT JOIN state_party_candidates USING (party_id, state_id)
        WHERE rank > coalesce(candidates, 0)
    ), additional_seats (party_id, state_id, seats) AS (
        SELECT party_id, state_id, count(1)
        FROM selected
          NATURAL JOIN party_seats
        WHERE rank <= seats - candidates
        GROUP BY party_id, state_id
    )
    SELECT party_id, state_id, coalesce(seats, 0) + coalesce(candidates, 0)
    FROM additional_seats
      FULL JOIN state_party_candidates USING (party_id, state_id)
);

-- ===================================================================
