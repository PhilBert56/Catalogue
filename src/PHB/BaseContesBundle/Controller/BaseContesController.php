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
      $repository = $this->getDoctrine()->getRepository(ReferenceConte::class);

// createQueryBuilder() automatically selects FROM AppBundle:Product
// and aliases it to "p"
      $query = $repository->createQueryBuilder('ct')
      ->where('ct.ouvrage = :ouvrage')
      ->setParameter('ouvrage', '1')
      ->orderBy('ct.id', 'ASC')
      ->getQuery();

      $products = $query->getResult();
      dump($products);
// to get just one result:
// $product = $query->setMaxResults(1)->getOneOrNullResult();








        return $this->render('PHBBaseContesBundle:Default:index.html.twig');
    }
}
