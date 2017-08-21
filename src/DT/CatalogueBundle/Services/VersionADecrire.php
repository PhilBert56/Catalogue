<?php

namespace DT\CatalogueBundle\Services;

class VersionADecrire
{
    public $numero;
    public $fichierSource;
    public $hasSource;

     public function __construct($numero, $hasSource, $fichierSource)
    {
        $this->fichierSource = $fichierSource;
        $this->hasSource = $hasSource;
        $this->numero = $numero ;
    }

}
