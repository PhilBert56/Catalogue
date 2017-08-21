<?php

namespace DT\CatalogueBundle\Services;
use DT\CatalogueBundle\ObjetsUtilitaires\CodeDansUneChaine;


class ExtracteurDeCodes
{

  public function listeLesCodesContenusDansLaLigne($numeroLigne, $ligne, $conteTypeCode, $versionNumber, $sectionEnCours) {

//echo 'reçu ligne = |',$ligne,'| pour le conte type '.$conteTypeCode.'</br>';
//echo 'décomposition en '.count($ligne).'</br>';
    if($sectionEnCours == '')return [];

    $codesArray = [];

    $arr = explode(' ',$ligne);
    $parentheseOuverte = false;
    $curseurDebut = -1;
    $curseurFin = -1;

    $chaineTraitee = '';
    //$ligne = utf8_encode($ligne);
    for ($i = 0; $i < count($arr); $i++) {

//echo '</br>$arr ['.$i.'] = |'.$arr[$i].'|</br>';
      $sectionEDC ='';
      $chaineTraitee = $chaineTraitee.$arr[$i];
      if ($i < (count($arr) - 1))   {
          /* on rajoute l'espace retiré lors de l'explode */
          $chaineTraitee = $chaineTraitee.' ';
          $arr[$i] = $arr[$i].' ';
          //$curseurFin = $curseurFin + 1 ;
      }
      $curseurDebut = $curseurFin + 1;
      $curseurFin = $curseurDebut + (grapheme_strlen(utf8_encode($arr[$i])) - 1);
//echo 'debut = '.$curseurDebut.' et fin = '.$curseurFin.'</br>';

      //$chaineTraitee = $chaineTraitee.' ';
      $debutSection = false;
      if( $this->isRomain($arr[$i])){
        $debutSection = true;
//echo 'DEBUT DE SECTION avec section = '.$arr[$i].'</br>';
        $deuxPoints = strpos ($arr[$i] , ':');
        if (!$deuxPoints) {
          $sectionEDC = trim($arr[$i]);
        }else {
//echo ' : detecte </br>';
          $section = explode(':', $arr[$i] );
          $sectionEDC = $section[0];
        }

      } else {
        if ( $this->isRomain($sectionEnCours) ){
//echo 'on poursuit une section déjà ouverte';
          $sectionEDC = $sectionEnCours;
        } else $sectionEDC = '';

      }


      if (!$debutSection) {
        if (substr($arr[$i],0,1) == '(' ){
//echo 'On Ouvre une parenthèse </br>';
          $parentheseOuverte = true;
          $string = '';
        }

        if ($parentheseOuverte) {

          $s = stristr ( $arr[$i] ,  ')',  true);
          if ($s) {
//echo 'fin de parentese avec $s = '.$s.'</br>';
            $string = $string.$arr[$i];
//echo '$s !!!!!! ON FERME AVEC |'.$string.'|</br>';
            $parentheseOuverte = false;
          } else $string = $string.$arr[$i].' ';
        }

        $lettreSimple = preg_match('#[A-L][0-9]#', $arr[$i]);
        $lettreSuivieDeChiffres = preg_match('#[A-L]#', $arr[$i]);
        $code = ($lettreSimple || $lettreSuivieDeChiffres);
        if (!$parentheseOuverte && $code) {
//echo 'On a trouvé potentiellement un code  </br>';
          $codes = $this->gererLesDelimiteursDUneSousChaine ($arr[$i], $curseurDebut, $conteTypeCode, $versionNumber, $sectionEDC, $numeroLigne);

          foreach ($codes as $cd) {
            $codesArray[] = $cd;
          }
        }

      }
    }

//echo 'CHAINE = |'.$chaineTraitee.'|</br>';
//echo 'longueur de chaine = '.iconv_strlen($chaineTraitee).'|</br>';

//echo 'CHAINE = |'.$ligne.'|</br>';
//echo 'longueur de chaine = '.iconv_strlen($ligne).'|</br>';
//echo 'curseur Fin ='.$curseurFin.'</br>';
//echo 'curseur Debut ='.$curseurDebut.'</br>';

    return $codesArray;
  }



  private function isRomain($aTester){

      $romain = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII','XIII','XIV','XV','XVI','XVII','XVIII','XIX','XX'];
      $delimiters = [':', ' '];
      $temp = str_replace($delimiters, $delimiters[0], $aTester);
      $arr = explode( $delimiters[0],$temp);
      $aTesterDebut = $arr[0];
//echo 'on teste si section avec |'.$aTesterDebut.'| resultat du test :'.in_array($aTesterDebut,$romain).'</br>';
      return in_array($aTesterDebut,$romain);
  }


