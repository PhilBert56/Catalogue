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
    public $occurencesDesElementsDuConte;

     public function __construct($conteTypeCode)
    {   
        $this->ctCode = $conteTypeCode;
        $this->description = [];
        $this->fichierSource = '';
        $this->hasSource = false;
        $this->occurencesDesElementsDuConte =[];
    }

    public function trouverLeFichierSourceAssocie($conteType){
        $v = 'V'.$this->numero;
        $lines = file($conteType->fichierDesSources);
        foreach ($lines as $lineNumber => $lineContent){ 
            $elements = explode ("=",$lines[$lineNumber],2);
            if (count ($elements) > 1){ 
                if (rtrim($elements[0]) == $v ){
                    $fileName = trim($elements[1]);
                    $path = $conteType->pathDesSources;
                    $this->fichierSource = $path.$fileName;
                    $this->hasSource = true;

                    if ( iconv_strlen($elements[1]) <= 5){
                        $this->fichierSource = '';
                        $this->hasSource = false;
                        break;
                    }
                }
            }
        }
    }

    public function setDescription($description){

        $this->description[] = $description;

    }

}