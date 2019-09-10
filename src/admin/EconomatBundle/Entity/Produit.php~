<?php

/**
 * Définition de l'entité Produit
 * Représente le type de champ à presenter à l'utilisateur par rapport au champ choisi
 * nous avons entre autre (Text, bouton radio etc ...)-.
 * 
 * @author TEVI Fessou Atassé <tevi.armand@gmail.com> 
 */
namespace admin\EconomatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Produit.
 *
 * @ORM\Table(name="Produit")
 * @ORM\Entity(repositoryClass="admin\EconomatBundle\Entity\ProduitRepository")
 */
class Produit
{
    public function __construct()
    {
        $this->montantHtAchat = 0;
        $this->montantHtVente = 0;
        $this->montantTtcVente = 0;
        $this->pourcentageProduit = 0;
        $this->tauxTva= 17;
        $this->autreTaxe = false;
    }

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

 /**
     * @var string
     *
     * @ORM\Column(name="code_produit", type="string", length=50)
     * @Assert\Length(max=50)
     */
    private $codeProduit;	
	
    /**
     * @var string
     *
     * @ORM\Column(name="nom_produit", type="string", length=50)
     * @Assert\Length(max=50)
     * @Assert\NotBlank(message="Libellé modéle requis !")
     */
    private $nomProduit;
    
    /**
     * @var string
     *
     * @ORM\Column(name="description_produit", type="string")
     */
    private $descriptionProduit;
    /**
     * @var string
     *
     * @ORM\Column(name="pourcentage_produit", type="string", nullable=true)
     */
    private $pourcentageProduit;
	
    /**
     * @var string
     *
     * @ORM\Column(name="poids_produit", type="string")
     */
    private $poidsProduit;	
	
    /**
     * @var integer
     *
     * @ORM\Column(name="seuil_produit", type="integer")
     */
    private $seuilProduit;		


    /**
     * @var string
     *
     * @ORM\Column(name="fabricat_produit", type="string")
     */
    private $fabricatProduit;	
    
    /**
     * @var date
     *
     * @ORM\Column(name="date_publication", type="date")
     */
    private $datePublication;
    
    /**
     * @var date
     *
     * @ORM\Column(name="date_commer", type="date")
     */
    private $dateCommer;

    
    /**
     * @var date
     *
     * @ORM\Column(name="date_modification", type="date")
     */
    private $dateModification;
    
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id_auteur", type="integer")
     */
    private $idAuteur;  
    
    
    /**
     * @var int
     *
     * @ORM\Column(name="etat_produit", type="integer")
     */
    private $etatProduit;

    
    /**
     * @var CategorieProduit  $categorie
     * @ORM\ManyToOne(targetEntity="admin\EconomatBundle\Entity\CategorieProduit", inversedBy="produits")
     * @ORM\JoinColumn(nullable=true)
     */
    private $categorie;  
    
    
    /**
     * @var PlanComptable $plancomptable
     * @ORM\ManyToOne(targetEntity="admin\EconomatBundle\Entity\PlanComptable", inversedBy="produits", cascade={"persist","merge"})
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="compte", referencedColumnName="compte")
     * })
     */
    private $plancomptable;     
    
    /**
     * @var Fournisseur  $fournisseur
     * @ORM\ManyToOne(targetEntity="admin\EconomatBundle\Entity\Fournisseur", inversedBy="produits")
     * @ORM\JoinColumn(nullable=true)
     */
    private $fournisseur;     
  
    
    /**
     * @var ArrayCollection lignecommandes
     * @ORM\OneToMany(targetEntity="admin\EconomatBundle\Entity\LigneCommande", cascade={"persist", "remove"}, mappedBy="produit")
     */
    private $lignecommandes;    
    
    /**
     * @var ArrayCollection imagepros
     * @ORM\OneToMany(targetEntity="admin\EconomatBundle\Entity\ImageProduit", cascade={"persist", "remove"}, mappedBy="produit")
     */
    private $imagepros; 	
    
    
    /**
     * @var int
     *
     * @ORM\Column(name="montanthtAchat", type="integer",nullable = true)
     */
    private $montantHtAchat; 
 
 /**
     * @var ArrayCollection operations
     * @ORM\OneToMany(targetEntity="admin\EconomatBundle\Entity\Operation", cascade={"persist", "remove"}, mappedBy="commande")
     */
    private $operations;    
    
        /**
     * @var int
     *
     * @ORM\Column(name="montanthtvente", type="integer", nullable = true)
     */
    private $montantHtVente; 
    
    /**
     * @var string
     *
     * @ORM\Column(name="tauxTva", type="string",nullable = true)
     */
    private $tauxTva;     
    
    
    /**
     * @var int
     *
     * @ORM\Column(name="montantttcvente", type="integer",nullable = true)
     */
    private $montantTtcVente; 

    /**
     * @var boolean
     *
     * @ORM\Column(name="autretaxe", type="boolean", nullable = true)
     */
    private $autreTaxe;      
    

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
     * Set codeProduit
     *
     * @param string $codeProduit
     * @return Produit
     */
    public function setCodeProduit($codeProduit)
    {
        $this->codeProduit = $codeProduit;
    
        return $this;
    }

