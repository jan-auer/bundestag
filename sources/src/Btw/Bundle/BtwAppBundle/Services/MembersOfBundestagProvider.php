<?php

namespace Btw\Bundle\BtwAppBundle\Services;

use Btw\Bundle\BtwAppBundle\Model\MemberOfBundestag;
use Btw\Bundle\PersistenceBundle\Entity\Constituency;
use Btw\Bundle\PersistenceBundle\Entity\Election;
use Btw\Bundle\PersistenceBundle\Entity\State;
use Doctrine\ORM\EntityManager;

class MembersOfBundestagProvider
	extends AbstractProvider
{

	function __construct(EntityManager $entityManager)
	{
		parent::__construct($entityManager);
	}

	/**
	 * @param Election $election
	 *
	 * @return MemberOfBundestag[]
	 */
	public function getAllForElection(Election $election)
	{
		$query = $this->prepareQuery("
			SELECT c.candidate_id AS candidate, c.name AS name, state_id AS state, constituency_id AS constituency, c.party_id AS party
			FROM candidate c
			  JOIN constituency_winners cw USING(candidate_id)
			  JOIN constituency USING (constituency_id)
			  JOIN state USING (state_id)
			WHERE c.election_id= :election");
		$query->bindValue('election', $election->getId());
		$direct = $this->executeQuery($query, function ($result) {
			return MemberOfBundestag::fromArray($result);
		});

		$query = $this->prepareQuery("SELECT c.candidate_id AS candidate, c.name AS name, sl.state_id AS state, 0 AS constituency, c.party_id AS party
										   FROM Candidate c
										    JOIN elected_candidates USING(candidate_id)
										    JOIN state_candidacy USING (candidate_id)
										    JOIN state_list sl USING (state_list_id)
										   WHERE c.election_id = :election AND c.candidate_id NOT IN
										   		(SELECT candidate_id
										         FROM Candidate
										          JOIN constituency_winners USING (candidate_id)
										         WHERE election_id=:election)");
		$query->bindValue('election', $election->getId());

		$indirect = $this->executeQuery($query, function ($result) {
			return MemberOfBundestag::fromArray($result);
		});

		return array_merge($direct, $indirect);
	}

	/**
	 * @param Election $election
	 *
	 * @return MemberOfBundestag[]
	 *
	 * @todo: use the view `elected_candidates` instead
	 */
	public function forCountry(Election $election)
	{
		$query = $this->prepareQuery("
			SELECT c.name AS name, p.abbreviation AS party
			FROM candidate c
			  JOIN constituency_winners cw USING (candidate_id)
			  JOIN party p USING (party_id)
			WHERE c.election_id=:election");

		$query->bindValue('election', $election->getId());
		$direct = $this->executeQuery($query, function ($result) {
			return MemberOfBundestag::fromArray($result);
		});

		$query = $this->prepareQuery("
			SELECT c.name AS name, p.abbreviation AS party
			FROM candidate c
			  JOIN elected_candidates ec USING (candidate_id)
			  JOIN party p USING (party_id)
			WHERE c.election_id = :election AND c.candidate_id NOT IN (
				SELECT c.candidate_id AS name
				FROM candidate c, constituency_winners cw
				WHERE c.candidate_id=cw.candidate_id AND c.election_id=:electionId
			)");

		$query->bindValue('election', $election->getId());
		$indirect = $this->executeQuery($query, function ($result) {
			return MemberOfBundestag::fromArray($result);
		});

		return array_merge($direct, $indirect);
	}

	/**
	 * @param State $state
	 *
	 * @return MemberOfBundestag[]
	 *
	 * @todo: use the view `elected_candidates` instead
	 */
	public function forState(State $state)
	{
		$query = $this->prepareQuery("
			SELECT c.name AS name, p.abbreviation AS party
			FROM candidate c
			  JOIN elected_candidates ec USING(candidate_id)
			  JOIN constituency_candidacy cc USING (candidate_id)
			  JOIN constituency ct USING (constituency_id)
			  JOIN party p USING (party_id)
			WHERE ct.state_id=:state");

		$query->bindParam('state', $state->getId());
		$direct = $this->executeQuery($query, function ($result) {
			return MemberOfBundestag::fromArray($result);
		});

		$query = $this->prepareQuery("
			SELECT c.name AS name, p.abbreviation AS party
			FROM candidate c
			  JOIN elected_candidates ec USING (candidate_id)
			  JOIN state_candidacy sc USING (candidate_id)
			  JOIN state_list sl USING(state_list_id)
			  JOIN party p USING (party_id)
			WHERE sl.state_id=:state");

		$query->bindParam('state', $state->getId());
		$indirect = $this->executeQuery($query, function ($result) {
			return MemberOfBundestag::fromArray($result);
		});

		return array_merge($direct, $indirect);
	}

	/**
	 * @param Constituency $constituency
	 *
	 * @return MemberOfBundestag[]
	 *
	 * @todo: use the view `elected_candidates` instead
	 */
	public function forConstituency(Constituency $constituency)
	{
		$query = $this->prepareQuery("
			SELECT c.name AS name, p.abbreviation AS party
			FROM candidate c
			  JOIN elected_candidates ec USING (candidate_id)
			  JOIN constituency_candidacy cc USING (candidate_id)
			  JOIN party p USING (party_id)
			WHERE cc.constituency_id=:constituency");

		$query->bindValue('constituency', $constituency->getId());
		$direct = $this->executeQuery($query, function ($result) {
			return MemberOfBundestag::fromArray($result);
		});


		$query = $this->prepareQuery("
			SELECT c.name AS name, p.abbreviation AS party
			FROM candidate c
			  JOIN elected_candidates ec USING (candidate_id)
			  JOIN constituency_candidacy cc USING (candidate_id)
			  JOIN party p USING (party_id)
			WHERE cc.constituency_id=:constituency");

		$query->bindValue('constituency', $constituency->getId());
		$indirect = $this->executeQuery($query, function ($result) {
			return MemberOfBundestag::fromArray($result);
		});

		return array_merge($direct, $indirect);
	}

}
