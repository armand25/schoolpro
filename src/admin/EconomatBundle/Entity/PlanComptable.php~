<?php

namespace admin\EconomatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="admin\EconomatBundle\Entity\PlanComptableRepository")
 * @ORM\Table(name="plancomptable")
 */
class PlanComptable{

    //constructeur    
    public function __construct() {

    }

    /**
     * @var string $compte
     * @ORM\Id
     * @ORM\Column(name="compte", type="string",length=30)
     */
    private $compte;

    /**
     * @var string $libelle
     * @ORM\Column(name="libelle", type="string",length=100)
     */
    private $libelle;

    /**
     * @var integer $liea
     * @ORM\Column(name="liea", type="integer")
     */
    private $liea;
    
    /**
     * @var string $type
     * @ORM\Column(name="type", type="string",length=2)
     */
    private $type;    
    
    /**
     * @ORM\Column(name="datecreation", type="datetime")
     * @var string $dateCreation 
     */
    private $dateCreation;   

    /**
     * @var integer $suppr
     * @ORM\Column(name="suppr", type="integer")
     * @Assert\NotBlank()  
     */
    private $suppr;
    

    /**
     * @var ArrayCollection Caisse $caisses
     * @ORM\OneToMany(targetEntity="admin\EconomatBundle\Entity\Caisse", mappedBy="plancomptable")
     * 
     */
    private $caisses;

    
    /**
     * @var ArrayCollection Produit $produits
     * @ORM\OneToMany(targetEntity="admin\EconomatBundle\Entity\Produit", mappedBy="plancomptable")
     * 
     */
    private $produits;
    
    /**
     * @var ArrayCollection Schema $schemas
     * @ORM\OneToMany(targetEntity="admin\EconomatBundle\Entity\Schema", mappedBy="plancomptable")
     * 
     */
    private $schemas;    
    
    /**
     * @var ArrayCollection Operation $operations
     * @ORM\OneToMany(targetEntity="admin\EconomatBundle\Entity\Operation", mappedBy="plancomptable")
     * 
     */
    private $operations;
    
    /**
     * @var Etablissement  $etablissement
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Etablissement", inversedBy="plancomptables")
     * @ORM\JoinColumn(nullable=true)
     */
    private $etablissement;      
    

    /**
     * Set compte
     *
     * @param string $compte
     * @return PlanComptable
     */
    public function setCompte($compte)
    {
        $this->compte = $compte;
    
        return $this;
    }

    /**
     * Get compte
     *
     * @return string 
     */
    public function getCompte()
    {
        return $this->compte;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     * @return PlanComptable
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
    
        return $this;
    }

    /**
     * Get libelle
     *
     * @return string 
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set liea
     *
     * @param integer $liea
     * @return PlanComptable
     */
    public function setLiea($liea)
    {
        $this->liea = $liea;
    
        return $this;
    }

    /**
     * Get liea
     *
     * @return integer 
     */
    public function getLiea()
    {
        return $this->liea;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return PlanComptable
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return PlanComptable
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;
    
        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime 
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set suppr
     *
     * @param integer $suppr
     * @return PlanComptable
     */
    public function setSuppr($suppr)
    {
        $this->suppr = $suppr;
    
        return $this;
    }

    /**
     * Get suppr
     *
     * @return integer 
     */
    public function getSuppr()
    {
        return $this->suppr;
    }

    /**
     * Add caisses
     *
     * @param \admin\EconomatBundle\Entity\Caisse $caisses
     * @return PlanComptable
     */
    public function addCaiss(\admin\EconomatBundle\Entity\Caisse $caisses)
    {
        $this->caisses[] = $caisses;
    
        return $this;
    }

    /**
     * Remove caisses
     *
     * @param \admin\EconomatBundle\Entity\Caisse $caisses
     */
    public function removeCaiss(\admin\EconomatBundle\Entity\Caisse $caisses)
    {
        $this->caisses->removeElement($caisses);
    }

    /**
     * Get caisses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCaisses()
    {
        return $this->caisses;
    }

    /**
     * Add schemas
     *
     * @param \admin\EconomatBundle\Entity\Schema $schemas
     * @return PlanComptable
     */
    public function addSchema(\admin\EconomatBundle\Entity\Schema $schemas)
    {
        $this->schemas[] = $schemas;
    
        return $this;
    }

    /**
     * Remove schemas
     *
     * @param \admin\EconomatBundle\Entity\Schema $schemas
     */
    public function removeSchema(\admin\EconomatBundle\Entity\Schema $schemas)
    {
        $this->schemas->removeElement($schemas);
    }

    /**
     * Get schemas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSchemas()
    {
        return $this->schemas;
    }

    /**
     * Add operations
     *
     * @param \admin\EconomatBundle\Entity\Operation $operations
     * @return PlanComptable
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
     * Add produits
     *
     * @param \admin\EconomatBundle\Entity\Produit $produits
     * @return PlanComptable
     */
    public function addProduit(\admin\EconomatBundle\Entity\Produit $produits)
    {
        $this->produits[] = $produits;
    
        return $this;
    }

    /**
     * Remove produits
     *
     * @param \admin\EconomatBundle\Entity\Produit $produits
     */
    public function removeProduit(\admin\EconomatBundle\Entity\Produit $produits)
    {
        $this->produits->removeElement($produits);
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
     * Set etablissement
     *
     * @param \admin\ScolariteBundle\Entity\Etablissement $etablissement
     *
     * @return PlanComptable
     */
    public function setEtablissement(\admin\ScolariteBundle\Entity\Etablissement $etablissement)
    {
        $this->etablissement = $etablissement;

        return $this;
    }

    /**
     * Get etablissement
     *
     * @return \admin\ScolariteBundle\Entity\Etablissement
     */
    public function getEtablissement()
    {
        return $this->etablissement;
    }
}
