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

	function __construct(EntityManager $entityManager)
	{
		$this->em = $entityManager;
	}

	public function getConstituencyFor($year, $name)
	{
		$election = $this->getElectionFor($year);
		$constituenciesRepository = $this->em->getRepository('BtwPersistenceBundle:Constituency');
		$state = $constituenciesRepository->findOneBy(array('election' => $election, 'name' => $name));
		return $state;
	}

	/**
	 * @param $year
	 * @return array
	 */
	public function getConstituenciesFor($year)
	{
		/** DUMMY */
		//TODO
		$constituencies = array();
		$constituency = new Constituency();
		$constituency->setId(1)->setName('Bayern WK 1')->setNumber(1);
		$constituencies[] = $constituency;
		return $constituencies;
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
} 