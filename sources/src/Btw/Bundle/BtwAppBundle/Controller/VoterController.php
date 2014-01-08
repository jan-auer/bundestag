<?php

namespace Btw\Bundle\BtwAppBundle\Controller;

use Btw\Bundle\BtwAppBundle\Form\Type\ElectorLoginFormType;
use Btw\Bundle\BtwAppBundle\Services\CandidateProvider;
use Btw\Bundle\BtwAppBundle\Services\ConstituencyProvider;
use Btw\Bundle\BtwAppBundle\Services\ElectionProvider;
use Btw\Bundle\BtwAppBundle\Services\StateListProvider;
use Btw\Bundle\BtwAppBundle\Services\VoterProvider;
use Btw\Bundle\PersistenceBundle\Entity\Candidate;
use Btw\Bundle\PersistenceBundle\Entity\StateList;
use Btw\Bundle\PersistenceBundle\Entity\Voter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class VoterController extends Controller
{

	/** @var ElectionProvider */
	private $electionProvider;
	/** @var VoterProvider */
	private $voterProvider;
	/** @var StateListProvider */
	private $stateListProvider;
	/** @var ConstituencyProvider */
	private $constituencyProvider;
	/** @var CandidateProvider */
	private $candidateProvider;
	/** @var Session */
	private $session;

	/**
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function indexAction(Request $request)
	{
		$voter = new Voter();
		$year  = date('Y');
		$form  = $this->createForm(new ElectorLoginFormType(), $voter);

		$form->handleRequest($request);

		/** @var Voter $voter */
		$voter = $this->getVoterProvider()->byHash($voter->getHash());

		if ($form->isValid() && !is_null($voter) && !$voter->getVoted()) {
			$session = new Session();
			$session->set('hash', $voter->getHash());

			return $this->redirect($this->generateUrl('btw_app_vote_ballot'));
		}

		return $this->render('BtwAppBundle:Elector:index.html.twig', array(
			'form' => $form->createView(),
			'year' => $year,
		));
	}

	/**
	 * @param Request $request
	 *
	 * @return Response
	 * @throws \Exception
	 */
	public function ballotAction(Request $request)
	{
		$hash = $this->getSession()->get('hash');

		$voter = $this->getVoterProvider()->byHash($hash);
		if (empty($voter) || $voter->getVoted())
			throw new \Exception('YOU SHALL NOT VOTE!');

		$constituency = $voter->getConstituency();
		$candidates   = array_map(function ($candidate) {
			/** @var Candidate $candidate */
			$party = $candidate->getParty();
			return array(
				'name'        => $candidate->getName(),
				'party_abbr'  => $party->getAbbreviation(),
				'party_name'  => $party->getName(),
				'party_color' => $party->getColor()
			);
		}, $this->getCandidateProvider()->forConstituency($constituency));

		$state   = $constituency->getState();
		$parties = array_map(function ($stateListEntry) {
			/** @var StateList $stateListEntry */
			$party = $stateListEntry->getParty();
			return array(
				'abbr'  => $party->getAbbreviation(),
				'name'  => $party->getName(),
				'color' => $party->getColor()
			);
		}, $this->getStateListProvider()->forState($state));

		return $this->render('BtwAppBundle:Elector:ballot.html.twig', array(
				'candidates' => $candidates,
				'parties'    => $parties)
		);
	}

	/**
	 * Expects POST body with variables candidateId, stateListId
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function submitAction(Request $request)
	{
		$hash = $this->getSession()->get('hash');

		$candidateId = $request->get('candidate_id');
		$stateListId = $request->get('state_list_id');

		if ($candidateId && $stateListId) {
			$success = $this->getVoterProvider()->vote($hash, $candidateId, $stateListId);
			if ($success) {
				$this->getSession()->remove('hash');
			}
		} else {
			$success = false;
		}

		return $this->render('BtwAppBundle:Elector:submit.html.twig', array(
			'success' => $success,
		));
	}

	/**
	 * @return CandidateProvider
	 */
	public function getCandidateProvider()
	{
		if ($this->candidateProvider == null)
			$this->candidateProvider = $this->get('btw_candidate_provider');
		return $this->candidateProvider;
	}

	/**
	 * @return ConstituencyProvider
	 */
	public function getConstituencyProvider()
	{
		if ($this->constituencyProvider == null)
			$this->constituencyProvider = $this->get('btw_constituency_provider');
		return $this->constituencyProvider;
	}

	/**
	 * @return ElectionProvider
	 */
	public function getElectionProvider()
	{
		if ($this->electionProvider == null)
			$this->electionProvider = $this->get('btw_election_provider');
		return $this->electionProvider;
	}

	/**
	 * @return StateListProvider
	 */
	public function getStateListProvider()
	{
		if ($this->stateListProvider == null)
			$this->stateListProvider = $this->get('btw_state_list_provider');
		return $this->stateListProvider;
	}

	/**
	 * @return VoterProvider
	 */
	public function getVoterProvider()
	{
		if ($this->voterProvider == null)
			$this->voterProvider = $this->get('btw_voter_provider');
		return $this->voterProvider;
	}

	/**
	 * @return \Symfony\Component\HttpFoundation\Session\Session
	 */
	public function getSession()
	{
		if ($this->session == null)
			$this->session = $this->get('session');
		return $this->session;
	}

}
