<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 07.12.13
 * Time: 18:36
 */

namespace Btw\Bundle\BtwAppBundle\Services;

use Btw\Bundle\BtwAppBundle\Model\LocationDetailsModel;


use Btw\Bundle\PersistenceBundle\Entity\Constituency;
use Doctrine\ORM\EntityManager;


class ConstituencyProvider extends Provider
{

	/**
	 * @param EntityManager $entityManager
	 */
	function __construct(EntityManager $entityManager)
	{
		$this->em = $entityManager;
	}

	/**
	 * @param $year
	 * @param Constituency $constituency
	 * @return LocationDetailsModel LocationDetailsModel
	 */
	public function getDetailsFor(Constituency $constituency)
	{
		$qb = $this->em->createQueryBuilder();

		$connection = $this->em->getConnection();
		$statement = $connection->prepare("SELECT turnout AS participation FROM constituency_turnout ct, constituency c WHERE ct.constituency_id=:constituencyId AND ct.constituency_id=c.constituency_id");
		$statement->bindValue('constituencyId', $constituency->getId());
		$statement->execute();
		$participation = $statement->fetchAll()[0]['participation'];

		$statement = $connection->prepare("SELECT c.name AS name FROM Candidate c, elected_candidates ec, constituency_candidacy cc WHERE c.candidate_id=ec.candidate_id AND c.candidate_id=cc.candidate_id AND cc.constituency_id=:constituencyId");
		$statement->bindValue('constituencyId', $constituency->getId());
		$statement->execute();
		$membersOfBundestag = array();
		foreach ($statement->fetchAll() as $member) {
			$membersOfBundestag[] = $member['name'];
		}

		$details = new LocationDetailsModel();
		$details->setName($constituency->getName());
		$details->setPopulation($constituency->getElectives());
		$details->setParticipation($participation);
		$details->setMembersOfBundestag($membersOfBundestag);

		return $details;
	}

	public function getResultsfor(Constituency $constituency)
	{
		$connection = $this->em->getConnection();
		$statement = $connection->prepare("SELECT abbreviation AS name, color, absoluteVotes :: INT AS y FROM constituency_votes cv, party p WHERE cv.party_id=p.party_id AND constituency_id=:constituencyId");
		$statement->bindValue('constituencyId', $constituency->getId());

		$statement->execute();
		return $statement->fetchAll();
	}

	public function getConstituencyById($id)
	{
		return $this->em->find('BtwPersistenceBundle:Constituency', $id);
	}
} 