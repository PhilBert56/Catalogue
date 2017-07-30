<?php

namespace DT\CatalogueBundle\Services;
use DT\CatalogueBundle\Services\VersionDuConteType;

class ConteType 
{
    public $ctCode ;
    public $titre;
    public $isDefined;
    public $fichierDesElementsDuConte;
    public $fichierDesVersions;
    public $fichierDesSources;
    public $elementsDuConte=[];
    public $versions=[];

    public function genererLesInformationsDuConteType()
    {
        if ($this->isDefined)return;

        $this->genererLaListeDesVersions();
        $this->genererLaListeDesElementsDuConte();
        $this->isDefined = true;

    }

    public function genererLaListeDesVersions(){

        $lines = file($this->fichierDesVersions);
        
        foreach ($lines as $lineNumber => $lineContent){ 
            
            $elements = explode (".",$lines[$lineNumber],2);
            
            if (is_numeric($elements[0])){
                $version = new VersionDuConteType($this->ctCode);
                $version->numero = $elements[0];
                $version->reference = $elements[1];
                $this->versions[] = $version;
                $version->trouverLeFichierSourceAssocie($this);
                //$version->$description = ''; 
            } else {
                
                $version->setDescription ($lines[$lineNumber]);
            }
        }
        dump($this->versions);

    }

    public function genererLaListeDesElementsduConte(){
        
        $lines = file($this->fichierDesElementsDuConte);
        $edcTab = [];
        foreach ($lines as $lineNumber => $lineContent){
            $edct = new ElementDuConteType($this->ctCode);
            $edct->setDescription($lines[$lineNumber]);
            $edcTab[] = $edct;
        }
        $this->elementsDuConte = $edcTab;
    }

}