<?php

namespace PHB\BaseContesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="referenceOuvrage")
 */

class ReferenceOuvrage
{
     /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
     private $id;

     /**
     * @ORM\Column(type="integer")
     */
     private $codeOuvrage;


     /**
     * @ORM\Column(type="string")
     */
     private $titre;

     /**
     * @ORM\Column(type="string")
     */
     private $auteur;

     /**
     * @ORM\Column(type="string", nullable=true)
     */
     private $editeur;


     /**
     * @ORM\Column(type="string", nullable=true)
     */
     private $annee;


    /**
    * @ORM\Column(type="string", nullable=true)
    */
     private $traducteur;

    /**
    * @ORM\OneToMany(targetEntity="ReferenceConte", mappedBy ="ouvrage")
    */
    private $contes;



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
    public function getCodeOuvrage()
    {
        return $this->codeOuvrage;
    }

    /**
    * @param mixed $codeOuvrage
    */
    public function setCodeOuvrage($codeOuvrage)
    {
        $this->codeOuvrage = $codeOuvrage;
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
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
    * @param mixed $auteur
    */
    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;
    }

    /**
    * @return mixed
    */
    public function getEditeur()
    {
        return $this->editeur;
    }

    /**
    * @param mixed $editeur
    */
    public function setEditeur($editeur)
    {
        $this->editeur = $editeur;
    }

    /**
    * @return mixed
    */
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
    * @param mixed $annee
    */
    public function setAnnee($annee)
    {
        $this->annee = $annee;
    }
    /**
    * @return mixed
    */
    public function getTraducteur()
    {
        return $this->traducteur;
    }

    /**
    * @param mixed $traducteur
    */
    public function setTraducteur($traducteur)
    {
        $this->traducteur = $traducteur;
    }

    /**
    * @return mixed
    */
    public function getContes()
    {
        return $this->contes;
    }

    /**
    * @param mixed $contes
    */
    public function setContes($contes)
    {
        $this->contes = $contes;
    }




}
