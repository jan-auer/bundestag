<?php
/**
 * Created by PhpStorm.
 * User: manuel
 * Date: 18/12/13
 * Time: 18:15
 */

namespace Btw\Bundle\BtwAppBundle\Controller;


use Btw\Bundle\BtwAppBundle\Services\BenchmarkProvider;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class BenchmarkController extends Controller
{

	public function q1Action($year)
	{
		$benchmarkProvider = $this->get("btw_benchmark_provider");
		$result = $benchmarkProvider->executeQuery1($year);
		return new Response(json_encode($result));
	}


	public function q2Action($year)
	{
		$benchmarkProvider = $this->get("btw_benchmark_provider");
		$result = $benchmarkProvider->executeQuery2($year);
		return new Response(json_encode($result));
	}


	public function q3Action($constituencyId)
	{
		$benchmarkProvider = $this->get("btw_benchmark_provider");
		$turnoutWinner = $benchmarkProvider->executeQuery31_2($constituencyId);
		$resultsHistory = $benchmarkProvider->executeQuery33_4($constituencyId);

		$result = array();
		if(is_array($turnoutWinner)) {
			$result = array(
				'constituency' => array(
					'name' => $turnoutWinner['constituencyname'],
					'number' => $turnoutWinner['constituencynumber'],
				),
				'turnout' => $turnoutWinner['turnout'],
				'voters' => $turnoutWinner['voters'],
				'electives' => $turnoutWinner['electives'],
				'winner' => array(
					'name' => $turnoutWinner['constituencywinner'],
					'party' => array(
						'name' => $turnoutWinner['winnerpartyname'],
						'abbreviation' => $turnoutWinner['winnerpartyabbreviation']
					)
				)
			);

		}

		if(is_array($resultsHistory)) {
			$result['results']=$resultsHistory;
		}

		if(count($result)==0) {
			return new Response(json_encode(null));
		} else {
			return new Response(json_encode($result));
		}
	}


	public function q4Action($year)
	{
		$benchmarkProvider = $this->get("btw_benchmark_provider");
		$result = $benchmarkProvider->executeQuery4($year);
		return new Response(json_encode($result));
	}


	public function q5Action($year)
	{
		$benchmarkProvider = $this->get("btw_benchmark_provider");
		$result = $benchmarkProvider->executeQuery5($year);
		return new Response(json_encode($result));
	}

	public function q6Action($year)
	{
		$benchmarkProvider = $this->get("btw_benchmark_provider");
		$result = $benchmarkProvider->executeQuery6($year);
		return new Response(json_encode($result));
	}


}