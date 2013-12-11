<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 07.12.13
 * Time: 17:37
 */

namespace Btw\Bundle\BtwAppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LayoutController extends Controller {

	public function electionsNavbarAction()
	{
		$electionProvider = $this->get('btw_election_provider');
		$elections = $electionProvider->getAll();
		$years = array();
		foreach ($elections as $election) {
			$years[] = date('Y', $election->getDate()->getTimestamp());
		}

		return $this->render('BtwAppBundle:Layout:electionsnavbar.html.twig', array('years' => $years));
	}
} 