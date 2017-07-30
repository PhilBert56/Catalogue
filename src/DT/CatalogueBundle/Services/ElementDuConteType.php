<?php

namespace DT\CatalogueBundle\Services;

class ElementDuConteType 
{
    public $ctCode ;
    public $section;
    public $codeElementDuConte;
    public $description;
    public $listeDesVersions;

    public function __construct($ctCode)
    {   
        $this->ctCode = $ctCode;
        $this->description = '';
    }


    public function setDescription($description){

        $this->description = $description;

    }
}