<?php

namespace admin\ScolariteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \admin\UserBundle\Types\TypeEtat;

/**
 * Enseignement
 *
 * @ORM\Table(name="enseignement")
 * @ORM\Entity(repositoryClass="admin\ScolariteBundle\Entity\EnseignementRepository")
 */
class Enseignement
{
     public function __construct() {
        $this->etatEnseignement = TypeEtat::ACTIF;
       
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
     * @var string $libelleEnseignement
     *
     * @ORM\Column(name="libelle_enseignement", type="string")
     */
    private $libelleEnseignement;
    /**
     * @var string $descriptionEnseignement
     *
     * @ORM\Column(name="description_enseignement", type="string")
     */
    private $descriptionEnseignement;
    /**
     * @var string $etatEnseignement
     *
     * @ORM\Column(name="etat_enseignement", type="integer")
     */
    private $etatEnseignement;
    
    /**
     *
     * @var ArrayCollection $ecolages 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\Ecolage", cascade={"persist", "remove"}, mappedBy="enseignement")
     * 
     */
    private $ecolages;   
    /**
     *
     * @var ArrayCollection $filieres 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\Filiere", cascade={"persist", "remove"}, mappedBy="enseignement")
     * 
     */
    private $filieres;   
    
    
    /**
     * @var Degre  $degre
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Degre", inversedBy="enseigements")
     * @ORM\JoinColumn(nullable=true)
     */
    private $degre; 
    
        /**
     *
     * @var ArrayCollection $concerners 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\Concerner", cascade={"persist", "remove"}, mappedBy="enseignement")
     * 
     */
    private $concerners;
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
     * Set libelleEnseignement
     *
     * @param string $libelleEnseignement
     *
     * @return Enseignement
     */
    public function setLibelleEnseignement($libelleEnseignement)
    {
        $this->libelleEnseignement = $libelleEnseignement;

        return $this;
    }

    /**
     * Get libelleEnseignement
     *
     * @return string
     */
    public function getLibelleEnseignement()
    {
        return $this->libelleEnseignement;
    }

    /**
     * Set etatEnseignement
     *
     * @param integer $etatEnseignement
     *
     * @return Enseignement
     */
    public function setEtatEnseignement($etatEnseignement)
    {
        $this->etatEnseignement = $etatEnseignement;

        return $this;
    }

    /**
     * Get etatEnseignement
     *
     * @return integer
     */
    public function getEtatEnseignement()
    {
        return $this->etatEnseignement;
    }

    /**
     * Add ecolage
     *
     * @param \admin\ScolariteBundle\Entity\Ecolage $ecolage
     *
     * @return Enseignement
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
     * Add filiere
     *
     * @param \admin\ScolariteBundle\Entity\Filiere $filiere
     *
     * @return Enseignement
     */
    public function addFiliere(\admin\ScolariteBundle\Entity\Filiere $filiere)
    {
        $this->filieres[] = $filiere;

        return $this;
    }

    /**
     * Remove filiere
     *
     * @param \admin\ScolariteBundle\Entity\Filiere $filiere
     */
    public function removeFiliere(\admin\ScolariteBundle\Entity\Filiere $filiere)
    {
        $this->filieres->removeElement($filiere);
    }

    /**
     * Get filieres
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFilieres()
    {
        return $this->filieres;
    }

    /**
     * Set degre
     *
     * @param \admin\ScolariteBundle\Entity\Degre $degre
     *
     * @return Enseignement
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
     * Set descriptionEnseignement
     *
     * @param string $descriptionEnseignement
     *
     * @return Enseignement
     */
    public function setDescriptionEnseignement($descriptionEnseignement)
    {
        $this->descriptionEnseignement = $descriptionEnseignement;

        return $this;
    }

    /**
     * Get descriptionEnseignement
     *
     * @return string
     */
    public function getDescriptionEnseignement()
    {
        return $this->descriptionEnseignement;
    }

    /**
     * Add concerner
     *
     * @param \admin\ScolariteBundle\Entity\Concerner $concerner
     *
     * @return Enseignement
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
}
