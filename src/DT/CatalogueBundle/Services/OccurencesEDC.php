<?php

namespace DT\CatalogueBundle\Services;
use DT\CatalogueBundle\Services\VersionDuConteType;

class OccurencesEDC 
{
    public $ctCode;
    public $versionNumber;
    public $sections;
    public $edcCodes;

    public function __construct($conteTypeCode, $versionNumber)
    {  
        $this->ctCode = $conteTypeCode;
        $this->versionNumber = $versionNumber;
        $this->sections = [];
        $this->edcCodes = [];
    }


    public function genererLesOccurencesDesElementsDuConte($ligne)
    {
        $edcList = [];
        $section = stristr($ligne,':',true) ;
        $section = trim($section);
        $section2 = stristr($ligne,':');
        $section2 = str_replace(':','',$section2);
        $section3 = explode (',', $section2);
        if($section != '' && $this->isRomain($section)){
            
        
            echo 'conte type = ', $this->ctCode,' version = ', $this->versionNumber,'  section =',$section;
            $listeDesElements =[];
            foreach ($section3 as $ss){
            
                if ( strpos($ss,'(') != false) {
                    $ss1 = explode ('(', $ss);
                    $ss = $ss1[0];
                }

                if ( strpos($ss,'.') != false) {
                    $ss1 = explode ('.', $ss);
                    $ss = $ss1[0];
                }
                
                if (strlen($ss) > 3) $ss = 'vide';

                $premier = substr($ss,1,1);
                if (ctype_upper($premier)) { 
                //echo $ss, '</br>';
                $listeDesElements[] = $ss;

                }
            }

            foreach ($listeDesElements as $element){ 
                echo '|',trim($element);
                }
                echo '</br>';
        }

    } 


    private function isRomain($aTester){

        $romain = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII','XIII','XIV','XV','XVI','XVII','XVIII','XIX','XX'];
        /*echo ' ICI =', $aTester;
        foreach ($romain as $rom)
        {   echo 'compare |', $aTester,'| avec |', $rom,'|';
            if ($rom == $aTester)return true;
        }
        return false;*/
        return in_array($aTester,$romain);
    }
}

