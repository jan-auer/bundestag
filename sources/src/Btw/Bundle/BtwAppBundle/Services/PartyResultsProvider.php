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
	 * @param Election $previousElection
	 *
	 * @return VotesResult[]
	 */
	public function getVotesForElection(Election $election, $previousElection)
	{
		$query = $this->prepareQuery("
			SELECT state_id AS state, constituency_id AS constituency, party_id AS party, absoluteVotes :: INT AS votes, oldAbsoluteVotes :: INT AS votes_prev
			FROM constituency_votes cv
			  JOIN constituency c USING (constituency_id)
			  JOIN state s USING (state_id)
			  JOIN party p USING (party_id)
			  LEFT JOIN constituency_votes_history h ON (h.constituency_name = c.name AND p.abbreviation = h.party_abbreviation)
			WHERE date_part('Y', newDate) = :new AND date_part('Y', oldDate) = :old");

		$query->bindValue('new', date('Y', $election->getDate()->getTimestamp()));
		$query->bindValue('old', is_null($previousElection) ? 0 : date('Y', $previousElection->getDate()->getTimestamp()));
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
