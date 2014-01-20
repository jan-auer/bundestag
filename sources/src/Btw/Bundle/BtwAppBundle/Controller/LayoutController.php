<?php

namespace Btw\Bundle\BtwAppBundle\Controller;

use Btw\Bundle\BtwAppBundle\Services\ElectionProvider;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Contains actions for all pages within the system.
 */
class LayoutController extends Controller
{

	/**
	 * Renders a navigation populated with a list of recent elections.
	 * @return Response
	 */
	public function electionsNavbarAction()
	{
		/** @var ElectionProvider $provider */
		$provider = $this->get('btw_election_provider');
		$years = $provider->getAllYears();
		return $this->render('BtwAppBundle:Layout:electionsnavbar.html.twig', array('years' => $years));
	}

}
