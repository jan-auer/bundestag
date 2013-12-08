<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 07.12.13
 * Time: 18:36
 */

namespace Btw\Bundle\BtwAppBundle\Services;


use Btw\Bundle\PersistenceBundle\Entity\State;
use Doctrine\ORM\EntityManager;


class StateProvider {

	/** @var  EntityManager */
	private $em;

	function __construct(EntityManager $entityManager)
	{
		$this->em = $entityManager;
	}

	public function getStatesFor($year)
	{
		/** DUMMY */
		$states = $this->em->getRepository('BtwPersistenceBundle:State');
		return $states->findAll();
	}
} 