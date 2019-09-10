<?php

namespace admin\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use \admin\UserBundle\Entity\Profil;
use \admin\UserBundle\Entity\Connexion;
use \Doctrine\Common\Collections\ArrayCollection;
use \admin\UserBundle\Types\TypeEtat;
use \admin\UserBundle\Types\TypeSexe;

/**
 * User
 *
 * @ORM\Table(name="abonne")
 * @ORM\Entity(repositoryClass="admin\UserBundle\Entity\AbonneRepository")
 */
class Abonne {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $username
     *
     * @ORM\Column(name="username_abonne", type="string", length=50,  nullable=true)
     * @Assert\Length(max=50)
     */
    private $username;

    /**
     * @var string $nom
     *
     * @ORM\Column(name="nom_abonne", type="string", length=50, nullable=true)
     * @Assert\Length(max=50)
     */
    private $nom;

    /**
     * @var string $prenoms
     *
     * @ORM\Column(name="prenoms_abonne", type="string", length=50, nullable=true, nullable=true)
     * 
     */
    private $prenoms;

    /**
     * @var string $codeBase
     *
     * @ORM\Column(name="code_base_abonne", type="string", length=50, nullable=true)
     * 
     */
    private $codeBase;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email_abonne", type="string", length=50, unique=true, nullable=true)
     * @Assert\Email
     */
    private $email;

    /**
     * @var string $tel1
     *
     * @ORM\Column(name="tel1_abonne", type="string", length=20, nullable=true)
     */
    private $tel1;

    /**
     * @var string $tel2
     *
     * @ORM\Column(name="tel2_abonne", type="string", length=20, nullable=true)
     */
    private $tel2;

    /**
     * @var string $adresse
     *
     * @ORM\Column(name="adresse_abonne",   type="text", nullable=true)
     */
    private $adresse;

    /**
     * @var integer $etat
     *
     * @ORM\Column(name="etat_abonne", type="smallint")
     */
    private $etat;

    /**
     * @var integer $attempt
     *
     * @ORM\Column(name="attempt_abonne", type="smallint", nullable=true)
     */
    private $attempt;

    /**
     * @var string $password
     *
     * @ORM\Column(name="password_abonne", type="string", length=255, nullable=true, nullable=true)
     * @Assert\Length(max=255)
     */
    private $password;

    /**
     * @var string $cPassword
     *
     * @ORM\Column(name="c_password_abonne", type="string", length=255, nullable=true)
     */
    private $cPassword;

    /**
     * @var string $salt
     *
     * @ORM\Column(name="salt_abonne", type="string", length=255, nullable=true)
     */
    private $salt;

    /**
     * @var boolean $etatConnecte
     *
     * @ORM\Column(name="etat_connecte", type="boolean", nullable=true)
     */
    private $etatConnecte;

    /**
     * @var string $adresseIp
     *
     * @ORM\Column(name="adresse_ip_abonne", type="string", length=100, nullable=true)
     */
    private $adresseIp;
    
    /**
     * @var string $numCompte
     *
     * @ORM\Column(name="num_compte", type="string", length=100, nullable=true)
     */
    private $numCompte;

    /**
     * @var integer $sexe
     *
     * @ORM\Column(name="sexe_abonne", type="smallint", nullable=true)
     */
    private $sexe;

    /**
     * @var \DateTime $dateAjout
     *
     * @ORM\Column(name="date_ajout_abonne", type="datetime")
     */
    private $dateAjout;

    /**
     * @var \DateTime $dateModification
     *
     * @ORM\Column(name="date_edit_abonne", type="datetime", nullable=true)
     */
    private $dateModification;

    /**
     * @var string $lossPasswordUrl
     *
     * @ORM\Column(name="loss_password_url", type="string", length=255, nullable=true)
     */
    private $lossPasswordUrl;

    /**
     * @var \DateTime $dateLossPassword
     *
     * @ORM\Column(name="date_loss_password", type="datetime", nullable=true)
     */
    private $dateLossPassword;