    /**
     * Get codeProduit
     *
     * @return string 
     */
    public function getCodeProduit()
    {
        return $this->codeProduit;
    }

    /**
     * Set nomProduit
     *
     * @param string $nomProduit
     * @return Produit
     */
    public function setNomProduit($nomProduit)
    {
        $this->nomProduit = $nomProduit;
    
        return $this;
    }

    /**
     * Get nomProduit
     *
     * @return string 
     */
    public function getNomProduit()
    {
        return $this->nomProduit;
    }

    /**
     * Set descriptionProduit
     *
     * @param string $descriptionProduit
     * @return Produit
     */
    public function setDescriptionProduit($descriptionProduit)
    {
        $this->descriptionProduit = $descriptionProduit;
    
        return $this;
    }

    /**
     * Get descriptionProduit
     *
     * @return string 
     */
    public function getDescriptionProduit()
    {
        return $this->descriptionProduit;
    }

    /**
     * Set poidsProduit
     *
     * @param string $poidsProduit
     * @return Produit
     */
    public function setPoidsProduit($poidsProduit)
    {
        $this->poidsProduit = $poidsProduit;
    
        return $this;
    }

    /**
     * Get poidsProduit
     *
     * @return string 
     */
    public function getPoidsProduit()
    {
        return $this->poidsProduit;
    }

    /**
     * Set seuilProduit
     *
     * @param integer $seuilProduit
     * @return Produit
     */
    public function setSeuilProduit($seuilProduit)
    {
        $this->seuilProduit = $seuilProduit;
    
        return $this;
    }

    /**
     * Get seuilProduit
     *
     * @return integer 
     */
    public function getSeuilProduit()
    {
        return $this->seuilProduit;
    }

    /**
     * Set fabricatProduit
     *
     * @param string $fabricatProduit
     * @return Produit
     */
    public function setFabricatProduit($fabricatProduit)
    {
        $this->fabricatProduit = $fabricatProduit;
    
        return $this;
    }

    /**
     * Get fabricatProduit
     *
     * @return string 
     */
    public function getFabricatProduit()
    {
        return $this->fabricatProduit;
    }

    /**
     * Set datePublication
     *
     * @param \DateTime $datePublication
     * @return Produit
     */
    public function setDatePublication($datePublication)
    {
        $this->datePublication = $datePublication;
    
        return $this;
    }

    /**
     * Get datePublication
     *
     * @return \DateTime 
     */
    public function getDatePublication()
    {
        return $this->datePublication;
    }

    /**
     * Set dateCommer
     *
     * @param \DateTime $dateCommer
     * @return Produit
     */
    public function setDateCommer($dateCommer)
    {
        $this->dateCommer = $dateCommer;
    
        return $this;
    }

    /**
     * Get dateCommer
     *
     * @return \DateTime 
     */
    public function getDateCommer()
    {
        return $this->dateCommer;
    }

    /**
     * Set dateModification
     *
     * @param \DateTime $dateModification
     * @return Produit
     */
    public function setDateModification($dateModification)
    {
        $this->dateModification = $dateModification;
    
        return $this;
    }

    /**
     * Get dateModification
     *
     * @return \DateTime 
     */
    public function getDateModification()
    {
        return $this->dateModification;
    }

    /**
     * Set idAuteur
     *
     * @param integer $idAuteur
     * @return Produit
     */
    public function setIdAuteur($idAuteur)
    {
        $this->idAuteur = $idAuteur;
    
        return $this;
    }

    /**
     * Get idAuteur
     *
     * @return integer 
     */
    public function getIdAuteur()
    {
        return $this->idAuteur;
    }

    /**
     * Set etatProduit
     *
     * @param integer $etatProduit
     * @return Produit
     */
    public function setEtatProduit($etatProduit)
    {
        $this->etatProduit = $etatProduit;
    
        return $this;
    }

    /**
     * Get etatProduit
     *
     * @return integer 
     */
    public function getEtatProduit()
    {
        return $this->etatProduit;
    }

    /**
     * Set categorie
     *
     * @param \admin\EconomatBundle\Entity\Categorie $categorie
     * @return Produit
     */
    public function setCategorie(\admin\EconomatBundle\Entity\CategorieProduit $categorie)
    {
        $this->categorie = $categorie;
    
        return $this;
    }

    /**
     * Get categorie
     *
     * @return \admin\EconomatBundle\Entity\Categorie 
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set fournisseur
     *
     * @param \admin\EconomatBundle\Entity\Fournisseur $fournisseur
     * @return Produit
     */
    public function setFournisseur(\admin\EconomatBundle\Entity\Fournisseur $fournisseur)
    {
        $this->fournisseur = $fournisseur;
    
        return $this;
    }

    /**
     * Get fournisseur
     *
     * @return \admin\EconomatBundle\Entity\Fournisseur 
     */
    public function getFournisseur()
    {
        return $this->fournisseur;
    }

