<?php

namespace admin\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use \admin\UserBundle\Entity\Profil;
use \admin\UserBundle\Entity\Connexion;
use \Doctrine\Common\Collections\ArrayCollection;
use \DateTime;

/**
 * User
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="utilisateur")
 * @ORM\Entity(repositoryClass="admin\UserBundle\Entity\UtilisateurRepository")
 */
class Utilisateur {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @var string
     *
     * @ORM\Column(name="titre_image", type="string",  nullable=true)
     */
    private $titreImage;
    /**
     * @var string $username
     *
     * @ORM\Column(name="username_user", type="string", length=50,  nullable=true)
     * @Assert\Length(max=50)
     */
    private $username;

    /**
     * @var string $nom
     *
     * @ORM\Column(name="nom_user", type="string", length=50)
     * @Assert\Length(max=50)
     */
    private $nom;

    /**
     * @var string $prenoms
     *
     * @ORM\Column(name="prenoms_user", type="string", length=50, nullable=true)
     * 
     */
    private $prenoms;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email_user", type="string", length=50, unique=true)
     * @Assert\Email
     */
    private $email;

    /**
     * @var string $tel1
     *
     * @ORM\Column(name="tel1_user", type="string", length=20)
     */
    private $tel1;

    /**
     * @var string $tel2
     *
     * @ORM\Column(name="tel2_user", type="string", length=20, nullable=true)
     */
    private $tel2;

    /**
     * @var string $adresse
     *
     * @ORM\Column(name="adresse_user",   type="text", nullable=true)
     */
    private $adresse;

    /**
     * @var integer $etat
     *
     * @ORM\Column(name="etat_user", type="smallint")
     */
    private $etat;

    /**
     * @var integer $attempt
     *
     * @ORM\Column(name="attempt_user", type="smallint")
     */
    private $attempt;
    
    /**
     * @var integer $siAdminGeneral
     *
     * @ORM\Column(name="si_admin_general", type="smallint")
     */
    private $siAdminGeneral;

    /**
     * @var string $password
     *
     * @ORM\Column(name="password_user", type="string", length=255, nullable=true)
     * @Assert\Length(max=255)
     */
    private $password;

    /**
     * @var string $cPassword
     *
     * @ORM\Column(name="c_password_user", type="string", length=255, nullable=true)
     */
    private $cPassword;

    /**
     * @var string $salt
     *
     * @ORM\Column(name="salt_user", type="string", length=255)
     */
    private $salt;

    /**
     * @var boolean $etatConnecte
     *
     * @ORM\Column(name="etat_connecte", type="boolean")
     */
    private $etatConnecte;

    /**
     * @var string $adresseIp
     *
     * @ORM\Column(name="adresse_ip_user", type="string", length=100, nullable=true)
     */
    private $adresseIp;

    /**
     * @var integer $sexe
     *
     * @ORM\Column(name="sexe_user", type="smallint", nullable=true)
     */
    private $sexe;

    /**
     * @var \DateTime $dateAjout
     *
     * @ORM\Column(name="date_ajout_user", type="datetime")
     */
    private $dateAjout;

    /**
     * @var \DateTime $dateModification
     *
     * @ORM\Column(name="date_edit_user", type="datetime", nullable=true)
     */
    private $dateModification;

    /**
     * @var string $lossPasswordUrl
     *
     * @ORM\Column(name="loss_password_url_user", type="string", length=255, nullable=true)
     */
    private $lossPasswordUrl;

    /**
     * @var \DateTime $dateLossPassword
     *
     * @ORM\Column(name="date_loss_password_user", type="datetime", nullable=true)
     */
    private $dateLossPassword;

    /**
     * @var Profil  $profil
     * @ORM\ManyToOne(targetEntity="admin\UserBundle\Entity\Profil", inversedBy="utilisateurs")
     * @ORM\JoinColumn(nullable=true)
     */
    private $profil;
    
