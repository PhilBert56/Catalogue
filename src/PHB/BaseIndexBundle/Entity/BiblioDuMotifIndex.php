<?php

namespace PHB\BaseIndexBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="BibliofDuMotifIndex")
 */

 class BiblioDuMotifIndex
 {
      /**
      * @ORM\Id
      * @ORM\GeneratedValue(strategy="AUTO")
      * @ORM\Column(type="integer")
      */
      private $id;

      /**
      * @ORM\Column(type="string")
      */
      private $auteur;

      /**
      * @ORM\Column(type="string", nullable=true)
      */
      private $pays;

      /**
      * @ORM\Column(type="string", nullable=true)
      */
      private $titreOuvrage;


      /**
      * @ORM\Column(type="string", nullable=true)
      */
      private $liensInternet;



      /**
      * @return mixed
      */
       public function getId()
       {
         return $this->id;
       }

       /**
       * @param mixed $id
       */
       public function setId($id)
       {
           $this->id = $id;
       }


       /**
       * @return mixed
       */
       public function getAuteur()
       {
         return $this->auteur;
       }

       /**
       * @param mixed $id
       */
       public function setAuteur($auteur)
       {
           $this->auteur = $auteur;
       }


       /**
       * @return mixed
       */
       public function getPays()
       {
         return $this->pays;
       }

       /**
       * @param mixed $id
       */
       public function setPays($pays)
       {
           $this->pays = $pays;
       }

       /**
       * @return mixed
       */
       public function getTitreOuvrage()
       {
         return $this->titreOuvrage;
       }

       /**
       * @param mixed $id
       */
       public function setTitreOuvrage($titreOuvrage)
       {
           $this->titreOuvrage = $titreOuvrage;
       }

       /**
       * @return mixed
       */
       public function getLiensInternet()
       {
         return $this->liensInternet;
       }

       /**
       * @param mixed $id
       */
       public function setLiensInternet($liens)
       {
           $this->liensInternet = $liens;
       }
}
