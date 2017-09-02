<?php

namespace PHB\BaseIndexBundle\Requetes;


class BiblioMotifIndexRequest{

  private $references;

  public function getReferences($repository){

    $query = $repository->createQueryBuilder('ov')
      ->orderBy('ov.id', 'ASC')
      ->getQuery();

    $references = $query->getResult();

    return $references;

  }

}
