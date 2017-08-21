<?php

namespace DT\CatalogueBundle\Services;

class ExtracteurDeContesTypes{

  public function listeLesContesTypesContenusDansLaLigne($ligne ,$tableauDesContesType ) {

    $pos = stripos($ligne, 'Types');

    $codeASouligner = [];

    if (!$pos) {
      $pos = stripos($ligne, '*Type');
    }
    $finLigne = substr($ligne, $pos + 6 , grapheme_strlen($ligne) );

    $arr = explode( ' ',$finLigne);
    for ($i = 0; $i < count($arr); $i++) {
      $codeConteType = preg_match('#[0-9][0-9][0-9]#', $arr[$i]);

      if ($codeConteType){

        $delimiters = [',','.','*'];
        $temp = str_replace($delimiters, $delimiters[0], $arr[$i]);
        $code = explode( $delimiters[0] ,$arr[$i] );
        $cd = substr($code[0], 0, 3);
        //echo 'conte type identifié = '.$code[0].'   intval = '.intval($code[0]).'</br>';
        if (intval($cd) < 740) {
          $ctExiste = $this->leConteTypeExiste('T'.$cd,$tableauDesContesType );
          /* vérifier si le conte type existe */
          if ($ctExiste) {
          $codeASouligner[] = $cd;
          }
        }
      }

    }
    foreach ($codeASouligner as $code) {
      $sub1 = "<a href=\"/ConteType/edc/T".$code."\">";
      $sub2 = $code;
      $sub3 = "</a>";
      $sub = $sub1.$sub2.$sub3;
      $ligne = str_replace($code, $sub , $ligne);
    }
    return $ligne;
  }

  public function leConteTypeExiste($ctCode,$tableauDesContesType ){
    foreach ($tableauDesContesType as $ct) {
      if ($ct->ctCode ==  $ctCode) return true;
    }
    return false;
  }
}
