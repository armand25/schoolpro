<?php

namespace admin\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use \admin\UserBundle\Types\TypeEtat;


/**
 * User
 *
 * @ORM\Table(name="type_abonne")
 * @ORM\Entity(repositoryClass="admin\UserBundle\Entity\TypeAbonneRepository")
 */
class TypeAbonne {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @var string $nom
     *
     * @ORM\Column(name="nom_type_abonne", type="string", length=50, nullable=true)
     */
    private $nom;

    /**
     * @var string $description
     *
     * @ORM\Column(name="desc_type_abonne",   type="text", nullable=true)
     */
    private $description;

    /**
     * @var integer $etat
     *
     * @ORM\Column(name="etat_type_abonne", type="smallint")
     */
    private $etat;
    
    /**
     *
     * @var ArrayCollection $abonnes 
     * @ORM\OneToMany(targetEntity="admin\UserBundle\Entity\Abonne", cascade={"persist", "remove"}, mappedBy="typeAbonne")
     * 
     */
    private $abonnes;
    

    public function __construct() {
        $this->etat = TypeEtat::ACTIF;
        $this->description = '__';
    }


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
     * Set nom
     *
     * @param string $nom
     * @return TypeAbonne
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    
        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return TypeAbonne
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set etat
     *
     * @param integer $etat
     * @return TypeAbonne
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;
    
        return $this;
    }

    /**
     * Get etat
     *
     * @return integer 
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Add abonnes
     *
     * @param \admin\UserBundle\Entity\Abonne $abonnes
     * @return TypeAbonne
     */
    public function addAbonne(\admin\UserBundle\Entity\Abonne $abonnes)
    {
        $this->abonnes[] = $abonnes;
    
        return $this;
    }

    /**
     * Remove abonnes
     *
     * @param \admin\UserBundle\Entity\Abonne $abonnes
     */
    public function removeAbonne(\admin\UserBundle\Entity\Abonne $abonnes)
    {
        $this->abonnes->removeElement($abonnes);
    }

    /**
     * Get abonnes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAbonnes()
    {
        return $this->abonnes;
    }
}
