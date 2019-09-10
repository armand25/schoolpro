<?php

namespace admin\ScolariteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \admin\UserBundle\Types\TypeEtat;

/**
 * Jour
 *
 * @ORM\Table(name="jour")
 * @ORM\Entity(repositoryClass="admin\ScolariteBundle\Entity\JourRepository")
 */
class Jour
{
     public function __construct() {
        $this->etatJour = TypeEtat::ACTIF;
       
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
     * @var string $libelleJour
     *
     * @ORM\Column(name="libelle_jour", type="string")
     */
    private $libelleJour;
    /**
     * @var string $descriptionJour
     *
     * @ORM\Column(name="description_jour", type="string")
     */
    private $descriptionJour;
    /**
     * @var integer $etatJour
     *
     * @ORM\Column(name="etat_jour", type="integer")
     */
    private $etatJour;
    
    /**
     * @var string $etatJour
     *
     * @ORM\Column(name="code_jour", type="string")
     */
    private $codeJour;
    
    /**
     *
     * @var ArrayCollection $fairecourss 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\FaireCours", cascade={"persist", "remove"}, mappedBy="jour")
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
     * Set libelleJour
     *
     * @param string $libelleJour
     *
     * @return Jour
     */
    public function setLibelleJour($libelleJour)
    {
        $this->libelleJour = $libelleJour;

        return $this;
    }

    /**
     * Get libelleJour
     *
     * @return string
     */
    public function getLibelleJour()
    {
        return $this->libelleJour;
    }

    /**
     * Set etatJour
     *
     * @param integer $etatJour
     *
     * @return Jour
     */
    public function setEtatJour($etatJour)
    {
        $this->etatJour = $etatJour;

        return $this;
    }

    /**
     * Get etatJour
     *
     * @return integer
     */
    public function getEtatJour()
    {
        return $this->etatJour;
    }

    /**
     * Add fairecourss
     *
     * @param \admin\ScolariteBundle\Entity\FaireCours $fairecourss
     *
     * @return Jour
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
     * Set descriptionJour
     *
     * @param string $descriptionJour
     *
     * @return Jour
     */
    public function setDescriptionJour($descriptionJour)
    {
        $this->descriptionJour = $descriptionJour;

        return $this;
    }

    /**
     * Get descriptionJour
     *
     * @return string
     */
    public function getDescriptionJour()
    {
        return $this->descriptionJour;
    }

    /**
     * Set codeJour
     *
     * @param string $codeJour
     *
     * @return Jour
     */
    public function setCodeJour($codeJour)
    {
        $this->codeJour = $codeJour;

        return $this;
    }

    /**
     * Get codeJour
     *
     * @return string
     */
    public function getCodeJour()
    {
        return $this->codeJour;
    }
}
