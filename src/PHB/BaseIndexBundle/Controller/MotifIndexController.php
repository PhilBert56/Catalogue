<?php

namespace PHB\BaseIndexBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class MotifIndexController extends Controller
{

  /**
   * @Route("/Motif-Index", name="motifIndex")
  */
    public function indexAction()
    {

      $fileName = "..\src\PHB\BaseIndexBundle\Data\IndexTable.csv";
      $motifs = [];

      if (($handle = fopen($fileName, "r")) !== FALSE) {

        while (($data = fgetcsv($handle, 0, ";")) !== FALSE) {

          //echo utf8_encode($data[0]).'|'.utf8_encode($data[1].' | '.utf8_encode($data[2]).' | '.utf8_encode($data[3]));
          //echo '</br>';

          $data[2] = utf8_encode($data[2]);
          $data[3] = utf8_encode($data[3]);
          $motifs[] = $data;
        }

      }
      fclose($handle);

      return $this->render('PHBBaseIndexBundle:Default:indexview.html.twig', [
          'motifs' => $motifs
      ]);
    }
}
