<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 11.12.13
 * Time: 20:11
 */

namespace Btw\Bundle\BtwAppBundle\Services;


use Btw\Bundle\BtwAppBundle\Model\PartyVotesResult;
use Btw\Bundle\PersistenceBundle\Entity\Constituency;
use Doctrine\ORM\EntityManager;

class PartyVotesResultProvider
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

	/**
	 * @param Constituency $constituency
	 * @return array
	 */
	public function getPartyVotesForConstituency(Constituency $constituency)
	{
		$partyVotesResults = array();
		$connection = $this->em->getConnection();
		$statement = $connection->prepare("SELECT abbreviation AS name, color, absoluteVotes :: INT AS votes FROM constituency_votes cv, party p WHERE cv.party_id=p.party_id AND constituency_id=:constituencyId");
		$statement->bindValue('constituencyId', $constituency->getId());
		$statement->execute();
		foreach ($statement->fetchAll() as $result) {
			$partyVotesResult = new PartyVotesResult();
			$partyVotesResult->setAbbreviation($result['name']);
			$partyVotesResult->setColor($result['color']);
			$partyVotesResult->setVotes($result['votes']);
			$partyVotesResults[] = $partyVotesResult;
		}

		return $partyVotesResults;
	}
} 