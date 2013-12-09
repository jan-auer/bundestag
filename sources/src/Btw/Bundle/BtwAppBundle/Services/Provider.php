<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 09.12.13
 * Time: 20:08
 */

namespace Btw\Bundle\BtwAppBundle\Services;


class Provider {

	/** @var  EntityManager */
	protected $em;

	protected function getElectionFor($year)
	{
		$electionsRepository = $this->em->getRepository('BtwPersistenceBundle:Election');
		$elections = $electionsRepository->findAll();
		foreach($elections as $election) if(date('Y', $election->getDate()->getTimestamp()) == $year) return $election;
	}
} 