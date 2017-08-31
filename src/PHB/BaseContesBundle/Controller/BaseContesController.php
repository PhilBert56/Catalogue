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

class BaseContesController extends Controller
{

   /**
   * @Route("/Consulter la base", name="consulterBaseContes")
   */
    public function indexAction(Request $request)
    {

        $motClef = new MotClefRequest('% %');
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $motClef);

        $formBuilder
          ->add('motClef', TextType::class)
        ;
        $formMotClef = $formBuilder->getForm();

        $aTRequest = new ATRequest('% %');
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $aTRequest);

        $formBuilder
          ->add('numeroAT', TextType::class)
          ;
        $formAT = $formBuilder->getForm();


        if ($request->isMethod('POST')) {

          if(isset($_POST['submit_mc'])){

            $formMotClef->handleRequest($request);
            if ($formMotClef->isValid()) {
              $requete = $formMotClef['motClef']->getData();
              $repoConte = $this->getDoctrine()->getRepository(ReferenceConte::class);
              $mcRequest = new MotClefRequest($requete);
              $references = $mcRequest->getReferences($repoConte , $requete);

              return $this->render('PHBBaseContesBundle:Requetes:referencesview.html.twig' ,[
                  'references' => $references,
                  'query'=>$requete
              ]);
            }
          }

          if(isset($_POST['submit_nat'])){

            $formAT->handleRequest($request);

            if ($formAT->isValid()) {
              $requete = $formAT['numeroAT']->getData();

              $repoConte = $this->getDoctrine()->getRepository(ReferenceConte::class);
              $atrequest = new ATRequest($requete);
              $references = $atrequest->getReferences($repoConte , $requete);

              return $this->render('PHBBaseContesBundle:Requetes:referencesview.html.twig' ,[
                  'references' => $references,
                  'query'=>$requete

              ]);
            }else {
              /*
              $requete;
              $formAT>handleRequest($request);
              $requete = $formAT['numeroAT']->getData();
              $repoConte = $this->getDoctrine()->getRepository(ReferenceConte::class);
              $request = new ATRequest($requete);
              $references = $request->getReferences($repoConte , $requete);

              return $this->render('PHBBaseContesBundle:Requetes:referencesview.html.twig' ,[
                  'references' => $references,
                  'query'=>$requete

              ]);*/

            }

          }
        }

        return $this->render('PHBBaseContesBundle:Requetes:requetesview.html.twig' ,[
        'formMotClef' => $formMotClef->createView(),
        'formAT' => $formAT->createView(),
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


}
