<?php

namespace admin\ScolariteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \admin\ScolariteBundle\Types\TypeEtat;

/**
 * Periode
 *
 * @ORM\Table(name="periode")
 * @ORM\Entity(repositoryClass="admin\ScolariteBundle\Entity\PeriodeRepository")
 */
class Periode
{
     public function __construct() {
        $this->etatPeriode = TypeEtat::ACTIF;
       
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
     * @var string $libellePeriode
     *
     * @ORM\Column(name="libelle_periode", type="string")
     */
    private $libellePeriode;
    /**
     * @var string $etatPeriode
     *
     * @ORM\Column(name="etat_periode", type="integer")
     */
    private $etatPeriode;
    
    /**
     * @var date $dateDebutPeriode
     *
     * @ORM\Column(name="date_debut_periode", type="datetime")
     */
    private $dateDebutPeriode;
    
    
    /**
     * @var date $dateFinPeriode
     *
     * @ORM\Column(name="date_fin_periode", type="datetime")
     */
    private $dateFinPeriode;
    
    
    /**
     * @var AnneeScolaire  $anneescolaire
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\AnneeScolaire", inversedBy="periodes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $anneescolaire;      

    /**
     * @var Decoupage  $decoupage
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Decoupage", inversedBy="periodes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $decoupage;      
    
    /**
     * @var Niveau  $niveau
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Niveau", inversedBy="periodes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $niveau;  
    
    
   

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
     * Set libellePeriode
     *
     * @param string $libellePeriode
     *
     * @return Periode
     */
    public function setLibellePeriode($libellePeriode)
    {
        $this->libellePeriode = $libellePeriode;

        return $this;
    }

    /**
     * Get libellePeriode
     *
     * @return string
     */
    public function getLibellePeriode()
    {
        return $this->libellePeriode;
    }

    /**
     * Set etatPeriode
     *
     * @param integer $etatPeriode
     *
     * @return Periode
     */
    public function setEtatPeriode($etatPeriode)
    {
        $this->etatPeriode = $etatPeriode;

        return $this;
    }

    /**
     * Get etatPeriode
     *
     * @return integer
     */
    public function getEtatPeriode()
    {
        return $this->etatPeriode;
    }

    /**
     * Set dateDebutPeriode
     *
     * @param \Datetime $dateDebutPeriode
     *
     * @return Periode
     */
    public function setDateDebutPeriode(\Datetime $dateDebutPeriode)
    {
        $this->dateDebutPeriode = $dateDebutPeriode;

        return $this;
    }

    /**
     * Get dateDebutPeriode
     *
     * @return \Datetime
     */
    public function getDateDebutPeriode()
    {
        return $this->dateDebutPeriode;
    }

    /**
     * Set dateFinPeriode
     *
     * @param \Datetime $dateFinPeriode
     *
     * @return Periode
     */
    public function setDateFinPeriode(\Datetime $dateFinPeriode)
    {
        $this->dateFinPeriode = $dateFinPeriode;

        return $this;
    }

    /**
     * Get dateFinPeriode
     *
     * @return \Datetime
     */
    public function getDateFinPeriode()
    {
        return $this->dateFinPeriode;
    }

    /**
     * Set anneescolaire
     *
     * @param \admin\ScolariteBundle\Entity\AnneeScolaire $anneescolaire
     *
     * @return Periode
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
     * Set decoupage
     *
     * @param \admin\ScolariteBundle\Entity\Decoupage $decoupage
     *
     * @return Periode
     */
    public function setDecoupage(\admin\ScolariteBundle\Entity\Decoupage $decoupage = null)
    {
        $this->decoupage = $decoupage;

        return $this;
    }

    /**
     * Get decoupage
     *
     * @return \admin\ScolariteBundle\Entity\Decoupage
     */
    public function getDecoupage()
    {
        return $this->decoupage;
    }

    /**
     * Set niveau
     *
     * @param \admin\ScolariteBundle\Entity\Niveau $niveau
     *
     * @return Periode
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
}
