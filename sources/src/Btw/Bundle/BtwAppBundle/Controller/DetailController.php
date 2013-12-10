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
		return $this->render('BtwAppBundle:Analysis:details.html.twig',
			array(
				'year' => $year
			));

	}

	public function listStatesAction($year)
	{
		$stateProvider = $this->get("btw_state_provider");

		$states = array();
		foreach ($stateProvider->getStatesFor($year) as $state) {
			$states[] = array(
				'id' => $state->getId(),
				'name' => $state->getName()
			);
		}

		return new Response(json_encode($states));

	}

	public function listConstituenciesAction($stateId)
	{
		$stateProvider = $this->get("btw_state_provider");
		$state = $stateProvider->getStateById($stateId);
		$constituencies = array();
		foreach ($state->getConstituencies() as $constituency) {
			$constituencies[] = array(
				'id' => $constituency->getId(),
				'name' => $constituency->getName()
			);
		}
		return new Response(json_encode($constituencies));
	}
} 