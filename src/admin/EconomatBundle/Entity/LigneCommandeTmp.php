<?php

namespace admin\EconomatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LigneCommande.
 *
 * @ORM\Table(name="lignecommandetmp")
 * @ORM\Entity(repositoryClass="admin\EconomatBundle\Entity\LigneCommandeTmpRepository")
 */
class LigneCommandeTmp
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
     * @var integer
     *
     * @ORM\Column(name="quantite", type="integer", length=50)
     */
    private $quantite;

    /**
     * @var Produit  $produit
     * @ORM\ManyToOne(targetEntity="admin\EconomatBundle\Entity\Produit", inversedBy="lignecommandes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $produit;
    
   
    /**
     * @var boolean
     *
     * @ORM\Column(name="annule", type="boolean")
     */
    private $annule;
    
    /**
     * @var CommandeTmp  $commandeTmp
     * @ORM\ManyToOne(targetEntity="admin\EconomatBundle\Entity\CommandeTmp", inversedBy="lignecommandeTmps")
     * @ORM\JoinColumn(nullable=true)
     */
    private $commandeTmp;  
    
    
    /**
     * @var float
     *
     * @ORM\Column(name="tauxtva", type="float", nullable = true)
     */
    private $tauxTva;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="montantht", type="integer", nullable = true)
     */
    private $montantHt;
    
    
    /**
     * @var integer
     *
     * @ORM\Column(name="montantautretaxe", type="integer", nullable = true)
     */
    private $montantAutreTaxe;

    public function __construct()
    {

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
     * Set quantite
     *
     * @param integer $quantite
     * @return LigneCommandeTmp
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;
    
        return $this;
    }

    /**
     * Get quantite
     *
     * @return integer 
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * Set annule
     *
     * @param boolean $annule
     * @return LigneCommandeTmp
     */
    public function setAnnule($annule)
    {
        $this->annule = $annule;
    
        return $this;
    }

    /**
     * Get annule
     *
     * @return boolean 
     */
    public function getAnnule()
    {
        return $this->annule;
    }

    /**
     * Set tauxTva
     *
     * @param float $tauxTva
     * @return LigneCommandeTmp
     */
    public function setTauxTva($tauxTva)
    {
        $this->tauxTva = $tauxTva;
    
        return $this;
    }

    /**
     * Get tauxTva
     *
     * @return float 
     */
    public function getTauxTva()
    {
        return $this->tauxTva;
    }

    /**
     * Set montantHt
     *
     * @param integer $montantHt
     * @return LigneCommandeTmp
     */
    public function setMontantHt($montantHt)
    {
        $this->montantHt = $montantHt;
    
        return $this;
    }

    /**
     * Get montantHt
     *
     * @return integer 
     */
    public function getMontantHt()
    {
        return $this->montantHt;
    }

    /**
     * Set montantAutreTaxe
     *
     * @param integer $montantAutreTaxe
     * @return LigneCommandeTmp
     */
    public function setMontantAutreTaxe($montantAutreTaxe)
    {
        $this->montantAutreTaxe = $montantAutreTaxe;
    
        return $this;
    }

    /**
     * Get montantAutreTaxe
     *
     * @return integer 
     */
    public function getMontantAutreTaxe()
    {
        return $this->montantAutreTaxe;
    }

    /**
     * Set produit
     *
     * @param \admin\EconomatBundle\Entity\Produit $produit
     * @return LigneCommandeTmp
     */
    public function setProduit(\admin\EconomatBundle\Entity\Produit $produit)
    {
        $this->produit = $produit;
    
        return $this;
    }

    /**
     * Get produit
     *
     * @return \admin\EconomatBundle\Entity\Produit 
     */
    public function getProduit()
    {
        return $this->produit;
    }

    /**
     * Set commandeTmp
     *
     * @param \admin\EconomatBundle\Entity\CommandeTmp $commandeTmp
     * @return LigneCommandeTmp
     */
    public function setCommandeTmp(\admin\EconomatBundle\Entity\CommandeTmp $commandeTmp)
    {
        $this->commandeTmp = $commandeTmp;
    
        return $this;
    }

    /**
     * Get commandeTmp
     *
     * @return \admin\EconomatBundle\Entity\CommandeTmp 
     */
    public function getCommandeTmp()
    {
        return $this->commandeTmp;
    }
}
