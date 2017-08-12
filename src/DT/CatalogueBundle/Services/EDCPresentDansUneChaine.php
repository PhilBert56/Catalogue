<?php

namespace DT\CatalogueBundle\Services;

class EDCPresentDansUneChaine {

        public $chaineDeCaracteresSource;
        public $codeEDC ='';
        public $positionDebutEDCDansLaChaine = 0;
        public $positionFinEDCDansLaChaine = 0;


        public function __construct($chaineSource,$code,$posDebut,$posFin)
    {
        $this->chaineDeCaractereSource = $chaineSource;
        $this->codeEDC = $code;
        $this->positionDebutEDCDansLaChaine = $posDebut;
        $this->positionFinDansLaChaine = $posFin;
    }

    }