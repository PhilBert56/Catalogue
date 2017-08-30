<?php

namespace PHB\BaseContesBundle\Requetes;


class ATRequest{

  private $references;
  public $numeroAT;

  public function __construct($numeroAT)
  {
      $this->numeroAT = $numeroAT;
  }

  public function getReferences($repository){

    $query = $repository->createQueryBuilder('ct')
      ->where('ct.numerosAT LIKE \''.$this->numeroAT.'\'')
      ->orderBy('ct.titre', 'ASC')
      ->getQuery();

    $references = $query->getResult();

    return $references;

  }

}
