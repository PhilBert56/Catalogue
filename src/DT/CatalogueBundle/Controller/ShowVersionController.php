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
}