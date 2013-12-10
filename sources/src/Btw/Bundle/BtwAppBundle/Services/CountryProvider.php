<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 10.12.13
 * Time: 20:41
 */

namespace Btw\Bundle\BtwAppBundle\Services;


use Btw\Bundle\PersistenceBundle\Entity\Election;
use Doctrine\ORM\EntityManager;

class CountryProvider
{
	/** @var  EntityManager */
	protected $em;

	/**
	 * @param $entityManager EntityManager
	 */
	function __construct(EntityManager $entityManager)
	{
		$this->em = $entityManager;
	}

	/**
	 * @param $election Election
	 * @return LocationDetailsModel
	 */
	public function  getDetailsFor(Election $election)
	{
		/** POPULATION */
		$connection = $this->em->getConnection();
		$statement = $connection->prepare("SELECT SUM(population) AS population FROM state s WHERE s.election_id=:electionId");
		$statement->bindValue('electionId', $election->getId());
		$statement->execute();
		$population = $statement->fetchAll()[0]['population'];

		/** PARTICIPATION */
		$connection = $this->em->getConnection();
		$statement = $connection->prepare("SELECT SUM(ct.voters)/SUM(ct.electives) as participation FROM constituency_turnout ct, constituency c WHERE ct.constituency_id=c.constituency_id AND c.election_id=:electionId");
		$statement->bindValue('electionId', $election->getId());
		$statement->execute();
		$participation = $statement->fetchAll()[0]['participation'];

		/** MEMBERS OF BUNDESTAG */
		$statement = $connection->prepare("SELECT c.name AS name FROM Candidate c, elected_candidates ec WHERE c.candidate_id=ec.candidate_id AND c.election_id=:electionId");
		$statement->bindValue('electionId', $election->getId());
		$statement->execute();
		$membersOfBundestag = array();
		foreach($statement->fetchAll() as $member)
		{
			$membersOfBundestag[] = $member['name'];
		}

		$details = new LocationDetailsModel();
		$details->setName('Bundesrepublik Deutschland');
		$details->setPopulation($population);
		$details->setParticipation($participation);
		$details->setMembersOfBundestag($membersOfBundestag);

		return $details;
	}

	/**
	 * @param $election Election
	 * @return mixed
	 */
	public function getResultsFor(Election $election)
	{
		$connection = $this->em->getConnection();
		$statement = $connection->prepare("SELECT abbreviation as name, color, SUM(seats) :: INT as y FROM party_state_seats JOIN party USING (party_id, election_id) JOIN election USING (election_id) WHERE date_part('Y', date) = :electionYear GROUP BY abbreviation, color");
		$statement->bindValue('electionYear', date('Y', $election->getDate()->getTimestamp()));

		$statement->execute();
		return $statement->fetchAll();
	}
} 