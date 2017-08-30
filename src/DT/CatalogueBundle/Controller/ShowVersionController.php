<?php

namespace DT\CatalogueBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DT\CatalogueBundle\Services\ConteType;

class ShowVersionController extends Controller
{

    /**
    * @Route("/ConteType/version/{ctCode}/{numeroVersion}/", name = "ct_show_une_version")
    */

    public function showVersionAction($ctCode, $numeroVersion){
//echo 'Recherche le conte type ',$ctCode,' et la version ',$numeroVersion,'</br>';

        $conteType = $this->rechercherLeConteType($ctCode);
        $versionAMontrer ='';
        $developpements =[];
        foreach ($conteType->versions as $v) {

            if ($v->numero == $numeroVersion){
                $versionAMontrer = $v;

                //dump ($conteType);dump($v);
                $developpements = $versionAMontrer->developperUneVersion($conteType);
                //dump($developpements);
                //foreach($developpements as $ligne) echo $ligne.'</br>' ;
                break;
            }

        };

        return $this->render('DTCatalogueBundle:CatalogueViews:version.html.twig',
        [
            'version'=> $versionAMontrer ,
            'developpements'=> $developpements
        ]);


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


    /**
    * @Route("/ConteType/elementDuConte/{ctCode}/{section}/{edcCode}/", name = "ct_show_un_edc")
    */

    public function showElementDuConteAction($ctCode, $section, $edcCode){

        $conteType = $this->rechercherLeConteType($ctCode);
        //dump ($conteType);

        foreach ($conteType->elementsDuConte as $edc) {

            if ($edc->section == $section && $edc->codeElementDuConte == $edcCode){
                //dump($edc->description, $edc->listeDesVersions);
                break;
            }
        };

        return $this->render('DTCatalogueBundle:CatalogueViews:edcUnique.html.twig',
        [
            'element'=> $edc ,
            'conteTypeCode' =>$ctCode
        ]);


    }

}
