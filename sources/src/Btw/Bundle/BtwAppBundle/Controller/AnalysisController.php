<?php

namespace Btw\Bundle\BtwAppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AnalysisController extends Controller
{
	public function indexAction()
	{
		$dataService = $this->get('btw_analysis.overview');
		return $this->render('BtwAppBundle:Analysis:index.html.twig', array('year' => $dataService->getLatestElectionYear(),
			'all_years' => $dataService->getAllElectionYears(),
			'latest_result' => $dataService->getLatestElectionResults()));
	}
}
