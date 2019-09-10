<?php

namespace admin\ScolariteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \admin\UserBundle\Types\TypeEtat;

/**
 * FaireCours
 *
 * @ORM\Table(name="faire_cours")
 * @ORM\Entity(repositoryClass="admin\ScolariteBundle\Entity\FaireCoursRepository")
 */
class FaireCours
{
     public function __construct() {
        $this->etatFaireCours = TypeEtat::ACTIF;
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
     * @var string $etatFaireCours
     *
     * @ORM\Column(name="etat_faire_cours", type="integer")
     */
    private $etatFaireCours;       


    /**
     * @var Classe  $classe
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Classe", inversedBy="fairecourss")
     * @ORM\JoinColumn(nullable=true)
     */
    private $classe;    
    
    /**
     * @var Jour  $jour
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Jour", inversedBy="fairecourss")
     * @ORM\JoinColumn(nullable=true)
     */
    private $jour;   
    
    /**
     * @var AnneeScolaire  $anneescolaire
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\AnneeScolaire", inversedBy="fairecourss")
     * @ORM\JoinColumn(nullable=true)
     */
    private $anneescolaire;   
    
    /**
     * @var EstEnseigne  $estenseigne
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\EstEnseigne", inversedBy="fairecourss")
     * @ORM\JoinColumn(nullable=true)
     */
    private $estenseigne;  
    
    /**
     * @var HeureJournee  $heurejournee
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\HeureJournee", inversedBy="fairecourss")
     * @ORM\JoinColumn(nullable=true)
     */
    private $heurejournee;  
    
    /**
     * @var Utilisateur  $utilisateur
     * @ORM\ManyToOne(targetEntity="admin\UserBundle\Entity\Utilisateur", inversedBy="fairecourss")
     * @ORM\JoinColumn(nullable=true)
     */
    private $utilisateur;     

    /**
     *
     * @var ArrayCollection $heurecourss 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\HeureCours", cascade={"persist", "remove"}, mappedBy="fairecours")
     * 
     */
    private $heurecourss;  
    
    /**
     *
     * @var ArrayCollection $presences 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\Presence", cascade={"persist", "remove"}, mappedBy="fairecours")
     * 
     */
    private $presences;     
                
    /**
     * @var Etablissement  $etablissement
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Etablissement", inversedBy="fairecourss")
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
     * Set etatFaireCours
     *
     * @param integer $etatFaireCours
     *
     * @return FaireCours
     */
    public function setEtatFaireCours($etatFaireCours)
    {
        $this->etatFaireCours = $etatFaireCours;

        return $this;
    }

    /**
     * Get etatFaireCours
     *
     * @return integer
     */
    public function getEtatFaireCours()
    {
        return $this->etatFaireCours;
    }

    /**
     * Set classe
     *
     * @param \admin\ScolariteBundle\Entity\Classe $classe
     *
     * @return FaireCours
     */
    public function setClasse(\admin\ScolariteBundle\Entity\Classe $classe = null)
    {
        $this->classe = $classe;

        return $this;
    }

    /**
     * Get classe
     *
     * @return \admin\ScolariteBundle\Entity\Classe
     */
    public function getClasse()
    {
        return $this->classe;
    }

    /**
     * Set jour
     *
     * @param \admin\ScolariteBundle\Entity\Jour $jour
     *
     * @return FaireCours
     */
    public function setJour(\admin\ScolariteBundle\Entity\Jour $jour = null)
    {
        $this->jour = $jour;

        return $this;
    }

    /**
     * Get jour
     *
     * @return \admin\ScolariteBundle\Entity\Jour
     */
    public function getJour()
    {
        return $this->jour;
    }

    /**
     * Set anneescolaire
     *
     * @param \admin\ScolariteBundle\Entity\AnneeScolaire $anneescolaire
     *
     * @return FaireCours
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
     * Set estenseigne
     *
     * @param \admin\ScolariteBundle\Entity\EstEnseigne $estenseigne
     *
     * @return FaireCours
     */
    public function setEstenseigne(\admin\ScolariteBundle\Entity\EstEnseigne $estenseigne = null)
    {
        $this->estenseigne = $estenseigne;

        return $this;
    }

    /**
     * Get estenseigne
     *
     * @return \admin\ScolariteBundle\Entity\EstEnseigne
     */
    public function getEstenseigne()
    {
        return $this->estenseigne;
    }

    /**
     * Set utilisateur
     *
     * @param \admin\UserBundle\Entity\Utilisateur $utilisateur
     *
     * @return FaireCours
     */
    public function setUtilisateur(\admin\UserBundle\Entity\Utilisateur $utilisateur = null)
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * Get utilisateur
     *
     * @return \admin\UserBundle\Entity\Utilisateur
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * Add heurecourss
     *
     * @param \admin\ScolariteBundle\Entity\HeureCours $heurecourss
     *
     * @return FaireCours
     */
    public function addHeurecourss(\admin\ScolariteBundle\Entity\HeureCours $heurecourss)
    {
        $this->heurecourss[] = $heurecourss;

        return $this;
    }

    /**
     * Remove heurecourss
     *
     * @param \admin\ScolariteBundle\Entity\HeureCours $heurecourss
     */
    public function removeHeurecourss(\admin\ScolariteBundle\Entity\HeureCours $heurecourss)
    {
        $this->heurecourss->removeElement($heurecourss);
    }

    /**
     * Get heurecourss
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHeurecourss()
    {
        return $this->heurecourss;
    }

    /**
     * Add presence
     *
     * @param \admin\ScolariteBundle\Entity\Presence $presence
     *
     * @return FaireCours
     */
    public function addPresence(\admin\ScolariteBundle\Entity\Presence $presence)
    {
        $this->presences[] = $presence;

        return $this;
    }

    /**
     * Remove presence
     *
     * @param \admin\ScolariteBundle\Entity\Presence $presence
     */
    public function removePresence(\admin\ScolariteBundle\Entity\Presence $presence)
    {
        $this->presences->removeElement($presence);
    }

    /**
     * Get presences
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPresences()
    {
        return $this->presences;
    }
    /**
     * Set heurejournee
     *
     * @param \admin\ScolariteBundle\Entity\HeureJournee $heurejournee
     *
     * @return FaireCours
     */
    public function setHeurejournee(\admin\ScolariteBundle\Entity\HeureJournee $heurejournee = null)
    {
        $this->heurejournee = $heurejournee;

        return $this;
    }

    /**
     * Get heurejournee
     *
     * @return \admin\ScolariteBundle\Entity\HeureJournee
     */
    public function getHeurejournee()
    {
        return $this->heurejournee;
    }

    /**
     * Set etablissement
     *
     * @param \admin\ScolariteBundle\Entity\Etablissement $etablissement
     *
     * @return FaireCours
     */
    public function setEtablissement(\admin\ScolariteBundle\Entity\Etablissement $etablissement = null)
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
}
