<?php

namespace PHB\NarratologieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class NarratologieController extends Controller
{

  /**
  * @Route("/Narratologie", name="narratologie")
  */
    public function indexAction()
    {
        return $this->render('PHBNarratologieBundle:NarratologieViews:narratologieintroduction.html.twig');
    }
}
