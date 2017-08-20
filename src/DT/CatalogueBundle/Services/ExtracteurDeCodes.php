<?php

namespace DT\CatalogueBundle\Services;
use DT\CatalogueBundle\ObjetsUtilitaires\CodeDansUneChaine;


class ExtracteurDeCodes
{


  public function listeLesCodesContenusDansLaLigne($ligne, $conteTypeCode) {

    echo 'reçu ligne = |',$ligne,'| pour le conte type '.$conteTypeCode.'</br>';
    echo 'décomposition en '.count($ligne).'</br>';

    $codesArray = [];

    $arr = explode( ' ',$ligne);
    $parentheseOuverte = false;
    $curseurDebut = -1;
    $curseurFin = -1;

    $chaineTraitee = '';

    for ($i = 0; $i < count($arr); $i++) {

      echo '</br>$arr ['.$i.'] = |'.$arr[$i].'|</br>';

      $chaineTraitee = $chaineTraitee.$arr[$i];
      if ($i < (count($arr) - 1))   {
          /* on rajoute l'espace retiré lors de l'explode */
          $chaineTraitee = $chaineTraitee.' ';
          $arr[$i] = $arr[$i].' ';
          //$curseurFin = $curseurFin + 1 ;
      }
      $curseurDebut = $curseurFin + 1;
      $curseurFin = $curseurDebut + (iconv_strlen($arr[$i]) - 1);
      echo 'debut = '.$curseurDebut.' et fin = '.$curseurFin.'</br>';

      //$chaineTraitee = $chaineTraitee.' ';
      $debutSection = false;
      if( $this->isRomain($arr[$i])){
        $debutSection = true;
        echo 'DEBUT DE SECTION avec section = '.$arr[$i].'</br>';
        $deuxPoints = strpos ($arr[$i] , ':');
        if (!$deuxPoints) {
          $sectionEDC = trim($arr[$i]);
        }else {
          echo ' : detecte </br>';
          $section = explode(':', $arr[$i] );
          $sectionEDC = $section[0];
        }

      }

      if (!$debutSection) {
        if (substr($arr[$i],0,1) == '(' ){
          echo 'On Ouvre une parenthèse </br>';
          $parentheseOuverte = true;
          $string = '';
        } /*else {
        $s = stristr ( $arr[$i] ,  ')',  true);
        if ($s) {
          $string = $string.$arr[$i];
          echo '$s !!!!!! ON FERME AVEC |'.$string.'|</br>';

          } elseif ($parentheseOuverte) {
            $string = $string.$arr[$i].' ';
          }
      }*/
        if ($parentheseOuverte) {

          $s = stristr ( $arr[$i] ,  ')',  true);
          if ($s) {
            echo 'fin de parentese avec $s = '.$s.'</br>';
            $string = $string.$arr[$i];
            echo '$s !!!!!! ON FERME AVEC |'.$string.'|</br>';
            $parentheseOuverte = false;
          } else $string = $string.$arr[$i].' ';
        }

        $lettreSimple = preg_match('#[A-Z][0-9]#', $arr[$i]);
        $lettreSuivieDeChiffres = preg_match('#[A-Z]#', $arr[$i]);
        $code = ($lettreSimple || $lettreSuivieDeChiffres);
        if (!$parentheseOuverte && $code) {
          echo 'On a trouvé potentiellement un code  </br>';
          $codes = $this->gererLesDelimiteursDUneSousChaine ($arr[$i], $curseurDebut, $conteTypeCode, $sectionEDC);

          foreach ($codes as $cd) {
            $codesArray[] = $cd;
          }
        }

      }
    }

    echo 'CHAINE = |'.$chaineTraitee.'|</br>';
    echo 'longueur de chaine = '.iconv_strlen($chaineTraitee).'|</br>';

    echo 'CHAINE = |'.$ligne.'|</br>';
    echo 'longueur de chaine = '.iconv_strlen($ligne).'|</br>';
    echo 'curseur Fin ='.$curseurFin.'</br>';
    echo 'curseur Debut ='.$curseurDebut.'</br>';

    dump ($codesArray);
  }



  private function isRomain($aTester){

      $romain = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII','XIII','XIV','XV','XVI','XVII','XVIII','XIX','XX'];
      $delimiters = [':', ' '];
      $temp = str_replace($delimiters, $delimiters[0], $aTester);
      $arr = explode( $delimiters[0],$temp);
      $aTesterDebut = $arr[0];
      echo 'on teste si section avec |'.$aTesterDebut.'| resultat du test :'.in_array($aTesterDebut,$romain).'</br>';
      return in_array($aTesterDebut,$romain);
  }




  private function gererLesDelimiteursDUneSousChaine($string, $curseurDebut, $conteTypeCode, $sectionEDC){

    $codesArray = [];

    $delimiters = [',', ';', '.'];
    $temp = str_replace($delimiters, $delimiters[0], $string);
    $arr = explode( $delimiters[0],$temp);


    for ($i = 0; $i < count($arr); $i++) {
      echo 'Dans gerer séparateurs, on va examiner les fragments suivants : ';
      echo '$arr ['.$i.'] = |'.$arr[$i].'|</br>';
      echo 'position de départ de la chaine '.$arr[$i].' = '. $curseurDebut.'</br>';
      $curseurFin = $curseurDebut + strlen($arr[$i]) + 1;


      $lettreSimple = preg_match('#[A-Z]#', $arr[$i]);
      echo '  lettre simple = '.$lettreSimple;

      $lettreSuivieDeChiffres = preg_match('#[A-Z][0-9]#', $arr[$i]);
      echo '  lettre suivie de chiffres = '.$lettreSuivieDeChiffres;

      $lettreSuivieDeLettres = preg_match('#[A-Z][a-z-áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœáàâäãåçéèêëíìîïñóòôöõúùûüýÿæœ]#', $arr[$i]);
      echo '  lettre suivie de lettres = '.$lettreSuivieDeLettres;

      $chaineTropLongue = (strlen($arr[$i]) > 3);
      echo '  chaine trop longue = '.$chaineTropLongue.' pour la chaîne '.$arr[$i].' de longueur'.strlen($arr[$i]).'</br>';

      $isCode = ($lettreSimple || $lettreSuivieDeChiffres) && !$lettreSuivieDeLettres && !$chaineTropLongue;
      if ($isCode) {
        $code = trim($arr[$i]);
        echo 'ON A ISOLE LE CODE = |'.$code.'|</br>';
        echo 'pour conte type = '.$conteTypeCode.' et section = '.$sectionEDC;
        echo 'POSITION Du Code dans la chaine = entre la position '.$curseurDebut.' et la position '.$curseurFin.'</br>';
        echo 'POSITION Des caracteres du code dans la chaine = entre la position '.$curseurDebut.' et la position '.( $curseurDebut + iconv_strlen($code) -1 ).'</br>';

        $newCode = new CodeDansUneChaine();
        $newCode->conteTypeCode = $conteTypeCode;
        $newCode->section = $sectionEDC;
        $newCode->codeEDC = $code;
        $newCode->positionDebutDansLaChaine = $curseurDebut;
        $newCode->positionFinDansLaChaine = $curseurDebut + iconv_strlen($code) -1;

        $codesArray[] = $newCode;

      }
      $curseurDebut = $curseurFin;

    }

    return $codesArray;



  }


}
