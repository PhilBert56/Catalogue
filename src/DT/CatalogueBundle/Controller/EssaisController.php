<?php


namespace DT\CatalogueBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use PHB\BaseContesBundle\Requetes\ATRequest;
use PHB\BaseContesBundle\Entity\ReferenceConte;


class EssaisController extends Controller
{

    /**
    * @Route("/Essais/", name = "essais")
    */


    public function extraireCodesAction(Request $request){

/*
      $ligne = "II : A10,A333 (les)  (aînés, A5 Jeanne et Jean), A4; |B2|B3,C3 (étoupe aux buissons),E1,D1, D3 (par la mère), D6; C5, C6, F1 (la soeur fait monter Jean), F2, G2.";
      $extracteurDeCodes = $this->container->get('dt_catalogue.listeLesCodesContenusDansLaLigne');
      $listeDeCodes = $extracteurDeCodes->listeLesCodesContenusDansLaLigne($ligne);
      dump($listeDeCodes);

      $ligne = 'bonjour les gens';
      $extracteurDeCodes = $this->container->get('dt_catalogue.listeLesCodesContenusDansLaLigne');
      $listeDeCodes = $extracteurDeCodes->listeLesCodesContenusDansLaLigne($ligne);
*/

/*
      $ligne = "IV : Axx, Bxx, C5xx (grâce à onguent qu'a la bête; le héros le lui prend), E, G.";
      $ligne = "III : B6 (qui chauffe sur le feu, dans une écuelle), B7 (oiseau en cage), B10 (« Petit Chaperon rouge, tu bois le sang de ta grand-mère ! »), C4, C5 (le loup dit de mettre vêtements sur une chaise), D5 (peau rude).";
      $ligne = "IV : A, B, B4, D (1er  jour, avec cheval, habit 3 chiens, un sabre, tous noirs; 2e jour, rouges ; 3e jour blancs), E,E1,E3 (colliers faits aux chiens du 1er jour avec collier d’or de la princesse), G2.";

      echo strlen('â').'</br>';

    $chaine = 'èéééééééééé é à';

    echo 'gra |'.grapheme_strlen($ligne).'|</br>';
    echo 'len |'.grapheme_strlen($ligne).'|</br>';
    $arr = str_split($ligne);
    echo 'count = '.count($arr).'</br>';

      $extracteurDeCodes = $this->container->get('dt_catalogue.listeLesCodesContenusDansLaLigne');
      echo 'Avant :'.$ligne.'</br>';
      $listeDesCodes = $extracteurDeCodes->listeLesCodesContenusDansLaLigne(1,$ligne, 'T.300',1,'I');
      if (count ($listeDesCodes) > 0){
        dump($listeDesCodes);
        $ligne = $extracteurDeCodes->effectuerLesSubstitutions($ligne,$listeDesCodes);
      }

      echo 'Après :'.$ligne.'</br>';
*/
  $aTRequest = new ATRequest('% %');
  $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $aTRequest);

  $formBuilder
  ->add('autreDonnee', TextType::class)
  ->add('numeroAT', TextType::class)
  ;
  $form = $formBuilder->getForm();


  //dump ($request);

  if ($request->isMethod('POST')) {


    if(isset($_POST['submit_nat'])){
      echo ' requete type AT';

      // On fait le lien Requête <-> Formulaire
      // À partir de maintenant, la variable $advert contient les valeurs entrées dans le formulaire par le visiteur
      $form->handleRequest($request);

      echo ' Requete AT detectée :';
      dump ($form);

      if ($form->isValid()) {
          echo ' FORM valide :';
          //$form>handleRequest($request);
          $requete = $form['numeroAT']->getData();
          echo ' requete = '.$requete;

          $repoConte = $this->getDoctrine()->getRepository(ReferenceConte::class);
          $atrequest = new ATRequest($requete);
          $references = $atrequest->getReferences($repoConte , $requete);

          return $this->render('PHBBaseContesBundle:Requetes:referencesview.html.twig' ,[
          'references' => $references,
          'query'=>$requete

          ]);
        }else { echo ' FORM NON valide : requete = ';}
    //}
  }}

    return $this->render('PHBBaseContesBundle:Requetes:requetesview2.html.twig' ,[
    //'formMotClef' => $formMotClef->createView(),
    'form' => $form->createView(),
  ]);

  }






}
