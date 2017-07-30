<?php

namespace DT\CatalogueBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DT\CatalogueBundle\Services\ConteType;

class ShowFileController extends Controller
{

    /**
    * @Route("/ConteType/version/{ctCode}/{numeroVersion}/", name = "ct_show_file")
    */

    public function showFileAction($ctCode, $numeroVersion){

        $conteType = $this->rechercherLeConteType($ctCode) ;
        //dump($conteType); die();
        $fileName = $this->rechercherLeFichierDeLaVersion($conteType, $numeroVersion);
        return $this->render('DTCatalogueBundle:CatalogueViews:file.html.twig', 
        ['file' => $fileName]);

    }


    public function rechercherLeConteType($ctCode) {

        $session = $this->get('session');
        $tableauDesContesType = $session->get('tableauDesContesType');

        foreach($tableauDesContesType as $conteType){
            if ($ctCode == $conteType->ctCode ) {
                return $conteType;
            } 
        }
        
        return 'not found';
    }

   public function rechercherLeFichierDeLaVersion($conteType, $numeroVersion) {

       foreach ($conteType->versions as $version ){
            if ($version->numero == $numeroVersion) {
                return $version->fichierSource;
            }
       }
       return 'not found';

   }





}