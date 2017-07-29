<?php


namespace DTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="contestypes")
 */

class contetype

{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $numeroAT;


    /**
     * @ORM\Column(type="string")
     */
    private $titre;


    /**
     * @ORM\Column(type="string")
     */
    private $description;


    /**
     * @ORM\OneToMany(targetEntity="ElementDuConte", mappedBy="contetype")
     */
    private $elementsduconte;


    /**
     * @ORM\OneToMany(targetEntity="Version", mappedBy="contetype")
     */
    private $versions;



    /**
     * @ORM\Column(type="string")
     */
    private $fichierDesEDC;

    /**
     * @ORM\Column(type="string")
     */
    private $fichierDesReferencesDeVersions;

    /**
     * @ORM\Column(type="string")
     */
    private $fichierDesMotifs;

    /**
     * @ORM\Column(type="string")
     */
    private $fichiersDesVersions;


}