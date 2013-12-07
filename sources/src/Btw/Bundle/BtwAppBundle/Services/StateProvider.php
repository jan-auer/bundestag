<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 07.12.13
 * Time: 18:36
 */

namespace Btw\Bundle\BtwAppBundle\Services;


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
		return array(array('name' => 'Bayern', 'population' => 7219821, 'participation' => 60),
					 array('name' => 'NRW', 'population' => 83278948392, 'participation' => 40),
					array('name' => 'Saarland', 'population' => 3324, 'paticipation' => 20));
	}
} 