<?php

namespace DT\CatalogueBundle\Services;

class VersionDuConteType
{
    public $ctCode;
    public $numero;
    public $reference;
    public $description;
    public $developpements;
    public $comparatifAvecAutreVersions;
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

    public function trouverLeFichierSourceAssocie(ConteType $conteType){

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

        $this->description[] = $description;
    }

    public function setDeveloppements($developpements){
    /*  (même si l'attribut descriptio est public,
        on ne peut pas le modifier de l'extérieur sans passer par un setteur) */


        $this->developpements[] = $developpements;
    }


    public function developperUneVersion(ConteType $conteType){

        $occurencesEDC = $this->occurencesDesElementsDuConte;

        if (count ($occurencesEDC) > 0) {

        foreach ($occurencesEDC as $code) {

          foreach ($conteType->elementsDuConte as $EDC) {
            if ($EDC->section == $code->section && $EDC->codeElementDuConte == $code->codeEDC){
              $this->setDeveloppements($EDC->description);

              if ($EDC->hasVersions) {
                $listeVersions = '<font size = 1> Présent dans les versions : ';

                foreach ($EDC->listeDesVersions as $versionADecrire) {
                  $numero = $versionADecrire->numero;
                  $lien = "<a href =\"/ConteType/version/".$conteType->ctCode."/".$numero."\">";
                  $listeVersions = $listeVersions.$lien.$numero.' ; </a>';
                }

                $listeVersions = $listeVersions.'</font></br>';
                $this->setDeveloppements($listeVersions);
                //dump($this);
              }
              break;
            }
          }
       }
       $this->etablirLeTableauComparatifAvecLesAutresVersions($conteType);
    }
  }



    public function rechercherLeDescriptifDeLaSection(ConteType $conteType, $section){
        $description = '';
        foreach($conteType->elementsDuConte as $edc){
            if ($edc->section == $section.'.') {
                $description[] = '</br></br>';
                $description = '<strong>'.$edc->description.'</strong>';
                return $description;
            }
        }
    }


    public function rechercherLeDescriptifElementDuConte(ConteType $conteType, $section, $codeEDC){
        $description = '';
        foreach($conteType->elementsDuConte as $edc){
            if ($edc->section == $section && $edc->codeElementDuConte == $codeEDC) {
                $description = $edc->description;
                return $description;
            }
        }

    }

    public function rechercherLaListeDesVersionsElementDuConte(ConteType $conteType, $section, $codeEDC) {

        foreach($conteType->elementsDuConte as $edc){

            if ($edc->section == $section && $edc->codeElementDuConte == $codeEDC) {
                $listeDesVersions = ' <FONT size="1">Présent dans les versions : ';

                foreach ($conteType->versions as $v){
                    $listeDesVersions = $listeDesVersions.$v->numero.', ';
                }
                $listeDesVersions = $listeDesVersions.'</FONT><BR>';
                return $listeDesVersions;
            }
        }

    }



    public function etablirLeTableauComparatifAvecLesAutresVersions(ConteType $conteType){

      $this->comparatifAvecAutreVersions = [];
      $tableauDesComparaisons = [];
      $tableDesEdcDeCetteVersion = $this->occurencesDesElementsDuConte ;

      if (count($tableDesEdcDeCetteVersion) == 0) return $tableauDesComparaisons;

      foreach ($conteType->versions as $version) {
        $ligne=[];
        $tableDesEdcVersionATester = $version->occurencesDesElementsDuConte;

        if ( count($tableDesEdcVersionATester)>0 && ($version->numero !== $this->numero) ){
          $ligne[]=$version->numero;

          foreach ($tableDesEdcVersionATester as $edcAutreVersion) {

            foreach($tableDesEdcDeCetteVersion as $edcATester){
                if ($edcAutreVersion->section == $edcATester->section && $edcAutreVersion->codeEDC == $edcATester->codeEDC){
                  $ligne[] = $edcATester;
                  //echo 'ajout de '.$tableDesEdcVersionATester->numeroVersion.'  edc = '.$tableDesEdcVersionATester->section.':'.$tableDesEdcVersionATester->codeEDC.'</br>';

                }
            }

          }
          $tableauDesComparaisons[] = $ligne;
        }

      }

      foreach ($tableauDesComparaisons as $lng) {

        $l = ' Eléments commun avec la version : ';

        $link = '<a href="/ConteType/version/'.$conteType->ctCode.'/'.$lng[0].'/">';
        $link = $link.$lng[0].'</a> : |';
        $l = $l.$link;

        for($i = 1; $i<count($lng); $i++){
          $link = '<a href="/ConteType/elementDuConte/'.$conteType->ctCode.'/'.$lng[$i]->section.'/'.$lng[$i]->codeEDC.'">';
          $link = $link.$lng[$i]->section.':'.$lng[$i]->codeEDC.'</a>|';
          $l = $l.$link;
        }


        $this->comparatifAvecAutreVersions[] = $l;
      }

    }

}
