<?php

namespace Btw\Bundle\BtwAppBundle\Controller;

use Btw\Bundle\BtwAppBundle\Services\ElectionProvider;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LayoutController extends Controller
{

	public function electionsNavbarAction()
	{
		/** @var ElectionProvider $provider */
		$provider = $this->get('btw_election_provider');
		$years = $provider->getAllYears();
		return $this->render('BtwAppBundle:Layout:electionsnavbar.html.twig', array('years' => $years));
	}

}
