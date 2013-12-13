<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 07.12.13
 * Time: 18:36
 */

namespace Btw\Bundle\BtwAppBundle\Services;

use Btw\Bundle\BtwAppBundle\Model\LocationDetailsModel;


use Btw\Bundle\PersistenceBundle\Entity\Constituency;
use Doctrine\ORM\EntityManager;


class ConstituencyProvider
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

	public function byId($id)
	{
		return $this->em->find('BtwPersistenceBundle:Constituency', $id);
	}

	public function getAllForElection($election)
	{
		$query = $this->em->createQuery('SELECT c
								FROM Btw\Bundle\PersistenceBundle\Entity\Constituency c JOIN c.state s
								WHERE s.election = :election');
		$query->setParameter('election', $election);
		return $query->getResult();
	}
} 