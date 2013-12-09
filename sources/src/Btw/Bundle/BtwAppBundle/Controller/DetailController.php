<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 06.12.13
 * Time: 21:47
 */

namespace Btw\Bundle\BtwAppBundle\Controller;


use Btw\Bundle\BtwAppBundle\Form\ElectionAnalysisForm;
use Btw\Bundle\BtwAppBundle\Model\ElectionAnalysisModel;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DetailController extends Controller
{
	public function indexAction($year)
	{
		$stateProvider = $this->get("btw_state_provider");
		$electionAnalysis = new ElectionAnalysisModel();
		$form = $this->createForm($this->get("btw_election_analysis_form_builder"), $electionAnalysis, array(
			'year' => $year,
			'states' => $stateProvider->getStatesFor($year)));

		$form->handleRequest($this->getRequest());

		if ($form->isValid()) {
			// ... maybe do some form processing, like saving the Task and Tag objects
		}

		return $this->render('BtwAppBundle:Analysis:details.html.twig',
			array(
				'year' => $year,
				'form' => $form->createView()
			));
	}


	public function listConstituenciesAction($stateId)
	{
		$stateProvider = $this->get("btw_state_provider");
		$state = $stateProvider->getStateById($stateId);
		$constituencies = array();
		foreach($state->getConstituencies() as $constituency) {
			$constituencies[$constituency->getId()] = $constituency->getName();
		}
		return new Response(json_encode($constituencies));
	}
} 