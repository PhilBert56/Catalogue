<?php

namespace DT\CatalogueBundle\Services;
use DT\CatalogueBundle\Services\VersionDuConteType;
use DT\CatalogueBundle\Services\ExtracteurDeCodes;
class ConteType
{
    public $ctCode ;
    public $titre;
    public $isDefined;

    public $fichierDesElementsDuConte;

    public $fichierDesVersions;
    public $hasVersions = false;

    //public $fichierDesSources;
    public $pathDesSources;

    public $bonusFile ='';
    public $hasBonus = false;

    public $fichierScan ='';
    public $hasScanVersion = false;

    public $elementsDuConte=[];
    public $occurencesDesElementsDuConteType=[];
    public $versions=[];

    public $fichierDesMotifs ='';
    public $hasFichierDesMotifs = false;
    public $motifsDuConte =[];

    public function __construct($description)
    {
        $this->isDefined = false;
        $this->ctCode = (explode ("-",$description,2)[0]);
        $this->ctCode = rtrim($this->ctCode);
        $this->titre = (explode ("-",$description,2)[1]);

        $this->fichierDesElementsDuConte = '..\src\DT\DTData\A'.$this->ctCode.'\DT_A'.$this->ctCode.'_EDC.txt';


        /* Fichier des Versions = les versions référencées et analysée par le catalogue */
        $this->fichierDesVersions = '..\src\DT\DTData\A'.$this->ctCode.'\DT_A'.$this->ctCode.'_Liste_des_Versions.txt';
        if(file_exists ($this->fichierDesVersions))$this->hasVersions = true;

        /* Fichier des noms des fichiers pdf */
        $this->fichierDesSources =  '..\src\DT\DTData\A'.$this->ctCode.'\DT_A'.$this->ctCode.'_Fichier_des_Versions.txt';

        $this->pathDesSources = '\pdf\DT_A'.$this->ctCode.'_Versions\\';

        $this->fichierScan =  '..\pdf\DT_A'.$this->ctCode.'_Versions\CatalogueDT-'.$this->ctCode.'.pdf';
        //$this->hasScanVersion = false;
        //echo 'test si ',$this->fichierScan ,' existe ? ';
        $this->hasScanVersion = true;
        //if(file_exists ($this->fichierScan))

        $this->fichierDesMotifs =  '..\src\DT\DTData\A'.$this->ctCode.'\DT_A'.$this->ctCode.'_Motif_Index.txt';
        //if(file_exists ($this->fichierDesMotifs))
        $this->hasFichierDesMotifs = true;

        $this->hasBonus = false;
        $this->setBonus();

        dump($this);
    }


    public function genererLesInformationsDuConteType($extracteurDeCodes)
    {
        if ($this->isDefined)return;

        //$this->genererLaListeDesVersions();
        $this->genererLaListeDesVersions($extracteurDeCodes);


        $this->genererLaListeDesElementsDuConte($extracteurDeCodes);
        $this->associerElementsDuConteEtVersions();
//echo 'apelle motifs ';

        $this->genererLaListeDesMotifs();
        //$this->bonus();
        $this->isDefined = true;

    }


    public function genererLaListeDesVersions($extracteurDeCodes){

        if(file_exists($this->fichierDesVersions)){

            $lines = file($this->fichierDesVersions);

            $nouvelleSectionOuverte = false;

            foreach ($lines as $lineNumber => $lineContent){

               if( grapheme_strlen($lines[$lineNumber])==0  ){
                 $nouvelleSectionOuverte = false;
                 $lastSection = '';
               }

               $elements = explode (".",$lines[$lineNumber],2);

               if ( is_numeric ($elements[0])) {
                 /* on commence la description d'une nouvelle version */
                    $version = new VersionDuConteType($this->ctCode);
//echo 'nouvelle version numero '.$elements[0].'</br>';
                    $this->hasVersion = true;
                    $version->numero = $elements[0];
                    $version->reference = utf8_encode ($elements[1]);
                    $this->versions[] = $version;
                    $version->trouverLeFichierSourceAssocie($this);
                    //$version->description[] = utf8_encode ($lines[$lineNumber]);
                } else {

                    $ligne = utf8_encode ($lines[$lineNumber]);

                    //$occurencesEDC = new OccurencesEDC ($version->numero,$ligne) ;
                    //$extracteurDeCodes = $extracteurDeCodes->get('dt_catalogue.listeLesCodesContenusDansLaLigne');
                    //dump ($extracteurDeCodes);die();

                    $section = $extracteurDeCodes->laLigneEstUnDebutDeSection($ligne);
                    if ($section !='') {
                      $nouvelleSectionOuverte = true;
                      $lastSection = $section;
                    }
                    else {
                      if($nouvelleSectionOuverte) {
                        $section = $lastSection;
                      }
                        else $section ='';
                    }
                    //$ligne = utf8_encode ( $lines[$lineNumber] ) ;
                    $listeDesCodes = $extracteurDeCodes->listeLesCodesContenusDansLaLigne($lineNumber, $ligne, $this->ctCode, $version->numero, $section);

                    foreach ($listeDesCodes as $code ){
                      $version->occurencesDesElementsDuConte[] = $code;
                    }

                    //dump ($listeDeCodes);

                    /* ancienne version
                    if (count($occurencesEDC)>0){
                        $this->occurencesDesElementsDuConteType[] = $occurencesEDC;
                    }*/
                    /*
                    if (count($listeDesCodes)>0){
                        $this->occurencesDesElementsDuConteType[] = $listeDesCodes;
                      }*/
                    //$ligne = utf8_encode ( $lines[$lineNumber] ) ;


                    //$version->setDescription ($ligne);
                    if (count ($listeDesCodes) > 0){
                      //dump($listeDesCodes);
                      $ligne = $extracteurDeCodes->effectuerLesSubstitutions($ligne,$listeDesCodes);
                    }
                    $version->description[] = $ligne;
                }
            }
        }
        //dump($this->versions);

    }

