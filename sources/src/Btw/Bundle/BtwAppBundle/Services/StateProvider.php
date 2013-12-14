<?php

namespace Btw\Bundle\BtwAppBundle\Services;

use Btw\Bundle\PersistenceBundle\Entity\Election;
use Btw\Bundle\PersistenceBundle\Entity\State;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class StateProvider
	extends AbstractProvider
{

	/** @var  EntityRepository */
	private $repository;

	function __construct(EntityManager $entityManager)
	{
		parent::__construct($entityManager);
	}

	/**
	 * @param int $id
	 *
	 * @return State
	 */
	public function byId($id)
	{
		return $this->getMyRepository()->find($id);
	}

	/**
	 * @param $election Election
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
