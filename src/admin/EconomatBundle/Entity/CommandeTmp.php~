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
 * @ORM\Table(name="commandetmp")
 * @ORM\Entity(repositoryClass="admin\EconomatBundle\Entity\CommandeTmpRepository")
 */
class CommandeTmp
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
     * @ORM\Column(name="code_commande", type="string", length=15)
     * @Assert\Length(max=15)
     * @Assert\NotBlank(message="Code Commande  requis !")
     */
    private $codeCommande;	
	
    
    /**
     * @var string
     *
     * @ORM\Column(name="description_produit", type="text", nullable =true)
     */
    private $descriptionCommande;
			
    
    /**
     * @var date
     *
     * @ORM\Column(name="date_publication", type="date", nullable =true)
     */
    private $datePublication;
    
    /**
     * @var date
     *
     * @ORM\Column(name="date_commande", type="date", nullable =true)
     */
    private $dateCommande;

    
    /**
     * @var date
     *
     * @ORM\Column(name="date_modification", type="date", nullable =true)
     */
    private $dateModification;
    
    
    /**
     * @var Utilisateur utilisateur  
     * @ORM\ManyToOne(targetEntity="admin\UserBundle\Entity\Utilisateur", inversedBy="commandeTmps")
     * @ORM\JoinColumn(nullable=true)
     */
    private $utilisateur;   
    
    
    /**
     * @var int
     *
     * @ORM\Column(name="type_commande", type="integer")
     */
    private $typeCommande;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="annule", type="boolean")
     */
    private $annule;
    
    
    /**
     * @var Fournisseur  fournisseur
     * @ORM\ManyToOne(targetEntity="admin\EconomatBundle\Entity\Fournisseur", inversedBy="commandetmps")
     * @ORM\JoinColumn(nullable=true)
     */
    private $fournisseur;  
    
    /**
     * @var ArrayCollection $lignecommandeTmps
     * @ORM\OneToMany(targetEntity="admin\EconomatBundle\Entity\LigneCommandeTmp", cascade={"persist", "remove"}, mappedBy="commandeTmp")
     * @ORM\JoinColumn(nullable=true)
     */
    private $lignecommandeTmps;
 
    
    /**
     * @var Abonne  abonne
     * @ORM\ManyToOne(targetEntity="admin\UserBundle\Entity\Abonne", inversedBy="commandeTmps")
     * @ORM\JoinColumn(nullable=true)
     */
    private $abonne;  
    
    
    /**
     * @var String $refBonCommande  
     * @ORM\Column(name="refboncommande", type="string", length=15)
     */
    private $refBonCommande; 
    
    
    public function __construct()
    {        
        $this->annule = 0;
        $this->datePublication = new \DateTime();
        $this->lignecommandeTmps = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return CommandeTmp
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
     * @return CommandeTmp
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
     * @return CommandeTmp
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
     * @return CommandeTmp
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
     * @return CommandeTmp
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
     * Set typeCommande
     *
     * @param integer $typeCommande
     *
     * @return CommandeTmp
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
     * Set annule
     *
     * @param boolean $annule
     *
     * @return CommandeTmp
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
     * Set refBonCommande
     *
     * @param string $refBonCommande
     *
     * @return CommandeTmp
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
     * Set utilisateur
     *
     * @param \admin\UserBundle\Entity\Utilisateur $utilisateur
     *
     * @return CommandeTmp
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
     * @return CommandeTmp
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

    /**
     * Add lignecommandeTmp
     *
     * @param \admin\EconomatBundle\Entity\LigneCommandeTmp $lignecommandeTmp
     *
     * @return CommandeTmp
     */
    public function addLignecommandeTmp(\admin\EconomatBundle\Entity\LigneCommandeTmp $lignecommandeTmp)
    {
        $this->lignecommandeTmps[] = $lignecommandeTmp;

        return $this;
    }

    /**
     * Remove lignecommandeTmp
     *
     * @param \admin\EconomatBundle\Entity\LigneCommandeTmp $lignecommandeTmp
     */
    public function removeLignecommandeTmp(\admin\EconomatBundle\Entity\LigneCommandeTmp $lignecommandeTmp)
    {
        $this->lignecommandeTmps->removeElement($lignecommandeTmp);
    }

    /**
     * Get lignecommandeTmps
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLignecommandeTmps()
    {
        return $this->lignecommandeTmps;
    }

    /**
     * Set abonne
     *
     * @param \admin\UserBundle\Entity\Abonne $abonne
     *
     * @return CommandeTmp
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
}
