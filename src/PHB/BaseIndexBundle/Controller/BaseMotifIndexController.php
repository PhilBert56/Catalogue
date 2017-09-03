<?php

namespace PHB\BaseIndexBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

use PHB\BaseIndexBundle\Entity\MotifDuMotifIndex;
use PHB\BaseIndexBundle\Entity\BiblioDuMotifIndex;

use PHB\BaseIndexBundle\Requetes\CodeMotifRequest;
use PHB\BaseIndexBundle\Requetes\MotClefRequest;
use PHB\BaseIndexBundle\Requetes\BiblioMotifIndexRequest;
use PHB\BaseIndexBundle\Requetes\BiblioAuteurRequest;
use PHB\BaseIndexBundle\Requetes\BiblioTitreRequest;

class BaseMotifIndexController extends Controller
{

   /**
   * @Route("/Consulter le motif-Index", name="motifIndex")
   */
    public function indexAction(Request $request)
    {

        $motClef = new MotClefRequest('');
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $motClef);

        $formBuilder
          ->add('motClef', TextType::class ,array('label' => 'Requête par Mot Clef : '))
        ;
        $formMotClef = $formBuilder->getForm();

        $codeRequest = new CodeMotifRequest('');
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $codeRequest);

        $formBuilder
          ->add('codeMotif', TextType::class ,array('label' => 'Requête par code motif  : ' ))
          ;
        $formCodeMotif = $formBuilder->getForm();

        $livresRequest = new BiblioMotifIndexRequest();
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $livresRequest);
        $formBiblio = $formBuilder->getForm();


        $auteur = new BiblioAuteurRequest('');
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $auteur);

        $formBuilder
          ->add('auteur', TextType::class ,array('label' => "Nom de l'auteur : "))
        ;
        $formAuteur = $formBuilder->getForm();


        $titre = new BiblioTitreRequest('');
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $titre);

        $formBuilder
          ->add('titre', TextType::class ,array('label' => "Mot clef dans le titre : "))
        ;
        $formTitre = $formBuilder->getForm();


