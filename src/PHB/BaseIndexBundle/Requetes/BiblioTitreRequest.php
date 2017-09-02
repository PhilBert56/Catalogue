<?php

namespace PHB\BaseIndexBundle\Requetes;


class BiblioTitreRequest{

  private $references;
  public $titre;

    public function __construct($titre)
    {
        $this->titre = $titre;
    }

    public function getReferences($repository){

      $query = $repository->createQueryBuilder('ov')
        ->where('ov.titreOuvrage LIKE \''.$this->titre.'\'')
        ->orderBy('ov.id', 'ASC')
        ->getQuery();

        $references = $query->getResult();
        return $references;

      }

}
