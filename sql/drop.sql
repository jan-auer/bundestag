-- Analysis
DROP VIEW IF EXISTS top_close_constituency_candidates;
DROP VIEW IF EXISTS close_constituency_loosers;
DROP VIEW IF EXISTS close_constituency_winners;
DROP VIEW IF EXISTS constituency_votes_history;
DROP VIEW IF EXISTS constituency_votes;
DROP VIEW IF EXISTS constituency_turnout;

-- Seat Distribution
DROP VIEW IF EXISTS elected_candidates;
DROP VIEW IF EXISTS party_state_seats;
DROP VIEW IF EXISTS party_seats;
DROP VIEW IF EXISTS state_party_seats;
DROP VIEW IF EXISTS state_party_votes;
DROP VIEW IF EXISTS constituency_party_votes;
DROP VIEW IF EXISTS state_party_candidates;
DROP VIEW IF EXISTS constituency_winners;
DROP VIEW IF EXISTS candidate_results;
DROP VIEW IF EXISTS state_seats;

-- Schema
DROP TABLE IF EXISTS first_result;
DROP TABLE IF EXISTS second_result;
DROP TABLE IF EXISTS aggregated_first_result;
DROP TABLE IF EXISTS aggregated_second_result;
DROP TABLE IF EXISTS constituency_candidacy;
DROP TABLE IF EXISTS state_candidacy;
DROP TABLE IF EXISTS state_list;
DROP TABLE IF EXISTS candidate;
DROP TABLE IF EXISTS party;
DROP TABLE IF EXISTS constituency;
DROP TABLE IF EXISTS state;
DROP TABLE IF EXISTS voter;
DROP TABLE IF EXISTS election;