    /**
     * @var Eleve  $eleve
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Eleve", inversedBy="utilisateurs")
     * @ORM\JoinColumn(nullable=true)
     */
    private $eleve;

    /**
     *
     * @var ArrayCollection $connexions
     * @ORM\OneToMany(targetEntity="admin\UserBundle\Entity\Connexion",mappedBy="utilisateur")
     */
    private $connexions;
    
    
    /**
     *
     * @var ArrayCollection $presences
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\Presence",mappedBy="utilisateur")
     */
    private $presences;
    
    /**
     *
     * @var ArrayCollection $epreuves
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\Epreuve",mappedBy="utilisateur")
     */
    private $epreuves;
    
    
    /**
     *
     * @var ArrayCollection $fairecourss 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\FaireCours", cascade={"persist", "remove"}, mappedBy="utilisateur")
     * 
     */
    private $fairecourss;    
    
    /**
     * @var ArrayCollection operations
     * @ORM\OneToMany(targetEntity="admin\EconomatBundle\Entity\Operation", cascade={"persist", "remove"}, mappedBy="utilisateur")
     */
    private $operations; 
    
    /**
     * @var Etablissement  $etablissement
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Etablissement", inversedBy="utilisateurs")
     * @ORM\JoinColumn(nullable=true)
     */
    private $etablissement;

    /**
     * @var ArrayCollection $estparents
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\EstParent", cascade={"persist", "remove"}, mappedBy="utilisateur")
     */
    private $estparents;    
    
    /**
     * @var ArrayCollection Matiere $matieres
     * @ORM\ManyToMany(targetEntity="admin\ScolariteBundle\Entity\Matiere", inversedBy="utilisateurs",cascade={"persist","merge"})
     * 
     */
    private $matieres;   
    /**
     * @var ArrayCollection medias
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\Media", cascade={"persist", "remove"}, mappedBy="utilisateur")
     */
    private $medias;     
   /**
     *
     * @var ArrayCollection $notes
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\Note", cascade={"persist", "remove"}, mappedBy="utilisateur")
     * 
     */
    private $notes;   
    
    public function __construct() {
        $this->attempt = 0;
        $this->cPassword = '';
        $this->salt = md5(uniqid(null, true));
        $this->etat = \admin\UserBundle\Types\TypeEtat::ACTIF;
        $this->siAdminGeneral = \admin\UserBundle\Types\TypeEtat::APRODUIRE;
        $this->etatConnecte = FALSE;
        $this->dateAjout = new \DateTime();
        $this->connexions = new ArrayCollection();
        $this->sexe = \admin\UserBundle\Types\TypeSexe::MASCULIN;
        $this->adresseIp = '';
        $this->messagesEnvois = new ArrayCollection();
    }

/**
     * @Assert\File(
     * maxSize = "100M",
     * mimeTypes = {"/txt" },
     * mimeTypesMessage = "Format invalide"
     * )
     */
    public $file;
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
        $this->datePublication = new \DateTime();
        //$nomFichier = ""; 

