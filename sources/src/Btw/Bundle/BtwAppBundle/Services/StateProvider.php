<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 07.12.13
 * Time: 18:36
 */

namespace Btw\Bundle\BtwAppBundle\Services;


use Doctrine\ORM\EntityManager;


class StateProvider {

	/** @var  EntityManager */
	private $em;

	function __construct(EntityManager $entityManager)
	{
		$this->em = $entityManager;
	}

	public function getStatesFor($year)
	{
		$electionsRepository = $this->em->getRepository('BtwPersistenceBundle:Election');
		$elections = $electionsRepository->findAll();
		$desiredElection = null;
		foreach($elections as $election) if(date('Y', $election->getDate()->getTimestamp()) == $year) $desiredElection = $election;

		$statesRepository = $this->em->getRepository('BtwPersistenceBundle:State');
		$states = $statesRepository->findBy(array('election' => $desiredElection));
		return $states;
	}
} 