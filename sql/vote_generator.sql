-- ze single votes generator magix

INSERT INTO first_result (candidate_id)
  SELECT candidate_id
  FROM (
         SELECT candidate_id, generate_series(1, count)
         FROM aggregated_first_result
       ) t;

DROP TABLE aggregated_first_result CASCADE;
CREATE MATERIALIZED VIEW aggregated_first_result (candidate_id, count) AS (
  SELECT candidate_id, COUNT(*) AS count
  FROM first_result
  GROUP BY candidate_id
);

INSERT INTO second_result (state_list_id, constituency_id)
  SELECT state_list_id, constituency_id
  FROM (
         SELECT state_list_id, constituency_id, generate_series(1, count)
         FROM aggregated_second_result
       ) t;

DROP TABLE aggregated_second_result CASCADE;
CREATE MATERIALIZED VIEW aggregated_second_result (state_list_id, constituency_id, count) AS (
  SELECT state_list_id, constituency_id, COUNT(*) AS count
  FROM second_result
  GROUP BY state_list_id, constituency_id
);
--RERUN OF analysis.sql and seat_distribution.sql required (because of cascading)!!