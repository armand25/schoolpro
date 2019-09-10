<?php

/**
 * Définition de l'entité Operation
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
 * Operation.
 *
 * @ORM\Table(name="operation")
 * @ORM\Entity(repositoryClass="admin\EconomatBundle\Entity\OperationRepository")
 */
class Operation
{
    public function __construct()
    {
        
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
     * @ORM\Column(name="code_operation", type="string", length=50, nullable = true)
     * @Assert\Length(max=50)
     */
    private $codeOperation;	
	
    
    
    /**
     * @var date
     *
     * @ORM\Column(name="date_operation", type="date")
     */
    private $dateOperation;
    
    
    /**
     * @var date
     *
     * @ORM\Column(name="date_valeur", type="date")
     */
    private $dateValeur;
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="piece_operation", type="string")
     */
    private $pieceOperation;  
    
    
    /**
     * @var integer
     *
     * @ORM\Column(name="etat_operation", type="integer", nullable = true)
     */
    private $etatOperation;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="montant", type="integer")
     */
    private $montant;    

    
    /**
     * @var integer
     *
     * @ORM\Column(name="comptabilise", type="integer")
     */
    private $comptabilise;
    
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id_abonne", type="integer", nullable=true)
     */
    private $idAbonne; 
    
    
    
    /**
     * @var integer
     *
     * @ORM\Column(name="type_traite_operation", type="integer", nullable=true)
     */
    private $typeTraiteOperation; 
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="num_mvt", type="string", nullable=true)
     */
    private $numMvt;   
    
    /**
     * @var string
     *
     * @ORM\Column(name="compte_auxiliaire", type="string", nullable=true)
     */
    private $compteAuxiliaire;   
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="nom_deposant", type="string", nullable = true)
     */
    private $nomDeposant;
    
    /**
     * @var string
     *
     * @ORM\Column(name="lib_operation", type="string", nullable = true)
     */
    private $libOperation;
    
    /**
     * @var string
     *
     * @ORM\Column(name="tel_deposant", type="string", nullable = true)
     */
    private $telDeposant;  
    
    /**
     * @var string
     *
     * @ORM\Column(name="compte_client", type="string", nullable = true)
     */
    private $compte;    
    /**
     * @var string
     *
     * @ORM\Column(name="cheque", type="string", nullable = true)
     */
    private $cheque;    
    

    /**
     * @var string
     *
     * @ORM\Column(name="ref_facture", type="string", nullable = true)
     */
    private $refFacture;    
    
    
    /**
     * @var Commande  commande
     * @ORM\ManyToOne(targetEntity="admin\EconomatBundle\Entity\Commande", inversedBy="operations")
     * @ORM\JoinColumn(nullable=true)
     */
    private $commande;  
    
    /**
     * @var Produit  produit
     * @ORM\ManyToOne(targetEntity="admin\EconomatBundle\Entity\Produit", inversedBy="operations")
     * @ORM\JoinColumn(nullable=true)
     */
    private $produit;  
    
    /**
     * @var Caisse caisse  
     * @ORM\ManyToOne(targetEntity="admin\EconomatBundle\Entity\Caisse", inversedBy="operations")
     * @ORM\JoinColumn(nullable=true)
     */
    private $caisse; 
    
    /**
     * @var Eleve eleve  
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Eleve", inversedBy="operations")
     * @ORM\JoinColumn(nullable=true)
     */
    private $eleve; 
    
    
    /**
     * @var Classe classe  
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Classe", inversedBy="operations")
     * @ORM\JoinColumn(nullable=true)
     */
    private $classe;
    
    
    /**
     * @var AnneeScolaire anneescolaire  
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\AnneeScolaire", inversedBy="operations")
     * @ORM\JoinColumn(nullable=true)
     */
    private $anneescolaire; 
    
    /**
     * @var Utilisateur utilisateur  
     * @ORM\ManyToOne(targetEntity="admin\UserBundle\Entity\Utilisateur", inversedBy="operations")
     * @ORM\JoinColumn(nullable=true)
     */
    private $utilisateur; 
    
    
    /**
     * @var TypeOperation $typeoperation
     * @ORM\ManyToOne(targetEntity="admin\EconomatBundle\Entity\TypeOperation", inversedBy="operationcaisses", cascade={"persist","merge"})
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="idtypeoperation", referencedColumnName="idtypeoperation")
     * })
     */
    private $typeoperation;

    /**
     * @var PlanComptable $planComptable
     * @ORM\ManyToOne(targetEntity="admin\EconomatBundle\Entity\PlanComptable", inversedBy="operationcaisses", cascade={"persist","merge"})
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="compte", referencedColumnName="compte")
     * })
     */
    private $plancomptable;
    

    /**
     * @var string $sensOperation
     * @ORM\Column(name="sensoperation",type="string",length=2)
     */
    private $sensOperation;    
    
    /**
     * @var Etablissement  $etablissement
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Etablissement", inversedBy="operations")
     * @ORM\JoinColumn(nullable=true)
     */
    private $etablissement;
 

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
     * Set codeOperation
     *
     * @param string $codeOperation
     *
     * @return Operation
     */
    public function setCodeOperation($codeOperation)
    {
        $this->codeOperation = $codeOperation;

        return $this;
    }

    /**
     * Get codeOperation
     *
     * @return string
     */
    public function getCodeOperation()
    {
        return $this->codeOperation;
    }

    /**
     * Set dateOperation
     *
     * @param \DateTime $dateOperation
     *
     * @return Operation
     */
    public function setDateOperation($dateOperation)
    {
        $this->dateOperation = $dateOperation;

        return $this;
    }

    /**
     * Get dateOperation
     *
     * @return \DateTime
     */
    public function getDateOperation()
    {
        return $this->dateOperation;
    }

    /**
     * Set dateValeur
     *
     * @param \DateTime $dateValeur
     *
     * @return Operation
     */
    public function setDateValeur($dateValeur)
    {
        $this->dateValeur = $dateValeur;

        return $this;
    }

    /**
     * Get dateValeur
     *
     * @return \DateTime
     */
    public function getDateValeur()
    {
        return $this->dateValeur;
    }

    /**
     * Set pieceOperation
     *
     * @param string $pieceOperation
     *
     * @return Operation
     */
    public function setPieceOperation($pieceOperation)
    {
        $this->pieceOperation = $pieceOperation;

        return $this;
    }

    /**
     * Get pieceOperation
     *
     * @return string
     */
    public function getPieceOperation()
    {
        return $this->pieceOperation;
    }

    /**
     * Set etatOperation
     *
     * @param integer $etatOperation
     *
     * @return Operation
     */
    public function setEtatOperation($etatOperation)
    {
        $this->etatOperation = $etatOperation;

        return $this;
    }

    /**
     * Get etatOperation
     *
     * @return integer
     */
    public function getEtatOperation()
    {
        return $this->etatOperation;
    }

    /**
     * Set montant
     *
     * @param integer $montant
     *
     * @return Operation
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return integer
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set comptabilise
     *
     * @param integer $comptabilise
     *
     * @return Operation
     */
    public function setComptabilise($comptabilise)
    {
        $this->comptabilise = $comptabilise;

        return $this;
    }

    /**
     * Get comptabilise
     *
     * @return integer
     */
    public function getComptabilise()
    {
        return $this->comptabilise;
    }

    /**
     * Set idAbonne
     *
     * @param integer $idAbonne
     *
     * @return Operation
     */
    public function setIdAbonne($idAbonne)
    {
        $this->idAbonne = $idAbonne;

        return $this;
    }

    /**
     * Get idAbonne
     *
     * @return integer
     */
    public function getIdAbonne()
    {
        return $this->idAbonne;
    }

    /**
     * Set typeTraiteOperation
     *
     * @param integer $typeTraiteOperation
     *
     * @return Operation
     */
    public function setTypeTraiteOperation($typeTraiteOperation)
    {
        $this->typeTraiteOperation = $typeTraiteOperation;

        return $this;
    }

    /**
     * Get typeTraiteOperation
     *
     * @return integer
     */
    public function getTypeTraiteOperation()
    {
        return $this->typeTraiteOperation;
    }

    /**
     * Set numMvt
     *
     * @param string $numMvt
     *
     * @return Operation
     */
    public function setNumMvt($numMvt)
    {
        $this->numMvt = $numMvt;

        return $this;
    }

    /**
     * Get numMvt
     *
     * @return string
     */
    public function getNumMvt()
    {
        return $this->numMvt;
    }

    /**
     * Set compteAuxiliaire
     *
     * @param string $compteAuxiliaire
     *
     * @return Operation
     */
    public function setCompteAuxiliaire($compteAuxiliaire)
    {
        $this->compteAuxiliaire = $compteAuxiliaire;

        return $this;
    }

    /**
     * Get compteAuxiliaire
     *
     * @return string
     */
    public function getCompteAuxiliaire()
    {
        return $this->compteAuxiliaire;
    }

    /**
     * Set nomDeposant
     *
     * @param string $nomDeposant
     *
     * @return Operation
     */
    public function setNomDeposant($nomDeposant)
    {
        $this->nomDeposant = $nomDeposant;

        return $this;
    }

    /**
     * Get nomDeposant
     *
     * @return string
     */
    public function getNomDeposant()
    {
        return $this->nomDeposant;
    }

    /**
     * Set libOperation
     *
     * @param string $libOperation
     *
     * @return Operation
     */
    public function setLibOperation($libOperation)
    {
        $this->libOperation = $libOperation;

        return $this;
    }

    /**
     * Get libOperation
     *
     * @return string
     */
    public function getLibOperation()
    {
        return $this->libOperation;
    }

    /**
     * Set telDeposant
     *
     * @param string $telDeposant
     *
     * @return Operation
     */
    public function setTelDeposant($telDeposant)
    {
        $this->telDeposant = $telDeposant;

        return $this;
    }

    /**
     * Get telDeposant
     *
     * @return string
     */
    public function getTelDeposant()
    {
        return $this->telDeposant;
    }

    /**
     * Set compte
     *
     * @param string $compte
     *
     * @return Operation
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
     * Set cheque
     *
     * @param string $cheque
     *
     * @return Operation
     */
    public function setCheque($cheque)
    {
        $this->cheque = $cheque;

        return $this;
    }

    /**
     * Get cheque
     *
     * @return string
     */
    public function getCheque()
    {
        return $this->cheque;
    }

    /**
     * Set refFacture
     *
     * @param string $refFacture
     *
     * @return Operation
     */
    public function setRefFacture($refFacture)
    {
        $this->refFacture = $refFacture;

        return $this;
    }

    /**
     * Get refFacture
     *
     * @return string
     */
    public function getRefFacture()
    {
        return $this->refFacture;
    }

    /**
     * Set sensOperation
     *
     * @param string $sensOperation
     *
     * @return Operation
     */
    public function setSensOperation($sensOperation)
    {
        $this->sensOperation = $sensOperation;

        return $this;
    }

    /**
     * Get sensOperation
     *
     * @return string
     */
    public function getSensOperation()
    {
        return $this->sensOperation;
    }

    /**
     * Set commande
     *
     * @param \admin\EconomatBundle\Entity\Commande $commande
     *
     * @return Operation
     */
    public function setCommande(\admin\EconomatBundle\Entity\Commande $commande = null)
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
     * Set produit
     *
     * @param \admin\EconomatBundle\Entity\Produit $produit
     *
     * @return Operation
     */
    public function setProduit(\admin\EconomatBundle\Entity\Produit $produit = null)
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
     * Set caisse
     *
     * @param \admin\EconomatBundle\Entity\Caisse $caisse
     *
     * @return Operation
     */
    public function setCaisse(\admin\EconomatBundle\Entity\Caisse $caisse = null)
    {
        $this->caisse = $caisse;

        return $this;
    }

    /**
     * Get caisse
     *
     * @return \admin\EconomatBundle\Entity\Caisse
     */
    public function getCaisse()
    {
        return $this->caisse;
    }

    /**
     * Set eleve
     *
     * @param \admin\ScolariteBundle\Entity\Eleve $eleve
     *
     * @return Operation
     */
    public function setEleve(\admin\ScolariteBundle\Entity\Eleve $eleve = null)
    {
        $this->eleve = $eleve;

        return $this;
    }

    /**
     * Get eleve
     *
     * @return \admin\ScolariteBundle\Entity\Eleve
     */
    public function getEleve()
    {
        return $this->eleve;
    }

    /**
     * Set utilisateur
     *
     * @param \admin\UserBundle\Entity\Utilisateur $utilisateur
     *
     * @return Operation
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
     * Set typeoperation
     *
     * @param \admin\EconomatBundle\Entity\TypeOperation $typeoperation
     *
     * @return Operation
     */
    public function setTypeoperation(\admin\EconomatBundle\Entity\TypeOperation $typeoperation = null)
    {
        $this->typeoperation = $typeoperation;

        return $this;
    }

    /**
     * Get typeoperation
     *
     * @return \admin\EconomatBundle\Entity\TypeOperation
     */
    public function getTypeoperation()
    {
        return $this->typeoperation;
    }

    /**
     * Set plancomptable
     *
     * @param \admin\EconomatBundle\Entity\PlanComptable $plancomptable
     *
     * @return Operation
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
     * Set etablissement
     *
     * @param \admin\ScolariteBundle\Entity\Etablissement $etablissement
     *
     * @return Operation
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

    /**
     * Set classe
     *
     * @param \admin\ScolariteBundle\Entity\Classe $classe
     *
     * @return Operation
     */
    public function setClasse(\admin\ScolariteBundle\Entity\Classe $classe = null)
    {
        $this->classe = $classe;

        return $this;
    }

    /**
     * Get classe
     *
     * @return \admin\ScolariteBundle\Entity\Classe
     */
    public function getClasse()
    {
        return $this->classe;
    }

    /**
     * Set anneescolaire
     *
     * @param \admin\ScolariteBundle\Entity\AnneeScolaire $anneescolaire
     *
     * @return Operation
     */
    public function setAnneescolaire(\admin\ScolariteBundle\Entity\AnneeScolaire $anneescolaire = null)
    {
        $this->anneescolaire = $anneescolaire;

        return $this;
    }

    /**
     * Get anneescolaire
     *
     * @return \admin\ScolariteBundle\Entity\AnneeScolaire
     */
    public function getAnneescolaire()
    {
        return $this->anneescolaire;
    }
}
