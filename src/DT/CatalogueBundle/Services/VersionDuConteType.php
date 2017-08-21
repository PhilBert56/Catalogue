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
        //$nouvelleLigne = $this->marquerLesOccurencesDesElementsDuConte($description);

        $this->description[] = $description;
    }



    public function developperUneVersion($conteType){

        $occurencesEDC = $this->occurencesDesElementsDuConte;

        if (count ($occurencesEDC) > 0) {

        foreach ($occurencesEDC as $code) {

          foreach ($conteType->elementsDuConte as $EDC) {
            if ($EDC->section == $code->section && $EDC->codeElementDuConte == $code->codeEDC){
              $this->setDescription($EDC->description);
              break;
            }
          }
       }
  }



/*


      $occurencesEDC = $this->occurencesDesElementsDuConte;
      if (count($occurencesEDC > 0)){


*/

      }




    public function developperUneVersionOLD($conteType){

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
                $listeDesVersions = ' <FONT size="1">Présent dans les versions : ';

                foreach ($conteType->versions as $v){
                    $listeDesVersions = $listeDesVersions.$v->numero.', ';
                }
                $listeDesVersions = $listeDesVersions.'</FONT><BR>';
                return $listeDesVersions;
            }
        }

    }

}
