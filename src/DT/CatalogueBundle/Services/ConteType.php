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
    public $pathDesSources;
    public $elementsDuConte=[];
    public $versions=[];
    public $hasVersions = false;

    public function genererLesInformationsDuConteType()
    {
        if ($this->isDefined)return;

        $this->genererLaListeDesVersions();
        $this->genererLaListeDesElementsDuConte();
        $this->isDefined = true;

    }

    public function genererLaListeDesVersions(){

        if(file_exists($this->fichierDesVersions)){

            $lines = file($this->fichierDesVersions);
        
            foreach ($lines as $lineNumber => $lineContent){ 
            
                $elements = explode (".",$lines[$lineNumber],2);
            
                if (is_numeric($elements[0])){
                    
                    $version = new VersionDuConteType($this->ctCode);
                    $this->hasVersion = true;
                    $version->numero = $elements[0];
                    $version->reference = $elements[1];
                    $this->versions[] = $version;
                    $version->trouverLeFichierSourceAssocie($this);
                    $occurencesEDC = new OccurencesEDC ($this->ctCode,$version->numero ) ;
                    

                //$version->$description = ''; 
                } else {
                    $ligne = $lines[$lineNumber];
                    $occurencesEDC->genererLesOccurencesDesElementsDuConte($ligne);
                    $version->setDescription ($ligne);
                    //dump($occurencesEDC);
                }
            }
        }
        dump($this->versions);

    }

    public function genererLaListeDesElementsduConte(){
        if (!file_exists($this->fichierDesElementsDuConte) ){
            echo 'FILE :$this->fichierDesElementsDuConte inexistant';
            return;
        }
        $lines = file($this->fichierDesElementsDuConte);
        $edcTab = [];

        

        foreach ($lines as $lineNumber => $lineContent){
            $edct = new ElementDuConteType($this->ctCode);
            $edct->ctCode = $this->ctCode;
            $edct->setDescription($lines[$lineNumber]);


            /* recherche du numero de section en chiffres romains */
            
            
            $code = explode('-',$lines[$lineNumber]);
            $code = str_replace('[','',$code);
            $code = str_replace(']','',$code);
            $section = explode(' ',$code[0]) ;
            $sectionChiffreRomain = $section[0];
            $edct->section = trim($sectionChiffreRomain);

            /* si la section se termine par un . ce n'est pas un edc mais un titre de section */
            if ( !strpos($edct->section , '.') && count($section) > 1     ) { 
                $edct->codeElementDuConte = $section[1];
            }
            
            $edcTab[] = $edct;
        }
        $this->elementsDuConte = $edcTab;
        dump ($this->elementsDuConte);
    }

}