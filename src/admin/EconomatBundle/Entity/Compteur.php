<?php

namespace admin\EconomatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Caisse.
 *
 * @ORM\Table(name="compteur")
 * @ORM\Entity(repositoryClass="admin\EconomatBundle\Entity\CompteurRepository")
 */
class Compteur
{
    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var int
     *
     * @ORM\Column(name="annee", type="integer")
     */
    private $annee;

    /**
     * @var string
     *
     * @ORM\Column(name="mois", type="integer")
     */
    private $mois;

    /**
     * @var int
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type;    
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="compteur", type="string", length=5)
     */
    private $compteur;
    
    /**
     * @var string
     *
     * @ORM\Column(name="type_compt", type="string", length=20)
     */
    private $typeCompt;

    /**
     * @var string
     *
     * @ORM\Column(name="entite", type="string", length=25)
     */
    private $entite;
    

    public function __construct()
    {
        $this->compteur = 0;
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
     * Set annee
     *
     * @param integer $annee
     *
     * @return Compteur
     */
    public function setAnnee($annee)
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * Get annee
     *
     * @return integer
     */
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
     * Set mois
     *
     * @param integer $mois
     *
     * @return Compteur
     */
    public function setMois($mois)
    {
        $this->mois = $mois;

        return $this;
    }

    /**
     * Get mois
     *
     * @return integer
     */
    public function getMois()
    {
        return $this->mois;
    }

    /**
     * Set type
     *
     * @param integer $type
     *
     * @return Compteur
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set compteur
     *
     * @param string $compteur
     *
     * @return Compteur
     */
    public function setCompteur($compteur)
    {
        $this->compteur = $compteur;

        return $this;
    }

    /**
     * Get compteur
     *
     * @return string
     */
    public function getCompteur()
    {
        return $this->compteur;
    }

    /**
     * Set entite
     *
     * @param string $entite
     *
     * @return Compteur
     */
    public function setEntite($entite)
    {
        $this->entite = $entite;

        return $this;
    }

    /**
     * Get entite
     *
     * @return string
     */
    public function getEntite()
    {
        return $this->entite;
    }

    /**
     * Set typeCompt
     *
     * @param string $typeCompt
     *
     * @return Compteur
     */
    public function setTypeCompt($typeCompt)
    {
        $this->typeCompt = $typeCompt;

        return $this;
    }

    /**
     * Get typeCompt
     *
     * @return string
     */
    public function getTypeCompt()
    {
        return $this->typeCompt;
    }
}
