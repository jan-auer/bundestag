<?php

namespace Btw\Bundle\BtwAppBundle\Services;

use Btw\Bundle\PersistenceBundle\Entity\State;
use Btw\Bundle\PersistenceBundle\Entity\StateList;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * Provides access to {@link StateList} entities.
 */
class StateListProvider extends AbstractProvider
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
	 * Returns a state list entity identified by the given id.
	 *
	 * @param int $id The identifier of the state list.
	 *
	 * @return StateList
	 */
	public function byId($id)
	{
		return $this->getMyRepository()->find($id);
	}

	/**
	 * Returns all state lists of the given state. That is a list of all parties
	 * running for seats in that state.
	 *
	 * @param State $state The state to get parties (state lists) for.
	 *
	 * @return StateList[]
	 */
	public function forState(State $state)
	{
		$stateList = $this->getMyRepository()->findBy(array('state' => $state));
		return $stateList;
	}

	/**
	 * @return EntityRepository
	 */
	private function getMyRepository()
	{
		if ($this->repository == null) {
			$this->repository = $this->getRepository('StateList');
		}
		return $this->repository;
	}

}
