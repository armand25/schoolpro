<?php

namespace admin\ScolariteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \admin\UserBundle\Types\TypeEtat;

/**
 * Exercice
 *
 * @ORM\Table(name="exercice")
 * @ORM\Entity(repositoryClass="admin\ScolariteBundle\Entity\ExerciceRepository")
 */
class Exercice
{
     public function __construct() {
        $this->etatExercice = TypeEtat::ACTIF;
       
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
     * @var string $libelleExercice
     *
     * @ORM\Column(name="libelle_exercice", type="string")
     */
    private $libelleExercice;
    /**
     * @var string $descriptionExercice
     *
     * @ORM\Column(name="description_exercice", type="string", nullable=true)
     */
    private $descriptionExercice;
    /**
     * @var string $etatExercice
     *
     * @ORM\Column(name="etat_exercice", type="integer")
     */
    private $etatExercice;
    
    /**
     * @var integer $difficulteExercice
     *
     * @ORM\Column(name="difficulte_exercice", type="integer")
     */
    private $difficulteExercice;
    
    
    /**
     * @var Epreuve  $epreuve
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Epreuve", inversedBy="exercices")
     * @ORM\JoinColumn(nullable=true)
     */
    private $epreuve;  
      
    /**
     * @var ArrayCollection Chapitre $chapitres
     * @ORM\ManyToMany(targetEntity="admin\ScolariteBundle\Entity\Chapitre", inversedBy="exercices",cascade={"persist","merge"})
     * 
     */
    private $chapitres;    

 /**
     *
     * @var ArrayCollection $detailnotes 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\DetailsNote", cascade={"persist", "remove"}, mappedBy="exercice")
     * 
     */
    private $detailnotes;
   

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
     * Set libelleExercice
     *
     * @param string $libelleExercice
     *
     * @return Exercice
     */
    public function setLibelleExercice($libelleExercice)
    {
        $this->libelleExercice = $libelleExercice;

        return $this;
    }

    /**
     * Get libelleExercice
     *
     * @return string
     */
    public function getLibelleExercice()
    {
        return $this->libelleExercice;
    }

    /**
     * Set descriptionExercice
     *
     * @param string $descriptionExercice
     *
     * @return Exercice
     */
    public function setDescriptionExercice($descriptionExercice)
    {
        $this->descriptionExercice = $descriptionExercice;

        return $this;
    }

    /**
     * Get descriptionExercice
     *
     * @return string
     */
    public function getDescriptionExercice()
    {
        return $this->descriptionExercice;
    }

    /**
     * Set etatExercice
     *
     * @param integer $etatExercice
     *
     * @return Exercice
     */
    public function setEtatExercice($etatExercice)
    {
        $this->etatExercice = $etatExercice;

        return $this;
    }

    /**
     * Get etatExercice
     *
     * @return integer
     */
    public function getEtatExercice()
    {
        return $this->etatExercice;
    }

    /**
     * Set epreuve
     *
     * @param \admin\ScolariteBundle\Entity\Epreuve $epreuve
     *
     * @return Exercice
     */
    public function setEpreuve(\admin\ScolariteBundle\Entity\Epreuve $epreuve = null)
    {
        $this->epreuve = $epreuve;

        return $this;
    }

    /**
     * Get epreuve
     *
     * @return \admin\ScolariteBundle\Entity\Epreuve
     */
    public function getEpreuve()
    {
        return $this->epreuve;
    }

    /**
     * Set difficulteExercice
     *
     * @param integer $difficulteExercice
     *
     * @return Exercice
     */
    public function setDifficulteExercice($difficulteExercice)
    {
        $this->difficulteExercice = $difficulteExercice;

        return $this;
    }

    /**
     * Get difficulteExercice
     *
     * @return integer
     */
    public function getDifficulteExercice()
    {
        return $this->difficulteExercice;
    }

    /**
     * Add chapitre
     *
     * @param \admin\ScolariteBundle\Entity\Chapitre $chapitre
     *
     * @return Exercice
     */
    public function addChapitre(\admin\ScolariteBundle\Entity\Chapitre $chapitre)
    {
        $this->chapitres[] = $chapitre;

        return $this;
    }

    /**
     * Remove chapitre
     *
     * @param \admin\ScolariteBundle\Entity\Chapitre $chapitre
     */
    public function removeChapitre(\admin\ScolariteBundle\Entity\Chapitre $chapitre)
    {
        $this->chapitres->removeElement($chapitre);
    }

    /**
     * Get chapitres
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChapitres()
    {
        return $this->chapitres;
    }

    /**
     * Add detailnote
     *
     * @param \admin\ScolariteBundle\Entity\DetailsNote $detailnote
     *
     * @return Exercice
     */
    public function addDetailnote(\admin\ScolariteBundle\Entity\DetailsNote $detailnote)
    {
        $this->detailnotes[] = $detailnote;

        return $this;
    }

    /**
     * Remove detailnote
     *
     * @param \admin\ScolariteBundle\Entity\DetailsNote $detailnote
     */
    public function removeDetailnote(\admin\ScolariteBundle\Entity\DetailsNote $detailnote)
    {
        $this->detailnotes->removeElement($detailnote);
    }

    /**
     * Get detailnotes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDetailnotes()
    {
        return $this->detailnotes;
    }
}
