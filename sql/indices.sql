--
-- INDEX FOR TABLE 'state'
--
-- Speed up foreign key access between table 'state' and 'election'.
CREATE INDEX state_election_id ON state USING HASH (election_id);

--
-- INDEX FOR TABLE 'party'
--
-- Speed up foreign key access between table 'party' and 'election'.
CREATE INDEX party_election_id ON party USING HASH (election_id);
-- Speed up scans for a particular abbreviation used for election comparisons.
CREATE INDEX party_abbreviation ON party USING HASH (abbreviation);

--
-- INDEX FOR TABLE 'state_list'
--
-- Speed up foreign key access between 'state_list' and 'state'.
CREATE INDEX state_list_state_id ON state_list USING HASH (state_id);
-- Speed up foreign key access between 'state_list' and 'party'.
CREATE INDEX state_list_party_id ON state_list USING HASH (party_id);

--
-- INDEX FOR TABLE 'candidate'
--
-- Speed up foreign key access between table 'candidate' and 'party'.
CREATE INDEX candidate_party_id ON candidate USING HASH (party_id);
-- Speed up foreign key access between table 'candidate' and 'election'.
CREATE INDEX candidate_election_id ON candidate USING HASH (election_id);

--
-- INDEX FOR TABLE 'state_candidacy'
--
-- Speed up foreign key access and ordering by position within a list.
CREATE INDEX state_candidacy_state_list_position ON state_candidacy USING BTREE (state_list_id, position);

--
-- INDEX FOR TABLE 'constituency'
--
-- Speed up foreign key access between 'constituency' and 'state'.
CREATE INDEX constituency_state_id ON constituency USING HASH (state_id);
-- Speed up foreign key access between 'constituency' and 'election'.
CREATE INDEX constituency_election_id ON constituency USING HASH (election_id);

--
-- INDEX FOR TABLE 'constituency_candidacy'
--
-- Speed up foreign key access between 'constituency_candidacy' and 'constituency'.
CREATE INDEX constituency_candidacy_constituency_id ON constituency_candidacy USING HASH (constituency_id);

--
-- INDEX FOR TABLE 'voter'
--
-- Prevent duplicate voters per election.
CREATE UNIQUE INDEX voter_election_id ON voter (identity_number, election_id);
-- Speed up voter lookup by hash.
CREATE INDEX voter_hash ON voter USING HASH (hash);
