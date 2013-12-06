-- ze single votes generator magix

INSERT INTO first_result (candidate_id)
  SELECT candidate_id
  FROM (
         SELECT candidate_id, generate_series(1, count)
         FROM aggregated_first_result
       ) t;

INSERT INTO second_result (state_list_id, constituency_id)
  SELECT state_list_id, constituency_id
  FROM (
         SELECT state_list_id, constituency_id, generate_series(1, count)
         FROM aggregated_second_result
       ) t;
