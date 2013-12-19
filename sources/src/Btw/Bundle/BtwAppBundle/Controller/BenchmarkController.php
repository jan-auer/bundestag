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
		$constituencyProvider = $this->get("btw_constituency_provider");
		$constituency = $constituencyProvider->byId($constituencyId);

		if(is_null($constituency)) {
			return new Response(json_encode(null));
		}
		$benchmarkProvider = $this->get("btw_benchmark_provider");
		$turnout = $benchmarkProvider->executeQuery31($constituencyId);
		$winner = $benchmarkProvider->executeQuery32($constituencyId);
		$results = $benchmarkProvider->executeQuery33($constituencyId);
		$resultsHistory = $benchmarkProvider->executeQuery34($constituencyId);

		$result = array(
			'constituency' => array(
				'name' => $constituency->getName(),
				'number' => $constituency->getNumber(),
				'state' => array(
					'number' => $constituency->getState()->getNumber(),
					'name' => $constituency->getState()->getName()
				)
			),
			//Q3.1
			'turnout' => $turnout['turnout'],
			'voters' => $turnout['voters'],
			'electives' => $turnout['electives'],
			'winner' => array(
				'name' => $winner['name'],
				'party' => array(
					'name'=>$winner['partyname'],
					'abbreviation' => $winner['partyabbreviation']
				)
			),
			'results'=>$results,
			'resultsHistory'=>$resultsHistory

		);
		return new Response(json_encode($result));
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