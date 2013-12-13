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

	function __construct(EntityManager $entityManager)
	{
		$this->em = $entityManager;
	}

	public function getAllForElection(Election $election)
	{
		$partiesRepository = $this->em->getRepository('BtwPersistenceBundle:Party');
		$parties = $partiesRepository->findBy(array('election' => $election));
		return $parties;
	}
} 