<?php

namespace PHB\BaseIndexBundle\Requetes;


class MotClefRequest{

  private $references;
  public $motClef;

  public function __construct($motClef)
  {
      $this->motClef = $motClef;
  }

  public function getReferences($repository){

  $query = $repository->createQueryBuilder('motif')
    ->where('motif.description LIKE \''.$this->motClef.'\'')
    ->orderBy('motif.codeMotif', 'ASC')
    ->getQuery();

  $references = $query->getResult();

  return $references;

  }

}