  private function gererLesDelimiteursDUneSousChaine($string, $curseurDebut, $conteTypeCode, $versionNumber, $sectionEDC, $numeroDeLaLigne){

    $codesArray = [];

    $delimiters = [',', ';', '.'];
    $temp = str_replace($delimiters, $delimiters[0], $string);
    $arr = explode( $delimiters[0],$temp);


    for ($i = 0; $i < count($arr); $i++) {
//echo 'Dans gerer séparateurs, on va examiner les fragments suivants : ';
//echo '$arr ['.$i.'] = |'.$arr[$i].'|</br>';
//echo 'position de départ de la chaine '.$arr[$i].' = '. $curseurDebut.'</br>';
      $curseurFin = $curseurDebut + grapheme_strlen(utf8_encode($arr[$i])) + 1;


      $lettreSimple = preg_match('#[A-L]#', $arr[$i]);
//echo '  lettre simple = '.$lettreSimple;

      $lettreSuivieDeChiffres = preg_match('#[A-L][0-9]#', $arr[$i]);
//echo '  lettre suivie de chiffres = '.$lettreSuivieDeChiffres;

      $lettreSuivieDeLettres = preg_match('#[A-L][a-z-áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœáàâäãåçéèêëíìîïñóòôöõúùûüýÿæœ]#', $arr[$i]);
//echo '  lettre suivie de lettres = '.$lettreSuivieDeLettres;

      $chaineTropLongue = (grapheme_strlen(utf8_encode($arr[$i])) > 4);
//echo '  chaine trop longue = '.$chaineTropLongue.' pour la chaîne '.$arr[$i].' de longueur'.strlen($arr[$i]).'</br>';

      $isCode = ($lettreSimple || $lettreSuivieDeChiffres) && !$lettreSuivieDeLettres && !$chaineTropLongue;
      if ($isCode) {
        $code = trim($arr[$i]);
//echo 'ON A ISOLE LE CODE = |'.$code.'|</br>';
//echo 'pour conte type = '.$conteTypeCode.' et section = '.$sectionEDC;
//echo 'POSITION Du Code dans la chaine = entre la position '.$curseurDebut.' et la position '.$curseurFin.'</br>';
//echo 'POSITION Des caracteres du code dans la chaine = entre la position '.$curseurDebut.' et la position '.( $curseurDebut + iconv_strlen($code) -1 ).'</br>';

        $newCode = new CodeDansUneChaine();
        $newCode->numeroVersion = $versionNumber;
//echo 'ici version numéro = '.$newCode->numeroVersion.'</br>';
        $newCode->conteTypeCode = $conteTypeCode;
        $newCode->section = $sectionEDC;
        $newCode->codeEDC = $code;
        $newCode->positionDebutDansLaChaine = $curseurDebut;
        $newCode->positionFinDansLaChaine = $curseurDebut + grapheme_strlen(utf8_encode($code)) -1;
        $newCode->numeroDeLaLigne = $numeroDeLaLigne;

        $codesArray[] = $newCode;

      }
      $curseurDebut = $curseurFin;

    }

    return $codesArray;

  }




  public function effectuerLesSubstitutions($ligne, $EDC){

      $decalage = 0;

      for ( $i = 0; $i < count($EDC) ; $i++ ) {

          $positionDebut = $EDC[$i]->positionDebutDansLaChaine + $decalage;
          $positionFin = $EDC[$i]->positionFinDansLaChaine + $decalage;
          $insertion = " <a href=\"/ConteType/elementDuConte/".$EDC[$i]->conteTypeCode."/".$EDC[$i]->section."/".$EDC[$i]->codeEDC."\">".$EDC[$i]->codeEDC."</a>";
          $ligne = $this->substituerUneChaineParUneAutre($ligne,$positionDebut,$positionFin,$insertion);
          $decalage = $decalage + grapheme_strlen(utf8_encode($insertion)) - ($positionFin - $positionDebut) - 1;

      }
//echo $ligne.'</br>';
      return $ligne;

  }

  public function substituerUneChaineParUneAutre($ligne, $positionDebut, $positionFin, $aInserer) {

      $debutLigne = substr($ligne,0, $positionDebut);
//echo '$debutLigne = |',$debutLigne,'| position debut = ',$positionDebut,'</br>';

      $finLigne = substr($ligne, $positionFin + 1 , grapheme_strlen($ligne) );
//echo '$finLigne = |',$finLigne,'| position fin = ',$positionFin,'</br>';

//echo 'chaine a inserer = |',$aInserer,'|</br>';

//echo 'APRES SUBSTITUTION : |', $debutLigne.$aInserer.$finLigne,'|</br>';
      return $debutLigne.$aInserer.$finLigne;
  }


  public function laLigneEstUnDebutDeSection($ligne) {

    $debutSection = false;

    $deuxPoints = strpos ($ligne , ':');
    if (!$deuxPoints) return '';

    $sectionArray = explode(':', $ligne );
    $section = trim($sectionArray[0]);
    if( $this->isRomain($section) ){
      $debutSection = true;
//echo 'DEBUT DE SECTION avec section = '.$section.'</br>';
      return $section;
    }


  }


}
