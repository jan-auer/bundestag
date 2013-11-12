<?php
namespace Btw\Bundle\ImporterBundle\Import;

use Btw\Bundle\PersistenceBundle\Entity\Election;

/**
 * Creates Entity objects and also performs correct wiring.
 *
 * @package Btw\Bundle\ImporterBundle\Import
 */
class EntityFactory
{

	private $election;

	/**
	 * @param array $data
	 *
	 * @return Election
	 */
	public function createElection(array $data)
	{
		$election = new Election();
		$election->setNumber($data[0]);
		$election->setDate(new \DateTime($data[1]));

		$this->election = $election;
		return $election;
	}

}
