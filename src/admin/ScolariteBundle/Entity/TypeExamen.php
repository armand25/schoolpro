<?php

namespace admin\ScolariteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \admin\UserBundle\Types\TypeEtat;

/**
 * TypeExamen
 *
 * @ORM\Table(name="type_examen")
 * @ORM\Entity(repositoryClass="admin\ScolariteBundle\Entity\TypeExamenRepository")
 */
class TypeExamen
{
     public function __construct() {
        $this->etatTypeExamen = TypeEtat::ACTIF;
       
    }
    
    /**
     * @var integer $id
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $libelleTypeExamen
     *
     * @ORM\Column(name="libelle_type_examen", type="string")
     */
    private $libelleTypeExamen;
    /**
     * @var string $descriptionTypeExamen
     *
     * @ORM\Column(name="description_type_examen", type="string")
     */
    private $descriptionTypeExamen;
    /**
     * @var string $etatTypeExamen
     *
     * @ORM\Column(name="etat_type_examen", type="integer")
     */
    private $etatTypeExamen;
     /**
     *
     * @var ArrayCollection $epreuves 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\Epreuve", cascade={"persist", "remove"}, mappedBy="typeexamen")
     * 
     */
    private $epreuves;  
   
    
    
        /**
     *
     * @var ArrayCollection $notes
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\Note", cascade={"persist", "remove"}, mappedBy="typeexamen")
     * 
     */
    private $notes; 

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
     * Set libelleTypeExamen
     *
     * @param string $libelleTypeExamen
     *
     * @return TypeExamen
     */
    public function setLibelleTypeExamen($libelleTypeExamen)
    {
        $this->libelleTypeExamen = $libelleTypeExamen;

        return $this;
    }

    /**
     * Get libelleTypeExamen
     *
     * @return string
     */
    public function getLibelleTypeExamen()
    {
        return $this->libelleTypeExamen;
    }

    /**
     * Set descriptionTypeExamen
     *
     * @param string $descriptionTypeExamen
     *
     * @return TypeExamen
     */
    public function setDescriptionTypeExamen($descriptionTypeExamen)
    {
        $this->descriptionTypeExamen = $descriptionTypeExamen;

        return $this;
    }

    /**
     * Get descriptionTypeExamen
     *
     * @return string
     */
    public function getDescriptionTypeExamen()
    {
        return $this->descriptionTypeExamen;
    }

    /**
     * Set etatTypeExamen
     *
     * @param integer $etatTypeExamen
     *
     * @return TypeExamen
     */
    public function setEtatTypeExamen($etatTypeExamen)
    {
        $this->etatTypeExamen = $etatTypeExamen;

        return $this;
    }

    /**
     * Get etatTypeExamen
     *
     * @return integer
     */
    public function getEtatTypeExamen()
    {
        return $this->etatTypeExamen;
    }

    /**
     * Add note
     *
     * @param \admin\ScolariteBundle\Entity\Note $note
     *
     * @return TypeExamen
     */
    public function addNote(\admin\ScolariteBundle\Entity\Note $note)
    {
        $this->notes[] = $note;

        return $this;
    }

    /**
     * Remove note
     *
     * @param \admin\ScolariteBundle\Entity\Note $note
     */
    public function removeNote(\admin\ScolariteBundle\Entity\Note $note)
    {
        $this->notes->removeElement($note);
    }

    /**
     * Get notes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Add epreufe
     *
     * @param \admin\ScolariteBundle\Entity\Epreuve $epreufe
     *
     * @return TypeExamen
     */
    public function addEpreufe(\admin\ScolariteBundle\Entity\Epreuve $epreufe)
    {
        $this->epreuves[] = $epreufe;

        return $this;
    }

    /**
     * Remove epreufe
     *
     * @param \admin\ScolariteBundle\Entity\Epreuve $epreufe
     */
    public function removeEpreufe(\admin\ScolariteBundle\Entity\Epreuve $epreufe)
    {
        $this->epreuves->removeElement($epreufe);
    }

    /**
     * Get epreuves
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEpreuves()
    {
        return $this->epreuves;
    }
}
