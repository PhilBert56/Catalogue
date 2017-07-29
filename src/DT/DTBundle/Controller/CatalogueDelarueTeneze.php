<?php

namespace DT\DTBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CatalogueController extends Controller{ 

   /**
     * @Route("/Catalogue", name="catalogue")
     */

    public function newAction()
    {
        return $this->render('DTBundle:catalogue.html.twig');
    }
}