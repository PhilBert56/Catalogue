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
    public $isHeader;

    public function __construct($ctCode)
    {   
        $this->ctCode = $ctCode;
        $this->description = '';
        $this->listeDesVersions = '';
        $this->hasVersions = false;
        $this->isHeader = false;
    }


    public function setDescription($description){

        $this->description = $description;

    }
}