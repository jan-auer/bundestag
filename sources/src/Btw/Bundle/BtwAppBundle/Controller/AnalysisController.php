<?php

namespace Btw\Bundle\BtwAppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AnalysisController extends Controller
{
	public function indexAction()
	{
		$electionProvider = $this->get('btw_election_provider');
		$years = $electionProvider->getElections();
		$latest = max($years);

		$latestResults = $electionProvider->getResultsFor($latest);
		usort($latestResults, function($result1, $result2)
		{
			if($result1['y'] == $result2['y']) return 0;
			if($result1['y'] < $result2['y']) return 1;
			return -1;
		});

		return $this->render('BtwAppBundle:Analysis:index.html.twig', array('year' => $latest,
			'all_years' => $years,
			'population' => $latestResults));
	}
}
