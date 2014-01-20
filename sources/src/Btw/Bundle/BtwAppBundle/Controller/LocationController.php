<?php

namespace Btw\Bundle\BtwAppBundle\Controller;

use Btw\Bundle\BtwAppBundle\Form\Type\LocationLoginFormType;
use Btw\Bundle\BtwAppBundle\Form\Type\LocationRegisterFormType;
use Btw\Bundle\BtwAppBundle\Services\ConstituencyProvider;
use Btw\Bundle\BtwAppBundle\Services\ElectionProvider;
use Btw\Bundle\BtwAppBundle\Services\VoterProvider;
use Btw\Bundle\PersistenceBundle\Entity\Voter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class LocationController extends Controller
{

	/** @var  ElectionProvider */
	private $electionProvider;
	/** @var  VoterProvider */
	private $voterProvider;
	/** @var  ConstituencyProvider */
	private $constituencyProvider;
	/** @var  Session */
	private $session;

	/**
	 * Displays a form to perform login. Once login is complete, the user is
	 * redirected to the createVoterAction.
	 *
	 * @return Response
	 */
	public function indexAction()
	{
		$latestElection = $this->getElectionProvider()->getLatest();
		$form = $this->createForm(new LocationLoginFormType(), array('election' => $latestElection));

		return $this->render('BtwAppBundle:Location:index.html.twig', array(
			'form' => $form->createView(),
		));
	}

	/**
	 * Displays a form to register a new voter. If the voter was created successfully,
	 * the user is redirected to the voterHashAction.
	 *
	 * @param Request $request        An object containing request data.
	 * @param int     $constituencyId The id of this constituency.
	 *
	 * @return Response
	 */
	public function createVoterAction(Request $request, $constituencyId)
	{
		$constituency = $this->getConstituencyProvider()->byId($constituencyId);

		$createdVoter = new Voter();
		$form = $this->createForm(new LocationRegisterFormType(), $createdVoter);
		$form->handleRequest($request);

		if ($form->isValid()) {
			$identityNumber = $createdVoter->getIdentityNumber();
			$hash = $this->getVoterProvider()->createVoter($identityNumber, $constituency);

			if ($hash) {
				$this->getSession()->set('hash', $hash);
				$this->getSession()->set('constituencyId', $constituencyId);
				return $this->redirect($this->generateUrl('btw_app_location_voter_hash'));
			} else {
				$this->flashMessage('error', 'Dieser Wähler hat seinen Schlüssel bereits erhalten.');
			}
		}

		return $this->render('BtwAppBundle:Location:createVoter.html.twig', array(
			'constituency' => $constituency,
			'form'         => $form->createView()
		));
	}

	/**
	 * Displays the hash of a new voter for printing.
	 *
	 * @return Response
	 */
	public function voterHashAction()
	{
		$hash = $this->get('session')->get('hash');
		return $this->render('BtwAppBundle:Location:voterHash.html.twig', array(
			'hash' => $hash
		));
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

	//
	// Dependencies   ---------------------------------------------------------
	//

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
	 * @return VoterProvider
	 */
	private function getVoterProvider()
	{
		if ($this->voterProvider == null)
			$this->voterProvider = $this->get('btw_voter_provider');
		return $this->voterProvider;
	}

	/**
	 * @return Session
	 */
	private function getSession()
	{
		if ($this->session == null)
			$this->session = $this->get('session');
		return $this->session;
	}

}
