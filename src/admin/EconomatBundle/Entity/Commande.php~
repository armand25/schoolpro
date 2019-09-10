<?php

/**
 * Définition de l'entité Commande
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
 * Commande.
 *
 * @ORM\Table(name="Commande")
 * @ORM\Entity(repositoryClass="admin\EconomatBundle\Entity\CommandeRepository")
 */
class Commande
{

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
     * @ORM\Column(name="code_commande", type="string", length=50)
     * @Assert\Length(max=50)
     */
    private $codeCommande;	
	
    
    /**
     * @var string
     *
     * @ORM\Column(name="description_produit", type="string")
     */
    private $descriptionCommande;
			
    
    /**
     * @var date
     *
     * @ORM\Column(name="date_publication", type="date")
     */
    private $datePublication;
    
    /**
     * @var date
     *
     * @ORM\Column(name="date_commande", type="date")
     */
    private $dateCommande;

    
    /**
     * @var date
     *
     * @ORM\Column(name="date_modification", type="date", nullable=true)
     */
    private $dateModification;    
    
    /**
     * @var integer
     *
     * @ORM\Column(name="montant_commande", type="integer", nullable=true)
     */
    private  $montantCommande;  
    
    /**
     * @var integer
     *
     * @ORM\Column(name="montant_reste_commande", type="integer", nullable=true)
     */
    private $montantResteCommande;  
    
    
    /**
     * @var int
     *
     * @ORM\Column(name="etat_commande", type="integer")
     */
    private $etatCommande;

    /**
     * @var int
     *
     * @ORM\Column(name="type_commande", type="integer")
     */
    private $typeCommande;
    
 
    /**
     * @var ArrayCollection lignecommandes
     * @ORM\OneToMany(targetEntity="admin\EconomatBundle\Entity\LigneCommande", cascade={"persist", "remove"}, mappedBy="commande")
     */
    private $lignecommandes;
 
    /**
     * @var ArrayCollection operations
     * @ORM\OneToMany(targetEntity="admin\EconomatBundle\Entity\Operation", cascade={"persist", "remove"}, mappedBy="commande")
     */
    private $operations;
    
