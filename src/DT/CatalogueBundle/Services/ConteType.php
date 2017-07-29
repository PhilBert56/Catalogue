<?php

namespace DT\CatalogueBundle\Services;
use DT\CatalogueBundle\Services\VersionDuConteType;

class ConteType 
{
    public $ct ;
    public $titre;
    public $isDefined;
    public $fichierDesElementsDuConte;
    public $fichierDesVersions;
    public $fichierDesSources;
    public $elementsDuConte=[];
    public $versions=[];

    public function genererLesInformationsDuConteType($session)
    {
            
            $tableauDesContesType = $session->get('tableauDesContesType');
            
            $isAlredyDefined = false;
            
            
            foreach($tableauDesContesType as $ct){
                if ($this->ct == $ct->ct && $ct->isDefined) $isAlredyDefined = true;
            }

            if(!$isAlredyDefined ){

                $lines = file($this->fichierDesElementsDuConte);
                $edc = [];
                foreach ($lines as $lineNumber => $lineContent){ 
                    $edc [] = $lines[$lineNumber];
                }
                $this->elementsDuConte = $edc;

                $this->genererLaListeDesVersions($this);
             }

             dump($tableauDesContesType );

    }

    public function getConteType($ct){
        $tableauDesContesTypes = $session->get('tableauDesContesTypes');
        foreach($tableauDesContesTypes as $ctype){
            if ($ct == $ctype->ct ) return $ctype;
        }
        return null;
    }


    public function genererLaListeDesVersions(){

        $lines = file($this->fichierDesVersions);
        
        foreach ($lines as $lineNumber => $lineContent){ 
            
            $elements = explode (".",$lines[$lineNumber],2);
            
            if (is_numeric($elements[0])){
                $version = new VersionDuConteType($this);
                $version->numero = $elements[0];
                $version->reference = $elements[1];
                $this->versions[] = $version;
                $version->trouverLeFichierSourceAssocie($this);
                //$version->$description = ''; 
            } else {
                
                //$version->$description = $lines[$lineNumber];
            }
        }
        dump($this->versions);

    }

    

    


}