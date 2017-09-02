<?php

namespace PHB\BaseIndexBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use PHB\BaseIndexBundle\Entity\BiblioDuMotifIndex;
use PHB\BaseIndexBundle\Entity\MotifDuMotifIndex;

class MaintenanceBaseIndexBiblioController extends Controller
{

  /**
   * @Route("/RechargerBiblioMotifIndex", name="mainenanceBaseIndexBiblio")
   */
    public function indexAction()
    {
      /* création de la table des motifs du Motifindex */
      $em = $this->getDoctrine()->getManager();

      //$biblioRepository = $em->getRepository('PHBBaseContesBundle:BiblioDuMotifIndex');
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



      return $this->render('PHBBaseContesBundle:Default:index.html.twig');
    }





}
