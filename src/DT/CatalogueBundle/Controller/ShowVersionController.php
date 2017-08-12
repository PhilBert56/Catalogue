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

        $conteType = $this->rechercherLeConteType($ctCode);
        $versionAMontrer ='';
        foreach ($conteType->versions as $v) {
            
            if ($v->numero == $numeroVersion){
                $versionAMontrer = $v;
                
                break;
            }
        };

        return $this->render('DTCatalogueBundle:CatalogueViews:version.html.twig',
        [
            'version'=> $versionAMontrer 
            
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

        echo 'conte type = ',$ctCode,' section = ',$section,' edc = ', $edcCode;

        $conteType = $this->rechercherLeConteType($ctCode);
        dump ($conteType);

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