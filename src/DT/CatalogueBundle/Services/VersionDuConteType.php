<?php

namespace DT\CatalogueBundle\Services;

class VersionDuConteType 
{
    public $ct;
    public $numero;
    public $reference;
    public $description;
    public $fichierSource;
    public $hasSource;

     public function __construct($conteType)
    {   
        $this->ct = $conteType;
        $this->description = '';
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
                    break;
                }
            }
        }
    }

}