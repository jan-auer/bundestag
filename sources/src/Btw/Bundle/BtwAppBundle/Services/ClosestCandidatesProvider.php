<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 13.12.13
 * Time: 21:03
 */

namespace Btw\Bundle\BtwAppBundle\Services;


use Btw\Bundle\BtwAppBundle\Model\ClosestCandidate;
use Btw\Bundle\PersistenceBundle\Entity\Election;
use Btw\Bundle\PersistenceBundle\Entity\Party;
use Doctrine\ORM\EntityManager;

class ClosestCandidatesProvider
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

	public function forParty(Election $election, Party $party)
	{
		$candidates = array();
		$connection = $this->em->getConnection();
		$statement = $connection->prepare("SELECT const.name AS constituency, cand.name AS candidate, type
										  FROM top_close_constituency_candidates tccc
										   JOIN candidate cand USING (candidate_id)
										   JOIN constituency const USING (constituency_id)
										  WHERE tccc.election_id=:electionId AND tccc.party_id=:partyId");
		$statement->bindValue('electionId', $election->getId());
		$statement->bindValue('partyId', $party->getId());
		$statement->execute();
		foreach($statement->fetchAll() as $closest)
		{
			$candidate = new ClosestCandidate();
			$candidate->setName($closest['candidate']);
			$candidate->setConstituencyName($closest['constituency']);
			$candidate->setType($closest['type']);
			$candidates[] = $candidate;
		}

		return $candidates;
	}
} 