<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 11.12.13
 * Time: 21:40
 */

namespace Btw\Bundle\BtwAppBundle\Services;


use Btw\Bundle\BtwAppBundle\Model\PartyResult;
use Btw\Bundle\BtwAppBundle\Model\SeatsResult;
use Btw\Bundle\BtwAppBundle\Model\VotesResult;
use Btw\Bundle\PersistenceBundle\Entity\State;
use Btw\Bundle\PersistenceBundle\Entity\Election;
use Btw\Bundle\PersistenceBundle\Entity\Constituency;
use Doctrine\ORM\EntityManager;

class PartyResultsProvider
{

	/** @var  EntityManager */
	protected $em;

	function __construct(EntityManager $entityManager)
	{
		$this->em = $entityManager;
	}

	/**
	 * @param Election $election
	 * @return array
	 */
	public function getSeatsForElection(Election $election)
	{
		$results = array();
		$connection = $this->em->getConnection();
		$statement = $connection->prepare("
			SELECT state_id state, party_id party, sum(pss.seats) seats, sum(overhead) overhead
			FROM party_state_seats pss
			  JOIN state_party_seats sps USING (state_id, party_id)
			WHERE election_id = :electionId
			GROUP BY state_id, party_id");
		$statement->bindValue('electionId', $election->getId());
		$statement->execute();
		foreach($statement->fetchAll() AS $result)
		{
			$seatResult = new SeatsResult();
			$seatResult->setPartyId($result['party']);
			$seatResult->setOverhead($result['overhead']);
			$seatResult->setSeats($result['seats']);
			$seatResult->setStateId($result['state']);
			$results[] = $seatResult;
		}

		return $results;
	}

	/**
	 * @param Election $election
	 * @return array
	 */
	public function getVotesForElection(Election $election)
	{
		$results = array();
		$connection = $this->em->getConnection();
		$statement = $connection->prepare("SELECT state_id AS state, constituency_id AS constituency, party_id AS party, absoluteVotes :: INT AS votes
										   FROM constituency_votes cv
										    JOIN constituency USING (constituency_id)
										    JOIN state s USING (state_id)
										   WHERE s.election_id = :electionId");
		$statement->bindValue('electionId', $election->getId());
		$statement->execute();
		foreach($statement->fetchAll() as $result)
		{
			$voteResult = new VotesResult();
			$voteResult->setStateId($result['state']);
			$voteResult->setConstituencyId($result['constituency']);
			$voteResult->setPartyId($result['party']);
			$voteResult->setVotes($result['votes']);
			$results[] = $voteResult;
		}

		return $results;
	}

	/**
	 * @param Election $election
	 * @return array
	 */
	public function forCountry(Election $election)
	{
		$partyResults = array();
		$connection = $this->em->getConnection();
		$statement = $connection->prepare("SELECT party_id, abbreviation AS abbr, name, color, party_seats.seats AS seats, SUM(overhead) AS overhead
										   FROM party_seats
										    JOIN state_party_seats using (party_id)
										    JOIN party using (party_id)
										    JOIN election using (election_id)
										   WHERE date_part('Y', date) = :electionYear
										   GROUP BY party_id, abbreviation, name, color, party_seats.seats;");
		$statement->bindValue('electionYear', date('Y', $election->getDate()->getTimestamp()));
		$statement->execute();
		foreach ($statement->fetchAll() AS $result) {
			$statement= $connection->prepare("");
			$partyResult = new PartyResult();
			$partyResult->setPartyAbbreviation($result['abbr']);
			$partyResult->setPartyFullName($result['name']);
			$partyResult->setColor($result['color']);
			$partyResult->setSeats($result['seats']);
			$partyResult->setOverhead($result['overhead']);
			$partyResult->setVotes('-');
			$partyResult->setPercentage('-');
			$partyResults[] = $partyResult;
		}

		return $partyResults;
	}

	/**
	 * @param State $state
	 * @return array
	 */
	public function forState(State $state)
	{
		$partyResults = array();
		$connection = $this->em->getConnection();
		$statement = $connection->prepare("SELECT abbreviation as abbr, party.name AS name, party.party_id AS party_id, color, SUM(party_state_seats.seats) :: INT as seats, SUM(overhead) AS overhead
										   FROM party_state_seats
										    JOIN party USING (party_id, election_id)
										    JOIN state_party_seats USING (state_id, party_id)
										   WHERE party_state_seats.state_id = :stateId
										   GROUP BY party_id, abbreviation, color, party.party_id, party.name");
		$statement->bindValue('stateId', $state->getId());
		$statement->execute();
		foreach ($statement->fetchAll() AS $result) {
			$partyId = $result['party_id'];
			$statement = $connection->prepare("SELECT SUM(absoluteVotes) AS votes, totalVotes as total
											   FROM constituency_votes cv, constituency c, state s
											   WHERE cv.constituency_id=c.constituency_id AND c.state_id=s.state_id AND s.state_id=:stateId AND cv.party_id=:partyId
											   GROUP BY s.state_id, cv.party_id, totalVotes");
			$statement->bindValue('stateId', $state->getId());
			$statement->bindValue('partyId', $partyId);
			$statement->execute();
			$votes = 0;
			$percentage = 0;
			foreach ($statement->fetchAll() AS $voteresult) {
				$total = $voteresult['total'];
				$votes = $voteresult['votes'];
				$percentage = $votes / $total;
			}

			$partyResult = new PartyResult();
			$partyResult->setPartyAbbreviation($result['abbr']);
			$partyResult->setPartyFullName($result['name']);
			$partyResult->setColor($result['color']);
			$partyResult->setSeats($result['seats']);
			$partyResult->setOverhead($result['overhead']);
			$partyResult->setVotes($votes);
			$partyResult->setPercentage($percentage);
			$partyResults[] = $partyResult;
		}

		return $partyResults;
	}

	/**
	 * @param Constituency $constituency
	 * @return array
	 */
	public function forConstituency(Constituency $constituency)
	{
		$partyResults = array();
		$connection = $this->em->getConnection();
		$statement = $connection->prepare("SELECT abbreviation AS abbr, p.name AS name, color, absoluteVotes :: INT AS votes, percentualVotes :: INT AS percent
										   FROM constituency_votes cv, party p
										   WHERE cv.party_id=p.party_id AND constituency_id=:constituencyId");
		$statement->bindValue('constituencyId', $constituency->getId());
		$statement->execute();
		foreach ($statement->fetchAll() as $result) {
			$partyResult = new PartyResult();
			$partyResult->setPartyAbbreviation($result['abbr']);
			$partyResult->setPartyFullName($result['name']);
			$partyResult->setColor($result['color']);
			$partyResult->setVotes($result['votes']);
			$partyResult->setPercentage($result['percent']);
			$partyResult->setSeats("-");
			$partyResult->setOverhead("-");
			$partyResults[] = $partyResult;
		}

		return $partyResults;
	}
}
