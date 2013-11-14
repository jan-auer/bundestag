<?php
namespace Btw\Bundle\ImporterBundle\Import;

use Btw\Bundle\PersistenceBundle\Entity\Constituency;
use Btw\Bundle\PersistenceBundle\Entity\Election;
use Btw\Bundle\PersistenceBundle\Entity\Party;
use Btw\Bundle\PersistenceBundle\Entity\State;
use Btw\Bundle\PersistenceBundle\Entity\StateList;
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
	/** @var  Constituency[] */
	private $constituencies;
	/** @var  Party[] */
	private $parties;
	/** @var StateList[][] */
	private $stateLists;

	function __construct()
	{
		$this->formatter = new \NumberFormatter('de_DE', NumberFormatter::DECIMAL);

		$this->states = array();
		$this->constituencies = array();
		$this->parties = array();
	}

	public function createElection(array &$data)
	{
		$election = new Election();
		$election->setNumber($data[0]);
		$election->setDate(new \DateTime($data[1]));

		$this->election = $election;
		return $election;
	}

	public function createState(array &$row)
	{
		$state = new State();
		$state->setName($row[0]);
		$state->setPopulation($this->formatter->parse($row[5]) * 1000);
		$state->setElection($this->election);

		$this->states[$state->getName()] = $state;
		return $state;
	}

	public function createConstituency(array &$row)
	{
		$constituency = new Constituency();
		$constituency->setName($row[2]);
		$constituency->setNumber($row[1]);
		$constituency->setState($this->states[$row[0]]);

		$this->constituencies[$constituency->getNumber()] = $constituency;
		return $constituency;
	}

	public function createParty($partyAbbr)
	{
		$party = new Party();
		$party->setName($partyAbbr);
		$party->setAbbreviation($partyAbbr);
		$party->setMinorityRepresentation(false);

		$this->parties[$partyAbbr] = $party;
		return $party;
	}

	public function createStateList($stateName, $partyAbbr)
	{
		$state = $this->states[$stateName];
		$party = $this->parties[$partyAbbr];
		
		$stateList = new StateList();
		$stateList->setParty($party);
		$stateList->setState($state);

		$this->stateLists[$party->getAbbreviation()][$state->getName()] = $stateList;
		return $stateList;
	}
}
