<?php

namespace Btw\Bundle\BtwAppBundle\Controller;

use Btw\Bundle\BtwAppBundle\Model\ConstituencyDetail;
use Btw\Bundle\BtwAppBundle\Model\MemberOfBundestag;
use Btw\Bundle\BtwAppBundle\Model\SeatsResult;
use Btw\Bundle\BtwAppBundle\Model\VotesResult;
use Btw\Bundle\BtwAppBundle\Services\ClosestCandidatesProvider;
use Btw\Bundle\BtwAppBundle\Services\ConstituencyProvider;
use Btw\Bundle\BtwAppBundle\Services\ElectionProvider;
use Btw\Bundle\BtwAppBundle\Services\MembersOfBundestagProvider;
use Btw\Bundle\BtwAppBundle\Services\PartyProvider;
use Btw\Bundle\BtwAppBundle\Services\PartyResultsProvider;
use Btw\Bundle\BtwAppBundle\Services\StateProvider;
use Btw\Bundle\PersistenceBundle\Entity\Party;
use Btw\Bundle\PersistenceBundle\Entity\State;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DetailController
	extends Controller
{

	public function indexAction($year)
	{
		return $this->render('BtwAppBundle:Analysis:details.html.twig', array('year' => $year));
	}

	public function electionResultsAction($year)
	{
		//INJECT SERVICES
		/** @var ElectionProvider $electionProvider */
		$electionProvider = $this->get("btw_election_provider");
		/** @var StateProvider $stateProvider */
		$stateProvider = $this->get("btw_state_provider");
		/** @var ConstituencyProvider $constituencyProvider */
		$constituencyProvider = $this->get('btw_constituency_provider');
		/** @var PartyProvider $partyProvider */
		$partyProvider = $this->get('btw_party_provider');
		/** @var MembersOfBundestagProvider $mdbProvider */
		$mdbProvider = $this->get('btw_members_of_bundestag_provider');
		/** @var PartyResultsProvider $partyResultsProvider */
		$partyResultsProvider = $this->get('btw_party_results_provider');

		//INIT
		$election = $electionProvider->forYear($year);
		$prevElection = $electionProvider->getPreviousElectionFor($election);

		//DATA

		//1. States
		$states = array_map(function ($state) {
			/** @var State $state */
			return array(
				'id'         => $state->getId(),
				'name'       => $state->getName(),
				'population' => $state->getPopulation()
			);
		}, $stateProvider->getAllForElection($election));

		//2. Constituencies
		$constituencies = array_map(function ($constituency) {
			/** @var ConstituencyDetail $constituency */
			return $constituency->toArray();
		}, $constituencyProvider->getAllDetailsForElection($election, $prevElection));

		//3. Parties
		$parties = array_map(function ($party) {
			/** @var Party $party */
			return array(
				'id'    => $party->getId(),
				'name'  => $party->getName(),
				'abbr'  => $party->getAbbreviation(),
				'color' => $party->getColor()
			);
		}, $partyProvider->getAllForElection($election));

		//4. MdBs
		$members = array_map(function ($member) {
			/** @var MemberOfBundestag $member */
			return $member->toArray();
		}, $mdbProvider->getAllForElection($election));

		//5. Votes
		$votes = array_map(function ($votes) {
			/** @var VotesResult $votes */
			return $votes->toArray();
		}, $partyResultsProvider->getVotesForElection($election, $prevElection));

		//6. Seats
		$seats = array_map(function ($seats) {
			/** @var SeatsResult $seats */
			return $seats->toArray();
		}, $partyResultsProvider->getSeatsForElection($election));

		//RESULT
		$result = array(
			'states'         => $states,
			'constituencies' => $constituencies,
			'parties'        => $parties,
			'members'        => $members,
			'votes'          => $votes,
			'seats'          => $seats,
		);
		return new Response(json_encode($result));
	}

	public function closestAction($partyId)
	{
		/** @var PartyProvider $partyProvider */
		$partyProvider   = $this->get('btw_party_provider');
		/** @var ClosestCandidatesProvider $closestProvider */
		$closestProvider = $this->get('btw_closest_candidates_provider');

		$party = $partyProvider->byId($partyId);
		$closest = array_map(function ($candidate) {
			return array(
				'name'         => $candidate->getName(),
				'constituency' => $candidate->getConstituencyName(),
				'type'         => $candidate->getType()
			);
		}, $closestProvider->forParty($party));

		$result = array(
			'candidates' => $closest,
			'abbr'       => $party->getAbbreviation(),
			'name'       => $party->getName(),
			'color'      => $party->getColor()
		);

		return $this->render('BtwAppBundle:Analysis:closest.html.twig', $result);
	}
}
