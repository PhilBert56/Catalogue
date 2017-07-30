<?php

namespace DT\CatalogueBundle\Services;

class VersionDuConteType 
{
    public $ctCode;
    public $numero;
    public $reference;
    public $description;
    public $fichierSource;
    public $hasSource;

     public function __construct($conteTypeCode)
    {   
        $this->ctCode = $conteTypeCode;
        $this->description = [];
        $this->fichierSource = '';
        $this->hasSource = false;
    }

    public function trouverLeFichierSourceAssocie($conteType){
        $v = 'V'.$this->numero;
        $lines = file($conteType->fichierDesSources);
        foreach ($lines as $lineNumber => $lineContent){ 
            $elements = explode ("=",$lines[$lineNumber],2);
            if (count ($elements) > 1){ 
                if (rtrim($elements[0]) == $v ){
                    $this->fichierSource = $elements[1] ;
                    $this->hasSource = true;
                    if ( iconv_strlen($elements[1]) <= 2)$this->fichierSource = '' ;
                    break;
                }
            }
        }
    }
    public function setDescription($description){

        $this->description[] = $description;

    }

}