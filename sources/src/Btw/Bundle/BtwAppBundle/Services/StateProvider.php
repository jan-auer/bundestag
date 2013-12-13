<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 07.12.13
 * Time: 18:36
 */

namespace Btw\Bundle\BtwAppBundle\Services;


use Btw\Bundle\BtwAppBundle\Model\LocationDetailsModel;
use Btw\Bundle\PersistenceBundle\Entity\State;
use Btw\Bundle\PersistenceBundle\Entity\Election;
use Doctrine\ORM\EntityManager;


class StateProvider
{

	/** @var  EntityManager */
	protected $em;

	/**
	 * @param EntityManager $entityManager
	 */
	function __construct(EntityManager $entityManager)
	{
		$this->em = $entityManager;
	}

	/**
	 * @param $election Election
	 * @return array
	 */
	public function getAllForElection(Election $election)
	{
		$statesRepository = $this->em->getRepository('BtwPersistenceBundle:State');
		$states = $statesRepository->findBy(array('election' => $election), array('name' => 'ASC'));
		return $states;
	}

	/**
	 * @param $id state id
	 * @return State
	 */
	public function byId($id)
	{
		return $this->em->find('BtwPersistenceBundle:State', $id);
	}
} 