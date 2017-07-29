<?php
//namespace DT\CatalogueBundle\Services;
namespace DT\CatalogueBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DT\CatalogueBundle\Services\ConteType;

class DefaultController extends Controller
{
    /**
     * @Route("/Catalogue", name="catalogue")
     */
    public function indexAction()
    {
        $session = $this->get('session');
    /*Ouvre le fichier et retourne un tableau contenant une ligne par élément*/
        //$lines = file('C:\Catalogue_sous_Symfony\DTCatalogue\src\DT\DTData\DT_Titres_Contes_Types.txt');*On parcourt le tableau $lines et on affiche le contenu de chaque ligne précédée de son numéro*/
    
    //$lines = file('..\src\DT\DTData\DT_Titres_Contes_Types.txt');
        $lines = file('..\src\DT\DTData\DT_Titres_Contes_Types.txt');
        $tableauDesContesType = [];

     foreach ($lines as $lineNumber => $lineContent)
        { 
            $conteType = new ConteType();
            $conteType->isDefined = false;
            $conteType->ct = (explode ("-",$lines[$lineNumber],2)[0]);
            $conteType->ct = rtrim($conteType->ct);
            $conteType->titre = (explode ("-",$lines[$lineNumber],2)[1]);
            $conteType->fichierDesElementsDuConte = '..\src\DT\DTData\A'.$conteType->ct.'\DT_A'.$conteType->ct.'_EDC.txt';
            $conteType->fichierDesVersions = '..\src\DT\DTData\A'.$conteType->ct.'\DT_A'.$conteType->ct.'_Liste_des_Versions.txt';
            $conteType->fichierDesSources =  '..\src\DT\DTData\A'.$conteType->ct.'\DT_A'.$conteType->ct.'_Fichier_des_Versions.txt';
            $tableauDesContesType[] = $conteType;
    
        }
            $session->set('tableauDesContesType', $tableauDesContesType );

            dump($tableauDesContesType);
            return $this->render('DTCatalogueBundle:Default:index.html.twig', [
                'contes_type'=> $tableauDesContesType
        ]);
    
    }

    /**
     * @Route("/ConteType/edc/{ct}", name = "ct_show_edc")
     */
    public function showEdcAction($ct)
    {
        $session = $this->get('session');
        $tableauDesContesType = $session->get('tableauDesContesType');

        foreach($tableauDesContesType as $ctype){
            if ($ct == $ctype->ct ) {
                $conteType = $ctype;
                break;
            } 
        };

        if (!$conteType->isDefined) {
            $conteType->genererLesInformationsDuConteType($session);
        };
    
        /*
        $lines = file($fichierDesElementsDuConte);
        $edc = [];
        foreach ($lines as $lineNumber => $lineContent){ 
             $edc [] = $lines[$lineNumber];
        }*/

        return $this->render('DTCatalogueBundle:Default:edc.html.twig',
        [
            'edc'=> $conteType->elementsDuConte, 
        ]);
   
    }

    /**
     * @Route("/ConteType/versions/{ct}", name = "ct_show_versions")
     */
    public function showVersionsAction($ct)
    {
        $fileName = '..\src\DT\DTData\A'.$ct.'\DT_A'.$ct.'_Liste_des_Versions.txt';
        $lines = file($fileName);
        $versions = [];
        foreach ($lines as $lineNumber => $lineContent){ 
             $versions [] = $lines[$lineNumber];
        }
        
        return $this->render('DTCatalogueBundle:Default:versions.html.twig',
        [
            'versions' => $versions, 
        ]);
    }
}