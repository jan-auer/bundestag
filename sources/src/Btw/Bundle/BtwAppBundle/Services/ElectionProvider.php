<?php
/**
 * Created by PhpStorm.
 * User: manuel
 * Date: 06/12/13
 * Time: 21:03
 */

namespace Btw\Bundle\BtwAppBundle\Services;

use Doctrine\ORM\EntityManager;


class ElectionProvider
{

	/** @var  EntityManager */
	private $em;

	function __construct(EntityManager $entityManager)
	{
		$this->em = $entityManager;
	}

	public function getElectionYears()
	{
		$electionsRepository = $this->em->getRepository('BtwPersistenceBundle:Election');
		$elections = $electionsRepository->findAll();
		$years = array();
		foreach ($elections as $election) {
			$years[] = date('Y', $election->getDate()->getTimestamp());
		}
		return $years;
	}

	public function getResultsFor($electionYear)
	{
		$connection = $this->em->getConnection();
		$statement = $connection->prepare("SELECT abbreviation as name, color, SUM(seats) :: INT as y FROM party_state_seats JOIN party USING (party_id, election_id) JOIN election USING (election_id) WHERE date_part('Y', date) = :electionYear GROUP BY abbreviation, color");
		$statement->bindValue('electionYear', $electionYear);

		$statement->execute();
		return $statement->fetchAll();
	}

}