<?php

namespace DT\CatalogueBundle\Services;
use DT\CatalogueBundle\Services\VersionDuConteType;

class OccurencesEDC 
{
    //public $conteType;
    public $versionNumber;
    public $edcCodes;
    public $edcSection;

    public function __construct($versionNumber, $ligne)
    {  
        //$this->conteType = $conteType;
        $this->versionNumber = $versionNumber;
        $this->edcCodes = [];
        $this->edcSection ='';
        $this->genererLesOccurencesDesElementsDuConte($ligne);
    }


    public function genererLesOccurencesDesElementsDuConte($ligne)
    {
        $edcList = [];
        $section = stristr($ligne,':',true) ;
        $section = trim($section);
        $section2 = stristr($ligne,':');
        $section2 = str_replace(':','',$section2);
        $section3 = explode (',', $section2);
        echo 'VERSION : ', $this->versionNumber,' reçu à parser ligne = *', $ligne, '*</br>';
        

        $listeDesElements =[];
/*
        if($section != ''){
            $section = trim($section);
            //echo 'section à tester = |',$section,'| = ', $this->isRomain($section),'</br>';
        }
*/
/* ============================================================================ */
        if($section != '' && $this->isRomain($section)){
            $this->edcSection = $section;
            echo 'section = ', $section, '</br>';

/* -------------------------------------------------------------- */
            foreach ($section3 as $ss){

                $ss = trim($ss);

                echo 'examine ',$ss,'</br>';

                if ( strpos($ss,'(') != false) {
                    $ss1 = explode ('(', $ss);
                    $ss = trim($ss1[0]);
                    //echo '  parenthèse détectée => $ss = |', $ss, '|</br>';
                }

                if ( strpos($ss,'.') != false) {
                    $ss1 = explode ('.', $ss);
                    $ss = $ss1[0];
                }
                
                if (strlen($ss) > 3) $ss = 'vide';

                $ss = trim($ss);
    
                //echo 'ICI : $ss = |',$ss,'| et longueur = ', strlen($ss),'</br>';
                if (strlen ($ss) > 1) { 
                    $premier = substr($ss,0,1);
                    //echo ' longueur de chaine > 1 => reste plus qu à tester si CAP pour ',$premier,'</br>';
                    if (ctype_upper($premier)) { 
                        $listeDesElements[] = trim($ss);
                        echo ' extrait :', trim($ss), '</br>';
                    } 
                }
            }

            }
/* -------------------------------------------------------------- */   
                    
        foreach ($listeDesElements as $element){ 
            $this->edcCodes[]=$elem
       } 
    }
   } }



    private function isRomain($aTester){

        $romain = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII','XIII','XIV','XV','XVI','XVII','XVIII','XIX','XX'];
        
        return in_array($aTester,$romain);
    }

}

