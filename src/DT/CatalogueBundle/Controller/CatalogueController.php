<?php
//namespace DT\CatalogueBundle\Services;
namespace DT\CatalogueBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DT\CatalogueBundle\Services\ConteType;

class CatalogueController extends Controller
{
    /**
     * @Route("/Catalogue", name="catalogue")
     */
    public function indexAction()
    {
        $session = $this->get('session');
        $tableauDesContesType = $this->creerLeTableauDesContesTypes();
        $session->set('tableauDesContesType', $tableauDesContesType );
        dump($tableauDesContesType);
        return $this->render('DTCatalogueBundle:CatalogueViews:index.html.twig', [
                'contes_type'=> $tableauDesContesType
        ]);
    }

    public function creerLeTableauDesContesTypes(){ 

        
        $lines = file('..\src\DT\DTData\DT_Titres_Contes_Types.txt');
        $tableauDesContesType = [];

        foreach ($lines as $lineNumber => $lineContent)
        { 
            $newConteType = $this->creerUnConteType($lines[$lineNumber]);
            //dump($newConteType);

            $tableauDesContesType[] = $newConteType; 
        }
        return $tableauDesContesType;
        
    }

    public function creerUnConteType($description) {
        $conteType = new ConteType();
        $conteType->isDefined = false;
        $conteType->ctCode = (explode ("-",$description,2)[0]);
        $conteType->ctCode = rtrim($conteType->ctCode);
        $conteType->titre = (explode ("-",$description,2)[1]);
        $conteType->fichierDesElementsDuConte = '..\src\DT\DTData\A'.$conteType->ctCode.'\DT_A'.$conteType->ctCode.'_EDC.txt';
        $conteType->fichierDesVersions = '..\src\DT\DTData\A'.$conteType->ctCode.'\DT_A'.$conteType->ctCode.'_Liste_des_Versions.txt';
        $conteType->fichierDesSources =  '..\src\DT\DTData\A'.$conteType->ctCode.'\DT_A'.$conteType->ctCode.'_Fichier_des_Versions.txt';
        return $conteType;
    }

    /**
     * @Route("/ConteType/edc/{ctCode}", name = "ct_show_edc")
     */
    public function showEdcAction($ctCode) {
    
        $conteType = $this->rechercherLeConteType($ctCode);
        //$session = $this->get('session');
        if (!$conteType->isDefined) {
            $conteType->genererLesInformationsDuConteType();
        };

        return $this->render('DTCatalogueBundle:CatalogueViews:edc.html.twig',
        [
            'edc'=> $conteType->elementsDuConte, 
        ]);
   
    }

    /**
     * @Route("/ConteType/versions/{ctCode}", name = "ct_show_versions")
     */
    public function showVersionsAction($ctCode)
    {
        $conteType = $this->rechercherLeConteType($ctCode);
        if (!$conteType->isDefined) {
            $conteType->genererLesInformationsDuConteType();
        };

        return $this->render('DTCatalogueBundle:CatalogueViews:versions.html.twig',
        [
            'versions' => $conteType->versions, 
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