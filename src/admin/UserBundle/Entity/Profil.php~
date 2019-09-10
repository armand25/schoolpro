<?php

namespace admin\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Profil
 *
 * @ORM\Table(name="profil")
 * @ORM\Entity(repositoryClass="admin\UserBundle\Entity\ProfilRepository")
 */
class Profil {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=50)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=10)
     */
    private $code;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="etat", type="smallint")
     */
    private $etat;

   /**
     * @var $typeProfil
     *
     * @ORM\Column(name="type_profil", type="smallint")
     */
    private $typeProfil;
    
     /**
     * @var \DateTime $dateAjout
     *
     * @ORM\Column(name="date_ajout_profil", type="datetime")
     */
    private $dateAjout;
    
     /**
     * @var \DateTime $dateModification
     *
     * @ORM\Column(name="date_edit_profil", type="datetime", nullable=true)
     */
    private $dateModification;


    /**
     *
     * @var ArrayCollection $utilisateurs 
     * @ORM\OneToMany(targetEntity="admin\UserBundle\Entity\Utilisateur", cascade={"persist", "remove"}, mappedBy="profil")
     * 
     */
    private $utilisateurs;
    /**
     *
     * @var ArrayCollection $abonnes 
     * @ORM\OneToMany(targetEntity="admin\UserBundle\Entity\Abonne", cascade={"persist", "remove"}, mappedBy="profil")
     * 
     */
    private $abonnes;
    

    /**
     * @var array $tabCodeActions
     * @ORM\Column(name="tab_id_actions", type="array")
     */
    private $tabIdActions;

    public function __construct() {
        $this->dateAjout = new \DateTime();
        $this->utilisateurs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->abonnes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tabIdActions = array();
        $this->typeProfil = \admin\UserBundle\Types\TypeProfil::PROFIL_UTILISATEUR;
        $this->etat = \admin\UserBundle\Types\TypeEtat::ACTIF;
        
        
        
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
     * Set nom
     *
     * @param string $nom
     * @return Profil
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Profil
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Profil
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set etat
     *
     * @param integer $etat
     * @return Profil
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return integer 
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Set typeProfil
     *
     * @param integer $typeProfil
     * @return Profil
     */
    public function setTypeProfil($typeProfil)
    {
        $this->typeProfil = $typeProfil;

        return $this;
    }

    /**
     * Get typeProfil
     *
     * @return integer 
     */
    public function getTypeProfil()
    {
        return $this->typeProfil;
    }

    /**
     * Set tabIdActions
     *
     * @param array $tabIdActions
     * @return Profil
     */
    public function setTabIdActions($tabIdActions)
    {
        $this->tabIdActions = $tabIdActions;

        return $this;
    }

    /**
     * Get tabIdActions
     *
     * @return array 
     */
    public function getTabIdActions()
    {
        return $this->tabIdActions;
    }

    /**
     * Add utilisateurs
     *
     * @param \admin\UserBundle\Entity\Utilisateur $utilisateurs
     * @return Profil
     */
    public function addUtilisateur(\admin\UserBundle\Entity\Utilisateur $utilisateurs)
    {
        $this->utilisateurs[] = $utilisateurs;

        return $this;
    }

    /**
     * Remove utilisateurs
     *
     * @param \admin\UserBundle\Entity\Utilisateur $utilisateurs
     */
    public function removeUtilisateur(\admin\UserBundle\Entity\Utilisateur $utilisateurs)
    {
        $this->utilisateurs->removeElement($utilisateurs);
    }

    /**
     * Get utilisateurs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUtilisateurs()
    {
        return $this->utilisateurs;
    }

    /**
     * Add abonnes
     *
     * @param \admin\UserBundle\Entity\Abonne $abonnes
     * @return Profil
     */
    public function addAbonne(\admin\UserBundle\Entity\Abonne $abonnes)
    {
        $this->abonnes[] = $abonnes;

        return $this;
    }

    /**
     * Remove abonnes
     *
     * @param \admin\UserBundle\Entity\Abonne $abonnes
     */
    public function removeAbonne(\admin\UserBundle\Entity\Abonne $abonnes)
    {
        $this->abonnes->removeElement($abonnes);
    }

    /**
     * Get abonnes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAbonnes()
    {
        return $this->abonnes;
    }

    /**
     * Set dateAjout
     *
     * @param \DateTime $dateAjout
     * @return Profil
     */
    public function setDateAjout($dateAjout)
    {
        $this->dateAjout = $dateAjout;

        return $this;
    }

    /**
     * Get dateAjout
     *
     * @return \DateTime 
     */
    public function getDateAjout()
    {
        return $this->dateAjout;
    }

    /**
     * Set dateModification
     *
     * @param \DateTime $dateModification
     * @return Profil
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
}
