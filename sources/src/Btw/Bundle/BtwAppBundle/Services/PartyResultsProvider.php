<?php

namespace Btw\Bundle\BtwAppBundle\Services;

use Btw\Bundle\BtwAppBundle\Model\PartyResult;
use Btw\Bundle\BtwAppBundle\Model\SeatsResult;
use Btw\Bundle\BtwAppBundle\Model\VotesResult;
use Btw\Bundle\PersistenceBundle\Entity\Election;
use Doctrine\ORM\EntityManager;

class PartyResultsProvider
	extends AbstractProvider
{

	function __construct(EntityManager $entityManager)
	{
		parent::__construct($entityManager);
	}

	/**
	 * @param Election $election
	 *
	 * @return SeatsResult[]
	 */
	public function getSeatsForElection(Election $election)
	{
		$query = $this->prepareQuery("
			SELECT state_id state, party_id party, sum(pss.seats) seats, sum(overhead) overhead
			FROM party_state_seats pss
			  JOIN state_party_seats sps USING (state_id, party_id)
			WHERE election_id = :election
			GROUP BY state_id, party_id");

		$query->bindValue('election', $election->getId());
		return $this->executeQuery($query, function ($result) {
			return SeatsResult::fromArray($result);
		});
	}

	/**
	 * @param Election $election
	 *
	 * @return VotesResult[]
	 */
	public function getVotesForElection(Election $election)
	{
		$query = $this->prepareQuery("
			SELECT state_id AS state, constituency_id AS constituency, party_id AS party, absolutevotes :: INT AS votes
			FROM constituency_votes cv
			  JOIN constituency USING (constituency_id)
			  JOIN state s USING (state_id)
			WHERE s.election_id = :election");

		$query->bindValue('election', $election->getId());
		return $this->executeQuery($query, function ($result) {
			return VotesResult::fromArray($result);
		});
	}

	/**
	 * @param Election $election
	 *
	 * @return PartyResult[]
	 */
	public function forCountry(Election $election)
	{
		$query = $this->prepareQuery("
			SELECT party_id, abbreviation AS abbr, name, color, party_seats.seats AS seats, SUM(overhead) AS overhead
			FROM party_seats
			  JOIN state_party_seats USING (party_id)
			  JOIN party USING (party_id)
			WHERE election_id = :election
			GROUP BY party_id, abbreviation, name, color, party_seats.seats");

		$query->bindValue('election', $election->getId());
		return $this->executeQuery($query, function ($result) {
			return PartyResult::fromArray($result);
		});
	}

}
