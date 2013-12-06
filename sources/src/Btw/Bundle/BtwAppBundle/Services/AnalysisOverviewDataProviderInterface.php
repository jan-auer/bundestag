<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 06.12.13
 * Time: 17:37
 */

namespace Btw\Bundle\BtwAppBundle\Services;


interface AnalysisOverviewDataProviderInterface
{

	public function getLatestElectionYear();

	public function getAllElectionYears();

	public function getLatestElectionResults();
} 