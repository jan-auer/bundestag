<?php

namespace Btw\Bundle\BtwAppBundle\Controller;

use Btw\Bundle\BtwAppBundle\Services\BenchmarkProvider;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller to run benchmarks.
 */
class BenchmarkController extends Controller
{

	/** @var BenchmarkProvider */
	private $benchmarkProvider;

	/**
	 * Runs the query for Question 1.
	 */
	public function q1Action($year)
	{
		$result = $this->getBenchmarkProvider()->executeQuery1($year);
		return new Response(json_encode($result));
	}

	/**
	 * Runs the query for Question 2.
	 */
	public function q2Action($year)
	{
		$result = $this->getBenchmarkProvider()->executeQuery2($year);
		return new Response(json_encode($result));
	}

	/**
	 * Runs the queries for Question 3.
	 */
	public function q3Action($constituencyId)
	{
		$turnoutWinner = $this->getBenchmarkProvider()->executeQuery31_2($constituencyId);
		$resultsHistory = $this->getBenchmarkProvider()->executeQuery33_4($constituencyId);

		$result = array();
		if (is_array($turnoutWinner)) {
			$result = array(
				'constituency' => array(
					'name'   => $turnoutWinner['constituencyname'],
					'number' => $turnoutWinner['constituencynumber'],
				),
				'turnout'      => $turnoutWinner['turnout'],
				'voters'       => $turnoutWinner['voters'],
				'electives'    => $turnoutWinner['electives'],
				'winner'       => array(
					'name'  => $turnoutWinner['constituencywinner'],
					'party' => array(
						'name'         => $turnoutWinner['winnerpartyname'],
						'abbreviation' => $turnoutWinner['winnerpartyabbreviation']
					)
				)
			);
		}

		if (is_array($resultsHistory)) {
			$result['results'] = $resultsHistory;
		}

		return new Response(json_encode(count($result) == 0 ? null : $result));
	}

	/**
	 * Runs the query for Question 4.
	 */
	public function q4Action($year)
	{
		$result = $this->getBenchmarkProvider()->executeQuery4($year);
		return new Response(json_encode($result));
	}

	/**
	 * Runs the query for Question 5.
	 */
	public function q5Action($year)
	{
		$result = $this->getBenchmarkProvider()->executeQuery5($year);
		return new Response(json_encode($result));
	}

	/**
	 * Runs the query for Question 6.
	 */
	public function q6Action($year)
	{
		$result = $this->getBenchmarkProvider()->executeQuery6($year);
		return new Response(json_encode($result));
	}

	//
	// Dependencies   ---------------------------------------------------------
	//

	/**
	 * @return BenchmarkProvider
	 */
	private function getBenchmarkProvider() {
		if ($this->benchmarkProvider == null) {
			$this->benchmarkProvider = $this->get("btw_benchmark_provider");
		}
		return $this->benchmarkProvider;
	}

}
