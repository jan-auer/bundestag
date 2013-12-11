<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 06.12.13
 * Time: 21:47
 */

namespace Btw\Bundle\BtwAppBundle\Controller;


use Btw\Bundle\BtwAppBundle\Form\ElectionAnalysisForm;
use Btw\Bundle\BtwAppBundle\Model\ElectionAnalysisModel;
use DateTime;
use Doctrine\Tests\ORM\Id\AssignedGeneratorTest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DetailController extends Controller
{
	public function indexAction($year)
	{
		return $this->render('BtwAppBundle:Analysis:details.html.twig', array('year' => $year));

	}

	public function listStatesAction($year)
	{
		$electionProvider = $this->get("btw_election_provider");
		$stateProvider = $this->get("btw_state_provider");

		$election = $electionProvider->getElectionFor($year);
		$states = array();
		foreach ($stateProvider->getStatesFor($election) as $state) {
			$states[] = array(
				'id' => $state->getId(),
				'name' => $state->getName()
			);
		}

		return new Response(json_encode($states));

	}

	public function listConstituenciesAction($stateId)
	{
		$stateProvider = $this->get("btw_state_provider");
		$state = $stateProvider->getStateById($stateId);
		$constituencies = array();
		foreach ($state->getConstituencies() as $constituency) {
			$constituencies[] = array(
				'id' => $constituency->getId(),
				'name' => $constituency->getName()
			);
		}
		return new Response(json_encode($constituencies));
	}

	public function listResultsAction($year, $stateId = null, $constituencyId = null)
	{
		$results = array();
		if ($stateId > 0 && $constituencyId > 0) {
			//RESULTS PER CONSTITUENCY
			$constituencyProvider = $this->get('btw_constituency_provider');
			$partyVotesProvider = $this->get('btw_party_votes_result_provider');

			$constituency = $constituencyProvider->getConstituencyById($constituencyId);
			$partyVotesResults = $partyVotesProvider->getPartyVotesForConstituency($constituency);
			foreach($partyVotesResults as $result)
			{
				$results[] = array('name' => $result->getAbbreviation(), 'color' => $result->getColor(), 'y' => $result->getVotes());
			}
		} else if ($stateId > 0 && $constituencyId == 0) {
			//RESULTS PER STATE
			$stateProvider = $this->get('btw_state_provider');
			$partySeatsProvider = $this->get('btw_party_seats_result_provider');

			$state = $stateProvider->getStateById($stateId);
			$partySeatsResults = $partySeatsProvider->getPartySeatsForState($state);
			foreach($partySeatsResults as $result)
			{
				$results[] = array('name' => $result->getAbbreviation(), 'color' => $result->getColor(), 'y' => $result->getSeats());
			}
		} else {
			//TOTAL RESULTS
			$electionProvider = $this->get('btw_election_provider');
			$partySeatsProvider = $this->get('btw_party_seats_result_provider');

			$election = $electionProvider->getElectionFor($year);
			$partySeatsResults = $partySeatsProvider->getPartySeatsForCountry($election);
			foreach($partySeatsResults as $result)
			{
				$results[] = array('name' => $result->getAbbreviation(), 'color' => $result->getColor(), 'y' => $result->getSeats());
			}
		}

		usort($results, function($result1, $result2)
		{
			if($result1['y'] == $result2['y']) return 0;
			if($result1['y'] < $result2['y']) return 1;
			return -1;
		});
		return new Response(json_encode($results));
	}
}
