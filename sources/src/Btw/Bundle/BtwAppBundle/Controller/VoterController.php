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

	public function indexAction(Request $request)
	{
		$voter = new Voter();
		$year = date('Y');
		$form = $this->createForm(new ElectorLoginFormType(), $voter);

		$form->handleRequest($request);

		/** @var Voter $voter */
		$voter = $this->getVoterProvider()->byHash($voter->getHash());

		if ($form->isValid() && !is_null($voter) && !$voter->getVoted()) {
			$session = new Session();
			$session->set('hash', $voter->getHash());

			return $this->redirect($this->generateUrl('btw_app_vote_ballot'));
		} else if (!is_null($voter)) {
			$this->flashMessage('error', 'Fehlerhafter Wahlschlüssel, bitte überprüfen Sie Ihre Eingabe.');
		}

		return $this->render('BtwAppBundle:Elector:index.html.twig', array(
			'form' => $form->createView(),
			'year' => $year,
		));
	}

	public function ballotAction()
	{
		$hash = $this->getSession()->get('hash');

		$voter = $this->getVoterProvider()->byHash($hash);
		if (empty($voter) || $voter->getVoted())
			throw new \Exception('YOU SHALL NOT VOTE!');

		$constituency = $voter->getConstituency();
		$candidates = array_map(function ($candidate) {
			/** @var Candidate $candidate */
			$party = $candidate->getParty();
			return array(
				'id'          => $candidate->getId(),
				'name'        => $candidate->getName(),
				'party_abbr'  => $party ? $party->getAbbreviation() : "Parteilos",
				'party_name'  => $party ? $party->getName() : "Parteilos",
				'party_color' => $party ? $party->getColor() : "",
			);
		}, $this->getCandidateProvider()->forConstituency($constituency));

		$state = $constituency->getState();
		$parties = array_map(function ($stateListEntry) {
			/** @var StateList $stateListEntry */
			$party = $stateListEntry->getParty();
			return array(
				'id' => $stateListEntry->getId(),
				'abbr' => $party->getAbbreviation(),
				'name' => $party->getName(),
				'color' => $party->getColor()
			);
		}, $this->getStateListProvider()->forState($state));

		return $this->render('BtwAppBundle:Elector:ballot.html.twig', array(
			'submitUrl' => $this->generateUrl('btw_app_vote_preview'),
			'candidates' => $candidates,
			'parties' => $parties,
		));
	}

	public function previewAction(Request $request)
	{
		$hash = $this->getSession()->get('hash');
		$voter = $this->getVoterProvider()->byHash($hash);

		if (empty($voter) || $voter->getVoted())
			throw new \Exception('YOU SHALL NOT VOTE!');

		$candidateId = $request->get('candidate_id');
		$stateListId = $request->get('state_list_id');

		$this->getSession()->set('candidateId', $candidateId);
		$this->getSession()->set('stateListId', $stateListId);

		$candidate = $this->getCandidateProvider()->byId($candidateId);
		$party = $this->getStateListProvider()->byId($stateListId)->getParty();

		$message = sprintf('<b>1. Stimme:</b> %s <br /> <b>2. Stimme:</b> %s',
			$candidate->getName() ? : 'LEER',
			$party->getName() . " (" . $party->getAbbreviation() . ")" ? : 'LEER');

		return $this->render('BtwAppBundle:Elector:preview.html.twig', array(
			'message' => $message,
			'submitUrl' => $this->generateUrl('btw_app_vote_submit'),
			'backUrl' => $this->generateUrl('btw_app_vote_ballot'),
		));
	}

	public function submitAction()
	{
		$hash = $this->getSession()->get('hash');

		$candidateId = $this->getSession()->get('candidateId');
		$stateListId = $this->getSession()->get('stateListId');

		if ($candidateId && $stateListId) {
			if ($this->getVoterProvider()->vote($hash, $candidateId, $stateListId)) {
				$this->getSession()->remove('hash');
				$this->flashMessage('success', 'Ihre Stimme wurde für die Wahl berücksichtigt.');
			}
		}

		return $this->redirect($this->generateUrl('btw_app_vote'));
	}

	/**
	 * @param string $type
	 * @param string $message
	 */
	public function flashMessage($type, $message)
	{
		$this->getSession()->getFlashBag()->add($type, $message);
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
