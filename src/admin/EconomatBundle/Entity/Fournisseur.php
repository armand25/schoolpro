<?php

/**
 * Définition de l'entité Fournisseur
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
 * Fournisseur.
 *
 * @ORM\Table(name="fournisseur")
 * @ORM\Entity(repositoryClass="admin\EconomatBundle\Entity\FournisseurRepository")
 */
class Fournisseur
{
    public function __construct()
    {
        //$this->etat = TypeEtat::ACTIF;
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
     * @ORM\Column(name="code_fournisseur", type="string", length=50)
     * @Assert\Length(max=50)
     */
    private $codeFournisseur;	
	
    /**
     * @var string
     *
     * @ORM\Column(name="nom_fournisseur", type="string", length=50)
     * @Assert\Length(max=50)
     * @Assert\NotBlank(message="Libellé fournisseur requis !")
     */
    private $nomFournisseur;
    
    /**
     * @var string
     *
     * @ORM\Column(name="contact_fournisseur", type="string")
     */
    private $contactFournisseur;
    
    /**
     * @var string
     *
     * @ORM\Column(name="adresse_fournisseur", type="string")
     */
    private $adresseFournisseur;       
    
    /**
     * @var string
     *
     * @ORM\Column(name="ressource_fournisseur", type="string")
     */
    private $ressourceFournisseur;       
    
    
    /**
     * @var date
     *
     * @ORM\Column(name="date_publication", type="date")
     */
    private $datePublication;
    
    
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
     * @ORM\Column(name="etat_fournisseur", type="integer")
     */
    private $etatFournisseur;

    /**
     * @var ArrayCollection produits
     * @ORM\OneToMany(targetEntity="admin\EconomatBundle\Entity\Produit", cascade={"persist", "remove"}, mappedBy="fournisseur")
     */
    private $produits;
    
    
    /**
     * @var ArrayCollection $commandeTmps
     * @ORM\OneToMany(targetEntity="admin\EconomatBundle\Entity\CommandeTmp", cascade={"persist", "remove"}, mappedBy="fournisseur")
     * @ORM\JoinColumn(nullable=true)
     */
    private $commandeTmps;
    
    /**
     * @var ArrayCollection $commandes
     * @ORM\OneToMany(targetEntity="admin\EconomatBundle\Entity\Commande", cascade={"persist", "remove"}, mappedBy="fournisseur")
     * @ORM\JoinColumn(nullable=true)
     */
    private $commandes;
    
    /**
     * @var ArrayCollection images
     * @ORM\OneToMany(targetEntity="admin\EconomatBundle\Entity\Image", cascade={"persist", "remove"}, mappedBy="fournisseur")
     */
    private $images; 	

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
     * Set codeFournisseur
     *
     * @param string $codeFournisseur
     *
     * @return Fournisseur
     */
    public function setCodeFournisseur($codeFournisseur)
    {
        $this->codeFournisseur = $codeFournisseur;

        return $this;
    }

    /**
     * Get codeFournisseur
     *
     * @return string
     */
    public function getCodeFournisseur()
    {
        return $this->codeFournisseur;
    }

    /**
     * Set nomFournisseur
     *
     * @param string $nomFournisseur
     *
     * @return Fournisseur
     */
    public function setNomFournisseur($nomFournisseur)
    {
        $this->nomFournisseur = $nomFournisseur;

        return $this;
    }

    /**
     * Get nomFournisseur
     *
     * @return string
     */
    public function getNomFournisseur()
    {
        return $this->nomFournisseur;
    }

    /**
     * Set contactFournisseur
     *
     * @param string $contactFournisseur
     *
     * @return Fournisseur
     */
    public function setContactFournisseur($contactFournisseur)
    {
        $this->contactFournisseur = $contactFournisseur;

        return $this;
    }

    /**
     * Get contactFournisseur
     *
     * @return string
     */
    public function getContactFournisseur()
    {
        return $this->contactFournisseur;
    }

    /**
     * Set adresseFournisseur
     *
     * @param string $adresseFournisseur
     *
     * @return Fournisseur
     */
    public function setAdresseFournisseur($adresseFournisseur)
    {
        $this->adresseFournisseur = $adresseFournisseur;

        return $this;
    }

    /**
     * Get adresseFournisseur
     *
     * @return string
     */
    public function getAdresseFournisseur()
    {
        return $this->adresseFournisseur;
    }

    /**
     * Set ressourceFournisseur
     *
     * @param string $ressourceFournisseur
     *
     * @return Fournisseur
     */
    public function setRessourceFournisseur($ressourceFournisseur)
    {
        $this->ressourceFournisseur = $ressourceFournisseur;

        return $this;
    }

    /**
     * Get ressourceFournisseur
     *
     * @return string
     */
    public function getRessourceFournisseur()
    {
        return $this->ressourceFournisseur;
    }

    /**
     * Set datePublication
     *
     * @param \DateTime $datePublication
     *
     * @return Fournisseur
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
     * Set dateModification
     *
     * @param \DateTime $dateModification
     *
     * @return Fournisseur
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
     *
     * @return Fournisseur
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
     * Set etatFournisseur
     *
     * @param integer $etatFournisseur
     *
     * @return Fournisseur
     */
    public function setEtatFournisseur($etatFournisseur)
    {
        $this->etatFournisseur = $etatFournisseur;

        return $this;
    }

    /**
     * Get etatFournisseur
     *
     * @return integer
     */
    public function getEtatFournisseur()
    {
        return $this->etatFournisseur;
    }

    /**
     * Add produit
     *
     * @param \admin\EconomatBundle\Entity\Produit $produit
     *
     * @return Fournisseur
     */
    public function addProduit(\admin\EconomatBundle\Entity\Produit $produit)
    {
        $this->produits[] = $produit;

        return $this;
    }

    /**
     * Remove produit
     *
     * @param \admin\EconomatBundle\Entity\Produit $produit
     */
    public function removeProduit(\admin\EconomatBundle\Entity\Produit $produit)
    {
        $this->produits->removeElement($produit);
    }

    /**
     * Get produits
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProduits()
    {
        return $this->produits;
    }

    /**
     * Add commandeTmp
     *
     * @param \admin\EconomatBundle\Entity\CommandeTmp $commandeTmp
     *
     * @return Fournisseur
     */
    public function addCommandeTmp(\admin\EconomatBundle\Entity\CommandeTmp $commandeTmp)
    {
        $this->commandeTmps[] = $commandeTmp;

        return $this;
    }

    /**
     * Remove commandeTmp
     *
     * @param \admin\EconomatBundle\Entity\CommandeTmp $commandeTmp
     */
    public function removeCommandeTmp(\admin\EconomatBundle\Entity\CommandeTmp $commandeTmp)
    {
        $this->commandeTmps->removeElement($commandeTmp);
    }

    /**
     * Get commandeTmps
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommandeTmps()
    {
        return $this->commandeTmps;
    }

    /**
     * Add commande
     *
     * @param \admin\EconomatBundle\Entity\Commande $commande
     *
     * @return Fournisseur
     */
    public function addCommande(\admin\EconomatBundle\Entity\Commande $commande)
    {
        $this->commandes[] = $commande;

        return $this;
    }

    /**
     * Remove commande
     *
     * @param \admin\EconomatBundle\Entity\Commande $commande
     */
    public function removeCommande(\admin\EconomatBundle\Entity\Commande $commande)
    {
        $this->commandes->removeElement($commande);
    }

    /**
     * Get commandes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommandes()
    {
        return $this->commandes;
    }

    /**
     * Add image
     *
     * @param \admin\EconomatBundle\Entity\Image $image
     *
     * @return Fournisseur
     */
    public function addImage(\admin\EconomatBundle\Entity\Image $image)
    {
        $this->images[] = $image;

        return $this;
    }

    /**
     * Remove image
     *
     * @param \admin\EconomatBundle\Entity\Image $image
     */
    public function removeImage(\admin\EconomatBundle\Entity\Image $image)
    {
        $this->images->removeElement($image);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }
}
