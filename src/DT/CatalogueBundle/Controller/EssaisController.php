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


      $ligne = "IV : Axx, Bxx, C5xx (grâce à onguent qu'a la bête; le héros le lui prend), E, G.";
      $ligne = "III : B6 (qui chauffe sur le feu, dans une écuelle), B7 (oiseau en cage), B10 (« Petit Chaperon rouge, tu bois le sang de ta grand-mère ! »), C4, C5 (le loup dit de mettre vêtements sur une chaise), D5 (peau rude).";
      $ligne = "IV : A, B, B4, D (1er  jour, avec cheval, habit 3 chiens, un sabre, tous noirs; 2e jour, rouges ; 3e jour blancs), E,E1,E3 (colliers faits aux chiens du 1er jour avec collier d’or de la princesse), G2.";

      echo strlen('â').'</br>';

    $chaine = 'èéééééééééé é à';


    //$ligne = utf8_encode($ligne);
    echo 'gra |'.grapheme_strlen($ligne).'|</br>';
    echo 'len |'.grapheme_strlen($ligne).'|</br>';
    $arr = str_split($ligne);
    echo 'count = '.count($arr).'</br>';
    /*
    foreach ($arr as $c){
      echo 'car = |'.$c.'| longueur = '.grapheme_strlen($c).'</br>';
      echo 'car = |'.utf8_encode($c).'| longueur = '.grapheme_strlen($c).'</br>';
    }*/
      $extracteurDeCodes = $this->container->get('dt_catalogue.listeLesCodesContenusDansLaLigne');
      echo 'Avant :'.$ligne.'</br>';
      $listeDesCodes = $extracteurDeCodes->listeLesCodesContenusDansLaLigne(1,$ligne, 'T.300',1,'I');
      if (count ($listeDesCodes) > 0){
        dump($listeDesCodes);
        $ligne = $extracteurDeCodes->effectuerLesSubstitutions($ligne,$listeDesCodes);
      }

      echo 'Après :'.$ligne.'</br>';
      //echo 'SECTION ? '.$section.'</br>';
/*
      $conteTypeCode = 'T.327';
      $extracteurDeCodes = $this->container->get('dt_catalogue.listeLesCodesContenusDansLaLigne');
      $listeDeCodes = $extracteurDeCodes->listeLesCodesContenusDansLaLigne($ligne, $conteTypeCode, 'IV');
*/

      return $this->render('DTCatalogueBundle:CatalogueViews:essais.html.twig', [
              'ligne'=> $ligne
      ]);

    }

  }
