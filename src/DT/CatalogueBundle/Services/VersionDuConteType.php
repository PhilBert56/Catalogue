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
        $longueurSection1 = strlen($section1);
        $section1 = trim($section1);
        /*elimine les espaces en debut et fin de section */

        $section2 = stristr($ligne,':');
        /* ne pas eliminer les : dans la chaine, sinon probleme !*/
        //$section2 = str_replace(':','',$section2);
        $section2 = preg_replace("/:/","",$section2,1);
        /* retourne la sous-chaine de $ligne située après le caractère : */
        $delimiters = [',',';'];
        $temp = str_replace($delimiters, $delimiters[0], $section2);
        $launch = explode($delimiters[0], $temp);
        $sousSections = explode ($delimiters[0], $section2);
        
        $debug = false;
        $debug2 = false;
        $listeDesElements =[];
        $EDC=[];
        $positionsDebut =[];
        $positionsFin =[];
/* --------------------------------------------------------------------------- */

        if($section1 != '' && $this->isRomain($section1)){

            if ($debug) echo '</br></br> examine la ligne = |',$ligne,'|</br>';
            $this->edcSection = $section1;
            if ($debug) echo 'SECTION = ', $section1, '</br>';
            $curseurDebut = $longueurSection1 + 1;
            $curseurFin = strpos($ligne, ':') + 1;

            $EDCpresentsDansLaChaine = [];
            $positionsDebutDesEDCDansLaChaine = [];
            $positionFinDesEDCDansLaChaine = [];
            $parentheseOuverte = false;
            $ignoreSection = false;

            foreach ($sousSections as $ss){

                $curseurFin = $curseurDebut + strlen($ss);
//echo ' </br>ooooooooooooo etude de la sous section |',$ss,'| de la chaine ',$ligne,'  debut = ',$curseurDebut,' fin = ',$curseurFin, '</br>';
                $parentheseOuvrante = strpos($ss, '('); 
                if ($parentheseOuvrante != false) {
//echo 'PARENTHESE : ( DETECTEE dans |',$ss,'| </br>';
                    $parentheseOuverte = true;                   
                }

                $parentheseFermante = strpos($ss,')');
                if ($parentheseFermante != false) {
//echo 'PARENTHESE : ) DETECTEE dans |',$ss,'| </br>';
                    $parentheseOuverte = false;
                    $ignoreSection = false;
                }

                if ($parentheseOuverte)
                { 
                    if ($parentheseOuvrante == false && $parentheseFermante == false){
//echo 'parenthese toujours ouverte avec la chaine ', $ss, '  ON DOIT IGNORER CETTE CHAINE </br>';
                        $ignoreSection = true;
                    } else $ignoreSection = false;


                    //$parentheseFermante = strpos($ss, ')');
                    //if ($parentheseFermante != false) {
                }


                //$chaineInitiale = $ss;
                //$longueurDeLaChaineInitiale = strlen($ss);
                //$curseurDebut = $curseurFin;
//echo '</br>chaîne |',$ss, '| position du premier caractère dans la string = |', $ligne ,'| = ', $curseurDebut ,'</br>';
                
                if ($ignoreSection) {
//echo 'ON  IGNORE LA SECTION ',$ss,'</br>';
                    //$curseurDebut++;
                }


                if (!$ignoreSection) {

                    $retour = $this->extraireLesCodesEDCpresentsDansUneChaine($ss);

                    $deb = $curseurDebut;
                
                    for ($i = 0; $i<count($retour[0]); $i++){
                        $EDC[] = $retour[0][$i];

                        $positionsDebut[] = $deb; 
//echo '</br>DANS LA CHAINE |',$ligne, '| CODE |',$retour[0][$i],'| début = ',$deb,'</br>';
                        $deb = $deb + $retour[2][$i];
                        $positionsFin[] = $deb;
//echo 'DANS LA CHAINE |',$ligne, '| CODE |',$retour[0][$i],'| fin = ',$deb,'</br>';                    
                        $deb = $deb + 1;

                    }
                }


                $curseurDebut = $curseurFin + 1;
               
                } 

                foreach ($EDC as $element){ 
//echo ' ! $edc = ',$element,' </br>';
                    $EDCpresentsDansLaChaine[]=$element;
                    //dump ($EDCpresentsDansLaChaine);
                }

                //dump ($EDCpresentsDansLaChaine);
                /* stockage de la liste des EDC de la version */
                $listeDesEDCParSection = [];
                $listeDesEDCParSection[] = $section1;
                foreach ($EDCpresentsDansLaChaine as $codeEDC){
                    $listeDesEDCParSection[] = $codeEDC ;
                }
                $this->occurencesDesElementsDuConte[]=$listeDesEDCParSection;



                foreach ($positionsDebut as $pos){
                    $positionsDebutDesEDCDansLaChaine[] = $pos;
                }
                foreach ($positionsFin as $pos) {
                    $positionsFinDesEDCDansLaChaine[] = $pos;
                }


//echo 'AVANT substitution : ',$ligne, '</br>';
            if (count($EDCpresentsDansLaChaine)>0){ 
                $ligne = $this->effectuerLesSubstitutions($ligne, $section1, $EDCpresentsDansLaChaine, $positionsDebutDesEDCDansLaChaine, $positionsFinDesEDCDansLaChaine);
        
//echo 'APRES substitution : ', $ligne, '</br>';
            }
        }
        
        return $ligne;
    }




    private function isRomain($aTester){

        $romain = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII','XIII','XIV','XV','XVI','XVII','XVIII','XIX','XX'];
        
        return in_array($aTester,$romain);
    }


    private function extraireLesCodesEDCpresentsDansUneChaine($string){
        
        $EDCpresentsDansLaChaine = [];
        $positionsDebutDesEDC = [];
        $positionsFinDesEDC =[];

//echo '</br> STRING = |',$string,'| </br>';
        for ($i =0; $i< strlen($string) ; $i++) {
//echo 'Dans extraire un code de la chaîne |'.$string.'|, on teste le caractère |'.$string[$i].'| i= '.$i.'|</br>';
           
            if ( preg_match( '#[A-S]#', $string[$i] ) ){
                
//echo 'dans la ligne |'.$string.'| '.$string[$i].'| est peut etre un code EDC </br>';
//echo 'ce caractere est en position '.$i.' dans la chaine |'.$string[$i].'|</br>';
                
                $positionDeDepart = $i;
//echo 'on envoi la chaine |',$string,'| pour analyse </br>';
                $retour = $this->extraireLepremierCodesEDCpresentAPartirDePosition($string, $i);
//echo 'RETOUR = |',$retour[0],'| nouvelle position = |',$retour[1],'|  longueur = ',$retour[2],'</br>';
                //dump ($retour);

                if ($retour[0] != ''){ 
                    $EDCpresentsDansLaChaine[] = $retour[0];
//echo 'au retour de extraction premier code $i = ',$i,'</br>';
                    $i = min( [ ($retour[1] + $retour[2]) , strlen ($string) - 1 ] );
                    
//echo 'MAIS au retour de extraction premier code On SAUTE à la position ',$i,'</br>';
                    //$i = $i - 1;
                    //echo 'on va poursuivre exploration de la chaine |',$string,'| a partir de la position ', $i, '</br>';
                    
//echo '********** STRING = |'.$string.'|   EDC = |'.$retour[0].'| position = |'.$retour[1].'| que on devrait decaler de '.$positionDeDepart.'</br>';
                       //$posDebut = $retour[1];
                       //$posDebut = $posDebut + $positionDeDepart;
                       $posDebut = $positionDeDepart;
                       $posFin = $posDebut + $retour[2] - 1;
//echo 'EDC = |',$retour[0],'| dans la chaine |',$string,'| est en position debut = ',$posDebut,' et fin = ',$posFin,'</br>';
                       
                       $positionsDebutDesEDC[] = $posDebut;
                       $positionsFinDesEDC[] = $posFin;
                    
                }
            }
        }
//echo 'RETOUR extraction de tous les EDC dans la chaine';
        return [$EDCpresentsDansLaChaine, $positionsDebutDesEDC, $positionsFinDesEDC];
    }


    public function extraireLepremierCodesEDCpresentAPartirDePosition($string, $position){
/* reçoit la chaine $string et en extrait le premier EDC trouvé, 
avec sa position dans $string et sa longueur */

//echo 'on va extraire un code dans |',$string,'| a partir de la position', $position,'</br>';
        $code = $string[$position];
        $longueur = 1;
//echo 'Au début $code =',$code,'</br>';
        $nouvellePosition = 0;
        //$parentheseOuverte = false;

/**********************************************************************/
        

        if (strlen ($string)>1){ 

            $j = $position + 1;

            if ( $j < strlen($string) ){
/*
                if ( preg_match('#[a-z]#', $string[$j]) ||preg_match('#[A-Z]#', $string[$j]) ) {
                // $code n' est pas un EDC 
                    return ['',$j,0];
                }
*/
                if (preg_match('#[a-zA-Z-àáâãäåâòóôõöøèéêëçìíîïùúûüÿñ]#', $string[$j] ) ) {
                // $code n' est pas un EDC 
                    return ['',$j,0];
                }


            }

            while ( $j < strlen($string)) {

                if ($string[$j]== '(') {
                    //PARENTHESE DETECTEE;
                    $j = $this->skipParentheseOuvrante($string, $j);
                    //on saute directement à la position $j;
                    //$parentheseOuvrante = true;
                    /*
                    $parentheseFermante = strpos($string, ')');
                    if ($parentheseFermante == false) { 
                        $parentheseOuverte = true; 
                    } else $parentheseOuverte = false;*/
                }

                if ($string[$j]== ')') {

//echo 'on a detecte une ) a la position ', $j, ' dans la chaine $string = ', $string, '</br>';
                    
                    $parentheseOuvrante = strpos($string, '('); 
                    if ($parentheseOuvrante == false){ 
                        $j = $this->skipParentheseFermante($string, $j);
                    //$j = min( $j, strlen($string) - 1 );
//echo 'RETOUR DE SKIP ) AVEC $j = ',$j, '</br>';
                        if ($j == strlen($string) ) return ['', $j+1 , 1];
                    }
                }

                if (  $string[$j] == ' ' || preg_match('#[0-9]#', $string[$j]) ) { 
                    $code = $code.$string[$j] ;
                    $longueur++;
                } else {
                    //$nouvellePosition = $j - 1;
//echo '<strong>on sort de là avec le code |', $code, '| et une longeur de chaine length = ', strlen ($code),' position = ', $j - strlen($code),'</strong></br>';
                    return [$code, $j - 1 , $longueur];
                    //break;
                }
                $j++;

            }  ;
                
            $nouvellePosition = $j;

        } else {
//echo 'CODE AVEC UN SEUL CARACTERE |',$code,'|</br>';
            return [$code, 1 , 1];
        }

//echo '<strong>EN BOUT DE CHAINE ; on sort de là avec le code |', $code, '| et une longeur de chaine length = ', strlen ($code),' position = ', $j - strlen($code),'</strong></br>';
        return [$code, $nouvellePosition , $longueur];
    }


    
    public function skipParentheseOuvrante($string, $position) {

//echo 'skipParentheses dans $string = |',$string,'| position = ',$position;
        for ($i = $position ; $i < strlen($string) ; $i++) {

            if ($string[$i] == ')')return $i;
        }
//echo 'pas de fermeture detectée </br>';
        return $i - 1;
    }

    public function skipParentheseFermante($string, $position) {

//echo 'skipParentheses dans $string = |',$string,'| position = ',$position, '</br>';
        for ($i = 0 ; $i < strlen($string) ; $i++) {

            if ($string[$i] == ')') {
//echo 'on a detecte ) en position ',$i,' la chaine fait l = ', strlen($string), '</br>';
//if ($i == strlen($string) -1 )echo 'FIN DE LIGNE plus rien à extraire </br>';
                return $i + 1;
            }
        }
//echo 'pas de fermeture detectée PAS NORMAL !!!! </br>';
        return $i - 1;
    }

    







    public function effectuerLesSubstitutions($ligne, $section, $EDC, $positionsDebut, $positionsFin){

        $decalage = 1;

        for ( $i = 0; $i < count($EDC) ; $i++ ) {

            $positionDebut = $positionsDebut[$i] + $decalage;
            $positionFin = $positionsFin[$i] + $decalage; 
            $insertion = " <a href=\"/ConteType/elementDuConte/".$this->ctCode."/".$section."/".$EDC[$i]."\">".$EDC[$i]."</a>";
            $ligne = $this->substituerUneChaineParUneAutre($ligne,$positionDebut,$positionFin,$insertion);
            $decalage = $decalage + strlen($insertion) - ($positionFin - $positionDebut);

        }
        return $ligne;

    }

    public function substituerUneChaineParUneAutre($ligne, $positionDebut, $positionFin, $aInserer) {

        $debutLigne = substr($ligne,0, $positionDebut);
//echo '$debutLigne = |',$debutLigne,'| position debut = ',$positionDebut,'</br>';

        $finLigne = substr($ligne, $positionFin , strlen($ligne) );
//echo '$finLigne = |',$finLigne,'| position fin = ',$positionFin,'</br>';

//echo 'chaine a inserer = |',$aInserer,'|</br>';

//echo 'APRES SUBSTITUTION : |', $debutLigne.$aInserer.$finLigne,'|</br>';
        return $debutLigne.$aInserer.$finLigne;
    }



    public function developperUneVersion($conteType){

        $lignesDeveloppementVersion = [];

        //$conteType = $this->rechercherLeConteType($this->ctCode);

        foreach ($this->occurencesDesElementsDuConte as $occurence){

            $section = $occurence [0];

            $lignesDeveloppementVersion[] = $this->rechercherLeDescriptifDeLaSection($conteType,$section);

            for ($i = 1 ; $i < count($occurence) ; $i++) {

                $lignesDeveloppementVersion[] = $this->rechercherLeDescriptifElementDuConte($conteType, $section, $occurence[$i] );

                $lignesDeveloppementVersion[] = $this->rechercherLaListeDesVersionsElementDuConte($conteType,$section,$occurence[$i]  );
            }

        }

        return $lignesDeveloppementVersion;
    }


