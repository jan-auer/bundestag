<?php

namespace Btw\Bundle\BtwAppBundle\Services;

use Btw\Bundle\PersistenceBundle\Entity\Election;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class ElectionProvider
{

	/** @var  EntityManager */
	private $em;
	/** @var  EntityRepository */
	private $repository;

	function __construct(EntityManager $entityManager)
	{
		$this->em = $entityManager;
	}

	/**
	 * @return Election[]
	 */
	public function getAll()
	{
		return $this->getRepository()->findAll();
	}

	/**
	 * @return int[]
	 */
	public function getAllYears()
	{
		return array_map(function ($election) {
			return date('Y', $election->getDate()->getTimestamp());
		}, $this->getAll());
	}

	/**
	 * @param string $year
	 *
	 * @return Election
	 */
	public function forYear($year)
	{
		/** @var Election[] $elections */
		$elections = $this->getRepository()->findAll();

		foreach ($elections as $election) {
			if (date('Y', $election->getDate()->getTimestamp()) == $year)
				return $election;
		}

		return null;
	}

	/**
	 * @param Election $election
	 * @return Election|null
	 */
	public function getPreviousElectionFor(Election $election)
	{
		$currentYear = date('Y', $election->getDate()->getTimestamp());
		$elections = $this->getAll();
		foreach($elections as $e)
		{
			$y = date('Y', $e->getDate()->getTimestamp()) ;
			if($currentYear == ($y + 4)) return $e;
		}
		return null;
	}

	/**
	 * @return EntityRepository
	 */
	private function getRepository()
	{
		if ($this->repository == null) {
			$this->repository = $this->em->getRepository('BtwPersistenceBundle:Election');
		}
		return $this->repository;
	}

}
