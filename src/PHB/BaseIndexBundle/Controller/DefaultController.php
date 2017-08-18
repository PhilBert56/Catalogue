<?php

namespace PHB\BaseIndexBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('PHBBaseIndexBundle:Default:index.html.twig');
    }
}