    public function genererLaListeDesElementsduConte(){
        if (!file_exists($this->fichierDesElementsDuConte) ){
            echo 'FILE :$this->fichierDesElementsDuConte inexistant';
            return;
        }
        $lines = file($this->fichierDesElementsDuConte);
        $edcTab = [];



        foreach ($lines as $lineNumber => $lineContent){
            $edct = new ElementDuConteType($this->ctCode);
            $edct->ctCode = $this->ctCode;
            $edct->setDescription($lines[$lineNumber]);

            /* recherche du numero de section en chiffres romains */

            $code = explode('-',$lines[$lineNumber]);
            $code = str_replace('[','',$code);
            $code = str_replace(']','',$code);
            $section = explode(' ',$code[0]) ;
            $sectionChiffreRomain = $section[0];
            $edct->section = trim($sectionChiffreRomain);

            /* si la section se termine par un . ce n'est pas un edc mais un titre de section */
            if ( strpos($edct->section , '.')) {
                $edct->isHeader = true;
            } else {
                if ( count($section) > 1     ) {
                    for ($i = 1; $i<count($section); $i++){
                        if ($section[$i] ==':') break;
                        $edct->codeElementDuConte = $edct->codeElementDuConte.$section[$i];
                    }
                }
            }

            $edcTab[] = $edct;
        }
        $this->elementsDuConte = $edcTab;
    }



    public function associerElementsDuConteEtVersions(){

      foreach ($this->versions as $version) {

        $occurencesEDC = $version->occurencesDesElementsDuConte;

        foreach ($occurencesEDC as $code) {
          $edc = $this->rechercherUnEDC($code->section, $code->codeEDC);
          if ($edc != null){

            echo $edc->section.' '.$edc->codeElementDuConte.'  '.$edc->listeDesVersions.'</br>';

            if ($edc->listeDesVersions = ''){
              $edc->listeDesVersions = $version->numero;
            } else {
              $edc->listeDesVersions = $edc->listeDesVersions.' ; '.$version->numero;
              echo $edc->listeDesVersions.'</br>';

            }
          }
        }
      }

      dump ($this);

    }



    public function rechercherUnEDC ($section, $codeEDC){

echo 'recherche '.$section.' '.$codeEDC.'</br>';

      foreach ($this->elementsDuConte as $EDC) {
        if ($EDC->section == $section && $EDC->codeElementDuConte == $codeEDC){
          return $EDC;
        }
      }
      return null;
    }









    public function setBonus(){

        //echo 'BONUS ';
        //$path = '..\DTData\A'.$this->ctCode;


        //$this->bonusFile = '..\src\DT\CatalogueBundle\Resources\views\Complements\A'.$this->ctCode.'.HTM';
        $this->bonusFile = 'A'.$this->ctCode.'.HTM';
        $this->hasBonus = true;
   /*
        $numeroConte = str_replace('T', '', $this->ctCode);
        $numeroConte = substr($this->ctCode,1,3);



        $fichiers = scandir($path);

        foreach ($fichiers as $f){

            if ( stripos(basename($f),$numeroConte) !== false ) {

                $this->bonusFile = 'Complements\\'.basename($f);

                $this->hasBonus = true;
                break;
            }

        }*/
    }


    public function genererLaListeDesMotifs(){

        if (!file_exists($this->fichierDesMotifs)){
            echo 'FILE :$this->fichierDesMotifs inexistant';
            return;
       }


        //echo 'ouvre : ',$this->fichierDesMotifs;
        $lines = file($this->fichierDesMotifs);
        $motifsTab = [];

        foreach ($lines as $lineNumber => $lineContent){

            /* recherche du numero de section en chiffres romains */

            $code = explode(' ',$lines[$lineNumber]);
            $c0 = $code[0] ;
            $l = strlen($c0);

            $cond1 = !( $l == 0 );
            $cond2 = !( $c0 == "\r\n") ;

            if ($cond1 && $cond2 ){
                //echo 'chaine acceptee = ', $lines[$lineNumber] ,'</br>';
                $motif = new MotifDansLeConteType($this->ctCode);
                $motif->ctCode = $this->ctCode;
                $motif->setDescription($lines[$lineNumber]);

                $lineDescription = $lines[$lineNumber];
                $motif->description = $motif->setDescription($lineDescription);

                $motif->motifCode = $code[0];

                $motif->motifDescription = $lineDescription;

                if ($code[0] == 'Motifs' ) {
                    $motif->isTitre = true;

                } else {
                    $motif->isTitre = false;
                    $motif->motifCode = $code[0];
                }

                $this->motifsDuConte[] =  $motif;

            }

        } /* fin de boucle foreach */
    }






}
