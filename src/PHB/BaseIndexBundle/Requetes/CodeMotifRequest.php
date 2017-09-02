<?php

namespace PHB\BaseIndexBundle\Requetes;


class CodeMotifRequest{

  private $references;
  public $codeMotif;

  public function __construct($codeMotif)
  {
      $this->codeMotif = $codeMotif;
  }

  public function getReferences($repository){

    $query = $repository->createQueryBuilder('motif')
      ->where('motif.codeMotif LIKE \''.$this->codeMotif.'\'')
      ->orderBy('motif.codeMotif', 'ASC')
      ->getQuery();

    $references = $query->getResult();

    return $references;

  }

}
