<?php

namespace admin\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \admin\UserBundle\User;

/**
 * Connexion
 *
 * @ORM\Table(name="connexion")
 * @ORM\Entity(repositoryClass="admin\UserBundle\Entity\ConnexionRepository")
 */
class Connexion {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime $dateConnexion
     *
     * @ORM\Column(name="date_connexion", type="datetime")
     */
    private $dateConnexion;

    /**
     * @var \DateTime $dateLastAction
     *
     * @ORM\Column(name="date_last_action", type="datetime", nullable=true)
     */
    private $dateLastAction;

    /**
     * @var \DateTime $dateDeconnexion
     *
     * @ORM\Column(name="date_deconnexion", type="datetime", nullable=true)
     */
    private $dateDeconnexion;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse_ip", type="string", length=100)
     */
    private $adresseIp;

    /**
     * @var text $tabIdActions
     * @ORM\Column(name="tab_id_actions",   type="text")
     */
    private $tabIdActions;

    /**
     * @var text $tabDateActions
     * @ORM\Column(name="tab_date_actions",   type="text")
     */
    private $tabDateActions;

    /**
     *
     * @var Utilisateur $utilisateur;
     * @ORM\ManyToOne(targetEntity="admin\UserBundle\Entity\Utilisateur", inversedBy="connexions")
     * @ORM\JoinColumn(nullable=true)
     */
    private $utilisateur;
    
    /**
     *
     * @var Abonne $abonne;
     * @ORM\ManyToOne(targetEntity="admin\UserBundle\Entity\Abonne", inversedBy="connexions")
     * @ORM\JoinColumn(nullable=true)
     */
    private $abonne;
    
    /**
     *
     * @var Eleve eleve;
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Eleve", inversedBy="connexions")
     * @ORM\JoinColumn(nullable=true)
     */
    private $eleve;
    
    
    /**
     * @var string $dureeConnexion
     *
     * @ORM\Column(name="duree_connexion", type="string", length=50, unique=false)
     */
    private $dureeConnexion;

    public function __construct($adresseIp) {
        $d = new \DateTime();
        $this->dateConnexion = $d;
        $this->dateLastAction = $d;
        $this->dateDeconnexion = NULL;
        $this->adresseIp = $adresseIp;
        $this->tabIdActions = '';
        $this->tabDateActions = '';
        $this->dureeConnexion = '';
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
     * Set dateConnexion
     *
     * @param \DateTime $dateConnexion
     * @return Connexion
     */
    public function setDateConnexion($dateConnexion)
    {
        $this->dateConnexion = $dateConnexion;

        return $this;
    }

    /**
     * Get dateConnexion
     *
     * @return \DateTime 
     */
    public function getDateConnexion()
    {
        return $this->dateConnexion;
    }

    /**
     * Set dateLastAction
     *
     * @param \DateTime $dateLastAction
     * @return Connexion
     */
    public function setDateLastAction($dateLastAction)
    {
        $this->dateLastAction = $dateLastAction;

        return $this;
    }

    /**
     * Get dateLastAction
     *
     * @return \DateTime 
     */
    public function getDateLastAction()
    {
        return $this->dateLastAction;
    }

    /**
     * Set dateDeconnexion
     *
     * @param \DateTime $dateDeconnexion
     * @return Connexion
     */
    public function setDateDeconnexion($dateDeconnexion)
    {
        $this->dateDeconnexion = $dateDeconnexion;

        return $this;
    }

    /**
     * Get dateDeconnexion
     *
     * @return \DateTime 
     */
    public function getDateDeconnexion()
    {
        return $this->dateDeconnexion;
    }

    /**
     * Set adresseIp
     *
     * @param string $adresseIp
     * @return Connexion
     */
    public function setAdresseIp($adresseIp)
    {
        $this->adresseIp = $adresseIp;

        return $this;
    }

    /**
     * Get adresseIp
     *
     * @return string 
     */
    public function getAdresseIp()
    {
        return $this->adresseIp;
    }

    /**
     * Set tabIdActions
     *
     * @param Array $tabIdActions
     * @return Connexion
     */
    public function setTabIdActions($tabIdActions)
    {
        $this->tabIdActions = json_encode($tabIdActions);

        return $this;
    }

    /**
     * Get tabIdActions
     *
     * @return Array 
     */
    public function getTabIdActions()
    {
        return json_decode($this->tabIdActions) ;
    }

    /**
     * Set tabDateActions
     *
     * @param Array $tabDateActions
     * @return Connexion
     */
    public function setTabDateActions($tabDateActions)
    {
        $this->tabDateActions = json_encode($tabDateActions);

        return $this;
    }

    /**
     * Get tabDateActions
     *
     * @return Array 
     */
    public function getTabDateActions()
    {
        //return $this->tabDateActions;
        return json_decode($this->tabDateActions) ;
    }

    /**
     * Set dureeConnexion
     *
     * @param string $dureeConnexion
     * @return Connexion
     */
    public function setDureeConnexion($dureeConnexion)
    {
        $this->dureeConnexion = $dureeConnexion;

        return $this;
    }

    /**
     * Get dureeConnexion
     *
     * @return string 
     */
    public function getDureeConnexion()
    {
        return $this->dureeConnexion;
    }

    /**
     * Set utilisateur
     *
     * @param \admin\UserBundle\Entity\Utilisateur $utilisateur
     * @return Connexion
     */
    public function setUtilisateur(\admin\UserBundle\Entity\Utilisateur $utilisateur)
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
     * Set abonne
     *
     * @param \admin\UserBundle\Entity\Abonne $abonne
     * @return Connexion
     */
    public function setAbonne(\admin\UserBundle\Entity\Abonne $abonne = null)
    {
        $this->abonne = $abonne;

        return $this;
    }

    /**
     * Get abonne
     *
     * @return \admin\ScolariteBundle\Entity\Abonne 
     */
    public function getAbonne()
    {
        return $this->abonne;
    }

    /**
     * Set eleve
     *
     * @param \admin\ScolariteBundle\Entity\Eleve $eleve
     *
     * @return Connexion
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
}
