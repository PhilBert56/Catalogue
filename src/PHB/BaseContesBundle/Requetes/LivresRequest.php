<?php

namespace PHB\BaseContesBundle\Requetes;


class LivresRequest{

  private $references;

  public function getReferences($repository){

    $query = $repository->createQueryBuilder('ov')
      ->orderBy('ov.titre', 'ASC')
      ->getQuery();

    $references = $query->getResult();

    return $references;

  }

}
