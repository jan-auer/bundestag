<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 06.12.13
 * Time: 11:57
 */

namespace Btw\Bundle\BtwAppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class VoterController extends Controller {

	public function indexAction()
	{
		return $this->render('BtwAppBundle:Elector:index.html.twig');
	}

	public function ballotAction(Request $request)
	{
		return $this->render('BtwAppBundle:Elector:index.html.twig');
	}

	public function submitAction(Request $request)
	{

	}
} 