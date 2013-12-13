<?php

namespace Btw\Bundle\BtwAppBundle\Services;

use Btw\Bundle\BtwAppBundle\Model\ClosestCandidate;
use Btw\Bundle\PersistenceBundle\Entity\Party;
use Doctrine\ORM\EntityManager;

class ClosestCandidatesProvider
	extends AbstractProvider
{
	function __construct(EntityManager $entityManager)
	{
		parent::__construct($entityManager);
	}

	/**
	 * @param Party $party
	 *
	 * @return ClosestCandidate[]
	 */
	public function forParty(Party $party)
	{
		$query = $this->prepareQuery("
			SELECT const.name AS constituency, cand.name AS candidate, type
			FROM top_close_constituency_candidates tccc
			  JOIN candidate cand USING (candidate_id)
			  JOIN constituency const USING (constituency_id)
			WHERE tccc.party_id=:partyid AND tccc.ranking<=10");

		$query->bindParam('partyId', $party->getId());

		return $this->executeQuery($query, function ($result) {
			$candidate = new ClosestCandidate();
			$candidate->setName($result['candidate']);
			$candidate->setConstituencyName($result['constituency']);
			$candidate->setType($result['type']);
			return $candidate;
		});
	}
}
