<?php

namespace PHB\BaseContesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class BaseContesController extends Controller
{

  /**
   * @Route("/Base de donnees contes", name="baseContes")
   */
    public function indexAction()
    {

      //$row = 1;
      $fileName = "..\src\PHB\BaseContesBundle\Data\TableDesTitresDeContesUTF8_corrige.csv";


      if (($handle = fopen($fileName, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 0, ";")) !== FALSE) {

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





        echo 'Titre : '.$data[1].'|Genre : '.$data[2].'|Origine :'.$data[3].'  | CodeOuvrage : '.$data[4].'| NumeroAT : '.$numerosAT.'| Page :'.$data[21];
/*
          foreach ($data as $d) {
            echo utf8_encode($d), ' | ';
          }

*/

          //$row++;
          echo '</br>';
        }

      }
      fclose($handle);

      $fileName = "..\src\PHB\BaseContesBundle\Data\TableDesOuvragesUTF8.csv";

      if (($handle = fopen($fileName, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 0, ";")) !== FALSE) {
        echo 'CodeOuvrage '.$data[0].'|Titre :'.$data[2].'|Auteur : '.$data[3].'|Editeur :'.$data[4].'  | Traducteur = '.$data[6];
        echo '</br>';
        }

      }
      fclose($handle);



      return $this->render('PHBBaseContesBundle:Default:index.html.twig');
    }
}
