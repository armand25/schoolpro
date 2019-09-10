<?php

namespace admin\ScolariteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \admin\UserBundle\Types\TypeEtat;

/**
 * Ecolage
 *
 * @ORM\Table(name="ecolage")
 * @ORM\Entity(repositoryClass="admin\ScolariteBundle\Entity\EcolageRepository")
 */
class Ecolage
{
     public function __construct() {
        $this->etatEcolage = TypeEtat::ACTIF;
       
    }
    
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $libelleEcolage
     *
     * @ORM\Column(name="libelle_ecolage", type="string")
     */
    private $libelleEcolage;
    
    /**
     * @var integer $montantEcolage
     *
     * @ORM\Column(name="montant_ecolage", type="integer")
     */
    private $montantEcolage;
    
    /**
     * @var string $etatEcolage
     *
     * @ORM\Column(name="etat_ecolage", type="integer")
     */
    private $etatEcolage;
    
    /**
     * @var string $trancheEcolage
     *
     * @ORM\Column(name="tranche_ecolage", type="integer")
     */
    private $trancheEcolage;
    
    /**
     * @var AnneeScolaire  $anneescolaire
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\AnneeScolaire", inversedBy="ecolages")
     * @ORM\JoinColumn(nullable=true)
     */
    private $anneescolaire;     

    
    /**
     * @var Degre  $degre
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Degre", inversedBy="ecolages")
     * @ORM\JoinColumn(nullable=true)
     */
    private $degre;     
    
    /**
     * @var Enseignement  $enseignement
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Enseignement", inversedBy="ecolages")
     * @ORM\JoinColumn(nullable=true)
     */
    private $enseignement;   
    
    /**
     * @var Niveau  $niveau
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Niveau", inversedBy="ecolages")
     * @ORM\JoinColumn(nullable=true)
     */
    private $niveau;  
    
    
    /**
     * @var Etablissement  $etablissement
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Etablissement", inversedBy="ecolages")
     * @ORM\JoinColumn(nullable=true)
     */
    private $etablissement;      
    

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set libelleEcolage
     *
     * @param string $libelleEcolage
     *
     * @return Ecolage
     */
    public function setLibelleEcolage($libelleEcolage)
    {
        $this->libelleEcolage = $libelleEcolage;

        return $this;
    }

    /**
     * Get libelleEcolage
     *
     * @return string
     */
    public function getLibelleEcolage()
    {
        return $this->libelleEcolage;
    }

    /**
     * Set montantEcolage
     *
     * @param integer $montantEcolage
     *
     * @return Ecolage
     */
    public function setMontantEcolage($montantEcolage)
    {
        $this->montantEcolage = $montantEcolage;

        return $this;
    }

    /**
     * Get montantEcolage
     *
     * @return integer
     */
    public function getMontantEcolage()
    {
        return $this->montantEcolage;
    }

    /**
     * Set etatEcolage
     *
     * @param integer $etatEcolage
     *
     * @return Ecolage
     */
    public function setEtatEcolage($etatEcolage)
    {
        $this->etatEcolage = $etatEcolage;

        return $this;
    }

    /**
     * Get etatEcolage
     *
     * @return integer
     */
    public function getEtatEcolage()
    {
        return $this->etatEcolage;
    }

    /**
     * Set anneescolaire
     *
     * @param \admin\ScolariteBundle\Entity\AnneeScolaire $anneescolaire
     *
     * @return Ecolage
     */
    public function setAnneescolaire(\admin\ScolariteBundle\Entity\AnneeScolaire $anneescolaire = null)
    {
        $this->anneescolaire = $anneescolaire;

        return $this;
    }

    /**
     * Get anneescolaire
     *
     * @return \admin\ScolariteBundle\Entity\AnneeScolaire
     */
    public function getAnneescolaire()
    {
        return $this->anneescolaire;
    }

    /**
     * Set degre
     *
     * @param \admin\ScolariteBundle\Entity\Degre $degre
     *
     * @return Ecolage
     */
    public function setDegre(\admin\ScolariteBundle\Entity\Degre $degre = null)
    {
        $this->degre = $degre;

        return $this;
    }

    /**
     * Get degre
     *
     * @return \admin\ScolariteBundle\Entity\Degre
     */
    public function getDegre()
    {
        return $this->degre;
    }

    /**
     * Set enseignement
     *
     * @param \admin\ScolariteBundle\Entity\Enseignement $enseignement
     *
     * @return Ecolage
     */
    public function setEnseignement(\admin\ScolariteBundle\Entity\Enseignement $enseignement = null)
    {
        $this->enseignement = $enseignement;

        return $this;
    }

    /**
     * Get enseignement
     *
     * @return \admin\ScolariteBundle\Entity\Enseignement
     */
    public function getEnseignement()
    {
        return $this->enseignement;
    }

    /**
     * Set etablissement
     *
     * @param \admin\ScolariteBundle\Entity\Etablissement $etablissement
     *
     * @return Ecolage
     */
    public function setEtablissement(\admin\ScolariteBundle\Entity\Etablissement $etablissement)
    {
        $this->etablissement = $etablissement;

        return $this;
    }

    /**
     * Get etablissement
     *
     * @return \admin\ScolariteBundle\Entity\Etablissement
     */
    public function getEtablissement()
    {
        return $this->etablissement;
    }

    /**
     * Set niveau
     *
     * @param \admin\ScolariteBundle\Entity\Niveau $niveau
     *
     * @return Ecolage
     */
    public function setNiveau(\admin\ScolariteBundle\Entity\Niveau $niveau = null)
    {
        $this->niveau = $niveau;

        return $this;
    }

    /**
     * Get niveau
     *
     * @return \admin\ScolariteBundle\Entity\Niveau
     */
    public function getNiveau()
    {
        return $this->niveau;
    }

    /**
     * Set trancheEcolage
     *
     * @param integer $trancheEcolage
     *
     * @return Ecolage
     */
    public function setTrancheEcolage($trancheEcolage)
    {
        $this->trancheEcolage = $trancheEcolage;

        return $this;
    }

    /**
     * Get trancheEcolage
     *
     * @return integer
     */
    public function getTrancheEcolage()
    {
        return $this->trancheEcolage;
    }
}
