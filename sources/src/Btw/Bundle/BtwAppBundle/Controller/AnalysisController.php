<?php

namespace Btw\Bundle\BtwAppBundle\Controller;

use Btw\Bundle\BtwAppBundle\Model\PartyResult;
use Btw\Bundle\BtwAppBundle\Services\ElectionProvider;
use Btw\Bundle\BtwAppBundle\Services\PartyResultsProvider;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller for the main page.
 */
class AnalysisController extends Controller
{

	/** @var  ElectionProvider */
	private $electionProvider;
	/** @var  PartyResultsProvider */
	private $partyResultsProvider;

	/**
	 * Main entry point for all page visitors. Loads the latest election and
	 * computes election results.
	 *
	 * @return Response
	 */
	public function indexAction()
	{
		$years    = $this->getElectionProvider()->getAllYears();
		$year     = max($years);
		$election = $this->getElectionProvider()->forYear($year);

		$results = $this->getPartyResultsProvider()->forCountry($election);
		usort($results, array($this, 'compareResults'));

		$serialized = array_map(array($this, 'serializeResult'), $results);
		return $this->render('BtwAppBundle:Analysis:index.html.twig', array(
			'year'    => $year,
			'years'   => $years,
			'results' => $serialized
		));
	}

	//
	// Helpers   --------------------------------------------------------------
	//

	/**
	 * Converts a {@link PartyResult} object into a plain array.
	 *
	 * @param PartyResult $result The party result to convert.
	 *
	 * @return array A serialized array suitable for JSON encoding.
	 */
	private function serializeResult(PartyResult $result)
	{
		return array(
			'name'  => $result->getAbbr(),
			'label' => $result->getName(),
			'color' => $result->getColor(),
			'seats' => $result->getSeats(),
			'y'     => $result->getSeats(),
		);
	}

	/**
	 * Compares two results based on their seat count.
	 *
	 * @param PartyResult $a The first result.
	 * @param PartyResult $b The second result.
	 *
	 * @return int -1 if the second result is smaller; 0 if they are equal; 1 if the second result is bigger.
	 */
	private function compareResults(PartyResult $a, PartyResult $b)
	{
		$x = $a->getSeats();
		$y = $b->getSeats();
		return $x < $y ? 1 : ($x > $y ? -1 : 0);
	}

	//
	// Dependencies   ---------------------------------------------------------
	//

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
