<?php

namespace admin\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use \admin\UserBundle\Entity\Action;
use \admin\UserBundle\Entity\Controleur;
use \admin\UserBundle\Entity\Module;
use \Doctrine\Common\Collections\ArrayCollection;

/**
 * Module
 *
 * @ORM\Table(name="module")
 * @ORM\Entity(repositoryClass="admin\UserBundle\Entity\ModuleRepository")
 */
class Module {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $nom
     *
     * @ORM\Column(name="nom", type="string", length=50)
     */
    private $nom;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var integer $etat
     *
     * @ORM\Column(name="etat_module", type="smallint")
     */
    private $etat;

    /**
     *
     * @var ArrayCollection $actions 
     * @ORM\OneToMany(targetEntity="admin\UserBundle\Entity\Action", cascade={"persist", "remove"}, mappedBy="module")
     * 
     */
    private $actions;

    /**
     * Constructor
     */
    public function __construct($nom, $description) {
        $this->etat =1 ;
        $this->nom = $nom;
        $this->description = $description;
        $this->actions = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Module
     */
    public function setNom($nom) {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom() {
        return $this->nom;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Module
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Add actions
     *
     * @param \admin\UserBundle\Entity\Action $actions
     * @return Module
     */
    public function addAction(\admin\UserBundle\Entity\Action $actions) {
        $this->actions[] = $actions;

        return $this;
    }

    /**
     * Remove actions
     *
     * @param \admin\UserBundle\Entity\Action $actions
     */
    public function removeAction(\admin\UserBundle\Entity\Action $actions) {
        $this->actions->removeElement($actions);
    }

    /**
     * Get actions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getActions() {
        return $this->actions;
    }

    /**
     * Set etat
     *
     * @param integer $etat
     * @return Utilisateur
     */
    public function setEtat($etat) {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return integer 
     */
    public function getEtat() {
        return $this->etat;
    }

    /**
     *  -- Modules administration
     */
    const MOD_GEST_USER = "MODULE ADMINISTRATEUR";
    const MOD_GEST_USER_DESC = "Module de gestion des administrateurs";
    
    
    const MOD_PARAM = "MODULE PARAM";
    const MOD_PARAM_DESC = "Module de gestion des paramètres de l'application";
    const MOD_TYPE_PAIEMENT = "MODULE TYPE PAIEMENT";
    const MOD_TYPE_PAIEMENT_DESC = "Module de gestion des types de paiement";
    const MOD_GEST_ABONNE = "MODULE UTILISATEUR";
    const MOD_GEST_ABONNE_DESC = "Module de gestion des utilisateurs";
    const MOD_DROIT = "MODULE DROIT";
    const MOD_DROIT_DESC = "Module de gestion des droits d'accès aux fonctionnalités";
	
    const MOD_MESSAGE_ADMIN = "MODULE MESSAGERIE";
    const MOD_MESSAGE_ADMIN_DESC = "MODULE de gestion de la messagerie";

    /**
     *  Modules du site public. Le nom de tout module du site public commence avec MOD_SITE_NOM-MODULE
     */
    const MOD_SITE_ABONNE = "MODULE SITE ABONNE";
    const MOD_SITE_ABONNE_DESC = "Module des abonnés du site public";
    
    const MOD_SITE_MESSAGE = "MODULE SITE MESSAGERIE";
    const MOD_SITE_MESSAGE_DESC = "MODULE de gestion de la messagerie du site public";

    /**
     * Constantes pour connaitres les parmetres specifiques du module de Gestion des configurations
     */
    const MOD_CONFIG = "MODULE CONFIG";
    const MOD_CONFIG_DESC = "Module de gestion des configurations ";
    /**
     * Constantes pour connaitres les parmetres specifiques du module de Gestion des menu
     */
    const MOD_MENU = "MODULE MENU";
    const MOD_MENU_DESC = "Module de gestion des menus ";

    /**
     * Constantes pour connaitres les parmetres specifiques du module de Gestion des configurations
     */
    const MOD_GEST_FOUR = "MODULE FOURNISSUER";
    const MOD_GEST_FOUR_DESC = "Module de gestion des fournisseurs ";
    
    /**
     * Constantes pour connaitres les parmetres specifiques du module de Gestion des caisses
     */
    const MOD_GEST_CAIS = "MODULE CAISSE";
    const MOD_GEST_CAIS_DESC = "Module de gestion des caisses ";    
    /**
     * Constantes pour connaitres les parmetres specifiques du module de Gestion des caisses
     */
    const MOD_GEST_RUB = "MODULE RUBRIQUE";
    const MOD_GEST_RUB_DESC = "Module de gestion des rubriques ";    
    /**
     * Constantes pour connaitres les parmetres specifiques du module de Gestion des caisses
     */
    const MOD_GEST_ART = "MODULE ARTICLE";
    const MOD_GEST_ART_DESC = "Module de gestion des articles ";    
    
    /**
     * Constantes pour connaitres les parmetres specifiques du module de Gestion des plans comptable
     */
    const MOD_GEST_PLAN = "MODULE PLAN COMPTABLE";
    const MOD_GEST_PLAN_DESC = "Module de gestion des plans comptable ";    
    
    
    /**
     * Constantes pour connaitres les parmetres specifiques du module de Gestion des types operations
     */
    const MOD_GEST_TYPOP = "MODULE TYPE OPERATION";
    const MOD_GEST_TYPOP_DESC = "Module de gestion des types operations ";    
    
    /**
     * Constantes pour connaitres les parmetres specifiques du module de Gestion des types operations
     */
    const MOD_GEST_SCHE = "MODULE SCHEMA";
    const MOD_GEST_SCHE_DESC = "Module de gestion des schemas comptable ";    
    
    /**
     * Constantes pour connaitres les parmetres specifiques du module de Gestion des catégories
     */
    const MOD_GEST_CATE = "MODULE CATEGORIE";
    const MOD_GEST_CATE_DESC = "Module de gestion des catégories ";    
    
    
    /**
     * Constantes pour connaitres les parmetres specifiques du module de Gestion des produits
     */
    const MOD_GEST_PROD = "MODULE PRODUIT";
    const MOD_GEST_PROD_DESC = "Module de gestion des produits ";  
    
    /**
     * Constantes pour connaitres les parmetres specifiques du module de Gestion des produits
     */
    const MOD_GEST_COM = "MODULE COMMANDE";
    const MOD_GEST_COM_DESC = "Module de gestion des commandes ";       
    
    /**
     * Constantes pour connaitres les parmetres specifiques du module de Gestion des entrepots
     */
    const MOD_GEST_ENTRE = "MODULE ENTREPOT";
    const MOD_GEST_ENTRE_DESC = "Module de gestion des entrepots ";    
    
    /**
     * Constantes pour connaitres les parmetres specifiques du module de Gestion des entrepots
     */
    const MOD_GEST_VILLE = "MODULE VILLE";
    const MOD_GEST_VILLE_DESC = "Module de gestion des villes ";      
    
    
    /**
     * Constantes pour le module de gestion des fichiers
     */
    const MOD_FILE = "MODULE FILE";
    const MOD_FILE_DESC = "Module de gestion des fichiers ";
    
    /**
     * Constantes pour le module de gestion des découpages
     */
    const MOD_DECOUP = "MODULE DECOUPAGE";
    const MOD_DECOUP_DESC = "Module de gestion des découpages ";
    
    /**
     * Constantes pour le module de gestion des découpages
     */
    const MOD_DEGRE = "MODULE DEGRE";
    const MOD_DEGRE_DESC = "Module de gestion des dégrés ";
    
    /**
     * Constantes pour le module de gestion des enseignements
     */
    const MOD_ENSEIGN = "MODULE ENSEIGNEMENT";
    const MOD_ENSEIGN_DESC = "Module de gestion des enseignements ";
    /**
     * Constantes pour le module de gestion des epreuves
     */
    const MOD_EPREUVE = "MODULE EPREUVE";
    const MOD_EPREUVE_DESC = "Module de gestion des epreuves ";
    
    /**
     * Constantes pour le module de gestion des epreuves
     */
    const MOD_ETABL = "MODULE ETABLISSEMENT";
    const MOD_ETABL_DESC = "Module de gestion des etablissements ";
    
    /**
     * Constantes pour le module de gestion des années scolaire
     */
    const MOD_ANNEE = "MODULE ANNEE SCOLAIRE";
    const MOD_ANNEE_DESC = "Module de gestion des années scolaire ";
    
    /**
     * Constantes pour le module de gestion des découpages
     */
    const MOD_MATIERE = "MODULE MATIERE";
    const MOD_MATIERE_DESC = "Module de gestion des matières ";
    /**
     * Constantes pour le module de gestion des classes
     */
    const MOD_CLASSE = "MODULE CLASSE";
    const MOD_CLASSE_DESC = "Module de gestion des classes ";
    
    /**
     * Constantes pour le module de gestion des élèves
     */
    const MOD_ELEVE = "MODULE ELEVE";
    const MOD_ELEVE_DESC = "Module de gestion des élèves";
    
    /**
     * Constantes pour le module de gestion des balises
     */
    const MOD_GEST_TELE_XML = "MODULE TELECHARGEMENT DES FICHIERS ";
    const MOD_GEST_TELE_XML_DESC = "Module de gestion de téléchargement du fichier xml ";

   
    
    
}
