<?php

namespace admin\EconomatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ville.
 *
 * @ORM\Table(name="ville")
 * @ORM\Entity(repositoryClass="admin\EconomatBundle\Entity\VilleRepository")
 */
class Ville
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
     * @var string
     *
     * @ORM\Column(name="nom_ville", type="string", length=50)
     */
    private $nomVille;

    /**
     * @var int
     *
     * @ORM\Column(name="etat_ville", type="smallint")
     */
    private $etatVille;


 
    /**
     * @var Pays  pays
     * @ORM\ManyToOne(targetEntity="admin\EconomatBundle\Entity\Pays", inversedBy="villes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $pays; 
    /**
     * @var ArrayCollection entrepots
     * @ORM\OneToMany(targetEntity="admin\EconomatBundle\Entity\Entrepot", cascade={"persist", "remove"}, mappedBy="ville")
     */
    private $entrepots;
 

    public function __construct()
    {
        $this->entrepots = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nomVille
     *
     * @param string $nomVille
     * @return Ville
     */
    public function setNomVille($nomVille)
    {
        $this->nomVille = $nomVille;
    
        return $this;
    }

    /**
     * Get nomVille
     *
     * @return string 
     */
    public function getNomVille()
    {
        return $this->nomVille;
    }

    /**
     * Set etatVille
     *
     * @param integer $etatVille
     * @return Ville
     */
    public function setEtatVille($etatVille)
    {
        $this->etatVille = $etatVille;
    
        return $this;
    }

    /**
     * Get etatVille
     *
     * @return integer 
     */
    public function getEtatVille()
    {
        return $this->etatVille;
    }

    /**
     * Set pays
     *
     * @param \admin\EconomatBundle\Entity\Pays $pays
     * @return Ville
     */
    public function setPays(\admin\EconomatBundle\Entity\Pays $pays)
    {
        $this->pays = $pays;
    
        return $this;
    }

    /**
     * Get pays
     *
     * @return \admin\EconomatBundle\Entity\Pays 
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * Add entrepots
     *
     * @param \admin\EconomatBundle\Entity\Entrepot $entrepots
     * @return Ville
     */
    public function addEntrepot(\admin\EconomatBundle\Entity\Entrepot $entrepots)
    {
        $this->entrepots[] = $entrepots;
    
        return $this;
    }

    /**
     * Remove entrepots
     *
     * @param \admin\EconomatBundle\Entity\Entrepot $entrepots
     */
    public function removeEntrepot(\admin\EconomatBundle\Entity\Entrepot $entrepots)
    {
        $this->entrepots->removeElement($entrepots);
    }

    /**
     * Get entrepots
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEntrepots()
    {
        return $this->entrepots;
    }
}
