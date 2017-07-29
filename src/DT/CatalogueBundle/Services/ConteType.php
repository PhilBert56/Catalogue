<?php

namespace DT\CatalogueBundle\Services;

class ConteType 
{
    public $ct ;
    public $titre;
    public $isDefined;
    public $fichierDesElementsDuConte;
    public $fichierDesVersions;
    public $elementsDuConte=[];
    public $versions=[];

    public function genererLesInformationsDuConteType()
    {
            $session = $this->get('session');
            $tableauDesContesTypes = $session->get('tableauDesContesTypes');
            
            $isAlredyDefined = false;
            
            foreach($tableauDesContesTypes as $ct){
                if ($this->ct == $ct->ct && $ct->isDefined) $isAlredyDefined = true;
            }

            if(!$isAlredyDefined ){
                

            }
    }

}