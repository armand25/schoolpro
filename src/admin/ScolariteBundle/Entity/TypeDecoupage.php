<?php

namespace admin\ScolariteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \admin\UserBundle\Types\TypeEtat;

/**
 * TypeDecoupage
 *
 * @ORM\Table(name="type_decoupage")
 * @ORM\Entity(repositoryClass="admin\ScolariteBundle\Entity\TypeDecoupageRepository")
 */
class TypeDecoupage
{
     public function __construct() {
        $this->etatTypeDecoupage = TypeEtat::ACTIF;
       
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
     * @var string $libelleTypeDecoupage
     *
     * @ORM\Column(name="libelle_type_decoupage", type="string")
     */
    private $libelleTypeDecoupage;
    /**
     * @var string $descriptionTypeDecoupage
     *
     * @ORM\Column(name="description_type_decoupage", type="string")
     */
    private $descriptionTypeDecoupage;
    /**
     * @var string $etatTypeDecoupage
     *
     * @ORM\Column(name="etat_type_decoupage", type="integer")
     */
    private $etatTypeDecoupage;
    
    
    /**
     *
     * @var ArrayCollection $decoupages 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\Matiere", cascade={"persist", "remove"}, mappedBy="typedecoupage")
     * 
     */
    private $decoupages;    

   
        /**
     *
     * @var ArrayCollection $concerners 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\Concerner", cascade={"persist", "remove"}, mappedBy="typedecoupage")
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
     * Set libelleTypeDecoupage
     *
     * @param string $libelleTypeDecoupage
     *
     * @return TypeDecoupage
     */
    public function setLibelleTypeDecoupage($libelleTypeDecoupage)
    {
        $this->libelleTypeDecoupage = $libelleTypeDecoupage;

        return $this;
    }

    /**
     * Get libelleTypeDecoupage
     *
     * @return string
     */
    public function getLibelleTypeDecoupage()
    {
        return $this->libelleTypeDecoupage;
    }

    /**
     * Set etatTypeDecoupage
     *
     * @param integer $etatTypeDecoupage
     *
     * @return TypeDecoupage
     */
    public function setEtatTypeDecoupage($etatTypeDecoupage)
    {
        $this->etatTypeDecoupage = $etatTypeDecoupage;

        return $this;
    }

    /**
     * Get etatTypeDecoupage
     *
     * @return integer
     */
    public function getEtatTypeDecoupage()
    {
        return $this->etatTypeDecoupage;
    }

    /**
     * Add decoupage
     *
     * @param \admin\ScolariteBundle\Entity\Matiere $decoupage
     *
     * @return TypeDecoupage
     */
    public function addDecoupage(\admin\ScolariteBundle\Entity\Matiere $decoupage)
    {
        $this->decoupages[] = $decoupage;

        return $this;
    }

    /**
     * Remove decoupage
     *
     * @param \admin\ScolariteBundle\Entity\Matiere $decoupage
     */
    public function removeDecoupage(\admin\ScolariteBundle\Entity\Matiere $decoupage)
    {
        $this->decoupages->removeElement($decoupage);
    }

    /**
     * Get decoupages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDecoupages()
    {
        return $this->decoupages;
    }

    /**
     * Set descriptionTypeDecoupage
     *
     * @param string $descriptionTypeDecoupage
     *
     * @return TypeDecoupage
     */
    public function setDescriptionTypeDecoupage($descriptionTypeDecoupage)
    {
        $this->descriptionTypeDecoupage = $descriptionTypeDecoupage;

        return $this;
    }

    /**
     * Get descriptionTypeDecoupage
     *
     * @return string
     */
    public function getDescriptionTypeDecoupage()
    {
        return $this->descriptionTypeDecoupage;
    }


    /**
     * Add concerner
     *
     * @param \admin\ScolariteBundle\Entity\Concerner $concerner
     *
     * @return TypeDecoupage
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
