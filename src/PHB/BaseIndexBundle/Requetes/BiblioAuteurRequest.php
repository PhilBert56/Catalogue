<?php

namespace PHB\BaseIndexBundle\Requetes;


class BiblioAuteurRequest{

  private $references;
  public $auteur;

  public function __construct($auteur)
  {
      $this->auteur = $auteur;
  }

  public function getReferences($repository){

  $query = $repository->createQueryBuilder('ov')
    ->where('ov.auteur LIKE \''.$this->auteur.'\'')
    ->orderBy('ov.id', 'ASC')
    ->getQuery();

  $references = $query->getResult();

  return $references;

  }

}
