<?php

namespace PHB\BaseContesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('PHBBaseContesBundle:Default:index.html.twig');
    }
}
