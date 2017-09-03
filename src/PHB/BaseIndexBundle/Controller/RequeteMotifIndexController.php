<?php

namespace PHB\BaseIndexBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use PHB\BaseIndexBundle\Requetes\CodeMotifRequest;
use PHB\BaseIndexBundle\Requetes\BiblioMotifIndexRequest;
use PHB\BaseIndexBundle\Entity\MotifDuMotifIndex;

class RequeteMotifIndexController extends Controller
{


  /**
  * @Route("/Code-motif-Index/{codeMotif}/", name = "requeteCodeMotif")
  */
  public function requestMotifAction($codeMotif)
  {

    $repoConte = $this->getDoctrine()->getRepository(MotifDuMotifIndex::class);
    $codeRequest = new CodeMotifRequest($codeMotif);
    $references = $codeRequest->getReferences($repoConte , $codeRequest);

    $session = $this->get('session');
    $tableauDesContesType = $session->get('tableauDesContesType');
    $extracteurDeContestypes = $this->container->get('dt_catalogue.listeLesContesTypesContenusDansLaLigne');

    foreach ($references as $ligne ) {


      $bib1 = $ligne->getBibliographie1();
      $bib1 = $extracteurDeContestypes->listeLesContesTypesContenusDansLaLigne($bib1,$tableauDesContesType) ;
      $ligne->setBibliographie1($bib1);
      $bib2 = $ligne->getBibliographie2();
      $bib2 = $extracteurDeContestypes->listeLesContesTypesContenusDansLaLigne($bib2,$tableauDesContesType) ;
      $ligne->setBibliographie2($bib2);

    }

    return $this->render('PHBBaseIndexBundle:Requetes:referencesview.html.twig' ,[
        'references' => $references,
        'query'=>$codeMotif
    ]);
  }


}
