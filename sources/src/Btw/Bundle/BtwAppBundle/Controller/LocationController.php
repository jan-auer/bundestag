<?php


namespace Btw\Bundle\BtwAppBundle\Controller;

use Btw\Bundle\BtwAppBundle\Form\Type\LocationLoginFormType;
use Btw\Bundle\BtwAppBundle\Form\Type\LocationRegisterFormType;
use Btw\Bundle\BtwAppBundle\Services\ConstituencyProvider;
use Btw\Bundle\BtwAppBundle\Services\VoterProvider;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LocationController extends Controller
{

	public function indexAction()
	{
		$form = $this->createForm(new LocationLoginFormType());

		return $this->render('BtwAppBundle:Location:index.html.twig', array(
			'form' => $form->createView(),
		));
	}

	/**
	 * Expects POST body with variables identityNumber, constituencyId
	 *
	 * @param Request $request
	 *
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

		$form = $this->createForm(new LocationRegisterFormType());
		return $this->render('BtwAppBundle:Location:createVoter.html.twig', array(
			'form' => $form->createView(),
		));
	}

}