    /**
     * Add lignecommandes
     *
     * @param \admin\EconomatBundle\Entity\LigneCommande $lignecommandes
     * @return Produit
     */
    public function addLignecommande(\admin\EconomatBundle\Entity\LigneCommande $lignecommandes)
    {
        $this->lignecommandes[] = $lignecommandes;
    
        return $this;
    }

    /**
     * Remove lignecommandes
     *
     * @param \admin\EconomatBundle\Entity\LigneCommande $lignecommandes
     */
    public function removeLignecommande(\admin\EconomatBundle\Entity\LigneCommande $lignecommandes)
    {
        $this->lignecommandes->removeElement($lignecommandes);
    }

    /**
     * Get lignecommandes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLignecommandes()
    {
        return $this->lignecommandes;
    }

    /**
     * Add imagepros
     *
     * @param \admin\EconomatBundle\Entity\ImageProduit $imagepros
     * @return Produit
     */
    public function addImagepro(\admin\EconomatBundle\Entity\ImageProduit $imagepros)
    {
        $this->imagepros[] = $imagepros;
    
        return $this;
    }

    /**
     * Remove imagepros
     *
     * @param \admin\EconomatBundle\Entity\ImageProduit $imagepros
     */
    public function removeImagepro(\admin\EconomatBundle\Entity\ImageProduit $imagepros)
    {
        $this->imagepros->removeElement($imagepros);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImagepros()
    {
        return $this->imagepros;
    }

    /**
     * Set montantHtAchat
     *
     * @param integer $montantHtAchat
     * @return Produit
     */
    public function setMontantHtAchat($montantHtAchat)
    {
        $this->montantHtAchat = $montantHtAchat;
    
        return $this;
    }

    /**
     * Get montantHtAchat
     *
     * @return integer 
     */
    public function getMontantHtAchat()
    {
        return $this->montantHtAchat;
    }

    /**
     * Set montantHtVente
     *
     * @param integer $montantHtVente
     * @return Produit
     */
    public function setMontantHtVente($montantHtVente)
    {
        $this->montantHtVente = $montantHtVente;
    
        return $this;
    }

    /**
     * Get montantHtVente
     *
     * @return integer 
     */
    public function getMontantHtVente()
    {
        return $this->montantHtVente;
    }



    /**
     * Set autreTaxe
     *
     * @param boolean $autreTaxe
     * @return Produit
     */
    public function setAutreTaxe($autreTaxe)
    {
        $this->autreTaxe = $autreTaxe;
    
        return $this;
    }

    /**
     * Get autreTaxe
     *
     * @return boolean 
     */
    public function getAutreTaxe()
    {
        return $this->autreTaxe;
    }

    /**
     * Set tauxTva
     *
     * @param string $tauxTva
     * @return Produit
     */
    public function setTauxTva($tauxTva)
    {
        $this->tauxTva = $tauxTva;
    
        return $this;
    }

    /**
     * Get tauxTva
     *
     * @return string 
     */
    public function getTauxTva()
    {
        return $this->tauxTva;
    }

    /**
     * Set montantTtcVente
     *
     * @param integer $montantTtcVente
     * @return Produit
     */
    public function setMontantTtcVente($montantTtcVente)
    {
        $this->montantTtcVente = $montantTtcVente;
    
        return $this;
    }

    /**
     * Get montantTtcVente
     *
     * @return integer 
     */
    public function getMontantTtcVente()
    {
        return $this->montantTtcVente;
    }

    /**
     * Add operations
     *
     * @param \admin\EconomatBundle\Entity\Operation $operations
     * @return Produit
     */
    public function addOperation(\admin\EconomatBundle\Entity\Operation $operations)
    {
        $this->operations[] = $operations;
    
        return $this;
    }

    /**
     * Remove operations
     *
     * @param \admin\EconomatBundle\Entity\Operation $operations
     */
    public function removeOperation(\admin\EconomatBundle\Entity\Operation $operations)
    {
        $this->operations->removeElement($operations);
    }

    /**
     * Get operations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOperations()
    {
        return $this->operations;
    }

    /**
     * Set plancomptable
     *
     * @param \admin\EconomatBundle\Entity\PlanComptable $plancomptable
     * @return Produit
     */
    public function setPlancomptable(\admin\EconomatBundle\Entity\PlanComptable $plancomptable = null)
    {
        $this->plancomptable = $plancomptable;
    
        return $this;
    }

    /**
     * Get plancomptable
     *
     * @return \admin\EconomatBundle\Entity\PlanComptable 
     */
    public function getPlancomptable()
    {
        return $this->plancomptable;
    }

    /**
     * Set pourcentageProduit
     *
     * @param string $pourcentageProduit
     * @return Produit
     */
    public function setPourcentageProduit($pourcentageProduit)
    {
        $this->pourcentageProduit = $pourcentageProduit;
    
        return $this;
    }

    /**
     * Get pourcentageProduit
     *
     * @return string 
     */
    public function getPourcentageProduit()
    {
        return $this->pourcentageProduit;
    }
}
