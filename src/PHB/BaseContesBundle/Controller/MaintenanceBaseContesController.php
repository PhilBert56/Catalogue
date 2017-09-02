<?php

namespace PHB\BaseContesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use PHB\BaseContesBundle\Entity\ReferenceConte;
use PHB\BaseContesBundle\Entity\ReferenceOuvrage;

class MaintenanceBaseContesController extends Controller
{

  /**
   * @Route("/Recharger la Base de donnees contes", name="mainenanceBaseContes")
   */
    public function indexAction()
    {

      $tableauDesOuvrages =[];


      $em = $this->getDoctrine()->getManager();
      $conteRepository = $em->getRepository('PHBBaseContesBundle:ReferenceConte');
      $ouvrageRepository = $em->getRepository('PHBBaseContesBundle:ReferenceOuvrage');

/* création de la table des ouvrages */
      $fileName = "..\src\PHB\BaseContesBundle\Data\TableDesOuvragesUTF8.csv";

      if (($handle = fopen($fileName, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 0, ";")) !== FALSE) {
        echo 'CodeOuvrage '.$data[0].'|Titre :'.$data[2].'|Auteur : '.$data[3].'|Editeur :'.$data[4].'  | Traducteur = '.$data[6];
        echo '</br>';
        $ouvrage = new ReferenceOuvrage();
        $ouvrage->setCodeOuvrage($data[1]);
        $ouvrage->setTitre ($data[2]);
        $ouvrage->setAuteur ($data[3]);
        $ouvrage->setEditeur ($data[4]);
        $ouvrage->setAnnee ($data[5]);
        $ouvrage->setTraducteur ($data[6]);

        $tableauDesOuvrages[] = $ouvrage;

        $em->persist($ouvrage);
        }

      }
      fclose($handle);
      $em->flush();
      //$em->clear();

/* création de la table des contes */
      $fileName = "..\src\PHB\BaseContesBundle\Data\TableDesTitresDeContesUTF8_corrige.csv";

      if (($handle = fopen($fileName, "r")) !== FALSE) {


        $count = 0;
        while (($data = fgetcsv($handle, 0, ";")) !== FALSE) {

          $count++;
          /* reconstitution de la liste des numéro AT */

          $at1 = $data[5];
          $at1ext = $data[6];
          $at2 = $data[7];
          $at2ext = $data[8];
          $at3 = $data[9];
          $at3ext = $data[10];
          $at4 = $data[11];
          $at4ext = $data[12];
          $at5 = $data[13];
          $at5ext = $data[14];
          $at6 = $data[15];
          $at6ext = $data[16];
          $at7 = $data[18];
          $at7ext = $data[18];
          $at8 = $data[19];
          $at8ext = $data[20];
          $numerosAT = '';
          $vide = true;
          for ($k = 5; $k<=20 ; $k++){
            if (!$vide) $numerosAT = $numerosAT.' ; ';
            if ($data[$k] != 0) {
              $numerosAT = $numerosAT.'T.'.$data[$k];
              $vide = false;
            }
            if ($data[$k + 1] != 0) $numerosAT = $numerosAT.' '.$data[$k +1];
            if ($data[$k + 2] == 0)break;
            $k = $k + 1;
          }

          echo ' '.$count.'  Titre : '.$data[1].'|Genre : '.$data[2].'|Origine :'.$data[3].'  | CodeOuvrage : '.$data[4].'| NumeroAT : '.$numerosAT.'| Page :'.$data[21];
          echo '</br>';

          $conte = new ReferenceConte();
          $conte->setTitre ($data[1]);
          $conte->setGenre ($data[2]);
          $conte->setOrigine ($data[3]);
          $conte->setNumerosAT ($numerosAT);
          $conte->setPageOuNumero ($data[21]);
          $ouvrage = $this->retrouverOuvrage($tableauDesOuvrages , $data[4] );
          $conte->setOuvrage ($ouvrage);

          $em->persist($conte);


        }

      }
      fclose($handle);
      $em->flush();




      return $this->render('PHBBaseContesBundle:Default:index.html.twig');
    }




    function retrouverOuvrage($tableauDesOuvrages , $codeOuvrage){

      foreach ($tableauDesOuvrages as $ouvrage){
        if ($ouvrage->getCodeOuvrage() == $codeOuvrage) return $ouvrage;
      }
      echo 'Ouvrage non trouvé';
      dump($codeOuvrage);
      dump($tableauDesOuvrages);die();
      return null;
    }
}
