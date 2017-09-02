<?php

namespace PHB\BaseContesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

use PHB\BaseContesBundle\Entity\ReferenceConte;
use PHB\BaseContesBundle\Entity\ReferenceOuvrage;

use PHB\BaseContesBundle\Requetes\MotClefRequest;
use PHB\BaseContesBundle\Requetes\ATRequest;
use PHB\BaseContesBundle\Requetes\LivresRequest;

class BaseContesController extends Controller
{

   /**
   * @Route("/Consulter la base", name="consulterBaseContes")
   */
    public function indexAction(Request $request)
    {

        $motClef = new MotClefRequest('');
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $motClef);

        $formBuilder
          ->add('motClef', TextType::class ,array('label' => 'Requête par Mot Clef : '))
        ;
        $formMotClef = $formBuilder->getForm();

        $aTRequest = new ATRequest('');
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $aTRequest);

        $formBuilder
          ->add('numeroAT', TextType::class ,array('label' => 'Requête par numéro AT  : ' ))
          ;
        $formAT = $formBuilder->getForm();

        $livresRequest = new LivresRequest();
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $livresRequest);
        $formLivres = $formBuilder->getForm();



        if ($request->isMethod('POST')) {

          if(isset($_POST['submit_l'])){
            $requete = '';
            $repoLivres = $this->getDoctrine()->getRepository(ReferenceOuvrage::class);
            $livresRequest = new LivresRequest();
            $references = $livresRequest->getReferences($repoLivres);

            return $this->render('PHBBaseContesBundle:Requetes:livresview.html.twig' ,[
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

                $repoConte = $this->getDoctrine()->getRepository(ReferenceConte::class);
                $mcRequest = new MotClefRequest($retourValidation[1]);
                $references = $mcRequest->getReferences($repoConte , $retourValidation[1]);

                return $this->render('PHBBaseContesBundle:Requetes:referencesview.html.twig' ,[
                  'references' => $references,
                  'query'=>$requete
                ]);

              }

            }
          }

          if(isset($_POST['submit_nat'])){

            $formAT->handleRequest($request);

            if ($formAT->isValid()) {
              $requete = $formAT['numeroAT']->getData();
              $retourValidation = $this->validerLaSaisieNumeroAT($requete);

              if ($retourValidation[0]){
                $repoConte = $this->getDoctrine()->getRepository(ReferenceConte::class);
                $atrequest = new ATRequest($retourValidation[1]);
                $references = $atrequest->getReferences($repoConte , $retourValidation[1]);

                return $this->render('PHBBaseContesBundle:Requetes:referencesview.html.twig' ,[
                  'references' => $references,
                  'query'=>$requete

              ]);
              }
            }
          }

        }



        return $this->render('PHBBaseContesBundle:Requetes:requetesview.html.twig' ,[
        'formMotClef' => $formMotClef->createView(),
        'formAT' => $formAT->createView(),
        'formLivres' => $formLivres->createView(),
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

      return $this->render('PHBBaseContesBundle:Requetes:referencesview.html.twig' ,[
        'references' => $references,
        'query'=>$requete

      ]);

    }

    /**
    * @Route("/Requete numero AT Contes/{requete}/", name="requeteBaseContes")
    */
    public function requestATAction($requete)
    {

      $repoConte = $this->getDoctrine()->getRepository(ReferenceConte::class);

      $request = new ATRequest($requete);

      $references = $request->getReferences($repoConte , $requete);

      return $this->render('PHBBaseContesBundle:Requetes:referencesview.html.twig' ,[
        'references' => $references,
        'query'=>$requete

      ]);

    }




    public function validerLaSaisieMotClef($motClefSaisi){

      //$this->addFlash('success', 'Le mot clef reçu est : |'.$motClefSaisi.'|');

      if (grapheme_strlen($motClefSaisi)< 4 ){
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


    public function validerLaSaisieNumeroAT($numeroATSaisi){

      //$this->addFlash('success', 'Le mot clef reçu est : |'.$motClefSaisi.'|');

      if (grapheme_strlen($numeroATSaisi)< 3 ){
        $this->addFlash('error', 'Le mot clef saisi est incorrect pour interroger cette base de données : le nombre de caractères du mot clef doit être supérieur à 2');
        return [false, ''];
      }

      $caracteresIllegaux = ['<', '>', '!', ';', ',', ':', '/'];
      for ($i = 0; $i< count($caracteresIllegaux);$i++) {
        if (strpos ($numeroATSaisi , $caracteresIllegaux[$i]) != false){
          $this->addFlash('error', 'Le caractère '.$caracteresIllegaux[$i]." n' est pas accepté") ;
          return [false,''];
        }
      }

      $separateur = strpos ($numeroATSaisi, '*');
      //$this->addFlash('success', '!!!!!!!!!!!!! separateur ? = '.$separateur);

      if ($separateur === 0){
        $numeroATSaisi = str_replace('*','%',$numeroATSaisi);
        //$this->addFlash('success', '************ Le mot clef renvoyé est : |'.$motClefSaisi.'|');
        return [true,$numeroATSaisi];
      }


      $numeroATSaisi  = '%'.$numeroATSaisi.'%';
      //$this->addFlash('success', 'Le mot clef renvoyé est : |'.$motClefSaisi.'|');
      return [true, $numeroATSaisi];

    }




}
