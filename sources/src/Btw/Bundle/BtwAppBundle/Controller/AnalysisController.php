<?php

namespace Btw\Bundle\BtwAppBundle\Controller;

use Btw\Bundle\BtwAppBundle\Model\PartyResult;
use Btw\Bundle\BtwAppBundle\Services\ElectionProvider;
use Btw\Bundle\BtwAppBundle\Services\PartyResultsProvider;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AnalysisController extends Controller
{
	/** @var  ElectionProvider */
	private $electionProvider;
	/** @var  PartyResultsProvider */
	private $partyResultsProvider;

	public function indexAction()
	{
		$years    = $this->getElectionYears();
		$year     = max($years);
		$election = $this->getElectionProvider()->forYear($year);

		$results = $this->getPartyResultsProvider()->forCountry($election);
		usort($results, array($this, 'compareResults'));

		$serialized = array_map(array($this, 'serializeResult'), $results);
		return $this->render('BtwAppBundle:Analysis:index.html.twig', array(
				'year'    => $year,
				'years'   => $years,
				'results' => $serialized)
		);
	}

	private function getElectionYears()
	{
		$elections = $this->getElectionProvider()->getAll();

		return array_map(function ($election) {
			return date('Y', $election->getDate()->getTimestamp());
		}, $elections);
	}

	private function serializeResult(PartyResult $result)
	{
		return array(
			'name'  => $result->getPartyAbbreviation(),
			'label' => $result->getPartyFullName(),
			'color' => $result->getColor(),
			'seats' => $result->getSeats(),
			'y'     => $result->getSeats(),
		);
	}

	private function compareResults(PartyResult $a, PartyResult $b)
	{
		$x = $a->getSeats();
		$y = $b->getSeats();
		return $x < $y ? 1 : ($x > $y ? -1 : 0);
	}

	/**
	 * @return ElectionProvider
	 */
	private function getElectionProvider()
	{
		if ($this->electionProvider == null) {
			$this->electionProvider = $this->get('btw_election_provider');
		}
		return $this->electionProvider;
	}

	/**
	 * @return PartyResultsProvider
	 */
	private function getPartyResultsProvider()
	{
		if ($this->partyResultsProvider == null) {
			$this->partyResultsProvider = $this->get('btw_party_results_provider');
		}
		return $this->partyResultsProvider;
	}
}
