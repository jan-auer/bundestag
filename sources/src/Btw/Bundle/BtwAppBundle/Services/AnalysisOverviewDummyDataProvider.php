<?php

namespace Btw\Bundle\BtwAppBundle\Services;

/**
 * Class AnalysisOverviewDummyDataProvider
 * @package Btw\Bundle\BtwAppBundle\Services
 *
 * Dummy implementation for prototyping purposes
 */
class AnalysisOverviewDummyDataProvider implements AnalysisOverviewDataProviderInterface
{

	public function getLatestElectionYear()
	{
		return 2013;
	}

	public function getAllElectionYears()
	{
		return array(2005, 2009, 2013);
	}

	public function getLatestElectionResults()
	{
		return array(array('SPD', 'Sozi Partei', '#e20019', 32.8, 288),
			array('CDU', 'Christliche demo', '#333333', 39.2, 402));
	}
}