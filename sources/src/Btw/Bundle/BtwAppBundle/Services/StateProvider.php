<?php

namespace Btw\Bundle\BtwAppBundle\Services;

use Btw\Bundle\PersistenceBundle\Entity\Election;
use Btw\Bundle\PersistenceBundle\Entity\State;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * Provides access to {@link State} entities.
 */
class StateProvider extends AbstractProvider
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
	 * Returns a state entity identified by the given id.
	 *
	 * @param int $id The identifier of the state entity.
	 *
	 * @return State
	 */
	public function byId($id)
	{
		return $this->getMyRepository()->find($id);
	}

	/**
	 * Returns a list of state entities for the given entities.
	 *
	 * @param $election Election The election to retrieve states for.
	 *
	 * @return State[]
	 */
	public function getAllForElection(Election $election)
	{
		return $this->getMyRepository()->findBy(array('election' => $election), array('name' => 'ASC'));
	}

	/**
	 * @return EntityRepository
	 */
	private function getMyRepository()
	{
		if ($this->repository == null) {
			$this->repository = $this->getRepository('State');
		}
		return $this->repository;
	}

}
