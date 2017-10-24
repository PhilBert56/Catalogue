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
        //echo 'VERSION : ', $this->versionNumber,' reçu à parser ligne = *', $ligne, '*</br>';

        $debug = false;
        $listeDesElements =[];
/* --------------------------------------------------------------------------- */
        if($section !== '' && $this->isRomain($section)){

            if ($debug) echo 'examine la ligne = ',$ligne,'</br>';
            $this->edcSection = $section;
            if ($debug) echo 'SECTION = ', $section, '</br>';

            foreach ($section3 as $ss){
                $ss = trim($ss);
                $ss = str_replace(' ','',$ss);

                if  ($debug)echo 'examine |',$ss,'|</br>';

                if ( strpos($ss,'(') !== false) {
                    $ss1 = explode ('(', $ss);
                    $ss = trim($ss1[0]);
                    if ($debug) echo '  parenthèse détectée => $ss = |', $ss, '|</br>';
                }

                if ( strpos($ss,'.') !== false) {
                    if ($debug) echo 'un point a ete detecte </br>';
                    $ss1 = explode ('.', $ss);
                    $ss = $ss1[0];
                    if ($debug) echo 'après retrait du point $ss = |',$ss,'|</br>';
                }

                if (strlen($ss) > 3) {
                    $ss = 'vide';
                    if ($debug) echo 'la chaine est trop longue pour etre un code </br>';
                }

                $ss = trim($ss);

                if (strlen ($ss) > 1) {
                    $premier = substr($ss,0,1);
                } else $premier = $ss;


                //echo 'reste plus qu à tester si CAPITALE pour ',$premier,'</br>';
                if (ctype_upper($premier)) {
                    $listeDesElements[] = $ss;
                    if ($debug) echo ' on extrait : |', $ss, '|</br>';
                    if ($debug) echo ' on devrait marquer |', $ss, '|</br>';
                }
            }/* fin de foreach $ss */
            if ($debug) echo 'stocke les codes extraits </br>';
            foreach ($listeDesElements as $element){
                    $this->edcCodes[]= trim($element);
                    if ($debug) echo 'code = |',trim($element),'| ';
            }
            if ($debug) echo '</br>';

        } /* fin de section */



/* --------------------------------------------------------------------------- */
    }


    private function isRomain($aTester){

        $romain = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII','XIII','XIV','XV','XVI','XVII','XVIII','XIX','XX'];

        return in_array($aTester,$romain);
    }






}
