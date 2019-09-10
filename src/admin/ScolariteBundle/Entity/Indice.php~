<?php

namespace admin\ScolariteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \admin\UserBundle\Types\TypeEtat;

/**
 * Indice
 *
 * @ORM\Table(name="indice")
 * @ORM\Entity(repositoryClass="admin\ScolariteBundle\Entity\IndiceRepository")
 */
class Indice
{
     public function __construct() {
        $this->etatIndice = TypeEtat::ACTIF;
       
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
     * @var string $libelleIndice
     *
     * @ORM\Column(name="libelle_indice", type="string")
     */
    private $libelleIndice;
    /**
     * @var string $descriptionIndice
     *
     * @ORM\Column(name="description_indice", type="string")
     */
    private $descriptionIndice;
    /**
     * @var string $etatIndice
     *
     * @ORM\Column(name="etat_indice", type="integer")
     */
    private $etatIndice;
    


    /**
     *
     * @var ArrayCollection $classes 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\Classe", cascade={"persist", "remove"}, mappedBy="indice")
     * 
     */
    private $classes;     


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
     * Set libelleIndice
     *
     * @param string $libelleIndice
     *
     * @return Indice
     */
    public function setLibelleIndice($libelleIndice)
    {
        $this->libelleIndice = $libelleIndice;

        return $this;
    }

    /**
     * Get libelleIndice
     *
     * @return string
     */
    public function getLibelleIndice()
    {
        return $this->libelleIndice;
    }

    /**
     * Set etatIndice
     *
     * @param integer $etatIndice
     *
     * @return Indice
     */
    public function setEtatIndice($etatIndice)
    {
        $this->etatIndice = $etatIndice;

        return $this;
    }

    /**
     * Get etatIndice
     *
     * @return integer
     */
    public function getEtatIndice()
    {
        return $this->etatIndice;
    }

    /**
     * Add classe
     *
     * @param \admin\ScolariteBundle\Entity\Classe $classe
     *
     * @return Indice
     */
    public function addClasse(\admin\ScolariteBundle\Entity\Classe $classe)
    {
        $this->classes[] = $classe;

        return $this;
    }

    /**
     * Remove classe
     *
     * @param \admin\ScolariteBundle\Entity\Classe $classe
     */
    public function removeClasse(\admin\ScolariteBundle\Entity\Classe $classe)
    {
        $this->classes->removeElement($classe);
    }

    /**
     * Get classes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * Set descriptionIndice
     *
     * @param string $descriptionIndice
     *
     * @return Indice
     */
    public function setDescriptionIndice($descriptionIndice)
    {
        $this->descriptionIndice = $descriptionIndice;

        return $this;
    }

    /**
     * Get descriptionIndice
     *
     * @return string
     */
    public function getDescriptionIndice()
    {
        return $this->descriptionIndice;
    }

    /**
     * Add class
     *
     * @param \admin\ScolariteBundle\Entity\Classe $class
     *
     * @return Indice
     */
    public function addClass(\admin\ScolariteBundle\Entity\Classe $class)
    {
        $this->classes[] = $class;

        return $this;
    }

    /**
     * Remove class
     *
     * @param \admin\ScolariteBundle\Entity\Classe $class
     */
    public function removeClass(\admin\ScolariteBundle\Entity\Classe $class)
    {
        $this->classes->removeElement($class);
    }
}
