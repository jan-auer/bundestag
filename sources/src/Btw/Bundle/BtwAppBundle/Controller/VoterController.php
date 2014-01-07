<?php

namespace Btw\Bundle\BtwAppBundle\Controller;

use Btw\Bundle\BtwAppBundle\Form\Type\ElectorLoginFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class VoterController extends Controller
{

	public function indexAction()
	{
		$year = date('Y');
		$form = $this->createForm(new ElectorLoginFormType());

		return $this->render('BtwAppBundle:Elector:index.html.twig', array(
			'form' => $form->createView(),
			'year' => $year,
		));
	}

	public function ballotAction(Request $request)
	{
		return $this->render('BtwAppBundle:Elector:index.html.twig');
	}

	public function submitAction(Request $request)
	{

	}
}
