<?php

namespace DT\CatalogueBundle\Services;

class ElementDuConteType 
{
    public $ctCode ;
    public $section;
    public $codeElementDuConte;
    public $description;
    public $listeDesVersions;
    public $hasVersions;

    public function __construct($ctCode)
    {   
        $this->ctCode = $ctCode;
        $this->description = '';
        $this->listeDesVersions = '';
        $this->hasVersions = false;
    }


    public function setDescription($description){

        $this->description = $description;

    }
}