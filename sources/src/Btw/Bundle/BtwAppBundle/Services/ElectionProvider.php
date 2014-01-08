<?php

namespace Btw\Bundle\BtwAppBundle\Services;

use Btw\Bundle\PersistenceBundle\Entity\Election;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class ElectionProvider
	extends AbstractProvider
{

	/** @var  EntityRepository */
	private $repository;

	function __construct(EntityManager $entityManager)
	{
		parent::__construct($entityManager);
	}

	public function byId($id)
	{
		return $this->getMyRepository()->find($id);
	}

	/**
	 * @return Election[]
	 */
	public function getAll()
	{
		return $this->getMyRepository()->findAll();
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
	 * @return Election|null
	 *
	 * @todo Use a DQL query.
	 */
	public function forYear($year)
	{
		/** @var Election[] $elections */
		$elections = $this->getMyRepository()->findAll();

		foreach ($elections as $election) {
			if (date('Y', $election->getDate()->getTimestamp()) == $year)
				return $election;
		}

		return null;
	}

	public function getLatest() {
		$qb = $this->createQueryBulder();
		$qb->select('e')
			->from('Btw\Bundle\PersistenceBundle\Entity\Election', 'e')
			->orderBy('e.date', 'DESC')
			->setMaxResults(1);

		$query = $qb->getQuery();
		$result = $query->getResult();
		if(is_array($result)) {
			return $result[0];
		}
		return null;
	}

	/**
	 * @param Election $election
	 *
	 * @return Election|null
	 */
	public function getPreviousElectionFor(Election $election)
	{
		$currentYear = date('Y', $election->getDate()->getTimestamp());
		return $this->forYear($currentYear - 4);
	}

	/**
	 * @return EntityRepository
	 */
	private function getMyRepository()
	{
		if ($this->repository == null) {
			$this->repository = $this->getRepository('Election');
		}
		return $this->repository;
	}

}
