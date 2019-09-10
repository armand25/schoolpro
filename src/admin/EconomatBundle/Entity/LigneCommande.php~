<?php

namespace admin\EconomatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LigneCommande.
 *
 * @ORM\Table(name="Lignecommande")
 * @ORM\Entity(repositoryClass="admin\EconomatBundle\Entity\LigneCommandeRepository")
 */
class LigneCommande
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
     * @ORM\Column(name="quantite", type="integer", nullable=true)
     */
    private $quantite;
    /**
     * @var integer
     *
     * @ORM\Column(name="quantite_livre", type="integer", nullable=true)
     */
    private $quantiteLivre;
    /**
     * @var integer
     *
     * @ORM\Column(name="quantite_reste", type="integer",nullable=true)
     */
    private $quantiteReste;

    
    /**
     * @var int
     *
     * @ORM\Column(name="etat_ligne_commande", type="smallint")
     */
    private $etatLigneCommande;


    /**
     * @var Produit  produit
     * @ORM\ManyToOne(targetEntity="admin\EconomatBundle\Entity\Produit", inversedBy="lignecommandes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $produit;
    
    /**
     * @var ArrayCollection livrers
     * @ORM\OneToMany(targetEntity="admin\EconomatBundle\Entity\Livrer", cascade={"persist", "remove"}, mappedBy="lignecommande")
     */
    private $livrers;     
    
    /**
     * @var Commande  commande
     * @ORM\ManyToOne(targetEntity="admin\EconomatBundle\Entity\Commande", inversedBy="lignecommandes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $commande;  
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="annule", type="boolean")
     */
    private $annule;
    
    
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
        $this->etatLigneCommande = 0;
        $this->quantiteLivre = 0;
        $this->quantiteReste = 0;
        $this->etatLigneCommande = 0;
        $this->livrers = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return LigneCommande
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
     * Set etatLigneCommande
     *
     * @param integer $etatLigneCommande
     * @return LigneCommande
     */
    public function setEtatLigneCommande($etatLigneCommande)
    {
        $this->etatLigneCommande = $etatLigneCommande;
    
        return $this;
    }

    /**
     * Get etatLigneCommande
     *
     * @return integer 
     */
    public function getEtatLigneCommande()
    {
        return $this->etatLigneCommande;
    }

    /**
     * Set produit
     *
     * @param \admin\EconomatBundle\Entity\Produit $produit
     * @return LigneCommande
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
     * Add livrers
     *
     * @param \admin\EconomatBundle\Entity\Livrer $livrers
     * @return LigneCommande
     */
    public function addLivrer(\admin\EconomatBundle\Entity\Livrer $livrers)
    {
        $this->livrers[] = $livrers;
    
        return $this;
    }

    /**
     * Remove livrers
     *
     * @param \admin\EconomatBundle\Entity\Livrer $livrers
     */
    public function removeLivrer(\admin\EconomatBundle\Entity\Livrer $livrers)
    {
        $this->livrers->removeElement($livrers);
    }

    /**
     * Get livrers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLivrers()
    {
        return $this->livrers;
    }

    /**
     * Set commande
     *
     * @param \admin\EconomatBundle\Entity\Commande $commande
     * @return LigneCommande
     */
    public function setCommande(\admin\EconomatBundle\Entity\Commande $commande)
    {
        $this->commande = $commande;
    
        return $this;
    }

    /**
     * Get commande
     *
     * @return \admin\EconomatBundle\Entity\Commande 
     */
    public function getCommande()
    {
        return $this->commande;
    }

    /**
     * Set annule
     *
     * @param boolean $annule
     * @return LigneCommande
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
     * @return LigneCommande
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
     * @return LigneCommande
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
     * @return LigneCommande
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
     * Set quantiteLivre
     *
     * @param integer $quantiteLivre
     * @return LigneCommande
     */
    public function setQuantiteLivre($quantiteLivre)
    {
        $this->quantiteLivre = $quantiteLivre;
    
        return $this;
    }

    /**
     * Get quantiteLivre
     *
     * @return integer 
     */
    public function getQuantiteLivre()
    {
        return $this->quantiteLivre;
    }

    /**
     * Set quantiteReste
     *
     * @param integer $quantiteReste
     * @return LigneCommande
     */
    public function setQuantiteReste($quantiteReste)
    {
        $this->quantiteReste = $quantiteReste;
    
        return $this;
    }

    /**
     * Get quantiteReste
     *
     * @return integer 
     */
    public function getQuantiteReste()
    {
        return $this->quantiteReste;
    }
}