    /**
     * @var Abonne  abonne
     * @ORM\ManyToOne(targetEntity="admin\UserBundle\Entity\Abonne", inversedBy="commandes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $abonne;  
    
    /**
     * @var Utilisateur utilisateur  
     * @ORM\ManyToOne(targetEntity="admin\UserBundle\Entity\Utilisateur", inversedBy="commandes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $utilisateur;     
    
    /**
     * @var String $refBonCommande  
     * @ORM\Column(name="refboncommande", type="string", length=15)
     */
    private $refBonCommande; 
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="annule", type="boolean")
     */
    private $annule;
    
    /**
     * @var Fournisseur  fournisseur
     * @ORM\ManyToOne(targetEntity="admin\EconomatBundle\Entity\Fournisseur", inversedBy="commandes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $fournisseur;  
    
    
    public function __construct()
    {
        $this->etatCommande = 0;
        $this->annule = 0;
        $this->datePublication = new \DateTime();
        $this->lignecommandes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->operations = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set codeCommande
     *
     * @param string $codeCommande
     *
     * @return Commande
     */
    public function setCodeCommande($codeCommande)
    {
        $this->codeCommande = $codeCommande;

        return $this;
    }

    /**
     * Get codeCommande
     *
     * @return string
     */
    public function getCodeCommande()
    {
        return $this->codeCommande;
    }

    /**
     * Set descriptionCommande
     *
     * @param string $descriptionCommande
     *
     * @return Commande
     */
    public function setDescriptionCommande($descriptionCommande)
    {
        $this->descriptionCommande = $descriptionCommande;

        return $this;
    }

    /**
     * Get descriptionCommande
     *
     * @return string
     */
    public function getDescriptionCommande()
    {
        return $this->descriptionCommande;
    }

    /**
     * Set datePublication
     *
     * @param \DateTime $datePublication
     *
     * @return Commande
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
     * Set dateCommande
     *
     * @param \DateTime $dateCommande
     *
     * @return Commande
     */
    public function setDateCommande($dateCommande)
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    /**
     * Get dateCommande
     *
     * @return \DateTime
     */
    public function getDateCommande()
    {
        return $this->dateCommande;
    }

    /**
     * Set dateModification
     *
     * @param \DateTime $dateModification
     *
     * @return Commande
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
     * Set montantCommande
     *
     * @param integer $montantCommande
     *
     * @return Commande
     */
    public function setMontantCommande($montantCommande)
    {
        $this->montantCommande = $montantCommande;

        return $this;
    }

    /**
     * Get montantCommande
     *
     * @return integer
     */
    public function getMontantCommande()
    {
        return $this->montantCommande;
    }

    /**
     * Set montantResteCommande
     *
     * @param integer $montantResteCommande
     *
     * @return Commande
     */
    public function setMontantResteCommande($montantResteCommande)
    {
        $this->montantResteCommande = $montantResteCommande;

        return $this;
    }

    /**
     * Get montantResteCommande
     *
     * @return integer
     */
    public function getMontantResteCommande()
    {
        return $this->montantResteCommande;
    }

    /**
     * Set etatCommande
     *
     * @param integer $etatCommande
     *
     * @return Commande
     */
    public function setEtatCommande($etatCommande)
    {
        $this->etatCommande = $etatCommande;

        return $this;
    }

    /**
     * Get etatCommande
     *
     * @return integer
     */
    public function getEtatCommande()
    {
        return $this->etatCommande;
    }

    /**
     * Set typeCommande
     *
     * @param integer $typeCommande
     *
     * @return Commande
     */
    public function setTypeCommande($typeCommande)
    {
        $this->typeCommande = $typeCommande;

        return $this;
    }

    /**
     * Get typeCommande
     *
     * @return integer
     */
    public function getTypeCommande()
    {
        return $this->typeCommande;
    }

    /**
     * Set refBonCommande
     *
     * @param string $refBonCommande
     *
     * @return Commande
     */
    public function setRefBonCommande($refBonCommande)
    {
        $this->refBonCommande = $refBonCommande;

        return $this;
    }

    /**
     * Get refBonCommande
     *
     * @return string
     */
    public function getRefBonCommande()
    {
        return $this->refBonCommande;
    }

    /**
     * Set annule
     *
     * @param boolean $annule
     *
     * @return Commande
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
     * Add lignecommande
     *
     * @param \admin\EconomatBundle\Entity\LigneCommande $lignecommande
     *
     * @return Commande
     */
    public function addLignecommande(\admin\EconomatBundle\Entity\LigneCommande $lignecommande)
    {
        $this->lignecommandes[] = $lignecommande;

        return $this;
    }

    /**
     * Remove lignecommande
     *
     * @param \admin\EconomatBundle\Entity\LigneCommande $lignecommande
     */
    public function removeLignecommande(\admin\EconomatBundle\Entity\LigneCommande $lignecommande)
    {
        $this->lignecommandes->removeElement($lignecommande);
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
     * Add operation
     *
     * @param \admin\EconomatBundle\Entity\Operation $operation
     *
     * @return Commande
     */
    public function addOperation(\admin\EconomatBundle\Entity\Operation $operation)
    {
        $this->operations[] = $operation;

        return $this;
    }

    /**
     * Remove operation
     *
     * @param \admin\EconomatBundle\Entity\Operation $operation
     */
    public function removeOperation(\admin\EconomatBundle\Entity\Operation $operation)
    {
        $this->operations->removeElement($operation);
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
     * Set abonne
     *
     * @param \admin\UserBundle\Entity\Abonne $abonne
     *
     * @return Commande
     */
    public function setAbonne(\admin\UserBundle\Entity\Abonne $abonne = null)
    {
        $this->abonne = $abonne;

        return $this;
    }

    /**
     * Get abonne
     *
     * @return \admin\UserBundle\Entity\Abonne
     */
    public function getAbonne()
    {
        return $this->abonne;
    }

    /**
     * Set utilisateur
     *
     * @param \admin\UserBundle\Entity\Utilisateur $utilisateur
     *
     * @return Commande
     */
    public function setUtilisateur(\admin\UserBundle\Entity\Utilisateur $utilisateur = null)
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * Get utilisateur
     *
     * @return \admin\UserBundle\Entity\Utilisateur
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * Set fournisseur
     *
     * @param \admin\EconomatBundle\Entity\Fournisseur $fournisseur
     *
     * @return Commande
     */
    public function setFournisseur(\admin\EconomatBundle\Entity\Fournisseur $fournisseur = null)
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
}
