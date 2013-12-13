<?php

namespace Btw\Bundle\BtwAppBundle\Services;

use Btw\Bundle\BtwAppBundle\Model\ConstituencyDetail;
use Btw\Bundle\PersistenceBundle\Entity\Constituency;
use Btw\Bundle\PersistenceBundle\Entity\Election;
use Doctrine\ORM\EntityManager;

class ConstituencyProvider
	extends AbstractProvider
{

	function __construct(EntityManager $entityManager)
	{
		parent::__construct($entityManager);
	}

	/**
	 * @param int $id
	 *
	 * @return Constituency
	 */
	public function byId($id)
	{
		return $this->getRepository('BtwPersistenceBundle:Constituency')->find($id);
	}

	/**
	 * @param Election $election
	 * @param Election $prevElection
	 *
	 * @return ConstituencyDetail[]
	 */
	public function getAllDetailsForElection($election, $prevElection)
	{
		$query = $this->prepareQuery("
			SELECT c.constituency_id AS id, c.name, c.state_id AS state, c.electives, ct.voters, ctprev.voters AS voters_prev
			FROM constituency c
			  JOIN constituency_turnout ct USING (constituency_id)
			  JOIN constituency cprev USING (name)
			  LEFT JOIN constituency_turnout ctprev ON (cprev.constituency_id = ctprev.constituency_id)
			WHERE c.election_id = :electionid AND cprev.election_id = :prevelectionid
		");

		$query->bindValue('electionId', $election->getId());
		$query->bindValue('prevElectionId', is_null($prevElection) ? 0 : $prevElection->getId());

		return $this->executeQuery($query, function ($result) {
			$detail = new ConstituencyDetail();
			$detail->setConstituencyId($result['id']);
			$detail->setName($result['name']);
			$detail->setStateId($result['state']);
			$detail->setElectives($result['electives']);
			$detail->setVoters($result['voters']);
			$detail->setVotersPrev($result['voters_prev']);
			return $detail;
		});
	}

}
