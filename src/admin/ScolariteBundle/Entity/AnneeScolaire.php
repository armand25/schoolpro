<?php

namespace admin\ScolariteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \admin\UserBundle\Types\TypeEtat;

/**
 * AnneeScolaire
 *
 * @ORM\Table(name="annee_scolaire")
 * @ORM\Entity(repositoryClass="admin\ScolariteBundle\Entity\AnneeScolaireRepository")
 */
class AnneeScolaire
{
     public function __construct() {
        $this->etatAnneeScolaire = TypeEtat::ACTIF;
        $this->dateInitialAnneeScolaire = new \Datetime();
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
     * @var string $libelleAnneeScolaire
     *
     * @ORM\Column(name="libelle_annee_scolaire", type="string")
     */
    private $libelleAnneeScolaire;
    
    /**
     * @var Datetime $dateRentreAnneeScolaire
     *
     * @ORM\Column(name="date_rentre_annee_scolaire", type="datetime")
     */
    private $dateRentreAnneeScolaire;
    
    
    /**
     * @var Datetime $dateVacanceAnneeScolaire
     *
     * @ORM\Column(name="date_vacance_annee_scolaire", type="datetime")
     */
    private $dateVacanceAnneeScolaire;
    
    /**
     * @var Datetime $dateInitialAnneeScolaire
     *
     * @ORM\Column(name="date_initial_annee_scolaire", type="datetime")
     */
    private $dateInitialAnneeScolaire;
    
    
    /**
     * @var string $etatAnneeScolaire
     *
     * @ORM\Column(name="etat_annee_scolaire", type="integer")
     */
    private $etatAnneeScolaire;
    
    /**
     *
     * @var ArrayCollection $ecolages 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\Ecolage", cascade={"persist", "remove"}, mappedBy="anneescolaire")
     * 
     */
    private $ecolages;   
    
    
   /**
     * @var ArrayCollection operations
     * @ORM\OneToMany(targetEntity="admin\EconomatBundle\Entity\Operation", cascade={"persist", "remove"}, mappedBy="anneescolaire")
     */
    private $operations; 

    
    /**
     *
     * @var ArrayCollection $periodes 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\Periode", cascade={"persist", "remove"}, mappedBy="anneescolaire")
     * 
     */
    private $periodes;   
    
    /**
     *
     * @var ArrayCollection $fairecourss 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\Fairecours", cascade={"persist", "remove"}, mappedBy="anneescolaire")
     * 
     */
    private $fairecourss;     
    
        /**
     *
     * @var ArrayCollection $setrouvers 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\SeTrouver", cascade={"persist", "remove"}, mappedBy="anneescolaire")
     * 
     */
    private $setrouvers; 
    
    /**
     * @var Etablissement  $etablissement
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Etablissement", inversedBy="anneescolaires")
     * @ORM\JoinColumn(nullable=true)
     */
    private $etablissement;      
    
        /**
     *
     * @var ArrayCollection $concerners 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\Concerner", cascade={"persist", "remove"}, mappedBy="anneescolaire")
     * 
     */
    private $concerners;
    
    /**
     *
     * @var ArrayCollection $epreuves 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\Epreuve", cascade={"persist", "remove"}, mappedBy="anneescolaire")
     * 
     */
    private $epreuves;  
   
   

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
     * Set libelleAnneeScolaire
     *
     * @param string $libelleAnneeScolaire
     *
     * @return AnneeScolaire
     */
    public function setLibelleAnneeScolaire($libelleAnneeScolaire)
    {
        $this->libelleAnneeScolaire = $libelleAnneeScolaire;

        return $this;
    }

    /**
     * Get libelleAnneeScolaire
     *
     * @return string
     */
    public function getLibelleAnneeScolaire()
    {
        return $this->libelleAnneeScolaire;
    }

    /**
     * Set dateRentreAnneeScolaire
     *
     * @param \DateTime $dateRentreAnneeScolaire
     *
     * @return AnneeScolaire
     */
    public function setDateRentreAnneeScolaire($dateRentreAnneeScolaire)
    {
        $this->dateRentreAnneeScolaire = $dateRentreAnneeScolaire;

        return $this;
    }

    /**
     * Get dateRentreAnneeScolaire
     *
     * @return \DateTime
     */
    public function getDateRentreAnneeScolaire()
    {
        return $this->dateRentreAnneeScolaire;
    }

    /**
     * Set dateVacanceAnneeScolaire
     *
     * @param \DateTime $dateVacanceAnneeScolaire
     *
     * @return AnneeScolaire
     */
    public function setDateVacanceAnneeScolaire($dateVacanceAnneeScolaire)
    {
        $this->dateVacanceAnneeScolaire = $dateVacanceAnneeScolaire;

        return $this;
    }

    /**
     * Get dateVacanceAnneeScolaire
     *
     * @return \DateTime
     */
    public function getDateVacanceAnneeScolaire()
    {
        return $this->dateVacanceAnneeScolaire;
    }

    /**
     * Set dateInitialAnneeScolaire
     *
     * @param \DateTime $dateInitialAnneeScolaire
     *
     * @return AnneeScolaire
     */
    public function setDateInitialAnneeScolaire($dateInitialAnneeScolaire)
    {
        $this->dateInitialAnneeScolaire = $dateInitialAnneeScolaire;

        return $this;
    }

    /**
     * Get dateInitialAnneeScolaire
     *
     * @return \DateTime
     */
    public function getDateInitialAnneeScolaire()
    {
        return $this->dateInitialAnneeScolaire;
    }

    /**
     * Set etatAnneeScolaire
     *
     * @param integer $etatAnneeScolaire
     *
     * @return AnneeScolaire
     */
    public function setEtatAnneeScolaire($etatAnneeScolaire)
    {
        $this->etatAnneeScolaire = $etatAnneeScolaire;

        return $this;
    }

    /**
     * Get etatAnneeScolaire
     *
     * @return integer
     */
    public function getEtatAnneeScolaire()
    {
        return $this->etatAnneeScolaire;
    }

    /**
     * Add ecolage
     *
     * @param \admin\ScolariteBundle\Entity\Ecolage $ecolage
     *
     * @return AnneeScolaire
     */
    public function addEcolage(\admin\ScolariteBundle\Entity\Ecolage $ecolage)
    {
        $this->ecolages[] = $ecolage;

        return $this;
    }

    /**
     * Remove ecolage
     *
     * @param \admin\ScolariteBundle\Entity\Ecolage $ecolage
     */
    public function removeEcolage(\admin\ScolariteBundle\Entity\Ecolage $ecolage)
    {
        $this->ecolages->removeElement($ecolage);
    }

    /**
     * Get ecolages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEcolages()
    {
        return $this->ecolages;
    }

    /**
     * Add periode
     *
     * @param \admin\ScolariteBundle\Entity\Periode $periode
     *
     * @return AnneeScolaire
     */
    public function addPeriode(\admin\ScolariteBundle\Entity\Periode $periode)
    {
        $this->periodes[] = $periode;

        return $this;
    }

    /**
     * Remove periode
     *
     * @param \admin\ScolariteBundle\Entity\Periode $periode
     */
    public function removePeriode(\admin\ScolariteBundle\Entity\Periode $periode)
    {
        $this->periodes->removeElement($periode);
    }

    /**
     * Get periodes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPeriodes()
    {
        return $this->periodes;
    }

    /**
     * Add fairecourss
     *
     * @param \admin\ScolariteBundle\Entity\Fairecours $fairecourss
     *
     * @return AnneeScolaire
     */
    public function addFairecourss(\admin\ScolariteBundle\Entity\Fairecours $fairecourss)
    {
        $this->fairecourss[] = $fairecourss;

        return $this;
    }

    /**
     * Remove fairecourss
     *
     * @param \admin\ScolariteBundle\Entity\Fairecours $fairecourss
     */
    public function removeFairecourss(\admin\ScolariteBundle\Entity\Fairecours $fairecourss)
    {
        $this->fairecourss->removeElement($fairecourss);
    }

    /**
     * Get fairecourss
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFairecourss()
    {
        return $this->fairecourss;
    }

    /**
     * Add setrouver
     *
     * @param \admin\ScolariteBundle\Entity\SeTrouver $setrouver
     *
     * @return AnneeScolaire
     */
    public function addSetrouver(\admin\ScolariteBundle\Entity\SeTrouver $setrouver)
    {
        $this->setrouvers[] = $setrouver;

        return $this;
    }

    /**
     * Remove setrouver
     *
     * @param \admin\ScolariteBundle\Entity\SeTrouver $setrouver
     */
    public function removeSetrouver(\admin\ScolariteBundle\Entity\SeTrouver $setrouver)
    {
        $this->setrouvers->removeElement($setrouver);
    }

    /**
     * Get setrouvers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSetrouvers()
    {
        return $this->setrouvers;
    }

    /**
     * Set etablissement
     *
     * @param \admin\ScolariteBundle\Entity\Etablissement $etablissement
     *
     * @return AnneeScolaire
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
     * Add operation
     *
     * @param \admin\EconomatBundle\Entity\Operation $operation
     *
     * @return AnneeScolaire
     */
    public function addOperation(\admin\EconomatBundle\Entity\Operation $operation)
    {
        $this->operations[] = $operation;

        return $this;
    }

    /**
     * Remove operation
     *
     * @param \admin\EconomatBundle\Entity\Operation $operation
     */
    public function removeOperation(\admin\EconomatBundle\Entity\Operation $operation)
    {
        $this->operations->removeElement($operation);
    }

    /**
     * Get operations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOperations()
    {
        return $this->operations;
    }

    /**
     * Add concerner
     *
     * @param \admin\ScolariteBundle\Entity\Concerner $concerner
     *
     * @return AnneeScolaire
     */
    public function addConcerner(\admin\ScolariteBundle\Entity\Concerner $concerner)
    {
        $this->concerners[] = $concerner;

        return $this;
    }

    /**
     * Remove concerner
     *
     * @param \admin\ScolariteBundle\Entity\Concerner $concerner
     */
    public function removeConcerner(\admin\ScolariteBundle\Entity\Concerner $concerner)
    {
        $this->concerners->removeElement($concerner);
    }

    /**
     * Get concerners
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getConcerners()
    {
        return $this->concerners;
    }

    /**
     * Add epreufe
     *
     * @param \admin\ScolariteBundle\Entity\Epreuve $epreufe
     *
     * @return AnneeScolaire
     */
    public function addEpreufe(\admin\ScolariteBundle\Entity\Epreuve $epreufe)
    {
        $this->epreuves[] = $epreufe;

        return $this;
    }

    /**
     * Remove epreufe
     *
     * @param \admin\ScolariteBundle\Entity\Epreuve $epreufe
     */
    public function removeEpreufe(\admin\ScolariteBundle\Entity\Epreuve $epreufe)
    {
        $this->epreuves->removeElement($epreufe);
    }

    /**
     * Get epreuves
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEpreuves()
    {
        return $this->epreuves;
    }
}
