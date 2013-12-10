<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 07.12.13
 * Time: 18:36
 */

namespace Btw\Bundle\BtwAppBundle\Services;


use Btw\Bundle\BtwAppBundle\Model\LocationDetailsModel;
use Btw\Bundle\PersistenceBundle\Entity\State;
use Doctrine\ORM\EntityManager;


class StateProvider extends Provider {

	function __construct(EntityManager $entityManager)
	{
		$this->em = $entityManager;
	}

	/**
	 * @param $year
	 * @param $name
	 * @return array
	 */
	public function getStateFor($year, $name)
	{
		$election = $this->getElectionFor($year);
		$statesRepository = $this->em->getRepository('BtwPersistenceBundle:State');
		$state = $statesRepository->findOneBy(array('election' => $election, 'name' => $name));
		return $state;
	}

	/**
	 * @param $year
	 * @return array
	 */
	public function getStatesFor($year)
	{
		$election = $this->getElectionFor($year);
		$statesRepository = $this->em->getRepository('BtwPersistenceBundle:State');
		$states = $statesRepository->findBy(array('election' => $election), array('name' => 'ASC'));
		return $states;
	}

	/**
	 * @param $year
	 * @param State $state
	 * @return LocationDetailsModel LocationDetailsModel
	 */
	public function getDetailsFor(State $state)
	{
		$qb = $this->em->createQueryBuilder();

		$connection = $this->em->getConnection();
		$statement = $connection->prepare("SELECT SUM(ct.voters)/SUM(ct.electives) as participation FROM constituency_turnout ct, constituency c, state s WHERE ct.constituency_id=c.constituency_id AND c.state_id=s.state_id AND s.state_id=:stateId");
		$statement->bindValue('stateId', $state->getId());
		$statement->execute();
		$participation = $statement->fetchAll()[0]['participation'];

		$statement = $connection->prepare("SELECT c.name AS name FROM Candidate c, elected_candidates ec, state_candidacy sc, state_list sl WHERE c.candidate_id=ec.candidate_id AND c.candidate_id=sc.candidate_id AND sc.state_list_id=sl.state_list_id AND sl.state_id=:stateId");
		$statement->bindValue('stateId', $state->getId());
		$statement->execute();
		$membersOfBundestag = array();
		foreach($statement->fetchAll() as $member)
		{
			$membersOfBundestag[] = $member['name'];
		}

		$details = new LocationDetailsModel();
		$details->setName($state->getName());
		$details->setPopulation($state->getPopulation());
		$details->setParticipation($participation);
		$details->setMembersOfBundestag($membersOfBundestag);

		return $details;
	}

	/**
	 * @param $id state id
	 * @return State
	 */
	public function getStateById($id) {
		return $this->em->find('BtwPersistenceBundle:State', $id);
	}
} 