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
		return $this->getRepository('Constituency')->find($id);
	}

	/**
	 * @param Election $election
	 * @param Election $prevElection
	 *
	 * @return ConstituencyDetail[]
	 *
	 * @TODO: Fix this query. It returns nothing when no previous election is specified
	 */
	public function getAllDetailsForElection($election, $prevElection)
	{
		$query = $this->prepareQuery("
			SELECT c.constituency_id AS id, c.name, c.state_id AS state, c.electives, ct.voters, ctprev.voters AS voters_prev
			FROM constituency c
			  JOIN constituency_turnout ct USING (constituency_id)
			  JOIN constituency cprev USING (name)
			  LEFT JOIN constituency_turnout ctprev ON (cprev.constituency_id = ctprev.constituency_id)
			WHERE c.election_id = :current AND cprev.election_id = :previous
		");

		$query->bindValue('current', $election->getId());
		$query->bindValue('previous', is_null($prevElection) ? 0 : $prevElection->getId());
		return $this->executeQuery($query, function ($result) {
			return ConstituencyDetail::fromArray($result);
		});
	}

}
