<?php

namespace Btw\Bundle\BtwAppBundle\Services;

use Btw\Bundle\PersistenceBundle\Entity\Election;
use Btw\Bundle\PersistenceBundle\Entity\Party;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class PartyProvider
	extends AbstractProvider
{

	/** @var  EntityRepository */
	private $repository;

	function __construct(EntityManager $entityManager)
	{
		parent::__construct($entityManager);
	}

	/**
	 * @param $id
	 *
	 * @return Party
	 */
	public function byId($id)
	{
		return $this->getMyRepository()->find($id);
	}

	/**
	 * @param Election $election
	 *
	 * @return Party[]
	 */
	public function getAllForElection(Election $election)
	{
		return $this->getMyRepository()->findBy(array('election' => $election));
	}

	/**
	 * @return EntityRepository
	 */
	private function getMyRepository()
	{
		if ($this->repository == null) {
			$this->repository = $this->getRepository('Party');
		}
		return $this->repository;
	}

}
