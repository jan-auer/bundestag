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


	public function q2Action()
	{
		return new Response(json_encode(null));
	}


	public function q3Action()
	{
		return new Response(json_encode(null));
	}


	public function q4Action()
	{
		return new Response(json_encode(null));
	}


	public function q5Action()
	{
		return new Response(json_encode(null));
	}

	public function q6Action()
	{
		return new Response(json_encode(null));
	}

}