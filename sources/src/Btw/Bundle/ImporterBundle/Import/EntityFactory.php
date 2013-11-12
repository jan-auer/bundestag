<?php
namespace Btw\Bundle\ImporterBundle\Import;

use Btw\Bundle\PersistenceBundle\Entity\Election;
use Btw\Bundle\PersistenceBundle\Entity\State;
use Symfony\Component\Intl\NumberFormatter\NumberFormatter;

/**
 * Creates Entity objects and also performs correct wiring.
 *
 * @package Btw\Bundle\ImporterBundle\Import
 */
class EntityFactory
{

	/** @var \NumberFormatter */
	private $formatter;

	/** @var  Election */
	private $election;
	/** @var  State[] */
	private $states;

	function __construct()
	{
		$this->formatter = new \NumberFormatter('de_DE', NumberFormatter::DECIMAL);
		$this->states = array();
	}

	public function createElection(array $data)
	{
		$election = new Election();
		$election->setNumber($data[0]);
		$election->setDate(new \DateTime($data[1]));

		$this->election = $election;
		return $election;
	}

	public function createState(array $row)
	{
		$state = new State();
		$state->setName($row[0]);
		$state->setPopulation($this->formatter->parse($row[5]) * 1000);
		$state->setElection($this->election);

		$this->states[$state->getName()] = $state;
		return $state;
	}

}
