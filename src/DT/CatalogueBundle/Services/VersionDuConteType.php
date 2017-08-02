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

    /*  
        Balaye le fichier des versions disponibles en PDF (fichier des sources)
        pour les associer à l'objet version dans l'attribut $this->fichierSource 
    */

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
    /*  (même si l'attribut descriptio est public, 
        on ne peut pas le modifier de l'extérieur sans passer par un setteur) */

        $this->description[] = $description;

    }

}