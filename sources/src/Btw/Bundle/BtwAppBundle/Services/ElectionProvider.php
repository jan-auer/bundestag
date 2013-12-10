<?php
/**
 * Created by PhpStorm.
 * User: manuel
 * Date: 06/12/13
 * Time: 21:03
 */

namespace Btw\Bundle\BtwAppBundle\Services;

use Doctrine\ORM\EntityManager;


class ElectionProvider
{

	/** @var  EntityManager */
	private $em;

	function __construct(EntityManager $entityManager)
	{
		$this->em = $entityManager;
	}

	/**
	 * @return array
	 */
	public function getElections()
	{
		$electionsRepository = $this->em->getRepository('BtwPersistenceBundle:Election');
		return$electionsRepository->findAll();
	}

	/**
	 * @param $year
	 * @return mixed
	 */
	public function getElectionFor($year)
	{
		$electionsRepository = $this->em->getRepository('BtwPersistenceBundle:Election');
		$elections = $electionsRepository->findAll();
		foreach($elections as $election) if(date('Y', $election->getDate()->getTimestamp()) == $year) return $election;
	}
}