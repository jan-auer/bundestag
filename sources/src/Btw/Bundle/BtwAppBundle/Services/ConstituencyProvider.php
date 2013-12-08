<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 07.12.13
 * Time: 18:36
 */

namespace Btw\Bundle\BtwAppBundle\Services;


use Btw\Bundle\PersistenceBundle\Entity\Constituency;
use Doctrine\ORM\EntityManager;


class ConstituencyProvider {

	/** @var  EntityManager */
	private $em;

	function __construct(EntityManager $entityManager)
	{
		$this->em = $entityManager;
	}

	public function getConstituenciesFor($year)
	{
		/** DUMMY */
		//TODO
		$constituencies = array();
		$constituency = new Constituency();
		$constituency->setId(1)->setName('Bayern WK 1')->setNumber(1);
		$constituencies[] = $constituency;
		return $constituencies;
	}
} 