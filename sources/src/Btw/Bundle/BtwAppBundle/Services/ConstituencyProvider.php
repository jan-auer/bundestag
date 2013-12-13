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

	public function getAllDetailsForElection($election)
	{
		$constituencies = array();
		$connection = $this->em->getConnection();
		$statement = $connection->prepare("SELECT c.constituency_id AS id, c.name, c.state_id AS state, c.electives, ct.voters
										   FROM constituency c
										    JOIN constituency_turnout ct USING (constituency_id)
										   WHERE c.election_id=:electionId");
		$statement->bindValue('electionId', $election->getId());
		$statement->execute();
		foreach($statement->fetchAll() as $constituency)
		{
			$constituencyDetail = new ConstituencyDetail();
			$constituencyDetail->setConstituencyId($constituency['id']);
			$constituencyDetail->setName($constituency['name']);
			$constituencyDetail->setStateId($constituency['state']);
			$constituencyDetail->setElectives($constituency['electives']);
			$constituencyDetail->setVoters($constituency['voters']);
			$constituencies[] = $constituencyDetail;
		}

		return $constituencies;
	}
} 