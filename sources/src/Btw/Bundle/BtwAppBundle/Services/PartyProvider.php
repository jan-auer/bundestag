<?php

namespace Btw\Bundle\BtwAppBundle\Services;

use Btw\Bundle\PersistenceBundle\Entity\Election;
use Btw\Bundle\PersistenceBundle\Entity\Party;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * Provides access to {@link Party} entities.
 */
class PartyProvider extends AbstractProvider
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
	 * Returns a party entity identified by the given id.
	 *
	 * @param int $id The id of the party entity.
	 *
	 * @return Party
	 */
	public function byId($id)
	{
		return $this->getMyRepository()->find($id);
	}

	/**
	 * Returns all parties of the given election.
	 *
	 * @param Election $election An election entity.
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