/* ============================================================================= */
        if ($request->isMethod('POST')) {

          if(isset($_POST['submit_bib'])){
            $requete = '';
            $repoLivres = $this->getDoctrine()->getRepository(BiblioDuMotifIndex::class);
            $livresRequest = new BiblioMotifIndexRequest();
            $references = $livresRequest->getReferences($repoLivres);
            $references = $this->formaterLesLiens($references);
            return $this->render('PHBBaseIndexBundle:Requetes:livresview.html.twig' ,[
                'references' => $references,
                'query'=>$requete
            ]);
          }



          if(isset($_POST['submit_mc'])){

            $formMotClef->handleRequest($request);
            if ($formMotClef->isValid()) {
              $requete = $formMotClef['motClef']->getData();

              $retourValidation = $this->validerLaSaisieMotClef($requete);

              if ($retourValidation[0]){

                $repoMotif = $this->getDoctrine()->getRepository(MotifDuMotifIndex::class);
                $mcRequest = new MotClefRequest($retourValidation[1]);
                $references = $mcRequest->getReferences($repoMotif , $retourValidation[1]);

                return $this->render('PHBBaseIndexBundle:Requetes:referencesview.html.twig' ,[
                  'references' => $references,
                  'query'=>$requete
                ]);

              }

            }
          }

          if(isset($_POST['submit_code'])){

            $formCodeMotif->handleRequest($request);

            if ($formCodeMotif->isValid()) {
              $requete = $formCodeMotif['codeMotif']->getData();
              $retourValidation = $this->validerLaSaisieCodeMotif($requete);

              if ($retourValidation[0]){
                $repoConte = $this->getDoctrine()->getRepository(MotifDuMotifIndex::class);
                $codeRequest = new CodeMotifRequest($retourValidation[1]);
                $references = $codeRequest->getReferences($repoConte , $retourValidation[1]);

                return $this->render('PHBBaseIndexBundle:Requetes:referencesview.html.twig' ,[
                  'references' => $references,
                  'query'=>$requete

              ]);
              }
            }
          }


          if(isset($_POST['submit_auteur'])){

            $formAuteur->handleRequest($request);

            if ($formAuteur->isValid()) {
              $requete = $formAuteur['auteur']->getData();

              $retourValidation = $this->validerLaSaisieMotClef($requete);

              if ($retourValidation[0]){
                $repoConte = $this->getDoctrine()->getRepository(BiblioDuMotifIndex::class);
                $auteurRequest = new BiblioAuteurRequest($retourValidation[1]);
                $references = $auteurRequest->getReferences($repoConte , $retourValidation[1]);
                $references = $this->formaterLesLiens($references);
                return $this->render('PHBBaseIndexBundle:Requetes:livresview.html.twig' ,[
                  'references' => $references,
                  'query'=>$requete

              ]);
              }
            }
          }

          if(isset($_POST['submit_titre'])){

            $formTitre->handleRequest($request);

            if ($formTitre->isValid()) {
              $requete = $formTitre['titre']->getData();

              $retourValidation = $this->validerLaSaisieMotClef($requete);

              if ($retourValidation[0]){
                $repoConte = $this->getDoctrine()->getRepository(BiblioDuMotifIndex::class);
                $titreRequest = new BiblioTitreRequest($retourValidation[1]);
                $references = $titreRequest->getReferences($repoConte , $retourValidation[1]);
                $references = $this->formaterLesLiens($references);
                return $this->render('PHBBaseIndexBundle:Requetes:livresview.html.twig' ,[
                  'references' => $references,
                  'query'=>$requete

              ]);
              }
            }
          }



        }
        /* ============================================================================ */



        return $this->render('PHBBaseIndexBundle:Requetes:requetesindexview.html.twig' ,[
        'formMotClef' => $formMotClef->createView(),
        'formCodeMotif' => $formCodeMotif->createView(),
        'formBiblio' => $formBiblio->createView(),
        'formAuteur' => $formAuteur->createView(),
        'formTitre' => $formTitre->createView()
      ]);

    }


    /**
    * @Route("/Requete Mot Clef Contes/{requete}/", name="requeteBaseContes")
    */
    public function requestMotCleAction($requete)
    {

      $repoConte = $this->getDoctrine()->getRepository(ReferenceConte::class);

      $request = new MotClefRequest($requete);

      $references = $request->getReferences($repoConte , $requete);

      return $this->render('PHBBaseIndexBundle:Requetes:referencesview.html.twig' ,[
        'references' => $references,
        'query'=>$requete

      ]);

    }

    /**
    * @Route("/Requete numero AT Contes/{requete}/", name="requeteBaseContes")
    */
    public function requestCodeMotifAction($requete)
    {

      $repoMotif = $this->getDoctrine()->getRepository(ReferenceConte::class);

      $request = new CodeMotifRequest($requete);

      $references = $request->getReferences($repoMotif , $requete);

      return $this->render('PHBBaseIndexBundle:Requetes:referencesview.html.twig' ,[
        'references' => $references,
        'query'=>$requete

      ]);

    }




    public function validerLaSaisieMotClef($motClefSaisi){

      //$this->addFlash('success', 'Le mot clef reçu est : |'.$motClefSaisi.'|');

      if (grapheme_strlen($motClefSaisi)< 3 ){
        $this->addFlash('error', 'Le mot clef saisi est incorrect pour interroger cette base de données : le nombre de caractères du mot clef doit être supérieur à 3');
        return [false, ''];
      }

      $caracteresIllegaux = ['<', '>', '!', ';', ',', ':', '/'];
      for ($i = 0; $i< count($caracteresIllegaux);$i++) {
        if (strpos ($motClefSaisi , $caracteresIllegaux[$i]) != false){
          $this->addFlash('error', 'Le caractère '.$caracteresIllegaux[$i]." n' est pas accepté") ;
          return [false,''];
        }
      }

      $separateur = strpos ($motClefSaisi, '*');
      //$this->addFlash('success', '!!!!!!!!!!!!! separateur ? = '.$separateur);

      if ($separateur === 0){
        $motClefSaisi = str_replace('*','%',$motClefSaisi);
        //$this->addFlash('success', '************ Le mot clef renvoyé est : |'.$motClefSaisi.'|');
        return [true,$motClefSaisi];
      }


      $motClefSaisi = '%'.$motClefSaisi.'%';
      //$this->addFlash('success', 'Le mot clef renvoyé est : |'.$motClefSaisi.'|');
      return [true, $motClefSaisi];

    }


    public function validerLaSaisieCodeMotif($codeSaisi){

      //$this->addFlash('success', 'Le mot clef reçu est : |'.$motClefSaisi.'|');

      if (grapheme_strlen($codeSaisi)< 1 ){
        $this->addFlash('error', 'Le mot clef saisi est incorrect pour interroger cette base de données : le nombre de caractères du mot clef doit être supérieur à 2');
        return [false, ''];
      }

      $caracteresIllegaux = ['<', '>', '!', ';', ',', ':', '/'];
      for ($i = 0; $i< count($caracteresIllegaux);$i++) {
        if (strpos ($codeSaisi , $caracteresIllegaux[$i]) != false){
          $this->addFlash('error', 'Le caractère '.$caracteresIllegaux[$i]." n' est pas accepté") ;
          return [false,''];
        }
      }

      $separateur = strpos ($codeSaisi, '*');
      //$this->addFlash('success', '!!!!!!!!!!!!! separateur ? = '.$separateur);

      if ($separateur === 0){
        $codeSaisi = str_replace('*','%',$codeSaisi);
        //$this->addFlash('success', '************ Le mot clef renvoyé est : |'.$motClefSaisi.'|');
        return [true,$codeSaisi];
      }


      $codeSaisi  = '%'.$codeSaisi.'%';
      //$this->addFlash('success', 'Le mot clef renvoyé est : |'.$motClefSaisi.'|');
      return [true, $codeSaisi];

    }


    public function formaterLesLiens($references){

      foreach ($references as $reference) {


          $liens = $reference->getLiensInternet();
          $isUrl = strpos ($liens, 'http');
          if ($isUrl === 0) {
            $listeLiens = explode (' ,', $liens);
            $ligne = '';
            for ($i = 0; $i <count($listeLiens); $i++){
              if (strlen($listeLiens[$i]) >4){
                $lien = "<a href =".$listeLiens[$i].">Source</a> ";
                $ligne = $ligne.$lien;

              }
            }
            $reference->setLiensInternet($ligne);
          }
      }




      return $references;
    }

}
