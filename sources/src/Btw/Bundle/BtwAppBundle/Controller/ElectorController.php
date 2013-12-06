<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 06.12.13
 * Time: 11:57
 */

namespace Btw\Bundle\BtwAppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ElectorController extends Controller {

	public function indexAction($year)
	{
		return $this->render('BtwAppBundle:Elector:index.html.twig', array('year' => $year));
	}

	/**
	 * @Route("/login", name="_btw_login")
	 * @Template()
	 */
	public function loginAction()
	{
		// TODO: Implement login
	}
} 