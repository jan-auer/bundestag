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
			if($result1[1] == $result2[1]) return 0;
			if($result1[1] < $result2[1]) return 1;
			return -1;
		});

		$population = array();
		foreach($latestResults as $result)
		{
			$population[] = array('name' => $result[0], 'y' => $result[1], 'color' => $result[3]);
		}

		return $this->render('BtwAppBundle:Analysis:index.html.twig', array('year' => $latest,
			'all_years' => $years,
			'population' => $population));
	}
}
