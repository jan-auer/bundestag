<?php


namespace Btw\Bundle\BtwAppBundle\Controller;

use Btw\Bundle\BtwAppBundle\Form\Type\LocationLoginFormType;
use Btw\Bundle\BtwAppBundle\Form\Type\LocationRegisterFormType;
use Btw\Bundle\BtwAppBundle\Services\ConstituencyProvider;
use Btw\Bundle\BtwAppBundle\Services\VoterProvider;
use Btw\Bundle\PersistenceBundle\Entity\Voter;
use PDOException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LocationController extends Controller
{

	public function indexAction()
	{
		$electionProvider = $this->get('btw_election_provider');
		$latestElection = $electionProvider->getLatest();

		$form = $this->createForm(new LocationLoginFormType(), array('election' => $latestElection));

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
	public function createVoterAction(Request $request, $constituencyId)
	{
		// Inject Services
		/** @var VoterProvider $voterProvider */
		$voterProvider = $this->get('btw_voter_provider');
		/** @var ConstituencyProvider $constituencyProvider */
		$constituencyProvider = $this->get('btw_constituency_provider');

		$createdVoter = new Voter();
		$form = $this->createForm(new LocationRegisterFormType(), $createdVoter);

		$form->handleRequest($request);


		$constituencyId = $request->get('constituencyId');
		$constituency = $constituencyProvider->byId($constituencyId);

		if ($form->isValid()) {
			$identityNumber = $createdVoter->getIdentityNumber();
			// Insert
			$hash = $voterProvider->createVoter($identityNumber, $constituency);

			var_dump($hash);
			if ($hash) {
				$this->get('session')->set('hash', $hash);
				$this->get('session')->set('constituencyId', $constituencyId);
				return $this->redirect($this->generateUrl('btw_app_location_voter_hash'));
			} else {
				$this->get('session')->getFlashBag()->add(
					'error',
					'Der Wähler konnte nicht angelegt werden.'
				);
			}
		}


		return $this->render('BtwAppBundle:Location:createVoter.html.twig', array(
			'constituency' => $constituency,
			'form' => $form->createView()
		));
	}

	public function voterHashAction()
	{
		$hash = $this->get('session')->get('hash');
		return $this->render('BtwAppBundle:Location:voterHash.html.twig', array(
			'hash' => $hash
		));
	}

}