        if (null !== $this->file) {

                $this->titreImage = uniqid(mt_rand(), true) . '.' . $this->file->guessExtension();
                $this->nomFile = $this->file->getClientOriginalName();
                
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload() {
        if (null === $this->file) {
            return;
        }
        $this->file->move($this->getUploadRootDir()."/".$this->id, $this->titreImage);
        //chmod($this->getUploadRootDir(), 0755);
        unset($this->file);
    }

    public function removeUpload($file) {
        unlink($file);
    }

    public function getAbsolutePath() {
        return null === $this->titreImage ? null : $this->getUploadRootDir() . '' . $this->titreImage;
    }

    public function getWebPath() {
        return null === $this->titreImage ? null : $this->getUploadDir() . '' . $this->titreImage;
    }

    public function getUploadRootDir() {
        // le chemin absolu du r�pertoire o� les documents upload�s doivent �tre sauvegard�s
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    public function getUploadDir() {
        // on se d�barrasse de � __DIR__ � afin de ne pas avoir de probl�me lorsqu'on affiche
        // le document/image dans la vue.
        return 'upload/chargement/enseignant';
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
     * Set username
     *
     * @param string $username
     * @return Utilisateur
     */
    public function setUsername($username) {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Utilisateur
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
     * Set prenoms
     *
     * @param string $prenoms
     * @return Utilisateur
     */
    public function setPrenoms($prenoms) {
        $this->prenoms = $prenoms;

        return $this;
    }

    /**
     * Get prenoms
     *
     * @return string 
     */
    public function getPrenoms() {
        return $this->prenoms;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Utilisateur
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set tel1
     *
     * @param string $tel1
     * @return Utilisateur
     */
    public function setTel1($tel1) {
        $this->tel1 = $tel1;

        return $this;
    }

    /**
     * Get tel1
     *
     * @return string 
     */
    public function getTel1() {
        return $this->tel1;
    }

    /**
     * Set tel2
     *
     * @param string $tel2
     * @return Utilisateur
     */
    public function setTel2($tel2) {
        $this->tel2 = $tel2;

        return $this;
    }

    /**
     * Get tel2
     *
     * @return string 
     */
    public function getTel2() {
        return $this->tel2;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     * @return Utilisateur
     */
    public function setAdresse($adresse) {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string 
     */
    public function getAdresse() {
        return $this->adresse;
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
     * Set attempt
     *
     * @param integer $attempt
     * @return Utilisateur
     */
    public function setAttempt($attempt) {
        $this->attempt = $attempt;

        return $this;
    }

    /**
     * Get attempt
     *
     * @return integer 
     */
    public function getAttempt() {
        return $this->attempt;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Utilisateur
     */
    public function setPassword($password) {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Set cPassword
     *
     * @param string $cPassword
     * @return Utilisateur
     */
    public function setCPassword($cPassword) {
        $this->cPassword = $cPassword;

        return $this;
    }

    /**
     * Get cPassword
     *
     * @return string 
     */
    public function getCPassword() {
        return $this->cPassword;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Utilisateur
     */
    public function setSalt($salt) {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt() {
        return $this->salt;
    }

    /**
     * Set etatConnecte
     *
     * @param boolean $etatConnecte
     * @return Utilisateur
     */
    public function setEtatConnecte($etatConnecte) {
        $this->etatConnecte = $etatConnecte;

        return $this;
    }

    /**
     * Get etatConnecte
     *
     * @return boolean 
     */
    public function getEtatConnecte() {
        return $this->etatConnecte;
    }

    /**
     * Set adresseIp
     *
     * @param string $adresseIp
     * @return Utilisateur
     */
    public function setAdresseIp($adresseIp) {
        $this->adresseIp = $adresseIp;

        return $this;
    }

    /**
     * Get adresseIp
     *
     * @return string 
     */
    public function getAdresseIp() {
        return $this->adresseIp;
    }

    /**
     * Set sexe
     *
     * @param integer $sexe
     * @return Utilisateur
     */
    public function setSexe($sexe) {
        $this->sexe = $sexe;

        return $this;
    }

    /**
     * Get sexe
     *
     * @return integer 
     */
    public function getSexe() {
        return $this->sexe;
    }

    /**
     * Set dateAjout
     *
     * @param \DateTime $dateAjout
     * @return Utilisateur
     */
    public function setDateAjout($dateAjout) {
        $this->dateAjout = $dateAjout;

        return $this;
    }

    /**
     * Get dateAjout
     *
     * @return \DateTime 
     */
    public function getDateAjout() {
        return $this->dateAjout;
    }

    /**
     * Set dateModification
     *
     * @param \DateTime $dateModification
     * @return Utilisateur
     */
    public function setDateModification($dateModification) {
        $this->dateModification = $dateModification;

        return $this;
    }

    /**
     * Get dateModification
     *
     * @return \DateTime 
     */
    public function getDateModification() {
        return $this->dateModification;
    }

    /**
     * Set lossPasswordUrl
     *
     * @param string $lossPasswordUrl
     * @return Utilisateur
     */
    public function setLossPasswordUrl($lossPasswordUrl) {
        $this->lossPasswordUrl = $lossPasswordUrl;

        return $this;
    }

    /**
     * Get lossPasswordUrl
     *
     * @return string 
     */
    public function getLossPasswordUrl() {
        return $this->lossPasswordUrl;
    }

    /**
     * Set dateLossPassword
     *
     * @param \DateTime $dateLossPassword
     * @return Utilisateur
     */
    public function setDateLossPassword($dateLossPassword) {
        $this->dateLossPassword = $dateLossPassword;

        return $this;
    }

    /**
     * Get dateLossPassword
     *
     * @return \DateTime 
     */
    public function getDateLossPassword() {
        return $this->dateLossPassword;
    }

    /**
     * Set profil
     *
     * @param \admin\UserBundle\Entity\Profil $profil
     * @return Utilisateur
     */
    public function setProfil(\admin\UserBundle\Entity\Profil $profil) {
        $this->profil = $profil;

        return $this;
    }

    /**
     * Get profil
     *
     * @return \admin\UserBundle\Entity\Profil 
     */
    public function getProfil() {
        return $this->profil;
    }

    /**
     * Add connexions
     *
     * @param \admin\UserBundle\Entity\Connexion $connexions
     * @return Utilisateur
     */
    public function addConnexion(\admin\UserBundle\Entity\Connexion $connexions) {
        $this->connexions[] = $connexions;

        return $this;
    }

    /**
     * Remove connexions
     *
     * @param \admin\UserBundle\Entity\Connexion $connexions
     */
    public function removeConnexion(\admin\UserBundle\Entity\Connexion $connexions) {
        $this->connexions->removeElement($connexions);
    }

    /**
     * Get connexions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getConnexions() {
        return $this->connexions;
    }


    /**
     * Add fairecourss
     *
     * @param \admin\ScolariteBundle\Entity\FaireCours $fairecourss
     *
     * @return Utilisateur
     */
    public function addFairecourss(\admin\ScolariteBundle\Entity\FaireCours $fairecourss)
    {
        $this->fairecourss[] = $fairecourss;

        return $this;
    }

    /**
     * Remove fairecourss
     *
     * @param \admin\ScolariteBundle\Entity\FaireCours $fairecourss
     */
    public function removeFairecourss(\admin\ScolariteBundle\Entity\FaireCours $fairecourss)
    {
        $this->fairecourss->removeElement($fairecourss);
    }

    /**
     * Get fairecourss
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFairecourss()
    {
        return $this->fairecourss;
    }

    /**
     * Add operation
     *
     * @param \admin\EconomatBundle\Entity\Operation $operation
     *
     * @return Utilisateur
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
     * Set etablissement
     *
     * @param \admin\ScolariteBundle\Entity\Etablissement $etablissement
     *
     * @return Utilisateur
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
     * Add estparent
     *
     * @param \admin\ScolariteBundle\Entity\EstParent $estparent
     *
     * @return Utilisateur
     */
    public function addEstparent(\admin\ScolariteBundle\Entity\EstParent $estparent)
    {
        $this->estparents[] = $estparent;

        return $this;
    }

    /**
     * Remove estparent
     *
     * @param \admin\ScolariteBundle\Entity\EstParent $estparent
     */
    public function removeEstparent(\admin\ScolariteBundle\Entity\EstParent $estparent)
    {
        $this->estparents->removeElement($estparent);
    }

    /**
     * Get estparents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEstparents()
    {
        return $this->estparents;
    }

    /**
     * Add matiere
     *
     * @param \admin\ScolariteBundle\Entity\Matiere $matiere
     *
     * @return Utilisateur
     */
    public function addMatiere(\admin\ScolariteBundle\Entity\Matiere $matiere)
    {
        $this->matieres[] = $matiere;

        return $this;
    }

    /**
     * Remove matiere
     *
     * @param \admin\ScolariteBundle\Entity\Matiere $matiere
     */
    public function removeMatiere(\admin\ScolariteBundle\Entity\Matiere $matiere)
    {
        $this->matieres->removeElement($matiere);
    }

    /**
     * Get matieres
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMatieres()
    {
        return $this->matieres;
    }

    /**
     * Add media
     *
     * @param \admin\ScolariteBundle\Entity\Media $media
     *
     * @return Utilisateur
     */
    public function addMedia(\admin\ScolariteBundle\Entity\Media $media)
    {
        $this->medias[] = $media;

        return $this;
    }

    /**
     * Remove media
     *
     * @param \admin\ScolariteBundle\Entity\Media $media
     */
    public function removeMedia(\admin\ScolariteBundle\Entity\Media $media)
    {
        $this->medias->removeElement($media);
    }

    /**
     * Get medias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMedias()
    {
        return $this->medias;
    }

    /**
     * Set titreImage
     *
     * @param string $titreImage
     *
     * @return Utilisateur
     */
    public function setTitreImage($titreImage)
    {
        $this->titreImage = $titreImage;

        return $this;
    }

    /**
     * Get titreImage
     *
     * @return string
     */
    public function getTitreImage()
    {
        return $this->titreImage;
    }

    /**
     * Add note
     *
     * @param \admin\ScolariteBundle\Entity\Note $note
     *
     * @return Utilisateur
     */
    public function addNote(\admin\ScolariteBundle\Entity\Note $note)
    {
        $this->notes[] = $note;

        return $this;
    }

    /**
     * Remove note
     *
     * @param \admin\ScolariteBundle\Entity\Note $note
     */
    public function removeNote(\admin\ScolariteBundle\Entity\Note $note)
    {
        $this->notes->removeElement($note);
    }

    /**
     * Get notes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set siAdminGeneral
     *
     * @param integer $siAdminGeneral
     *
     * @return Utilisateur
     */
    public function setSiAdminGeneral($siAdminGeneral)
    {
        $this->siAdminGeneral = $siAdminGeneral;

        return $this;
    }

    /**
     * Get siAdminGeneral
     *
     * @return integer
     */
    public function getSiAdminGeneral()
    {
        return $this->siAdminGeneral;
    }

    /**
     * Add presence
     *
     * @param \admin\ScolariteBundle\Entity\Presence $presence
     *
     * @return Utilisateur
     */
    public function addPresence(\admin\ScolariteBundle\Entity\Presence $presence)
    {
        $this->presences[] = $presence;

        return $this;
    }

    /**
     * Remove presence
     *
     * @param \admin\ScolariteBundle\Entity\Presence $presence
     */
    public function removePresence(\admin\ScolariteBundle\Entity\Presence $presence)
    {
        $this->presences->removeElement($presence);
    }

    /**
     * Get presences
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPresences()
    {
        return $this->presences;
    }

    /**
     * Add epreufe
     *
     * @param \admin\ScolariteBundle\Entity\Epreuve $epreufe
     *
     * @return Utilisateur
     */
    public function addEpreufe(\admin\ScolariteBundle\Entity\Epreuve $epreufe)
    {
        $this->epreuves[] = $epreufe;

        return $this;
    }

    /**
     * Remove epreufe
     *
     * @param \admin\ScolariteBundle\Entity\Epreuve $epreufe
     */
    public function removeEpreufe(\admin\ScolariteBundle\Entity\Epreuve $epreufe)
    {
        $this->epreuves->removeElement($epreufe);
    }

    /**
     * Get epreuves
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEpreuves()
    {
        return $this->epreuves;
    }

    /**
     * Set eleve
     *
     * @param \admin\ScolariteBundle\Entity\Eleve $eleve
     *
     * @return Utilisateur
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
