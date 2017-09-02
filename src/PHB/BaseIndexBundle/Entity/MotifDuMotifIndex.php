<?php

namespace PHB\BaseIndexBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="motifDuMotifIndex")
 */

 class MotifDuMotifIndex
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
      private $codeMotif;

      /**
      * @ORM\Column(type="string", nullable=true)
      */
      private $description;


      /**
      * @ORM\Column(type="string", nullable=true)
      */
      private $bibliographie1;

      /**
      * @ORM\Column(type="string", nullable=true)
      */
      private $bibliographie2;

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
       public function getCodeMotif()
       {
         return $this->codeMotif;
       }

       /**
       * @param mixed $id
       */
       public function setCodeMotif($codeMotif)
       {
           $this->codeMotif = $codeMotif;
       }


       /**
       * @return mixed
       */
       public function getDescription()
       {
         return $this->description;
       }

       /**
       * @param mixed $id
       */
       public function setDescription($description)
       {
           $this->description = $description;
       }


       /**
       * @return mixed
       */
       public function getBibliographie1()
       {
         return $this->bibliographie1;
       }

       /**
       * @param mixed $id
       */
       public function setBibliographie1($text)
       {
           $this->bibliographie1 = $text;
       }



       /**
       * @return mixed
       */
       public function getBibliographie2()
       {
         return $this->bibliographie2;
       }

       /**
       * @param mixed $id
       */
       public function setBibliographie2($text)
       {
           $this->bibliographie2 = $text;
       }
}