/*
    public function rechercherLeConteType($ctCode) {

        $session = $this->get('session');
        $tableauDesContesType = $session->get('tableauDesContesType');

        foreach($tableauDesContesType as $conteType){
            if ($ctCode == $conteType->ctCode ) {
                return $conteType;
            } 
        }
        
        return 'not found';
    }
*/

    public function rechercherLeDescriptifDeLaSection($conteType, $section){
        $description = '';
        foreach($conteType->elementsDuConte as $edc){
            if ($edc->section == $section.'.') {
                $description[] = '</br></br>';
                $description = '<strong>'.$edc->description.'</strong>';
                return $description;
            }
        }
    }


    public function rechercherLeDescriptifElementDuConte($conteType, $section, $codeEDC){
        $description = '';
        foreach($conteType->elementsDuConte as $edc){
            if ($edc->section == $section && $edc->codeElementDuConte == $codeEDC) {
                $description = $edc->description;
                return $description;
            } 
        }

    }

    public function rechercherLaListeDesVersionsElementDuConte($conteType, $section, $codeEDC) {

        foreach($conteType->elementsDuConte as $edc){

            if ($edc->section == $section && $edc->codeElementDuConte == $codeEDC) {
                $listeDesVersions = 'Présent dans les versions : ';
                
                foreach ($conteType->versions as $v){ 
                    $listeDesVersions = $listeDesVersions.$v->numero.', ';
                }
                return $listeDesVersions;
            }
        }

    }

}