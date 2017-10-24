<?php

namespace DT\CatalogueBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DT\CatalogueBundle\Services\ConteType;

class ShowFileController extends Controller
{

    /**
    * @Route("/ConteType/fichiers/{ctCode}/{numeroVersion}/", name = "ct_show_file")
    */

    public function showFileAction($ctCode, $numeroVersion){

        $conteType = $this->rechercherLeConteType($ctCode) ;
        dump($conteType);
        $path = $conteType->pathDesSources;
        $fileName = $this->rechercherLeFichierDeLaVersion($conteType, $numeroVersion);
        $file = $path.$fileName;
        echo '|',$fileName,'|',$file,'|';

        return $this->render('DTCatalogueBundle:CatalogueViews:file.html.twig',
        ['file' => $file]);

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
