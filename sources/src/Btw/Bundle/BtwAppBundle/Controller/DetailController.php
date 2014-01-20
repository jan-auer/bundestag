<?php

namespace Btw\Bundle\BtwAppBundle\Controller;

use Btw\Bundle\BtwAppBundle\Model\ClosestCandidate;
use Btw\Bundle\BtwAppBundle\Model\ModelInterface;
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

class DetailController extends Controller
{

	/** @var  ElectionProvider */
	private $electionProvider;
	/** @var  StateProvider */
	private $stateProvider;
	/** @var  ConstituencyProvider */
	private $constituencyProvider;
	/** @var  PartyProvider */
	private $partyProvider;
	/** @var  MembersOfBundestagProvider */
	private $mdbProvider;
	/** @var  PartyResultsProvider */
	private $partyResultsProvider;
	/** @var  ClosestCandidatesProvider */
	private $closestProvider;

	/**
	 * Renders the details view of an election. All further information is loaded via
	 * AJAX to improve page loading speed and increase user satisfaction.
	 *
	 * @param int $year A year to identify the election.
	 *
	 * @return Response A rendered HTML view script.
	 */
	public function indexAction($year)
	{
		return $this->render('BtwAppBundle:Analysis:details.html.twig', array('year' => $year));
	}

	/**
	 * Computes all election results of one specific election, including the number of
	 * votes for each party and constituency as well as elected members.
	 *
	 * @param int $year A year to identify the election.
	 *
	 * @return Response All results in a JSON encoded object.
	 */
	public function electionResultsAction($year)
	{
		$election     = $this->getElectionProvider()->forYear($year);
		$prevElection = $this->getElectionProvider()->getPreviousElectionFor($election);

		$states = $this->getStateProvider()->getAllForElection($election);
		$states = array_map(array($this, 'serializeState'), $states);

		$constituencies = $this->getConstituencyProvider()->getAllDetailsForElection($election, $prevElection);
		$constituencies = array_map(array($this, 'serializeModel'), $constituencies);

		$parties = $this->getPartyProvider()->getAllForElection($election);
		$parties = array_map(array($this, 'serializeParty'), $parties);

		$members = $this->getMdbProvider()->getAllForElection($election);
		$members = array_map(array($this, 'serializeModel'), $members);

		$votes = $this->getPartyResultsProvider()->getVotesForElection($election, $prevElection);
		$votes = array_map(array($this, 'serializeModel'), $votes);

		$seats = $this->getPartyResultsProvider()->getSeatsForElection($election);
		$seats = array_map(array($this, 'serializeModel'), $seats);

		return new Response(json_encode(array(
			'states'         => $states,
			'constituencies' => $constituencies,
			'parties'        => $parties,
			'members'        => $members,
			'votes'          => $votes,
			'seats'          => $seats,
		)));
	}

	/**
	 * Computes the closest winners or losers depending on the number of votes, a
	 * candidate received in his constituency.
	 *
	 * @param int $partyId The party of the candidates.
	 *
	 * @return Response A rendered HTML view with all closest candidates..
	 */
	public function closestAction($partyId)
	{
		$party = $this->getPartyProvider()->byId($partyId);

		$closest = $this->getClosestProvider()->forParty($party);
		$closest = array_map(array($this, 'serializeClosest'), $closest);

		return $this->render('BtwAppBundle:Analysis:closest.html.twig', array(
			'candidates' => $closest,
			'abbr'       => $party->getAbbreviation(),
			'name'       => $party->getName(),
			'color'      => $party->getColor()
		));
	}

	//
	// Helpers   --------------------------------------------------------------
	//

	/**
	 * Converts a {@link ModelInterface} object into a plain array.
	 *
	 * @param ModelInterface $model The object to convert.
	 *
	 * @return array A serialized array suitable for JSON encoding.
	 */
	private function serializeModel(ModelInterface $model)
	{
		return $model->toArray();
	}

	/**
	 * Converts a {@link State} object into a plain array.
	 *
	 * @param State $state The object to convert.
	 *
	 * @return array A serialized array suitable for JSON encoding.
	 */
	public function serializeState(State $state)
	{
		return array(
			'id'         => $state->getId(),
			'name'       => $state->getName(),
			'population' => $state->getPopulation()
		);
	}

	/**
	 * Converts a {@link Party} object into a plain array.
	 *
	 * @param Party $party The object to convert.
	 *
	 * @return array A serialized array suitable for JSON encoding.
	 */
	private function serializeParty(Party $party)
	{
		return array(
			'id'    => $party->getId(),
			'name'  => $party->getName(),
			'abbr'  => $party->getAbbreviation(),
			'color' => $party->getColor()
		);
	}

	/**
	 * Converts a {@link ClosestCandidate} object into a plain array.
	 *
	 * @param ClosestCandidate $candidate The object to convert.
	 *
	 * @return array A serialized array suitable for JSON encoding.
	 */
	private function serializeClosest(ClosestCandidate $candidate)
	{
		return array(
			'name'         => $candidate->getName(),
			'constituency' => $candidate->getConstituency(),
			'type'         => $candidate->getType()
		);
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
	 * @return StateProvider
	 */
	private function getStateProvider()
	{
		if ($this->stateProvider == null)
			$this->stateProvider = $this->get('btw_state_provider');
		return $this->stateProvider;
	}

	/**
	 * @return ConstituencyProvider
	 */
	private function getConstituencyProvider()
	{
		if ($this->constituencyProvider == null)
			$this->constituencyProvider = $this->get('btw_constituency_provider');
		return $this->constituencyProvider;
	}

	/**
	 * @return MembersOfBundestagProvider
	 */
	private function getMdbProvider()
	{
		if ($this->mdbProvider == null) {
			$this->mdbProvider = $this->get('btw_members_of_bundestag_provider');
		}
		return $this->mdbProvider;
	}

	/**
	 * @return PartyProvider
	 */
	private function getPartyProvider()
	{
		if ($this->partyProvider == null) {
			$this->partyProvider = $this->get('btw_party_provider');
		}
		return $this->partyProvider;
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

	/**
	 * @return ClosestCandidatesProvider
	 */
	private function getClosestProvider()
	{
		if ($this->closestProvider == null) {
			$this->closestProvider = $this->get('btw_closest_candidates_provider');
		}
		return $this->closestProvider;
	}

}
