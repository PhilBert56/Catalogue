<?php

namespace DT\CatalogueBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/Catalogue", name="catalogue")
     */
    public function indexAction()
    {
        return $this->render('DTCatalogueBundle:Default:index.html.twig');
    }
}
