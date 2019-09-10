<?php

namespace admin\ScolariteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \admin\UserBundle\Types\TypeEtat;

/**
 * Chapitre
 *
 * @ORM\Table(name="chapitre")
 * @ORM\Entity(repositoryClass="admin\ScolariteBundle\Entity\ChapitreRepository")
 */
class Chapitre
{
     public function __construct() {
        $this->etatChapitre = TypeEtat::ACTIF;
       
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
     * @var string $libelleChapitre
     *
     * @ORM\Column(name="libelle_chapitre", type="string")
     */
    private $libelleChapitre;
    /**
     * @var string $descriptionChapitre
     *
     * @ORM\Column(name="description_chapitre", type="string")
     */
    private $descriptionChapitre;
    /**
     * @var string $etatChapitre
     *
     * @ORM\Column(name="etat_chapitre", type="integer")
     */
    private $etatChapitre;
        /**
     * @var EstEnseigne  $estenseigne
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\EstEnseigne", inversedBy="chapitres")
     * @ORM\JoinColumn(nullable=true)
     */
    private $estenseigne;  
    
    /**
     * @var ArrayCollection Exercice $exercices
     * @ORM\ManyToMany(targetEntity="admin\ScolariteBundle\Entity\Exercice", inversedBy="chapitres",cascade={"persist","merge"})
     * 
     */
    private $exercices; 
    

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
     * Set libelleChapitre
     *
     * @param string $libelleChapitre
     *
     * @return Chapitre
     */
    public function setLibelleChapitre($libelleChapitre)
    {
        $this->libelleChapitre = $libelleChapitre;

        return $this;
    }

    /**
     * Get libelleChapitre
     *
     * @return string
     */
    public function getLibelleChapitre()
    {
        return $this->libelleChapitre;
    }

    /**
     * Set descriptionChapitre
     *
     * @param string $descriptionChapitre
     *
     * @return Chapitre
     */
    public function setDescriptionChapitre($descriptionChapitre)
    {
        $this->descriptionChapitre = $descriptionChapitre;

        return $this;
    }

    /**
     * Get descriptionChapitre
     *
     * @return string
     */
    public function getDescriptionChapitre()
    {
        return $this->descriptionChapitre;
    }

    /**
     * Set etatChapitre
     *
     * @param integer $etatChapitre
     *
     * @return Chapitre
     */
    public function setEtatChapitre($etatChapitre)
    {
        $this->etatChapitre = $etatChapitre;

        return $this;
    }

    /**
     * Get etatChapitre
     *
     * @return integer
     */
    public function getEtatChapitre()
    {
        return $this->etatChapitre;
    }

    /**
     * Set estenseigne
     *
     * @param \admin\ScolariteBundle\Entity\EstEnseigne $estenseigne
     *
     * @return Chapitre
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
     * Add exercice
     *
     * @param \admin\ScolariteBundle\Entity\Exercice $exercice
     *
     * @return Chapitre
     */
    public function addExercice(\admin\ScolariteBundle\Entity\Exercice $exercice)
    {
        $this->exercices[] = $exercice;

        return $this;
    }

    /**
     * Remove exercice
     *
     * @param \admin\ScolariteBundle\Entity\Exercice $exercice
     */
    public function removeExercice(\admin\ScolariteBundle\Entity\Exercice $exercice)
    {
        $this->exercices->removeElement($exercice);
    }

    /**
     * Get exercices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExercices()
    {
        return $this->exercices;
    }
}
