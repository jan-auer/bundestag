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
