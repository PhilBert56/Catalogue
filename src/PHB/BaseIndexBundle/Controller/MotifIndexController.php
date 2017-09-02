<?php

namespace PHB\BaseIndexBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class MotifIndexController extends Controller
{

  /**
   * @Route("/Motif-Index", name="biblioMotifIndex")
  */
    public function indexAction()
    {

      $fileName = "..\src\PHB\BaseIndexBundle\Data\BiblioMotifIndexLiensComplets.csv";
      $motifs = [];

      if (($handle = fopen($fileName, "r")) !== FALSE) {

        while (($data = fgetcsv($handle, 0, ";")) !== FALSE) {

          if ($data[1] != ''){

            $ligne = [];
            //echo '</br> n elements = '.count($data).'| ';
            for($i = 0; $i< count($data); $i++) {
              //echo 'i= '.$i.'|'.$data [$i].'| ';
              if ($data[$i] != ''){
                $ligne[] = utf8_encode($data[$i]);
              }else {
                $ligne[] = '';
              }

            }
            //echo '</br>';
            $motifs[] = $ligne;
            if (count($ligne)<9){

              //echo '</br> !!!!!!!!!!!!!!!!!! '.$ligne[0].'</br>';
              dump($ligne);
            }

          }
        }
      }
      fclose($handle);
      //dump ($motifs);
      return $this->render('PHBBaseIndexBundle:Default:indexview.html.twig', [
          'motifs' => $motifs
      ]);
    }
}
