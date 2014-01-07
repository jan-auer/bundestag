<?php

namespace Btw\Bundle\BtwAppBundle\Controller;

use Btw\Bundle\BtwAppBundle\Form\Type\ElectorLoginFormType;
use Btw\Bundle\BtwAppBundle\Services\CandidateProvider;
use Btw\Bundle\BtwAppBundle\Services\ConstituencyProvider;
use Btw\Bundle\BtwAppBundle\Services\ElectionProvider;
use Btw\Bundle\BtwAppBundle\Services\StateListProvider;
use Btw\Bundle\BtwAppBundle\Services\VoterProvider;
use Btw\Bundle\PersistenceBundle\Entity\Candidate;
use Btw\Bundle\PersistenceBundle\Entity\Constituency;
use Btw\Bundle\PersistenceBundle\Entity\Election;
use Btw\Bundle\PersistenceBundle\Entity\StateList;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;


class VoterController extends Controller
{

	/**
	 * @return Response
	 */
	public function indexAction()
	{
		$year = date('Y');
		$form = $this->createForm(new ElectorLoginFormType());

		return $this->render('BtwAppBundle:Elector:index.html.twig', array(
			'form' => $form->createView(),
			'year' => $year,
		));
	}

	/**
	 * @param Request $request
	 * @return Response
	 */
	public function ballotAction(Request $request)
	{
		// INJECT SERVICES
		/** @var ElectionProvider $electionProvider */
		$electionProvider = $this->get('btw_election_provider');
		/** @var VoterProvider $voterProvider */
		$voterProvider = $this->get('btw_voter_provider');
		/** @var StateListProvider $stateListProvider */
		$stateListProvider = $this->get('btw_state_list_provider');
		/** @var ConstituencyProvider $constituencyProvider */
		$constituencyProvider = $this->get('btw_constituency_provider');
		/** @var CandidateProvider $candidateProvider */
		$candidateProvider = $this->get('btw_candidate_provider');

		// Extract POST body
		$hash = $request->request->get('hash');

		// Get and validate user
		$voter = $voterProvider->byHash($hash);

		if (is_null($voter)) {
			// Error: Not yet registered or expired
			return new Response(1);
		}

		$canVote = !$voter->getVoted();

		if (!$canVote) {
			// Error: Already voted
			return new Response(2);
		}

		// Data retrieval
		//  General

		$electionId = $voter->getElectionId();
		$constituencyId = $voter->getConstituencyId();

		/** @var Election $parties */
		$election = $electionProvider->byId($electionId);
		/** @var Constituency $constituency */
		$constituency = $constituencyProvider->byId($constituencyId);

		//  First vote
		$candidates = array_map(function ($candidate) {
			/** @var Candidate $candidate */
			return array('name' => $candidate->getName(),
						 'party_abbr' => $candidate->getParty()->getAbbreviation(),
						 'party_name' => $candidate->getParty()->getName(),
						 'party_color' => $candidate->getParty()->getColor());
		}, $candidateProvider->forConstituency($constituency));

		//  Second vote
		$state = $constituency->getState();
		$parties = array_map(function ($stateListEntry) {
			/** @var StateList $stateListEntry */
			return array('party_abbr' => $stateListEntry->getParty()->getAbbreviation(),
						 'party_name' => $stateListEntry->getParty()->getName(),
						 'party_color' => $stateListEntry->getParty()->getColor());
		}, $stateListProvider->forState($state));

		// RESULT
		$result = array('candidates' => $candidates,
						'parties' => $parties);

		return $this->render('BtwAppBundle:Elector:ballot.html.twig', $result);
	}

	/**
	 * @param Request $request
	 * @return Response
	 */
	public function submitAction(Request $request)
	{
		/** @var VoterProvider $voterProvider */
		$voterProvider = $this->get('btw_voter_provider');

		$session = new Session();
		$hash = $session->get('hash');

		$candidateId = $request->get('candidateId');
		$stateListId = $request->get('stateListId');

		$successfull = $voterProvider->vote($hash, $candidateId, $stateListId);

		if ($successfull) {
			$session->remove('hash');
		}

		return $this->render('BtwAppBundle:Elector:submit.html.twig', array(
			'successfull' => $successfull
		));
	}

	/**
	 * @param Request $request
	 * @return Response
	 */
	public function createVoterAction(Request $request)
	{
		// Inject Services
		/** @var VoterProvider $voterProvider */
		$voterProvider = $this->get('btw_voter_provider');
		/** @var ConstituencyProvider $constituencyProvider */
		$constituencyProvider = $this->get('btw_constituency_provider');

		// Extract POST body
		$identityNumber = $request->get('identityNumber');
		$constituencyId = $request->get('constituencyId');

		$constituency = $constituencyProvider->byId($constituencyId);

		// Insert
		$success = $voterProvider->createVoter($identityNumber, $constituency);

		// Return result
		if(!$success){
			// Error: Insertion failed
			return new Response(1);
		}
		return new Response(0);
	}
} 