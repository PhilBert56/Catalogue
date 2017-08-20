<?php


namespace DT\CatalogueBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class EssaisController extends Controller
{

    /**
    * @Route("/Essais/", name = "essais")
    */


    public function extraireCodesAction(){

/*
      $ligne = "II : A10,A333 (les)  (aînés, A5 Jeanne et Jean), A4; |B2|B3,C3 (étoupe aux buissons),E1,D1, D3 (par la mère), D6; C5, C6, F1 (la soeur fait monter Jean), F2, G2.";
      $extracteurDeCodes = $this->container->get('dt_catalogue.listeLesCodesContenusDansLaLigne');
      $listeDeCodes = $extracteurDeCodes->listeLesCodesContenusDansLaLigne($ligne);
      dump($listeDeCodes);

      $ligne = 'bonjour les gens';
      $extracteurDeCodes = $this->container->get('dt_catalogue.listeLesCodesContenusDansLaLigne');
      $listeDeCodes = $extracteurDeCodes->listeLesCodesContenusDansLaLigne($ligne);
*/
      $ligne = "IV : A1, A3; 1° C5 (la chienne change les enfants en laveuses, elle-même se change en rivière), D1, D3; 2° C5 (champ, moutons, chien, berger), B1 (le berger), D3 (ibid.); 3° C5 (vachère et vaches), D2 (la vachère), D3 (dit qu'ils ont traversé la rivière), D5, D7, F2 (avec parents), F4.
";
      $conteTypeCode = 'T.327';
      $extracteurDeCodes = $this->container->get('dt_catalogue.listeLesCodesContenusDansLaLigne');
      $listeDeCodes = $extracteurDeCodes->listeLesCodesContenusDansLaLigne($ligne, $conteTypeCode);


      return $this->render('DTCatalogueBundle:CatalogueViews:essais.html.twig', [
              'ligne'=> $ligne
      ]);

    }

  }
