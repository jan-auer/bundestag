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

		$election = $electionProvider->forYear($year);
		$states = array();
		foreach ($stateProvider->getAllForElection($election) as $state) {
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
		$state = $stateProvider->byId($stateId);
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
			$results = $this->getResultForConstituency($constituencyId);
		} else if ($stateId > 0 && $constituencyId == 0) {
			//RESULTS PER STATE
			$results = $this->getResultForState($stateId);
		} else {
			//TOTAL RESULTS
			$results = $this->getResultForCountry($year);
		}

		return new Response(json_encode($results));
	}

	private function getResultForConstituency($constituencyId)
	{
		$scope = '';
		$chart  = array();
		$location   = array();
		$members = array();
		$parties = array();

		$constituencyProvider = $this->get('btw_constituency_provider');
		$locationDetailsProvider = $this->get('btw_location_details_provider');
		$membersOfBundestagProvider = $this->get('btw_members_of_bundestag_provider');
		$partyResultsProvider = $this->get('btw_party_results_provider');

		$constituency = $constituencyProvider->byId($constituencyId);
		$partyResults = $partyResultsProvider->forConstituency($constituency);

		//SCOPE
		$scope = $constituency->getName();

		//CHART
		foreach($partyResults as $result)
		{
			$chart[] = array('name' => $result->getPartyAbbreviation(), 'color' => $result->getColor(), 'y' => $result->getVotes());
		}
		usort($chart, function($result1, $result2)
		{
			if($result1['y'] == $result2['y']) return 0;
			if($result1['y'] < $result2['y']) return 1;
			return -1;
		});

		//LOCATION
		$locationDetails = $locationDetailsProvider->forConstituency($constituency);
		$location = array('population' => $locationDetails->getPopulation(),
						  'participation' => $locationDetails->getParticipation());

		// MEMBERS
		$membersOfBundestag = $membersOfBundestagProvider->forConstituency($constituency);
		foreach($membersOfBundestag as $member)
		{
			$members[] = array('name' => $member->getName(),
								'party' => $member->getPartyAbbreviation(),
								'direct' => $member->getIsDirect());
		}

		// PARTIES
		foreach($partyResults as $result)
		{
			$parties[] = array('abbreviation' => $result->getPartyAbbreviation(),
								'name' => $result->getPartyFullName(),
								'votes' => $result->getVotes(),
								'percentage' => $result->getPercentage(),
								'seats' => $result->getSeats(),
								'overhead' => $result->getOverhead());
		}

		return array('scope'     => $scope,
		             'chart'     => array('data' => $chart, 'type' => 'Zweitstimmen'),
		             'location'  => $location,
		             'members'   => $members,
		             'parties'   => $parties);
	}

	private function getResultForState($stateId)
	{
		$scope = '';
		$chart  = array();
		$location   = array();
		$members = array();
		$parties = array();

		$stateProvider = $this->get('btw_state_provider');
		$locationDetailsProvider = $this->get('btw_location_details_provider');
		$membersOfBundestagProvider = $this->get('btw_members_of_bundestag_provider');
		$partyResultsProvider = $this->get('btw_party_results_provider');

		$state = $stateProvider->byId($stateId);
		$partyResults = $partyResultsProvider->forState($state);

		//SCOPE
		$scope = $state->getName();

		//CHART
		foreach($partyResults as $result)
		{
			$chart[] = array('name' => $result->getPartyAbbreviation(), 'color' => $result->getColor(), 'y' => $result->getSeats());
		}
		usort($chart, function($result1, $result2)
		{
			if($result1['y'] == $result2['y']) return 0;
			if($result1['y'] < $result2['y']) return 1;
			return -1;
		});

		//LOCATION
		$locationDetails = $locationDetailsProvider->forState($state);
		$location = array('population' => $locationDetails->getPopulation(),
			'participation' => $locationDetails->getParticipation());

		//MEMBERS
		$membersOfBundestag = $membersOfBundestagProvider->forState($state);
		foreach($membersOfBundestag as $member)
		{
			$members[] = array('name' => $member->getName(),
				'party' => $member->getPartyAbbreviation(),
				'direct' => $member->getIsDirect());
		}

		//PARTIES
		foreach($partyResults as $result)
		{
			$parties[] = array('abbreviation' => $result->getPartyAbbreviation(),
				'name' => $result->getPartyFullName(),
				'votes' => $result->getVotes(),
				'percentage' => $result->getPercentage(),
				'seats' => $result->getSeats(),
				'overhead' => $result->getOverhead());
		}

		return array('scope'    => $scope,
		             'chart'    => array('data' => $chart, 'type' => 'Sitze'),
		             'location' => $location,
		             'members'  => $members,
		             'parties'  => $parties);
	}

	private function getResultForCountry($year)
	{
		$scope = '';
		$chart  = array();
		$location   = array();
		$members = array();
		$parties = array();

		$electionProvider = $this->get('btw_election_provider');
		$locationDetailsProvider = $this->get('btw_location_details_provider');
		$membersOfBundestagProvider = $this->get('btw_members_of_bundestag_provider');
		$partyResultsProvider = $this->get('btw_party_results_provider');

		$election = $electionProvider->forYear($year);
		$partyResults = $partyResultsProvider->forCountry($election);

		//SCOPE
		$scope = "Gesamt";

		//CHART
		foreach($partyResults as $result)
		{
			$chart[] = array('name' => $result->getPartyAbbreviation(), 'color' => $result->getColor(), 'y' => $result->getSeats());
		}
		usort($chart, function($result1, $result2)
		{
			if($result1['y'] == $result2['y']) return 0;
			if($result1['y'] < $result2['y']) return 1;
			return -1;
		});

		//LOCATION
		$locationDetails = $locationDetailsProvider->forCountry($election);
		$location = array('population' => $locationDetails->getPopulation(),
			'participation' => $locationDetails->getParticipation());

		//MEMBERS
		$membersOfBundestag = $membersOfBundestagProvider->forCountry($election);
		foreach($membersOfBundestag as $member)
		{
			$members[] = array('name' => $member->getName(),
				'party' => $member->getPartyAbbreviation(),
				'direct' => $member->getIsDirect());
		}

		//PARTIES
		foreach($partyResults as $result)
		{
			$parties[] = array('abbreviation' => $result->getPartyAbbreviation(),
				'name' => $result->getPartyFullName(),
				'votes' => $result->getVotes(),
				'percentage' => $result->getPercentage(),
				'seats' => $result->getSeats(),
				'overhead' => $result->getOverhead());
		}

		return array('scope'    => $scope,
		             'chart'    => array('data' => $chart, 'type' => 'Sitze'),
		             'location' => $location,
		             'members'  => $members,
		             'parties'  => $parties);
	}
}
