<?php

namespace Btw\Bundle\BtwAppBundle\Controller;

use Btw\Bundle\BtwAppBundle\Form\Type\ElectorLoginFormType;
use Btw\Bundle\BtwAppBundle\Services\CandidateProvider;
use Btw\Bundle\BtwAppBundle\Services\ConstituencyProvider;
use Btw\Bundle\BtwAppBundle\Services\ElectionProvider;
use Btw\Bundle\BtwAppBundle\Services\PartyProvider;
use Btw\Bundle\BtwAppBundle\Services\VoterProvider;
use Btw\Bundle\PersistenceBundle\Entity\Constituency;
use Btw\Bundle\PersistenceBundle\Entity\Election;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class VoterController extends Controller
{

	public function indexAction()
	{
		$year = date('Y');
		$form = $this->createForm(new ElectorLoginFormType());

		return $this->render('BtwAppBundle:Elector:index.html.twig', array(
			'form' => $form->createView(),
			'year' => $year,
		));
	}

	public function ballotAction(Request $request)
	{
		// INJECT SERVICES
		/** @var ElectionProvider $electionProvider */
		$electionProvider = $this->get('btw_election_provider');
		/** @var VoterProvider $voterProvider */
		$voterProvider = $this->get('btw_voter_provider');
		/** @var PartyProvider $partyProvider */
		$partyProvider = $this->get('btw_party_provider');
		/** @var ConstituencyProvider $constituencyProvider */
		$constituencyProvider = $this->get('btw_constituency_provider');
		/** @var CandidateProvider $candidateProvider */
		$candidateProvider = $this->get('btw_candidate_provider');

		// Extract POST body
		$hash = $request->request->get('hash');

		// Get and validate user
		$voter = $voterProvider->byHash($hash);

		if(is_null($voter))
		{
			// TODO: Error -> Not yet registered or expired
			return 0;
		}

		$canVote = !$voter->getVoted();

		if(!$canVote)
		{
			// TODO: Error -> Already voted
			return 0;
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
		$candidates = $candidateProvider->forConstituency($election, $constituency);

		//  Second vote
		/** @var Party[] $parties */
		$parties = $partyProvider->getAllForElection($election);


		return $this->render('BtwAppBundle:Elector:ballot.html.twig');
	}


	public function submitAction(Request $request)
	{
		return $this->render('BtwAppBundle:Elector:submit.html.twig');
	}
} 