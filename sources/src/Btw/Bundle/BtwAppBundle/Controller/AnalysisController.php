<?php

namespace Btw\Bundle\BtwAppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AnalysisController extends Controller
{
	public function indexAction()
	{
		$electionProvider = $this->get('btw_election_provider');
		$countryProvider = $this->get('btw_country_provider');

		/** ALL ELECTION YRS */
		$elections = $electionProvider->getElections();
		$years = array();
		foreach ($elections as $election) {
			$years[] = date('Y', $election->getDate()->getTimestamp());
		}

		/** LATEST ELECTION YEAR */
		$latestElectionYear = max($years);

		/** LATEST ELECTION RESULTS */
		$latestElection = $electionProvider->getElectionFor($latestElectionYear);
		$latestResults = $countryProvider->getResultsFor($latestElection);
		usort($latestResults, function($result1, $result2)
		{
			if($result1['y'] == $result2['y']) return 0;
			if($result1['y'] < $result2['y']) return 1;
			return -1;
		});

		return $this->render('BtwAppBundle:Analysis:index.html.twig', array('year' => $latestElectionYear,
			'all_years' => $years,
			'population' => $latestResults));
	}
}
