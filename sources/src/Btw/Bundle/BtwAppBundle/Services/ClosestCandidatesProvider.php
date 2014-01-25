<?php

namespace Btw\Bundle\BtwAppBundle\Services;

use Btw\Bundle\BtwAppBundle\Model\ClosestCandidate;
use Btw\Bundle\PersistenceBundle\Entity\Party;
use Doctrine\ORM\EntityManager;

/**
 * Computes a list of closest winners or losers of a party.
 */
class ClosestCandidatesProvider extends AbstractProvider
{

	/**
	 * @param EntityManager $entityManager
	 */
	function __construct(EntityManager $entityManager)
	{
		parent::__construct($entityManager);
	}

	/**
	 * Returns the closest winners or losers for the given party.
	 *
	 * @param Party $party
	 *
	 * @return ClosestCandidate[]
	 */
	public function forParty(Party $party)
	{
		$query = $this->prepareQuery("
			SELECT const.name AS constituency, cand.name AS name, type AS type
			FROM top_close_constituency_candidates tccc
			  JOIN candidate cand USING (candidate_id)
			  JOIN constituency const USING (constituency_id)
			WHERE tccc.party_id=:party AND tccc.ranking<=10");

		$query->bindValue('party', $party->getId());
		return $this->executeMappedQuery($query, function ($result) {
			return ClosestCandidate::fromArray($result);
		});
	}

}