    /**
     * @var Profil  $profil
     * @ORM\ManyToOne(targetEntity="admin\UserBundle\Entity\Profil", inversedBy="abonnes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $profil;

    /**
     *
     * @var ArrayCollection $connexions
     * @ORM\OneToMany(targetEntity="admin\UserBundle\Entity\Connexion", mappedBy="abonne")
     */
    private $connexions;


    
    /**
     * @var string $nomContact
     *
     * @ORM\Column(name="nom_contact", type="string", length=70, nullable=true)
     * @Assert\Length(max=50)
     */
    private $nomContact; 
    
    /**
     * @var Profil  $profil
     * @ORM\ManyToOne(targetEntity="admin\UserBundle\Entity\TypeAbonne", inversedBy="abonnes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $typeAbonne;
    
    
    /**
     * @var ArrayCollection imagepers
     * @ORM\OneToMany(targetEntity="admin\UserBundle\Entity\ImagePersonne", cascade={"persist", "remove"}, mappedBy="abonne")
     */
    private $imagepers;     
    
    
    public function __construct() {
        $this->dateAjout = new \DateTime();
        $this->connexions = new ArrayCollection();


        $this->attempt = 0;
        $this->cPassword = '';
        $this->salt = md5(uniqid(null, true));
        $this->etat = TypeEtat::ACTIF;
        $this->etatConnecte = FALSE;
        $this->sexe = TypeSexe::MASCULIN;
        $this->messagesEnvois = new ArrayCollection();
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
     * Set username
     *
     * @param string $username
     * @return Abonne
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Abonne
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
     * Set prenoms
     *
     * @param string $prenoms
     * @return Abonne
     */
    public function setPrenoms($prenoms)
    {
        $this->prenoms = $prenoms;

        return $this;
    }

    /**
     * Get prenoms
     *
     * @return string 
     */
    public function getPrenoms()
    {
        return $this->prenoms;
    }

    /**
     * Set codeBase
     *
     * @param string $codeBase
     * @return Abonne
     */
    public function setCodeBase($codeBase)
    {
        $this->codeBase = $codeBase;

        return $this;
    }

    /**
     * Get codeBase
     *
     * @return string 
     */
    public function getCodeBase()
    {
        return $this->codeBase;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Abonne
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set tel1
     *
     * @param string $tel1
     * @return Abonne
     */
    public function setTel1($tel1)
    {
        $this->tel1 = $tel1;

        return $this;
    }

    /**
     * Get tel1
     *
     * @return string 
     */
    public function getTel1()
    {
        return $this->tel1;
    }

    /**
     * Set tel2
     *
     * @param string $tel2
     * @return Abonne
     */
    public function setTel2($tel2)
    {
        $this->tel2 = $tel2;

        return $this;
    }

    /**
     * Get tel2
     *
     * @return string 
     */
    public function getTel2()
    {
        return $this->tel2;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     * @return Abonne
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string 
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set etat
     *
     * @param integer $etat
     * @return Abonne
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
     * Set attempt
     *
     * @param integer $attempt
     * @return Abonne
     */
    public function setAttempt($attempt)
    {
        $this->attempt = $attempt;

        return $this;
    }

    /**
     * Get attempt
     *
     * @return integer 
     */
    public function getAttempt()
    {
        return $this->attempt;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Abonne
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set cPassword
     *
     * @param string $cPassword
     * @return Abonne
     */
    public function setCPassword($cPassword)
    {
        $this->cPassword = $cPassword;

        return $this;
    }

    /**
     * Get cPassword
     *
     * @return string 
     */
    public function getCPassword()
    {
        return $this->cPassword;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Abonne
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set etatConnecte
     *
     * @param boolean $etatConnecte
     * @return Abonne
     */
    public function setEtatConnecte($etatConnecte)
    {
        $this->etatConnecte = $etatConnecte;

        return $this;
    }

    /**
     * Get etatConnecte
     *
     * @return boolean 
     */
    public function getEtatConnecte()
    {
        return $this->etatConnecte;
    }

    /**
     * Set adresseIp
     *
     * @param string $adresseIp
     * @return Abonne
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
     * Set sexe
     *
     * @param integer $sexe
     * @return Abonne
     */
    public function setSexe($sexe)
    {
        $this->sexe = $sexe;

        return $this;
    }

    /**
     * Get sexe
     *
     * @return integer 
     */
    public function getSexe()
    {
        return $this->sexe;
    }

    /**
     * Set dateAjout
     *
     * @param \DateTime $dateAjout
     * @return Abonne
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
     * @return Abonne
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
     * Set lossPasswordUrl
     *
     * @param string $lossPasswordUrl
     * @return Abonne
     */
    public function setLossPasswordUrl($lossPasswordUrl)
    {
        $this->lossPasswordUrl = $lossPasswordUrl;

        return $this;
    }

    /**
     * Get lossPasswordUrl
     *
     * @return string 
     */
    public function getLossPasswordUrl()
    {
        return $this->lossPasswordUrl;
    }

    /**
     * Set dateLossPassword
     *
     * @param \DateTime $dateLossPassword
     * @return Abonne
     */
    public function setDateLossPassword($dateLossPassword)
    {
        $this->dateLossPassword = $dateLossPassword;

        return $this;
    }

    /**
     * Get dateLossPassword
     *
     * @return \DateTime 
     */
    public function getDateLossPassword()
    {
        return $this->dateLossPassword;
    }

    /**
     * Set profil
     *
     * @param \admin\UserBundle\Entity\Profil $profil
     * @return Abonne
     */
    public function setProfil(\admin\UserBundle\Entity\Profil $profil)
    {
        $this->profil = $profil;

        return $this;
    }

    /**
     * Get profil
     *
     * @return \admin\UserBundle\Entity\Profil 
     */
    public function getProfil()
    {
        return $this->profil;
    }

    /**
     * Add connexions
     *
     * @param \admin\UserBundle\Entity\Connexion $connexions
     * @return Abonne
     */
    public function addConnexion(\admin\UserBundle\Entity\Connexion $connexions)
    {
        $this->connexions[] = $connexions;

        return $this;
    }

    /**
     * Remove connexions
     *
     * @param \admin\UserBundle\Entity\Connexion $connexions
     */
    public function removeConnexion(\admin\UserBundle\Entity\Connexion $connexions)
    {
        $this->connexions->removeElement($connexions);
    }

    /**
     * Get connexions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getConnexions()
    {
        return $this->connexions;
    }

    /**
     * Set nomContact
     *
     * @param string $nomContact
     * @return Abonne
     */
    public function setNomContact($nomContact)
    {
        $this->nomContact = $nomContact;
    
        return $this;
    }

    /**
     * Get nomContact
     *
     * @return string 
     */
    public function getNomContact()
    {
        return $this->nomContact;
    }

    /**
     * Set typeAbonne
     *
     * @param \admin\UserBundle\Entity\TypeAbonne $typeAbonne
     * @return Abonne
     */
    public function setTypeAbonne(\admin\UserBundle\Entity\TypeAbonne $typeAbonne = null)
    {
        $this->typeAbonne = $typeAbonne;
    
        return $this;
    }

    /**
     * Get typeAbonne
     *
     * @return \admin\UserBundle\Entity\TypeAbonne 
     */
    public function getTypeAbonne()
    {
        return $this->typeAbonne;
    }

    /**
     * Add imagepers
     *
     * @param \admin\UserBundle\Entity\ImagePersonne $imagepers
     * @return Abonne
     */
    public function addImageper(\admin\UserBundle\Entity\ImagePersonne $imagepers)
    {
        $this->imagepers[] = $imagepers;
    
        return $this;
    }

    /**
     * Remove imagepers
     *
     * @param \admin\UserBundle\Entity\ImagePersonne $imagepers
     */
    public function removeImageper(\admin\UserBundle\Entity\ImagePersonne $imagepers)
    {
        $this->imagepers->removeElement($imagepers);
    }

    /**
     * Get imagepers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImagepers()
    {
        return $this->imagepers;
    }

    /**
     * Set numCompte
     *
     * @param string $numCompte
     * @return Abonne
     */
    public function setNumCompte($numCompte)
    {
        $this->numCompte = $numCompte;
    
        return $this;
    }

    /**
     * Get numCompte
     *
     * @return string 
     */
    public function getNumCompte()
    {
        return $this->numCompte;
    }
}
