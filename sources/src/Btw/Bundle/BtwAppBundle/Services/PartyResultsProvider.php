<?php

namespace Btw\Bundle\BtwAppBundle\Services;

use Btw\Bundle\BtwAppBundle\Model\PartyResult;
use Btw\Bundle\BtwAppBundle\Model\SeatsResult;
use Btw\Bundle\BtwAppBundle\Model\VotesResult;
use Btw\Bundle\PersistenceBundle\Entity\Election;
use Doctrine\ORM\EntityManager;

/**
 * Provides election results for certain parties.
 */
class PartyResultsProvider extends AbstractProvider
{

	/**
	 * @param EntityManager $entityManager
	 */
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
		return $this->executeMappedQuery($query, function ($result) {
			return SeatsResult::fromArray($result);
		});
	}

	/**
	 * @param Election $election
	 * @param Election $previousElection
	 *
	 * @return VotesResult[]
	 *
	 * @see ConstituencyProvider::getAllDetailsForElection
	 */
	public function getVotesForElection(Election $election, $previousElection = null)
	{
		if (is_null($previousElection)) {
			$query = $this->prepareQuery("
			SELECT state_id AS state, constituency_id AS constituency, party_id AS party, absolutevotes :: INT AS votes
			FROM constituency_votes cv
			  JOIN constituency c USING (constituency_id)
			  JOIN state s USING (election_id, state_id)
			  JOIN party p USING (election_id, party_id)
			  JOIN election USING (election_id)
			WHERE date_part('Y', date) = :new");
		} else {
			$query = $this->prepareQuery("
			SELECT state_id AS state, constituency_id AS constituency, party_id AS party, absolutevotes :: INT AS votes, oldabsolutevotes :: INT AS votes_prev
			FROM constituency_votes cv
			  JOIN constituency c USING (constituency_id)
			  JOIN state s USING (election_id, state_id)
			  JOIN party p USING (election_id, party_id)
			  LEFT JOIN constituency_votes_history h ON (h.constituency_name = c.name AND p.abbreviation = h.party_abbreviation)
			WHERE ( (date_part('Y', newdate) = :new AND date_part('Y', olddate) = :old) OR (newdate IS NULL AND olddate IS NULL) )
			 AND election_id = :election_id");

			$query->bindValue('old', date('Y', $previousElection->getDate()->getTimestamp()));
			$query->bindValue('election_id', $election->getId());
		}

		$query->bindValue('new', date('Y', $election->getDate()->getTimestamp()));
		return $this->executeMappedQuery($query, function ($result) {
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
		return $this->executeMappedQuery($query, function ($result) {
			return PartyResult::fromArray($result);
		});
	}

}
