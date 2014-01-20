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

/**
 * Controller for the voting process.
 */
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
	 * Displays a form to authenticate the user for the current election. If
	 * the user has already voted, he is rejected and an error message is shown.
	 * Once the user has successfully authenticated, he is redirected to the
	 * ballotAction.
	 *
	 * @param Request $request An object containing request data.
	 *
	 * @return Response
	 */
	public function indexAction(Request $request)
	{
		$year = date('Y');

		$formVoter = new Voter();
		$form = $this->createForm(new ElectorLoginFormType(), $formVoter);
		$form->handleRequest($request);

		/** @var Voter $voter */
		$voter = $this->getVoterProvider()->byHash($formVoter->getHash());

		if ($form->isValid() && !is_null($voter) && !$voter->getVoted()) {
			$this->getSession()->set('hash', $voter->getHash());
			return $this->redirect($this->generateUrl('btw_app_vote_ballot'));
		} else if (is_null($voter) && !is_null($formVoter->getHash())) {
			$this->flashMessage('error', 'Fehlerhafter Wahlschlüssel, bitte überprüfen Sie Ihre Eingabe.');
		}

		return $this->render('BtwAppBundle:Elector:index.html.twig', array(
			'form' => $form->createView(),
			'year' => $year,
		));
	}

	/**
	 * Displays an election ballot and lets the user elect. Afterwards, the user
	 * is redirected to the previewAction to review his vote.
	 *
	 * @return Response
	 * @throws \Exception for script kiddies.
	 */
	public function ballotAction()
	{
		$hash = $this->getSession()->get('hash');
		$voter = $this->getVoterProvider()->byHash($hash);
		if (empty($voter) || $voter->getVoted()) {
			throw new \Exception('YOU SHALL NOT VOTE!');
		}

		$constituency = $voter->getConstituency();
		$candidates = $this->getCandidateProvider()->forConstituency($constituency);
		$candidates = array_map(array($this, 'serializeCandidate'), $candidates);

		$state = $constituency->getState();
		$parties = $this->getStateListProvider()->forState($state);
		$parties = array_map(array($this, 'serializeStateList'), $parties);

		return $this->render('BtwAppBundle:Elector:ballot.html.twig', array(
			'submitUrl'  => $this->generateUrl('btw_app_vote_preview'),
			'candidates' => $candidates,
			'parties'    => $parties,
		));
	}

	/**
	 * Displays a preview of the vote. The user may then correct the vote or
	 * proceed irreversibly (redirect to submitAction).
	 *
	 * @param Request $request An object containing request data.
	 *
	 * @return Response
	 * @throws \Exception for script kiddies.
	 */
	public function previewAction(Request $request)
	{
		$hash = $this->getSession()->get('hash');
		$voter = $this->getVoterProvider()->byHash($hash);
		if (empty($voter) || $voter->getVoted()) {
			throw new \Exception('YOU SHALL NOT VOTE!');
		}

		$candidateId = $request->get('candidate_id');
		$stateListId = $request->get('state_list_id');

		$this->getSession()->set('candidateId', $candidateId);
		$this->getSession()->set('stateListId', $stateListId);

		$candidate = $this->getCandidateProvider()->byId($candidateId);
		$party = $this->getStateListProvider()->byId($stateListId)->getParty();

		$message = sprintf('<b>1. Stimme:</b> %s <br /> <b>2. Stimme:</b> %s',
			$candidate->getName() . " (" . $candidate->getParty()->getAbbreviation() . ")" ? : 'LEER',
			$party->getName() . " (" . $party->getAbbreviation() . ")" ? : 'LEER');

		return $this->render('BtwAppBundle:Elector:preview.html.twig', array(
			'message'   => $message,
			'submitUrl' => $this->generateUrl('btw_app_vote_submit'),
			'backUrl'   => $this->generateUrl('btw_app_vote_ballot'),
		));
	}

	/**
	 * Persists the vote and redirects back to the indexAction, showing a success
	 * message which hides automatically after a few seconds.
	 *
	 * @return Response
	 */
	public function submitAction()
	{
		$hash = $this->getSession()->get('hash');

		$candidateId = $this->getSession()->get('candidateId');
		$stateListId = $this->getSession()->get('stateListId');

		if ($candidateId && $stateListId) {
			$success = $this->getVoterProvider()->vote($hash, $candidateId, $stateListId);
			if ($success) {
				$this->getSession()->remove('hash');
				$this->flashMessage('success', 'Ihre Stimme wurde erfolgreich abgegeben.');
			}
		}

		return $this->redirect($this->generateUrl('btw_app_vote'));
	}

	//
	// Helpers   --------------------------------------------------------------
	//

	/**
	 * Enqueues a new flash message which will be displayed the next time, a page
	 * is rendered. The rendering mechanism depends on the type of view script
	 * or renderer used when fetching messages from the underlying flash bag.
	 *
	 * @param string $type    The type of the message, e.g. "error" or "warn"
	 * @param string $message The message to display.
	 */
	private function flashMessage($type, $message)
	{
		$this->getSession()->getFlashBag()->add($type, $message);
	}

	/**
	 * @param Candidate $candidate
	 *
	 * @return array
	 */
	private function serializeCandidate(Candidate $candidate)
	{
		$party = $candidate->getParty();
		return array(
			'id'          => $candidate->getId(),
			'name'        => $candidate->getName(),
			'party_abbr'  => $party ? $party->getAbbreviation() : "Parteilos",
			'party_name'  => $party ? $party->getName() : "Parteilos",
			'party_color' => $party ? $party->getColor() : "",
		);
	}

	/**
	 * @param StateList $stateListEntry
	 *
	 * @return array
	 */
	private function serializeStateList(StateList $stateListEntry)
	{
		$party = $stateListEntry->getParty();
		return array(
			'id'    => $stateListEntry->getId(),
			'abbr'  => $party->getAbbreviation(),
			'name'  => $party->getName(),
			'color' => $party->getColor(),
		);
	}

	//
	// Dependencies   ---------------------------------------------------------
	//

	/**
	 * @return CandidateProvider
	 */
	private function getCandidateProvider()
	{
		if ($this->candidateProvider == null)
			$this->candidateProvider = $this->get('btw_candidate_provider');
		return $this->candidateProvider;
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
	 * @return ElectionProvider
	 */
	private function getElectionProvider()
	{
		if ($this->electionProvider == null)
			$this->electionProvider = $this->get('btw_election_provider');
		return $this->electionProvider;
	}

	/**
	 * @return StateListProvider
	 */
	private function getStateListProvider()
	{
		if ($this->stateListProvider == null)
			$this->stateListProvider = $this->get('btw_state_list_provider');
		return $this->stateListProvider;
	}

	/**
	 * @return VoterProvider
	 */
	private function getVoterProvider()
	{
		if ($this->voterProvider == null)
			$this->voterProvider = $this->get('btw_voter_provider');
		return $this->voterProvider;
	}

	/**
	 * @return \Symfony\Component\HttpFoundation\Session\Session
	 */
	private function getSession()
	{
		if ($this->session == null)
			$this->session = $this->get('session');
		return $this->session;
	}

}
