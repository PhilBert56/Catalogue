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

//echo 'on a appelé set Description pour', $description, '</br>';
        $nouvelleLigne = $this->marquerLesOccurencesDesElementsDuConte($description);
        $this->description[] = $nouvelleLigne;




    }



    public function marquerLesOccurencesDesElementsDuConte($ligne)
    {
        $edcList = [];
        /* tableau qui va contenir la liste des elements du conte repérés dans la chaîne */
        $section1 = stristr($ligne,':',true) ;
        /* retourne la sous-chaine de $ligne située avant le caractère : 
        cette sous-chaîne doit, par convention, contenir la section en chiffres romains */
        $section1 = trim($section1);
        /*elimine les espaces en debut et fin de section */

        $section2 = stristr($ligne,':');
        $section2 = str_replace(':','',$section2);
        /* retourne la sous-chaine de $ligne située après le caractère : */
        $delimiters = [',',';',':'];
        $temp = str_replace($delimiters, $delimiters[0], $section2);
        $launch = explode($delimiters[0], $temp);
        $sousSections = explode ($delimiters[0], $section2);
        //echo 'VERSION : ', $this->versionNumber,' reçu à parser ligne = *', $ligne, '*</br>';
        
        //echo 'premier parsing de la ligne ; section = |', $section, '|  section2  |', $section2, '|  section 3  |', $section3[0];

        $debug = false;
        $debug2 = false;
        $listeDesElements =[];
/* --------------------------------------------------------------------------- */
        if($section1 != '' && $this->isRomain($section1)){

        dump ($ligne,$sousSections);

            if ($debug) echo 'examine la ligne = ',$ligne,'</br>';
            $this->edcSection = $section1;
            if ($debug) echo 'SECTION = ', $section1, '</br>';
            $curseurDebut = 0;
            $curseurFin = strpos($ligne, ':') + 1;

            foreach ($sousSections as $ss){

                $chaineInitiale = $ss;
                $longueurDeLaChaineInitiale = strlen($ss);
                $curseurDebut = $curseurFin;
                $EDCpresentsDansLaChaine = $this->extraireLesCodesEDCpresentsDansUneChaine($ss);
                //$ssMemoire = $ss;
                
                if($debug2) echo '</br></br>Avec $ssMemoire = |', $chaineInitiale, '| longueur $ssMemoire = ', strlen($chaineInitiale),' on est entre ',$curseurDebut, ' et ', $curseurFin, '</br>';

                $ss = trim($ss);
                //$ss = str_replace(' ','',$ss);
                $ss2='';
            
                if  ($debug)echo 'examine |',$ss,'|</br>';

                if ( strpos($ss,'(') != false) {
                    $ss1 = explode ('(', $ss);
                    $ss = trim($ss1[0]);
                    $ss2 =$ss1[1];
                    if ($debug2) echo '  parenthèse détectée => $ss = |', $ss, '|</br>';
                }

                if ( strpos($ss,'.') != false) {
                    if ($debug) echo 'un point a ete detecte </br>';
                    $ss1 = explode ('.', $ss);
                    $ss = $ss1[0];
                    if ($debug) echo 'après retrait du point $ss = |',$ss,'|</br>';
                }

                if (strlen($ss) > 4) {
                    $ss = 'vide';
                    if ($debug) echo 'la chaine est trop longue pour etre un code </br>';
                }

                

                if (strlen ($ss) > 1) { 
                    $premier = substr($ss,0,1);
                } else $premier = $ss;
                    
                    
                //echo 'reste plus qu à tester si CAPITALE pour ',$premier,'</br>';
                if (ctype_upper($premier)) { 
                    // $listeDesElements[] = trim($ss);
                    if ($debug) echo ' on extrait : |', $ss, '|</br>';
                    //if ($debug) echo ' on devrait marquer |', $ss, '|</br>';
                    //$ss = trim($ss);
                    
                    $insertion = " <a href=\"/ConteType/elementDuConte/".$this->ctCode."/".$section1."/".$ss."\">".$ss."</a>";
                    //$insertion = "<strong>".$ssMemoire."</strong>";
                    
                    if ($debug) echo '</br> on devrait remplacer |',$chaineInitiale, '| par '.$insertion.'</br>';
                    if ($debug) echo ' dans la chaine ',  $ligne, '</br>';

                    //str_replace($ssMemoire,$insertion,$ligne);
                    /* str_replace ne fonctionne pas avec du code html */
                   
                    //$positionDebut = stripos($ligne,$ssMemoire );


                    /******************************************************************/
                    $curseurFin = $curseurDebut + strlen($ss); //+ strlen($ssMemoire); //- strlen($ss);
                    $debutLigne = substr($ligne,0, $curseurDebut);

                    $finLigne = substr($ligne, $curseurFin + 1 , strlen($ligne) );
                    /******************************************************************/


                    $finFligne = rtrim($finLigne);
                    echo 'pour $ssMemoire = |',$chaineInitiale,'| et $ss = |',$ss,'| la ligne ', $ligne,' débute par |',$debutLigne,'| et se termine par |', $finLigne, '|</br>';
                    
                    $aInserer = $insertion; 

                    if (strlen($ss2 > 0) )  $aInserer = $insertion.' ('.$ss2;
                
                echo 'ICI strlen(|',$finLigne,'|) = ',strlen($finLigne),'</br>';
                if($finLigne == '')echo 'fin ligne = vide';


                    
                    if (strlen($finLigne)>1 && $finLigne != '.' && substr($finLigne,0,1) !=',') $aInserer = $insertion.',';
                    
                    $curseurFin = $curseurFin + strlen($aInserer);
                    $ligne = $debutLigne.$aInserer.$finLigne;
                    echo 'APRES SUBSTITUTION LIGNE DEVIENT |', $ligne,'|</br>';
                    echo 'on a inséré |', $aInserer,'| longueur = ', strlen($aInserer), ' entre |', $debutLigne, '| et $finLigne = |',$finLigne,  '|</br>';

                    //substr_replace ( $ligne , $insertion , $start [, mixed $length ] )
                    //$ligne = $ligne.$insertion;
                    if ($debug2) echo 'replace '.$chaineInitiale.' par '.$insertion.' => '.$ligne.'</br>';

                } 
            }/* fin de foreach $ss */

/*
            if ($debug) echo 'stocke les codes extraits </br>';
            foreach ($listeDesElements as $element){ 
                    $this->edcCodes[]= trim($element);
                    if ($debug) echo 'code = |',trim($element),'| ';
            }
*/



            if ($debug) echo '</br>';

        } /* fin de section */


       return $ligne;
/* --------------------------------------------------------------------------- */
    }


    private function isRomain($aTester){

        $romain = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII','XIII','XIV','XV','XVI','XVII','XVIII','XIX','XX'];
        
        return in_array($aTester,$romain);
    }


    private function extraireLesCodesEDCpresentsDansUneChaine($string){
        
        $EDCpresentsDansLaChaine = [];

        echo '</br> STRING = |',$string,'| </br>';
        for ($i =0; $i< strlen($string) ; $i++) {

            if ( preg_match( '#[A-S]#', $string[$i] ) ){
                
                echo 'dans la ligne |'.$string.'| '.$string[$i].' est peut etre un code EDC </br>';
                $retour = $this->extraireLepremierCodesEDCpresentAPartirDePosition($string, $i);
                echo 'RETOUR = |',$retour[0],'| position = |',$retour[1];
                if ($retour[0] != ''){ 
                    $EDCpresentsDansLaChaine[] = $retour[0];
                    $i = $retour[1];
                    //$i = $i - 1;
                    echo 'on va poursuivre exploration de la chaine |',$string,'| a partir de la position ', $i, '</br>';
                }
            }
        }
        dump ($EDCpresentsDansLaChaine);
        return $EDCpresentsDansLaChaine;
    }


    public function extraireLepremierCodesEDCpresentAPartirDePosition($string, $position){

//echo 'on va extraire un code dans |',$string,'| a partir de la position', $position,'</br>';
        $code = $string[$position];
//echo 'Au début $code =',$code,'</br>';


/**********************************************************************/
        if (strlen ($string)>1){ 
//echo 'On explore les caracters suivants </br>';
            $j = $position + 1;

            


            if ( $j < strlen($string) ){

                if ( preg_match('#[a-z]#', $string[$j]) || preg_match('#[A-Z]#', $string[$j]) ) {
//echo 'en fait ',$code,' n\' est pas un EDC code';
                        return ['',$j];
                }

            }

            while ( $j < strlen($string)) {
//echo 'ICI $string[',$j,'] = |', $string[$j] ,'|</br>';    

                if ($string[$j]== '(') {
                    //echo 'PARENTHESE DETECTEE...';
                    $j = $this->skipParentheses($string, $j);
                    //echo 'on saute directement à la position ',$j,'</br>';
                }



                if (  $string[$j] == ' ' || preg_match('#[0-9]#', $string[$j]) ) { 
                    $code = $code.$string[$j] ;
//echo 'maintenant $code = |',$code,'|</br>';
                } else {
                    //$nouvellePosition = $j - 1;
//echo '<strong>on sort de là avec le code |', $code, '| et une longeur de chaine length = ', strlen ($code),'</strong></br>';
                    return [$code, $j - 1];
                    //break;
                }
                $j++;

            }  ;
                
            $nouvellePosition = $j;

        }
/***************************************************************************/
        else {
//echo 'bout de chaine, on va juste renvoyer', $code, '</br>';


            }
/****************************************************************************/
//echo '<strong>EN BOUT DE CHAINE ; on sort de là avec le code |', $code, '| et une longeur de chaine length = ', strlen ($code),'</strong></br>';
        return [$code, $nouvellePosition];
    }


    
    public function skipParentheses($string, $position) {

//echo 'skipParentheses dans $string = |',$string,'| position = ',$position;
        for ($i = $position ; $i < strlen($string) ; $i++) {

            if ($string[$i] == ')')return $i;
        }
//echo 'pas de fermeture detectée </br>';
        return $i;




    }
}