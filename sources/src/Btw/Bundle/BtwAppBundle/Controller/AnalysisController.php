<?php

namespace Btw\Bundle\BtwAppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AnalysisController extends Controller
{
    public function indexAction()
    {
        return $this->render('BtwAppBundle:Analysis:index.html.twig', array());
    }
}
