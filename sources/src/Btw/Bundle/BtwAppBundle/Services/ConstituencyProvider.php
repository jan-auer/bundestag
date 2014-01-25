<?php

namespace Btw\Bundle\BtwAppBundle\Services;

use Btw\Bundle\BtwAppBundle\Model\ConstituencyDetail;
use Btw\Bundle\PersistenceBundle\Entity\Constituency;
use Btw\Bundle\PersistenceBundle\Entity\Election;
use Doctrine\ORM\EntityManager;

/**
 * Provides access to {@link Constituency} entities.
 */
class ConstituencyProvider extends AbstractProvider
{

	/**
	 * @param EntityManager $entityManager
	 */
	function __construct(EntityManager $entityManager)
	{
		parent::__construct($entityManager);
	}

	/**
	 * Returns a constituency identified by the given id.
	 *
	 * @param int $id The id of the constituency entity.
	 *
	 * @return Constituency
	 */
	public function byId($id)
	{
		return $this->getRepository('Constituency')->find($id);
	}

	/**
	 * Returns details for the specified election, including the vote count and turnout.
	 *
	 * @param Election $election     The election to retrieve details for.
	 * @param Election $prevElection A previous election to compare to.
	 *
	 * @return ConstituencyDetail[]
	 */
	public function getAllDetailsForElection(Election $election, $prevElection = null)
	{
		if (is_null($prevElection)) {
			$query = $this->prepareQuery("
				SELECT c.constituency_id AS id, c.name, c.state_id AS state, c.electives, ct.voters, -1 AS voters_prev
				FROM constituency c
				  JOIN constituency_turnout ct USING (constituency_id)
				WHERE c.election_id = :election_id
			");

			$query->bindValue('election_id', $election->getId());
			return $this->executeMappedQuery($query, function ($result) {
				return ConstituencyDetail::fromArray($result);
			});
		} else {
			$query = $this->prepareQuery("
				SELECT c.constituency_id AS id, c.name, c.state_id AS state, c.electives, ct.voters, ctprev.voters AS voters_prev
				FROM constituency c
				  JOIN constituency_turnout ct USING (constituency_id)
				  LEFT JOIN constituency cprev ON (c.name = cprev.name AND cprev.election_id = :previous)
				  LEFT JOIN constituency_turnout ctprev ON (cprev.constituency_id = ctprev.constituency_id)
			WHERE c.election_id = :current
			");

			$query->bindValue('current', $election->getId());
			$query->bindValue('previous', $prevElection->getId());
			return $this->executeMappedQuery($query, function ($result) {
				return ConstituencyDetail::fromArray($result);
			});
		}
	}

}
