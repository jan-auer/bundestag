<?php
namespace Btw\Bundle\ImporterBundle\Import;

use Btw\Bundle\PersistenceBundle\Entity\AggregatedFirstResult;
use Btw\Bundle\PersistenceBundle\Entity\AggregatedSecondResult;
use Btw\Bundle\PersistenceBundle\Entity\Candidate;
use Btw\Bundle\PersistenceBundle\Entity\Constituency;
use Btw\Bundle\PersistenceBundle\Entity\ConstituencyCandidacy;
use Btw\Bundle\PersistenceBundle\Entity\Election;
use Btw\Bundle\PersistenceBundle\Entity\Party;
use Btw\Bundle\PersistenceBundle\Entity\State;
use Btw\Bundle\PersistenceBundle\Entity\FirstResult;
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
	/** @var Candidate[] */
	private $candidates;
	/** @var ConstituencyCandidacy[][] */
	private $constituencyCandidacies;

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

	public function createParty($partyAbbr, &$partynamemapping)
	{
		$partyName = $this->fullPartyNameForAbbreviation($partyAbbr, $partynamemapping);

		$party = new Party();
		$party->setName($partyName);
		$party->setAbbreviation($partyAbbr);
		$party->setMinorityRepresentation(false);

		$this->parties[$partyAbbr] = $party;
		return $party;
	}

	public function createFirstResult($freeConstituencyCandidate)
	{
		$firstResult = new FirstResult();
		$firstResult->setConstituencyCandidacy($freeConstituencyCandidate);
		return $firstResult;
	}

	private function fullPartyNameForAbbreviation($partyAbbr, &$partynamemapping)
	{
		foreach ($partynamemapping as $partyname) {
			if ($partyname[0] == $partyAbbr) {
				return $partyname[1];
			}
		}

		return $partyAbbr;
	}

	public function createStateList($stateName, $partyAbbr)
	{
		$state = $this->states[$stateName];
		$party = $this->parties[$partyAbbr];

		$stateList = new StateList();
		$stateList->setParty($party);
		$stateList->setState($state);

		$this->stateLists[$partyAbbr][$state->getNumber()] = $stateList;
		return $stateList;
	}

	public function createCandidate($name, $partyAbbr = null)
	{
		$candidate = new Candidate();
		$candidate->setName($name);

		if ($partyAbbr != null) {
			if (!array_key_exists($partyAbbr, $this->parties)) return null;

			$party = $this->parties[$partyAbbr];

			$candidate->setParty($party);
		}

		$this->candidates[] = $candidate;
		return $candidate;
	}

	public function createConstituencyCandidacy($candidate, $constituencyId)
	{
		$constituency = $this->constituencies[$constituencyId];

		$constituencyCandidacy = new ConstituencyCandidacy();
		$constituencyCandidacy->setCandidate($candidate);
		$constituencyCandidacy->setConstituency($constituency);

		if ($candidate->getParty() != null) {
			$abbr = $candidate->getParty()->getAbbreviation();
			$this->constituencyCandidacies[$constituencyId][$abbr] = $constituencyCandidacy;
		} else {
			$this->constituencyCandidacies[$constituencyId]['free'][] = $constituencyCandidacy;
		}
		return $constituencyCandidacy;
	}

	public function createAggregatedFirstResult($constituencyCandidacy, $votes)
	{
		$aggrFirstResult = new AggregatedFirstResult();
		$aggrFirstResult->setConstituencyCandidacy($constituencyCandidacy);
		$aggrFirstResult->setCount($votes);
		return $aggrFirstResult;
	}

	public function createAggregatedFirstResultRow($constituencyId, Party $party, $votes)
	{
		if (!array_key_exists($constituencyId, $this->constituencyCandidacies)) return null;

		$constituencyCandidacies = $this->constituencyCandidacies[$constituencyId];

		if (!array_key_exists($party->getAbbreviation(), $constituencyCandidacies)) {
			return null;
		}

		$constituencyCandidacy = $constituencyCandidacies[$party->getAbbreviation()];
		if ($constituencyCandidacy == null) return null;

		$aggrFirstResult = new AggregatedFirstResult();
		$aggrFirstResult->setConstituencyCandidacy($constituencyCandidacy);
		$aggrFirstResult->setCount($votes);

		return $aggrFirstResult;
	}

	public function createAggregatedSecondResult($party, $stateNo, $constituencyNo, $votes)
	{
		$partyAbbr = $party->getAbbreviation();
		if (!array_key_exists($partyAbbr, $this->stateLists)) return null;

		if (!array_key_exists($stateNo, $this->stateLists[$partyAbbr])) return null;

		$statelist = $this->stateLists[$partyAbbr][$stateNo];

		if (!array_key_exists($constituencyNo, $this->constituencies)) return null;
		$constituency = $this->constituencies[$constituencyNo];

		$aggrSecondResult = new AggregatedSecondResult();
		$aggrSecondResult->setStateList($statelist);
		$aggrSecondResult->setConstituency($constituency);
		$aggrSecondResult->setCount($votes);

		return $aggrSecondResult;
	}
}
