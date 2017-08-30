<?php

namespace PHB\BaseContesBundle\Requetes;


class MotClefRequest{

  private $references;
  public $motClef;

  public function __construct($motClef)
  {
      $this->motClef = $motClef;
  }

  public function getReferences($repository){

  $query = $repository->createQueryBuilder('ct')
    ->where('ct.titre LIKE \''.$this->motClef.'\'')
    ->orderBy('ct.titre', 'ASC')
    ->getQuery();

  $references = $query->getResult();

  return $references;

  }

}
