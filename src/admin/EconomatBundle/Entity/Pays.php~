<?php

namespace admin\EconomatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Pays.
 *
 * @ORM\Table(name="pays")
 * @ORM\Entity(repositoryClass="admin\EconomatBundle\Entity\PaysRepository")
 */
class Pays
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
     * @ORM\Column(name="nom_pays", type="string", length=50)
     */
    private $nomPays;

    /**
     * @var string
     *
     * @ORM\Column(name="code_pays", type="string", length=10)
     */
    private $codePays;


    /**
     * @var int
     *
     * @ORM\Column(name="etat_pays", type="smallint")
     */
    private $etatPays;


    /**
     * @var ArrayCollection villes
     * @ORM\OneToMany(targetEntity="admin\EconomatBundle\Entity\Ville", cascade={"persist", "remove"}, mappedBy="pays")
     */
    private $villes;
 

    public function __construct()
    {
        $this->villes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nomPays
     *
     * @param string $nomPays
     * @return Pays
     */
    public function setNomPays($nomPays)
    {
        $this->nomPays = $nomPays;
    
        return $this;
    }

    /**
     * Get nomPays
     *
     * @return string 
     */
    public function getNomPays()
    {
        return $this->nomPays;
    }

    /**
     * Set codePays
     *
     * @param string $codePays
     * @return Pays
     */
    public function setCodePays($codePays)
    {
        $this->codePays = $codePays;
    
        return $this;
    }

    /**
     * Get codePays
     *
     * @return string 
     */
    public function getCodePays()
    {
        return $this->codePays;
    }

    /**
     * Set etatPays
     *
     * @param integer $etatPays
     * @return Pays
     */
    public function setEtatPays($etatPays)
    {
        $this->etatPays = $etatPays;
    
        return $this;
    }

    /**
     * Get etatPays
     *
     * @return integer 
     */
    public function getEtatPays()
    {
        return $this->etatPays;
    }

    /**
     * Add villes
     *
     * @param \admin\EconomatBundle\Entity\Ville $villes
     * @return Pays
     */
    public function addVille(\admin\EconomatBundle\Entity\Ville $villes)
    {
        $this->villes[] = $villes;
    
        return $this;
    }

    /**
     * Remove villes
     *
     * @param \admin\EconomatBundle\Entity\Ville $villes
     */
    public function removeVille(\admin\EconomatBundle\Entity\Ville $villes)
    {
        $this->villes->removeElement($villes);
    }

    /**
     * Get villes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVilles()
    {
        return $this->villes;
    }
}
