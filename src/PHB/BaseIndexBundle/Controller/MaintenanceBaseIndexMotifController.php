<?php

namespace PHB\BaseIndexBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use PHB\BaseContesBundle\Entity\BiblioDuMotifIndex;
use PHB\BaseContesBundle\Entity\MotifDuMotifIndex;

class MaintenanceBaseIndexMotifController extends Controller
{

  /**
   * @Route("/RechargerMotifs/{debut}/{fin}", name="mainenanceBaseIndexMotifs")
   */
    public function indexAction($debut,$fin)
    {
      /* création de la table des motifs du Motifindex */
      $em = $this->getDoctrine()->getManager();

      //$motifRepository = $em->getRepository('PHBBaseContesBundle:MotifDuMotifIndex');

      $nbreLignes = 0;

      $fileName = "..\src\PHB\BaseIndexBundle\Data\IndexTable.csv";
      if (($handle = fopen($fileName, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 0, ";")) !== FALSE)  {

        $nbreLignes++;

        if ($nbreLignes >= $debut && $nbreLignes <= $fin) {
echo 'n = '.$data[0].' CodeMotif : '.$data[1].'|Description :'.utf8_encode($data[2]).'|Biblio : '.utf8_encode($data[3]).utf8_encode($data[4]).'</br>';
          $motif = new MotifDuMotifIndex();
          $motif->setCodeMotif (utf8_encode($data[1]));
          $motif->setDescription (utf8_encode($data[2]));
          $motif->setBibliographie1 (utf8_encode($data[3]));
          $motif->setBibliographie2 (utf8_encode($data[4]));
          $em->persist($motif);
         }
        }
      }
      fclose($handle);
echo 'FLUSH ==== ';
      $em->flush();
echo 'FLUSH ==== OK </br>';





      //$biblioRepository = $em->getRepository('PHBBaseContesBundle:BiblioDuMotifIndex');
      /*
      $fileName = "..\src\PHB\BaseIndexBundle\Data\BiblioMotifIndexLiensComplets.csv";
      if (($handle = fopen($fileName, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 0, ";")) !== FALSE) {
echo 'Auteur '.$data[1].'|Pays :'.utf8_encode($data[2]).'|Titre : '.utf8_encode($data[3]).' liens : '.utf8_encode($data[4]).'</br>';
        $biblio = new BiblioDuMotifIndex();

        $biblio->setAuteur (utf8_encode($data[1]));
        $biblio->setPays (utf8_encode($data[2]));
        $biblio->setTitreOuvrage (utf8_encode($data[3]));
        $biblio->setLiensInternet (utf8_encode($data[4]));


        $em->persist($biblio);
        }
      }
      fclose($handle);
      $em->flush();

*/



      return $this->render('PHBBaseContesBundle:Default:index.html.twig');
    }





}
