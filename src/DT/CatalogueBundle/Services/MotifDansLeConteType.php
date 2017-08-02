<?php

namespace DT\CatalogueBundle\Services;

class MotifDansLeConteType 
{
    public $ctCode ;
    public $motifCode;
    public $motifDescription;
    public $isTitre;
    
    public function __construct($ctCode)
    {   
        $this->ctCode = $ctCode;
        $this->motifCode = '';
        $this->motifDescription = '';
    }


    public function setDescription($description){

        $this->description = $description;

    }
}