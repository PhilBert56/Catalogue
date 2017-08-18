<?php

namespace PHB\BaseContesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="referenceConte")
 */

class ReferenceConte
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
     private $titre;

     /**
     * @ORM\Column(type="string", nullable=true)
     */
     private $genre;

     /**
     * @ORM\Column(type="string", nullable=true)
     */
     private $origine;


    /**
    * @ORM\Column(type="string", nullable=true)
    */
     private $numerosAT;


     /**
     * @ORM\Column(type="string", nullable=true)
     */
     private $pageOuNumero;


     /**
     * @ORM\ManyToOne(targetEntity="ReferenceOuvrage", inversedBy="contes")
     */
     private $ouvrage;



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
      public function getTitre()
      {
          return $this->titre;
      }

      /**
      * @param mixed $titre
      */
      public function setTitre($titre)
      {
          $this->titre = $titre;
      }


      /**
      * @return mixed
      */
      public function getGenre()
      {
          return $this->genre;
      }

      /**
      * @param mixed $genre
      */
      public function setGenre($genre)
      {
          $this->genre = $genre;
      }



      /**
      * @return mixed
      */
      public function getOrigine()
      {
          return $this->origine;
      }

      /**
      * @param mixed $origine
      */
      public function setOrigine($origine)
      {
          $this->origine = $origine;
      }

      /**
      * @return mixed
      */
      public function getNumerosAT()
      {
          return $this->numerosAT;
      }

      /**
      * @param mixed $numerosAT
      */
      public function setNumerosAT($numerosAT)
      {
          $this->numerosAT = $numerosAT;
      }


      /**
      * @return mixed
      */
      public function getPageOuNumero()
      {
          return $this->pageOuNumero;
      }

      /**
      * @param mixed $pageOuNumero
      */
      public function setPageOuNumero($pageOuNumero)
      {
          $this->pageOuNumero = $pageOuNumero;
      }

      /**
      * @return mixed
      */
      public function getOuvrage()
      {
          return $this->ouvrage;
      }

      /**
      * @param mixed $ouvrage
      */
      public function setOuvrage($ouvrage)
      {
          $this->ouvrage = $ouvrage;
      }



}
