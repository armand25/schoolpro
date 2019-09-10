<?php

namespace admin\ScolariteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \admin\UserBundle\Types\TypeEtat;

/**
 * TypeMatiere
 *
 * @ORM\Table(name="type_matiere")
 * @ORM\Entity(repositoryClass="admin\ScolariteBundle\Entity\TypeMatiereRepository")
 */
class TypeMatiere
{
     public function __construct() {
        $this->etatTypeMatiere = TypeEtat::ACTIF;
       
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
     * @var string $libelleTypeMatiere
     *
     * @ORM\Column(name="libelle_type_matiere", type="string")
     */
    private $libelleTypeMatiere;
    /**
     * @var string $descriptionTypeMatiere
     *
     * @ORM\Column(name="description_type_matiere", type="string")
     */
    private $descriptionTypeMatiere;
    /**
     * @var string $etatTypeMatiere
     *
     * @ORM\Column(name="etat_type_matiere", type="integer")
     */
    private $etatTypeMatiere;
    
    /**
     *
     * @var ArrayCollection $estenseignes 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\EstEnseigne", cascade={"persist", "remove"}, mappedBy="typematiere")
     * 
     */
    private $estenseignes;    


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
     * Set libelleTypeMatiere
     *
     * @param string $libelleTypeMatiere
     *
     * @return TypeMatiere
     */
    public function setLibelleTypeMatiere($libelleTypeMatiere)
    {
        $this->libelleTypeMatiere = $libelleTypeMatiere;

        return $this;
    }

    /**
     * Get libelleTypeMatiere
     *
     * @return string
     */
    public function getLibelleTypeMatiere()
    {
        return $this->libelleTypeMatiere;
    }

    /**
     * Set etatTypeMatiere
     *
     * @param integer $etatTypeMatiere
     *
     * @return TypeMatiere
     */
    public function setEtatTypeMatiere($etatTypeMatiere)
    {
        $this->etatTypeMatiere = $etatTypeMatiere;

        return $this;
    }

    /**
     * Get etatTypeMatiere
     *
     * @return integer
     */
    public function getEtatTypeMatiere()
    {
        return $this->etatTypeMatiere;
    }

    /**
     * Add estenseigne
     *
     * @param \admin\ScolariteBundle\Entity\EstEnseigne $estenseigne
     *
     * @return TypeMatiere
     */
    public function addEstenseigne(\admin\ScolariteBundle\Entity\EstEnseigne $estenseigne)
    {
        $this->estenseignes[] = $estenseigne;

        return $this;
    }

    /**
     * Remove estenseigne
     *
     * @param \admin\ScolariteBundle\Entity\EstEnseigne $estenseigne
     */
    public function removeEstenseigne(\admin\ScolariteBundle\Entity\EstEnseigne $estenseigne)
    {
        $this->estenseignes->removeElement($estenseigne);
    }

    /**
     * Get estenseignes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEstenseignes()
    {
        return $this->estenseignes;
    }

    /**
     * Set descriptionTypeMatiere
     *
     * @param string $descriptionTypeMatiere
     *
     * @return TypeMatiere
     */
    public function setDescriptionTypeMatiere($descriptionTypeMatiere)
    {
        $this->descriptionTypeMatiere = $descriptionTypeMatiere;

        return $this;
    }

    /**
     * Get descriptionTypeMatiere
     *
     * @return string
     */
    public function getDescriptionTypeMatiere()
    {
        return $this->descriptionTypeMatiere;
    }
}
