<?php
//namespace DT\CatalogueBundle\Services;
namespace DT\CatalogueBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DT\CatalogueBundle\Services\ConteType;

class CatalogueController extends Controller
{
    /**
     * @Route("/Catalogue-Delarue-Teneze", name="catalogue")
     */
    public function indexAction()
    {
        $session = $this->get('session');
        $tableauDesContesType = $this->creerLeTableauDesContesTypes();
        $session->set('tableauDesContesType', $tableauDesContesType );

        return $this->render('DTCatalogueBundle:CatalogueViews:index.html.twig', [
                'contes_type'=> $tableauDesContesType
        ]);
    }


    public function creerLeTableauDesContesTypes(){


        $lines = file('..\src\DT\DTData\DT_Titres_Contes_Types.txt');
        $tableauDesContesType = [];

        foreach ($lines as $lineNumber => $lineContent)
        {
            $tableauDesContesType[] = new ConteType($lines[$lineNumber]);
        }
        return $tableauDesContesType;

    }



    /**
     * @Route("/ConteType/edc/{ctCode}", name = "ct_show_edc")
     */
    public function showEdcAction($ctCode) {

        $extracteurDeCodes = $this->container->get('dt_catalogue.listeLesCodesContenusDansLaLigne');

        $conteType = $this->rechercherLeConteType($ctCode);

        if (!$conteType->isDefined) {
            $conteType->genererLesInformationsDuConteType($extracteurDeCodes);
        };

        return $this->render('DTCatalogueBundle:CatalogueViews:edc.html.twig',
        [
            'edc'=> $conteType->elementsDuConte,
            'conteTypeCode' => $conteType->ctCode
        ]);

    }

    /**
     * @Route("/ConteType/versions/{ctCode}", name = "ct_show_versions")
     */
    public function showVersionsAction($ctCode)
    {

        $extracteurDeCodes = $this->container->get('dt_catalogue.listeLesCodesContenusDansLaLigne');

        $conteType = $this->rechercherLeConteType($ctCode);

        if (!$conteType->isDefined) {
            $conteType->genererLesInformationsDuConteType($extracteurDeCodes);
        };

        return $this->render('DTCatalogueBundle:CatalogueViews:versions.html.twig',
        [
            'versions' => $conteType->versions,
            'conteTypeCode' => $conteType->ctCode
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
     * @Route("/ConteType/motifs-du-Motif-Index/{ctCode}", name = "ct_show_motifs")
     */
    public function showMotifsAction($ctCode) {

      $session = $this->get('session');
      $tableauDesContesType = $session->get('tableauDesContesType');


      $conteType = $this->rechercherLeConteType($ctCode);
      $extracteurDeCodes = $this->container->get('dt_catalogue.listeLesCodesContenusDansLaLigne');

      if (!$conteType->isDefined) {
            $conteType->genererLesInformationsDuConteType($extracteurDeCodes);
      };


      $extracteurDeContestypes = $this->container->get('dt_catalogue.listeLesContesTypesContenusDansLaLigne');
      //dump($conteType);
      $motifs = $conteType->motifsDuConte;
      //dump ($motifs);


      for ($i=0; $i < count($motifs); $i++) {

        $motif = $motifs[$i];
        $ligne = $motif->motifDescription;
        if (strpos($ligne, 'href') == FALSE){
          $ligne = $this->etablirLienPourBaseIndex($ligne);
          $ligne = $extracteurDeContestypes->listeLesContesTypesContenusDansLaLigne($ligne,$tableauDesContesType);
          $motif->motifDescription = $ligne;
        }
      }


        //dump ($conteType->motifsDuConte);
        return $this->render('DTCatalogueBundle:CatalogueViews:motifs.html.twig',
        [
            'motifs'=> $conteType->motifsDuConte,
            'conteTypeCode' => $conteType->ctCode
        ]);

    }

    /**
     * @Route("/ConteType/complements/{ctCode}", name = "ct_show_complements")
     */
    public function showComplementsAction($ctCode) {

        $conteType = $this->rechercherLeConteType($ctCode);
        /*
        if (!$conteType->isDefined) {
            $conteType->genererLesInformationsDuConteType();
        };
*/

// echo 'BONUS FILE = ', $conteType->bonusFile, '</br>';
        //$fileName = "DT\CatalogueBundle\DTData\A".$conteType->ctCode.'\'.$conteType->bonusFile;

        $fileName ='DT/CatalogueBundle/DTData/AT300/AT300.htm';
        return $this->render('DTCatalogueBundle:CatalogueViews:complements.html.twig',
        [
            'fichierHtml'=> $fileName,
            'conteTypeCode' => $conteType->ctCode
        ]);

    }

    public function etablirLienPourBaseIndex($ligne) {
      $mots = explode(' ',$ligne);

      if ( preg_match('#[A-z][a-z]#', $mots[0])  ){
        return $ligne;
      };

      $link = '<a href="/Code-motif-Index/'.$mots[0].'/">';
      $link = $link.$mots[0].'</a>';
      $ligne = str_replace($mots[0], $link, $ligne);
      return $ligne;
    }

}
