<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 11.12.13
 * Time: 20:02
 */

namespace Btw\Bundle\BtwAppBundle\Services;


use Btw\Bundle\BtwAppBundle\Model\PartySeatsResult;
use Btw\Bundle\PersistenceBundle\Entity\Election;
use Btw\Bundle\PersistenceBundle\Entity\State;
use Doctrine\ORM\EntityManager;

class PartySeatsResultProvider
{

	/**
	 *
	 * DEPRECATED CLASS
	 * ONLY USED AS AN INSPIRATION WHEN INTEGRATING THIS FUNCTIONALITY INTO PARTYRESULTSPROVIDER
	 *
	 */

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
	public function forCountry(Election $election)
	{
		$partySeatsResults = array();
		$connection = $this->em->getConnection();
		$statement = $connection->prepare("SELECT abbreviation AS name, color, SUM(seats) :: INT AS seats FROM party_state_seats JOIN party USING (party_id, election_id) JOIN election USING (election_id) WHERE date_part('Y', date) = :electionYear GROUP BY abbreviation, color");
		$statement->bindValue('electionYear', date('Y', $election->getDate()->getTimestamp()));
		$statement->execute();
		foreach ($statement->fetchAll() AS $result) {
			$partySeatsResult = new PartySeatsResult();
			$partySeatsResult->setAbbreviation($result['name']);
			$partySeatsResult->setColor($result['color']);
			$partySeatsResult->setSeats($result['seats']);
			$partySeatsResults[] = $partySeatsResult;
		}

		return $partySeatsResults;
	}

	/**
	 * @param State $state
	 * @return array
	 */
	public function forState(State $state)
	{
		$partySeatsResults = array();
		$connection = $this->em->getConnection();
		$statement = $connection->prepare("SELECT abbreviation as name, color, SUM(seats) :: INT as seats FROM party_state_seats JOIN party USING (party_id, election_id) WHERE party_state_seats.state_id=:stateId GROUP BY abbreviation, color");
		$statement->bindValue('stateId', $state->getId());
		$statement->execute();
		foreach ($statement->fetchAll() AS $result) {
			$partySeatsResult = new PartySeatsResult();
			$partySeatsResult->setAbbreviation($result['name']);
			$partySeatsResult->setColor($result['color']);
			$partySeatsResult->setSeats($result['seats']);
			$partySeatsResults[] = $partySeatsResult;
		}

		return $partySeatsResults;
	}
} 