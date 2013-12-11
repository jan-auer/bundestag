<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 11.12.13
 * Time: 21:40
 */

namespace Btw\Bundle\BtwAppBundle\Services;


use Btw\Bundle\BtwAppBundle\Model\PartyResult;
use Btw\Bundle\PersistenceBundle\Entity\State;
use Btw\Bundle\PersistenceBundle\Entity\Election;
use Btw\Bundle\PersistenceBundle\Entity\Constituency;
use Doctrine\ORM\EntityManager;

class PartyResultsProvider {

	/** @var  EntityManager */
	protected $em;

	function __construct(EntityManager $entityManager)
	{
		$this->em = $entityManager;
	}

	public function forCountry(Election $election)
	{
		$partyResults = array();
		$connection = $this->em->getConnection();
		$statement = $connection->prepare("SELECT abbreviation AS abbr, party.name AS name, color, SUM(party_state_seats.seats) :: INT AS seats, overhead FROM party_state_seats JOIN party USING (party_id, election_id) JOIN election USING (election_id) JOIN state_party_seats USING (state_id, party_id) WHERE date_part('Y', date) = :electionYear GROUP BY abbreviation, color, party.name, overhead");
		$statement->bindValue('electionYear', date('Y', $election->getDate()->getTimestamp()));
		$statement->execute();
		foreach ($statement->fetchAll() AS $result) {
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

	public function forState(State $state)
	{
		$partyResults = array();
		$connection = $this->em->getConnection();
		$statement = $connection->prepare("SELECT abbreviation as abbr, party.name AS name, color, SUM(party_state_seats.seats) :: INT as seats, overhead FROM party_state_seats JOIN party USING (party_id, election_id) JOIN state_party_seats USING (state_id, party_id) WHERE party_state_seats.state_id=:stateId GROUP BY abbreviation, color, party.name, overhead");
		$statement->bindValue('stateId', $state->getId());
		$statement->execute();
		foreach ($statement->fetchAll() AS $result) {
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

	public function forConstituency(Constituency $constituency)
	{
		$partyResults = array();
		$connection = $this->em->getConnection();
		$statement = $connection->prepare("SELECT abbreviation AS abbr, p.name AS name, color, absoluteVotes :: INT AS votes, percentualVotes :: INT AS percent FROM constituency_votes cv, party p WHERE cv.party_id=p.party_id AND constituency_id=:constituencyId");
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