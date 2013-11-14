<?php
namespace Btw\Bundle\ImporterBundle\Import;

use Btw\Bundle\PersistenceBundle\Entity\Candidate;
use Btw\Bundle\PersistenceBundle\Entity\Constituency;
use Btw\Bundle\PersistenceBundle\Entity\ConstituencyCandidacy;
use Btw\Bundle\PersistenceBundle\Entity\Election;
use Btw\Bundle\PersistenceBundle\Entity\Party;
use Btw\Bundle\PersistenceBundle\Entity\State;
use Btw\Bundle\PersistenceBundle\Entity\FirstResult;
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

	function __construct()
	{
		$this->formatter = new \NumberFormatter('de_DE', NumberFormatter::DECIMAL);

		$this->states = array();
		$this->constituencies = array();
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
		$state->setNumber($row[1] - 900);
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

	public function createParty($name)
	{
		$party = new Party();
		$party->setName($name);
		$party->setAbbreviation($name);
		$party->setMinorityRepresentation(false);

		$this->parties[$name] = $party;
		return $party;
	}

	public function createCandidateConstituency($candidate, $constituency)
	{
		$constituencyCandidacy = new ConstituencyCandidacy();
		$constituencyCandidacy->setConstituency($constituency);
		$constituencyCandidacy->setCandidate($candidate);

		return $constituencyCandidacy;
	}

	public function createFirstResult($candidate) {
		$firstResult = new FirstResult();
		$firstResult->setCandidate($candidate);
		return $firstResult;
		//TODO
	}

}
