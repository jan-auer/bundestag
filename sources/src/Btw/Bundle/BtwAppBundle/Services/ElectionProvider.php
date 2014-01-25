<?php

namespace Btw\Bundle\BtwAppBundle\Services;

use Btw\Bundle\PersistenceBundle\Entity\Election;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * Provides access to {@link Election} entities.
 */
class ElectionProvider extends AbstractProvider
{

	/** @var  EntityRepository */
	private $repository;

	/**
	 * @param EntityManager $entityManager
	 */
	function __construct(EntityManager $entityManager)
	{
		parent::__construct($entityManager);
	}

	/**
	 * Returns an election identified by the given id.
	 *
	 * @param int $id The id of the election.
	 *
	 * @return Election
	 */
	public function byId($id)
	{
		return $this->getMyRepository()->find($id);
	}

	/**
	 * Returns a list of all elections.
	 *
	 * @return Election[]
	 */
	public function getAll()
	{
		return $this->getMyRepository()->findAll();
	}

	/**
	 * Returns a list of all election years.
	 *
	 * @return int[]
	 */
	public function getAllYears()
	{
		return array_map(function ($election) {
			/** @var Election $election */
			return date('Y', $election->getDate()->getTimestamp());
		}, $this->getAll());
	}

	/**
	 * Returns an election for the given year or null, if no election was held in this year.
	 *
	 * @param string|int $year The year of the election.
	 *
	 * @return Election
	 */
	public function forYear($year)
	{
		/** @var Election[] $elections */
		$elections = $this->getMyRepository()->findAll();

		foreach ($elections as $election) {
			if (date('Y', $election->getDate()->getTimestamp()) == $year) {
				return $election;
			}
		}

		return null;
	}

	/**
	 * Returns the latest election, if any.
	 *
	 * @return Election
	 */
	public function getLatest()
	{
		$query = $this->getEntityManager()->createQuery('
			SELECT e
			FROM Btw\Bundle\PersistenceBundle\Entity\Election e
			ORDER BY e.date DESC');

		$query->setMaxResults(1);
		$results = $query->getResult();
		return is_array($results) ? $results[0] : null;
	}

	/**
	 * Returns the predecessor to the given election.
	 *
	 * @param Election $election The preceding election to the result.
	 *
	 * @return Election
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
