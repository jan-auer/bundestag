<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 13.12.13
 * Time: 15:18
 */

namespace Btw\Bundle\BtwAppBundle\Services;


use Btw\Bundle\PersistenceBundle\Entity\Election;
use Doctrine\ORM\EntityManager;

class PartyProvider {

	/** @var  EntityManager */
	protected $em;

	/**
	 * @param EntityManager $entityManager
	 */
	function __construct(EntityManager $entityManager)
	{
		$this->em = $entityManager;
	}

	public function byId($id)
	{
		return $this->em->find('BtwPersistenceBundle:Party', $id);
	}

	/**
	 * @param Election $election
	 * @return array
	 */
	public function getAllForElection(Election $election)
	{
		$partiesRepository = $this->em->getRepository('BtwPersistenceBundle:Party');
		$parties = $partiesRepository->findBy(array('election' => $election));
		return $parties;
	}
} 