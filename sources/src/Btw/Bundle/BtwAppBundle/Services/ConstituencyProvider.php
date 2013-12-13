<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 07.12.13
 * Time: 18:36
 */

namespace Btw\Bundle\BtwAppBundle\Services;

use Btw\Bundle\BtwAppBundle\Model\LocationDetailsModel;
use Btw\Bundle\BtwAppBundle\Model\ConstituencyDetail;


use Btw\Bundle\PersistenceBundle\Entity\Constituency;
use Doctrine\ORM\EntityManager;


class ConstituencyProvider
{

	/** @var  EntityManager */
	protected $em;

	/**
	 * @param EntityManager $entityManager
	 */
	function __construct(EntityManager $entityManager)
	{
		$this->em = $entityManager;
	}

	public function byId($id)
	{
		return $this->em->find('BtwPersistenceBundle:Constituency', $id);
	}

	public function getAllDetailsForElection($election, $prevElection)
	{
		$constituencies = array();
		$connection = $this->em->getConnection();
		$statement = $connection->prepare("SELECT c.constituency_id AS id, c.name, c.state_id AS state, c.electives, ct.voters, ctprev.voters AS voters_prev
										   FROM constituency c
										    JOIN constituency_turnout ct USING (constituency_id)
										    JOIN constituency cprev USING (name)
										    JOIN constituency_turnout ctprev ON (cprev.constituency_id = ctprev.constituency_id)
										   WHERE c.election_id=:electionId AND cprev.election_id=:prevElectionId");
		$statement->bindValue('electionId', $election->getId());
		$statement->bindValue('prevElectionId', $prevElection->getId());
		$statement->execute();
		foreach($statement->fetchAll() as $constituency)
		{
			$constituencyDetail = new ConstituencyDetail();
			$constituencyDetail->setConstituencyId($constituency['id']);
			$constituencyDetail->setName($constituency['name']);
			$constituencyDetail->setStateId($constituency['state']);
			$constituencyDetail->setElectives($constituency['electives']);
			$constituencyDetail->setVoters($constituency['voters']);
			$constituencyDetail->setVotersPrev($constituency['voters_prev']);
			$constituencies[] = $constituencyDetail;
		}

		return $constituencies;
	}
} 