<?php

namespace PHB\BaseContesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use PHB\BaseContesBundle\Entity\ReferenceConte;
use PHB\BaseContesBundle\Entity\ReferenceOuvrage;

class BaseContesController extends Controller
{

  /**
   * @Route("/Consulter la base", name="consulterBaseContes")
   */
    public function indexAction()
    {
        return $this->render('PHBBaseContesBundle:Default:index.html.twig');
    }
}
