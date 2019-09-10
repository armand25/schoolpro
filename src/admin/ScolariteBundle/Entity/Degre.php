<?php

namespace admin\ScolariteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \admin\UserBundle\Types\TypeEtat;

/**
 * Degre
 *
 * @ORM\Table(name="degre")
 * @ORM\Entity(repositoryClass="admin\ScolariteBundle\Entity\DegreRepository")
 */
class Degre
{
     public function __construct() {
        $this->etatDegre = TypeEtat::ACTIF;
       
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
     * @var string $libelleDegre
     *
     * @ORM\Column(name="libelle_degre", type="string")
     */
    private $libelleDegre;
    /**
     * @var string $descriptionDegre
     *
     * @ORM\Column(name="description_degre", type="string")
     */
    private $descriptionDegre;
    /**
     * @var string $etatDegre
     *
     * @ORM\Column(name="etat_degre", type="integer")
     */
    private $etatDegre;
    
    /**
     *
     * @var ArrayCollection $ecolages 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\Ecolage", cascade={"persist", "remove"}, mappedBy="degre")
     * 
     */
    private $ecolages;  
    
    /**
     *
     * @var ArrayCollection $enseignements 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\Enseignement", cascade={"persist", "remove"}, mappedBy="degre")
     * 
     */
    private $enseignements;      


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
     * Set libelleDegre
     *
     * @param string $libelleDegre
     *
     * @return Degre
     */
    public function setLibelleDegre($libelleDegre)
    {
        $this->libelleDegre = $libelleDegre;

        return $this;
    }

    /**
     * Get libelleDegre
     *
     * @return string
     */
    public function getLibelleDegre()
    {
        return $this->libelleDegre;
    }

    /**
     * Set etatDegre
     *
     * @param integer $etatDegre
     *
     * @return Degre
     */
    public function setEtatDegre($etatDegre)
    {
        $this->etatDegre = $etatDegre;

        return $this;
    }

    /**
     * Get etatDegre
     *
     * @return integer
     */
    public function getEtatDegre()
    {
        return $this->etatDegre;
    }

    /**
     * Add ecolage
     *
     * @param \admin\ScolariteBundle\Entity\Ecolage $ecolage
     *
     * @return Degre
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
     * Add enseignement
     *
     * @param \admin\ScolariteBundle\Entity\Enseignement $enseignement
     *
     * @return Degre
     */
    public function addEnseignement(\admin\ScolariteBundle\Entity\Enseignement $enseignement)
    {
        $this->enseignements[] = $enseignement;

        return $this;
    }

    /**
     * Remove enseignement
     *
     * @param \admin\ScolariteBundle\Entity\Enseignement $enseignement
     */
    public function removeEnseignement(\admin\ScolariteBundle\Entity\Enseignement $enseignement)
    {
        $this->enseignements->removeElement($enseignement);
    }

    /**
     * Get enseignements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEnseignements()
    {
        return $this->enseignements;
    }

    /**
     * Set descriptionDegre
     *
     * @param string $descriptionDegre
     *
     * @return Degre
     */
    public function setDescriptionDegre($descriptionDegre)
    {
        $this->descriptionDegre = $descriptionDegre;

        return $this;
    }

    /**
     * Get descriptionDegre
     *
     * @return string
     */
    public function getDescriptionDegre()
    {
        return $this->descriptionDegre;
    }
}
