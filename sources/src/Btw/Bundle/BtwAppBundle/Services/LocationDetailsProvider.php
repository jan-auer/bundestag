<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 11.12.13
 * Time: 19:13
 */

namespace Btw\Bundle\BtwAppBundle\Services;


use Btw\Bundle\BtwAppBundle\Model\LocationDetails;
use Btw\Bundle\PersistenceBundle\Entity\Constituency;
use Btw\Bundle\PersistenceBundle\Entity\Election;
use Btw\Bundle\PersistenceBundle\Entity\State;
use Doctrine\ORM\EntityManager;

class LocationDetailsProvider
{
	/** @var  EntityManager */
	protected $em;

	function __construct(EntityManager $entityManager)
	{
		$this->em = $entityManager;
	}

	/**
	 * @param $election Election
	 * @return LocationDetails
	 */
	public function  forCountry(Election $election)
	{
		$connection = $this->em->getConnection();
		$statement = $connection->prepare("SELECT SUM(population) AS population FROM state s WHERE s.election_id=:electionId");
		$statement->bindValue('electionId', $election->getId());
		$statement->execute();
		$population = $statement->fetchAll()[0]['population'];

		$connection = $this->em->getConnection();
		$statement = $connection->prepare("SELECT SUM(ct.voters)/SUM(ct.electives) as participation FROM constituency_turnout ct, constituency c WHERE ct.constituency_id=c.constituency_id AND c.election_id=:electionId");
		$statement->bindValue('electionId', $election->getId());
		$statement->execute();
		$participation = $statement->fetchAll()[0]['participation'];

		$details = new LocationDetails();
		$details->setName('Bundesrepublik Deutschland');
		$details->setPopulation($population);
		$details->setParticipation($participation);

		return $details;
	}

	/**
	 * @param State $state
	 * @return LocationDetails
	 */
	public function forState(State $state)
	{
		$connection = $this->em->getConnection();
		$statement = $connection->prepare("SELECT SUM(ct.voters)/SUM(ct.electives) as participation FROM constituency_turnout ct, constituency c, state s WHERE ct.constituency_id=c.constituency_id AND c.state_id=s.state_id AND s.state_id=:stateId");
		$statement->bindValue('stateId', $state->getId());
		$statement->execute();
		$participation = $statement->fetchAll()[0]['participation'];

		$details = new LocationDetails();
		$details->setName($state->getName());
		$details->setPopulation($state->getPopulation());
		$details->setParticipation($participation);

		return $details;
	}


	/**
	 * @param Constituency $constituency
	 * @return LocationDetails
	 */
	public function forConstituency(Constituency $constituency)
	{
		$connection = $this->em->getConnection();
		$statement = $connection->prepare("SELECT turnout AS participation FROM constituency_turnout ct, constituency c WHERE ct.constituency_id=:constituencyId AND ct.constituency_id=c.constituency_id");
		$statement->bindValue('constituencyId', $constituency->getId());
		$statement->execute();
		$participation = $statement->fetchAll()[0]['participation'];

		$details = new LocationDetails();
		$details->setName($constituency->getName());
		$details->setPopulation($constituency->getElectives());
		$details->setParticipation($participation);

		return $details;
	}
} 