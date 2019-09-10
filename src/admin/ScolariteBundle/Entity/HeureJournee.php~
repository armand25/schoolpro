<?php

namespace admin\ScolariteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \admin\ScolariteBundle\Types\TypeEtat;

/**
 * HeureJournee
 *
 * @ORM\Table(name="heure_journee")
 * @ORM\Entity(repositoryClass="admin\ScolariteBundle\Entity\HeureJourneeRepository")
 */
class HeureJournee
{
     public function __construct() {
        $this->etatHeureJournee = TypeEtat::ACTIF;
       
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
     * @var string $libelleHeureJournee
     *
     * @ORM\Column(name="libelle_heure_journee", type="string")
     */
    private $libelleHeureJournee;
    
    /**
     * @var datetime $debutHeureJournee
     *
     * @ORM\Column(name="debut_heure_journee", type="datetime")
     */
    private $debutHeureJournee;
    
    /**
     * @var datetime $finHeureJournee
     *
     * @ORM\Column(name="fin_heure_journee", type="datetime")
     */
    private $finHeureJournee;
    
    /**
     * @var string $etatHeureJournee
     *
     * @ORM\Column(name="etat_heure_journee", type="integer")
     */
    private $etatHeureJournee;
    
    /**
     *
     * @var ArrayCollection $heurecourss 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\HeureCours", cascade={"persist", "remove"}, mappedBy="heurejournee")
     * 
     */
    private $heurecourss;    
    
    /**
     *
     * @var ArrayCollection $fairecourss
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\FaireCours", cascade={"persist", "remove"}, mappedBy="heurejournee")
     * 
     */
    private $fairecourss;     


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
     * Set libelleHeureJournee
     *
     * @param string $libelleHeureJournee
     *
     * @return HeureJournee
     */
    public function setLibelleHeureJournee($libelleHeureJournee)
    {
        $this->libelleHeureJournee = $libelleHeureJournee;

        return $this;
    }

    /**
     * Get libelleHeureJournee
     *
     * @return string
     */
    public function getLibelleHeureJournee()
    {
        return $this->libelleHeureJournee;
    }

    /**
     * Set etatHeureJournee
     *
     * @param integer $etatHeureJournee
     *
     * @return HeureJournee
     */
    public function setEtatHeureJournee($etatHeureJournee)
    {
        $this->etatHeureJournee = $etatHeureJournee;

        return $this;
    }

    /**
     * Get etatHeureJournee
     *
     * @return integer
     */
    public function getEtatHeureJournee()
    {
        return $this->etatHeureJournee;
    }

    /**
     * Add heurecourss
     *
     * @param \admin\ScolariteBundle\Entity\HeureCours $heurecourss
     *
     * @return HeureJournee
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
     * Add fairecourss
     *
     * @param \admin\ScolariteBundle\Entity\FaireCours $fairecourss
     *
     * @return HeureJournee
     */
    public function addFairecourss(\admin\ScolariteBundle\Entity\FaireCours $fairecourss)
    {
        $this->fairecourss[] = $fairecourss;

        return $this;
    }

    /**
     * Remove fairecourss
     *
     * @param \admin\ScolariteBundle\Entity\FaireCours $fairecourss
     */
    public function removeFairecourss(\admin\ScolariteBundle\Entity\FaireCours $fairecourss)
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
     * Set debutHeureJournee
     *
     * @param \datetime $debutHeureJournee
     *
     * @return HeureJournee
     */
    public function setDebutHeureJournee(\datetime $debutHeureJournee)
    {
        $this->debutHeureJournee = $debutHeureJournee;

        return $this;
    }

    /**
     * Get debutHeureJournee
     *
     * @return \Datetime
     */
    public function getDebutHeureJournee()
    {
        return $this->debutHeureJournee;
    }

    /**
     * Set finHeureJournee
     *
     * @param \datetime $finHeureJournee
     *
     * @return HeureJournee
     */
    public function setFinHeureJournee(\datetime $finHeureJournee)
    {
        $this->finHeureJournee = $finHeureJournee;

        return $this;
    }

    /**
     * Get finHeureJournee
     *
     * @return \Datetime
     */
    public function getFinHeureJournee()
    {
        return $this->finHeureJournee;
    }
}
