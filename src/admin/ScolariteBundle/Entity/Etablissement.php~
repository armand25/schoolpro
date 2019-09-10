<?php

namespace admin\ScolariteBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \admin\UserBundle\Types\TypeEtat;

/**
 * Etablissement
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="etablissement")
 * @ORM\Entity(repositoryClass="admin\ScolariteBundle\Entity\EtablissementRepository")
 */
class Etablissement
{
     public function __construct() {
        $this->etatEtablissement = TypeEtat::ACTIF;
       
    }    
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
     * @Assert\File(
     * maxSize = "100M",
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
                $this->titreImage = $this->getUploadDir()."/".uniqid(mt_rand(), true) . '.' . $this->file->guessExtension();
                $this->nomFile = $this->file->getClientOriginalName();                
        }else{
            $this->titreImage = 'default.png';
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload() {
        if (null === $this->file) {
           // return;
        }else{
            $this->file->move($this->getUploadRootDir(), $this->titreImage);
            //chmod($this->getUploadRootDir(), 0755);
            unset($this->file);
        }
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
        return 'upload/chargement/etabl';
    }    
    
    
    /**
     * @var string $libelleEtablissement
     *
     * @ORM\Column(name="libelle_etablissement", type="string")
     */
    private $libelleEtablissement;
    
    /**
     * @var string $contactEtablissement
     *
     * @ORM\Column(name="contact_etablissement", type="string")
     */
    private $contactEtablissement; 
    
    /**
     * @var string $codeEtablissement
     *
     * @ORM\Column(name="code_etablissement", type="string")
     */
    private $codeEtablissement; 
    
    /**
     * @var string $adresseEtablissement
     *
     * @ORM\Column(name="adresse_etablissement", type="string")
     */
    private $adresseEtablissement;  
    
    /**
     * @var string $deviseEtablissement
     *
     * @ORM\Column(name="devise_etablissement", type="string")
     */
    private $deviseEtablissement;    
    
    
    /**
     * @var \Datetime $dateInitialEtablissement
     *
     * @ORM\Column(name="date_initial_anneescolaire", type="datetime")
     */
    private $dateInitialEtablissement;
    
    
    /**
     * @var string $etatEtablissement
     *
     * @ORM\Column(name="etat_etablissement", type="integer")
     */
    private $etatEtablissement;
    
    /**
     *
     * @var ArrayCollection $utilisateurs 
     * @ORM\OneToMany(targetEntity="admin\UserBundle\Entity\Utilisateur", cascade={"persist", "remove"}, mappedBy="etablissement")
     * 
     */
    private $utilisateurs;      

    
    /**
     *
     * @var ArrayCollection $eleves 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\Eleve", cascade={"persist", "remove"}, mappedBy="etablissement")
     * 
     */
    private $eleves;   
    
    /**
     *
     * @var ArrayCollection $menus 
     * @ORM\OneToMany(targetEntity="admin\CmsBundle\Entity\Menu", cascade={"persist", "remove"}, mappedBy="etablissement")
     * 
     */
    private $menus;   
    
    /**
     * @var Template  $template
     * @ORM\ManyToOne(targetEntity="admin\CmsBundle\Entity\Template", inversedBy="etablissements")
     * @ORM\JoinColumn(nullable=true)
     */
    private $template;  
    
    /**
     *
     * @var ArrayCollection $operations 
     * @ORM\OneToMany(targetEntity="admin\EconomatBundle\Entity\Operation", cascade={"persist", "remove"}, mappedBy="etablissement")
     * 
     */
    private $operations;     
    
    
    /**
     *
     * @var ArrayCollection $schemas 
     * @ORM\OneToMany(targetEntity="admin\EconomatBundle\Entity\Schema", cascade={"persist", "remove"}, mappedBy="etablissement")
     * 
     */
    private $schemas; 
    
    /**
     *
     * @var ArrayCollection $typeoperations 
     * @ORM\OneToMany(targetEntity="admin\EconomatBundle\Entity\TypeOperation", cascade={"persist", "remove"}, mappedBy="etablissement")
     * 
     */
    private $typeoperations; 
    
    /**
     *
     * @var ArrayCollection $classes 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\Classe", cascade={"persist", "remove"}, mappedBy="etablissement")
     * 
     */
    private $classes; 
    
    /**
     *
     * @var ArrayCollection $fairecourss 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\FaireCours", cascade={"persist", "remove"}, mappedBy="etablissement")
     * 
     */
    private $fairecourss; 
    
    /**
     *
     * @var ArrayCollection $estenseignes 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\EstEnseigne", cascade={"persist", "remove"}, mappedBy="etablissement")
     * 
     */
    private $estenseignes; 
    
    /**
     *
     * @var ArrayCollection $concerners 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\Concerner", cascade={"persist", "remove"}, mappedBy="etablissement")
     * 
     */
    private $concerners; 
    
    /**
     *
     * @var ArrayCollection $anneescolaires 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\AnneeScolaire", cascade={"persist", "remove"}, mappedBy="etablissement")
     * 
     */
    private $anneescolaires; 
    
    /**
     *
     * @var ArrayCollection $ecolages 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\Ecolage", cascade={"persist", "remove"}, mappedBy="etablissement")
     * 
     */
    private $ecolages; 
    
    /**
     *
     * @var ArrayCollection $rubriques 
     * @ORM\OneToMany(targetEntity="admin\CmsBundle\Entity\Rubrique", cascade={"persist", "remove"}, mappedBy="etablissement")
     * 
     */
    private $rubriques; 
    /**
     *
     * @var ArrayCollection $articles 
     * @ORM\OneToMany(targetEntity="admin\CmsBundle\Entity\Article", cascade={"persist", "remove"}, mappedBy="etablissement")
     * 
     */
    private $articles; 
    
    /**
     *
     * @var ArrayCollection $plancomptables 
     * @ORM\OneToMany(targetEntity="admin\EconomatBundle\Entity\PlanComptable", cascade={"persist", "remove"}, mappedBy="etablissement")
     * 
     */
    private $plancomptables; 
    
    /**
     * @var ArrayCollection zonepointeetablissements
     * @ORM\OneToMany(targetEntity="admin\CmsBundle\Entity\ZonePointeEtablissement", cascade={"persist", "remove"}, mappedBy="etablissement")
     */
    private $zonepointeetablissements;     
    
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
     * Set libelleEtablissement
     *
     * @param string $libelleEtablissement
     *
     * @return Etablissement
     */
    public function setLibelleEtablissement($libelleEtablissement)
    {
        $this->libelleEtablissement = $libelleEtablissement;

        return $this;
    }

    /**
     * Get libelleEtablissement
     *
     * @return string
     */
    public function getLibelleEtablissement()
    {
        return $this->libelleEtablissement;
    }

    /**
     * Set contactEtablissement
     *
     * @param string $contactEtablissement
     *
     * @return Etablissement
     */
    public function setContactEtablissement($contactEtablissement)
    {
        $this->contactEtablissement = $contactEtablissement;

        return $this;
    }

    /**
     * Get contactEtablissement
     *
     * @return string
     */
    public function getContactEtablissement()
    {
        return $this->contactEtablissement;
    }

    /**
     * Set adresseEtablissement
     *
     * @param string $adresseEtablissement
     *
     * @return Etablissement
     */
    public function setAdresseEtablissement($adresseEtablissement)
    {
        $this->adresseEtablissement = $adresseEtablissement;

        return $this;
    }

    /**
     * Get adresseEtablissement
     *
     * @return string
     */
    public function getAdresseEtablissement()
    {
        return $this->adresseEtablissement;
    }

    /**
     * Set deviseEtablissement
     *
     * @param string $deviseEtablissement
     *
     * @return Etablissement
     */
    public function setDeviseEtablissement($deviseEtablissement)
    {
        $this->deviseEtablissement = $deviseEtablissement;

        return $this;
    }

    /**
     * Get deviseEtablissement
     *
     * @return string
     */
    public function getDeviseEtablissement()
    {
        return $this->deviseEtablissement;
    }

    /**
     * Set dateInitialEtablissement
     *
     * @param \DateTime $dateInitialEtablissement
     *
     * @return Etablissement
     */
    public function setDateInitialEtablissement($dateInitialEtablissement)
    {
        $this->dateInitialEtablissement = $dateInitialEtablissement;

        return $this;
    }

    /**
     * Get dateInitialEtablissement
     *
     * @return \DateTime
     */
    public function getDateInitialEtablissement()
    {
        return $this->dateInitialEtablissement;
    }

    /**
     * Set etatEtablissement
     *
     * @param integer $etatEtablissement
     *
     * @return Etablissement
     */
    public function setEtatEtablissement($etatEtablissement)
    {
        $this->etatEtablissement = $etatEtablissement;

        return $this;
    }

    /**
     * Get etatEtablissement
     *
     * @return integer
     */
    public function getEtatEtablissement()
    {
        return $this->etatEtablissement;
    }

    /**
     * Add utilisateur
     *
     * @param \admin\UserBundle\Entity\Utilisateur $utilisateur
     *
     * @return Etablissement
     */
    public function addUtilisateur(\admin\UserBundle\Entity\Utilisateur $utilisateur)
    {
        $this->utilisateurs[] = $utilisateur;

        return $this;
    }

    /**
     * Remove utilisateur
     *
     * @param \admin\UserBundle\Entity\Utilisateur $utilisateur
     */
    public function removeUtilisateur(\admin\UserBundle\Entity\Utilisateur $utilisateur)
    {
        $this->utilisateurs->removeElement($utilisateur);
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
     * Add elefe
     *
     * @param \admin\ScolariteBundle\Entity\Eleve $elefe
     *
     * @return Etablissement
     */
    public function addElefe(\admin\ScolariteBundle\Entity\Eleve $elefe)
    {
        $this->eleves[] = $elefe;

        return $this;
    }

    /**
     * Remove elefe
     *
     * @param \admin\ScolariteBundle\Entity\Eleve $elefe
     */
    public function removeElefe(\admin\ScolariteBundle\Entity\Eleve $elefe)
    {
        $this->eleves->removeElement($elefe);
    }

    /**
     * Get eleves
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEleves()
    {
        return $this->eleves;
    }

    /**
     * Add operation
     *
     * @param \admin\EconomatBundle\Entity\Operation $operation
     *
     * @return Etablissement
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
     * Add schema
     *
     * @param \admin\EconomatBundle\Entity\Schema $schema
     *
     * @return Etablissement
     */
    public function addSchema(\admin\EconomatBundle\Entity\Schema $schema)
    {
        $this->schemas[] = $schema;

        return $this;
    }

    /**
     * Remove schema
     *
     * @param \admin\EconomatBundle\Entity\Schema $schema
     */
    public function removeSchema(\admin\EconomatBundle\Entity\Schema $schema)
    {
        $this->schemas->removeElement($schema);
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
     * Add typeoperation
     *
     * @param \admin\EconomatBundle\Entity\TypeOperation $typeoperation
     *
     * @return Etablissement
     */
    public function addTypeoperation(\admin\EconomatBundle\Entity\TypeOperation $typeoperation)
    {
        $this->typeoperations[] = $typeoperation;

        return $this;
    }

    /**
     * Remove typeoperation
     *
     * @param \admin\EconomatBundle\Entity\TypeOperation $typeoperation
     */
    public function removeTypeoperation(\admin\EconomatBundle\Entity\TypeOperation $typeoperation)
    {
        $this->typeoperations->removeElement($typeoperation);
    }

    /**
     * Get typeoperations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTypeoperations()
    {
        return $this->typeoperations;
    }

    /**
     * Add class
     *
     * @param \admin\ScolariteBundle\Entity\Classe $class
     *
     * @return Etablissement
     */
    public function addClass(\admin\ScolariteBundle\Entity\Classe $class)
    {
        $this->classes[] = $class;

        return $this;
    }

    /**
     * Remove class
     *
     * @param \admin\ScolariteBundle\Entity\Classe $class
     */
    public function removeClass(\admin\ScolariteBundle\Entity\Classe $class)
    {
        $this->classes->removeElement($class);
    }

    /**
     * Get classes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * Add anneescolaire
     *
     * @param \admin\ScolariteBundle\Entity\AnneeScolaire $anneescolaire
     *
     * @return Etablissement
     */
    public function addAnneescolaire(\admin\ScolariteBundle\Entity\AnneeScolaire $anneescolaire)
    {
        $this->anneescolaires[] = $anneescolaire;

        return $this;
    }

    /**
     * Remove anneescolaire
     *
     * @param \admin\ScolariteBundle\Entity\AnneeScolaire $anneescolaire
     */
    public function removeAnneescolaire(\admin\ScolariteBundle\Entity\AnneeScolaire $anneescolaire)
    {
        $this->anneescolaires->removeElement($anneescolaire);
    }

    /**
     * Get anneescolaires
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnneescolaires()
    {
        return $this->anneescolaires;
    }

    /**
     * Add ecolage
     *
     * @param \admin\ScolariteBundle\Entity\Ecolage $ecolage
     *
     * @return Etablissement
     */
    public function addEcolage(\admin\ScolariteBundle\Entity\Ecolage $ecolage)
    {
        $this->ecolages[] = $ecolage;

        return $this;
    }

    /**
     * Remove ecolage
     *
     * @param \admin\ScolariteBundle\Entity\Ecolage $ecolage
     */
    public function removeEcolage(\admin\ScolariteBundle\Entity\Ecolage $ecolage)
    {
        $this->ecolages->removeElement($ecolage);
    }

    /**
     * Get ecolages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEcolages()
    {
        return $this->ecolages;
    }

    /**
     * Add plancomptable
     *
     * @param \admin\EconomatBundle\Entity\PlanComptable $plancomptable
     *
     * @return Etablissement
     */
    public function addPlancomptable(\admin\EconomatBundle\Entity\PlanComptable $plancomptable)
    {
        $this->plancomptables[] = $plancomptable;

        return $this;
    }

    /**
     * Remove plancomptable
     *
     * @param \admin\EconomatBundle\Entity\PlanComptable $plancomptable
     */
    public function removePlancomptable(\admin\EconomatBundle\Entity\PlanComptable $plancomptable)
    {
        $this->plancomptables->removeElement($plancomptable);
    }

    /**
     * Get plancomptables
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlancomptables()
    {
        return $this->plancomptables;
    }

    /**
     * Add fairecourss
     *
     * @param \admin\ScolariteBundle\Entity\FaireCours $fairecourss
     *
     * @return Etablissement
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
     * Add estenseigne
     *
     * @param \admin\ScolariteBundle\Entity\EstEnseigne $estenseigne
     *
     * @return Etablissement
     */
    public function addEstenseigne(\admin\ScolariteBundle\Entity\EstEnseigne $estenseigne)
    {
        $this->estenseignes[] = $estenseigne;

        return $this;
    }

    /**
     * Remove estenseigne
     *
     * @param \admin\ScolariteBundle\Entity\EstEnseigne $estenseigne
     */
    public function removeEstenseigne(\admin\ScolariteBundle\Entity\EstEnseigne $estenseigne)
    {
        $this->estenseignes->removeElement($estenseigne);
    }

    /**
     * Get estenseignes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEstenseignes()
    {
        return $this->estenseignes;
    }

    /**
     * Add concerner
     *
     * @param \admin\ScolariteBundle\Entity\Concerner $concerner
     *
     * @return Etablissement
     */
    public function addConcerner(\admin\ScolariteBundle\Entity\Concerner $concerner)
    {
        $this->concerners[] = $concerner;

        return $this;
    }

    /**
     * Remove concerner
     *
     * @param \admin\ScolariteBundle\Entity\Concerner $concerner
     */
    public function removeConcerner(\admin\ScolariteBundle\Entity\Concerner $concerner)
    {
        $this->concerners->removeElement($concerner);
    }

    /**
     * Get concerners
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getConcerners()
    {
        return $this->concerners;
    }

    /**
     * Add rubrique
     *
     * @param \admin\CmsBundle\Entity\Rubrique $rubrique
     *
     * @return Etablissement
     */
    public function addRubrique(\admin\CmsBundle\Entity\Rubrique $rubrique)
    {
        $this->rubriques[] = $rubrique;

        return $this;
    }

    /**
     * Remove rubrique
     *
     * @param \admin\CmsBundle\Entity\Rubrique $rubrique
     */
    public function removeRubrique(\admin\CmsBundle\Entity\Rubrique $rubrique)
    {
        $this->rubriques->removeElement($rubrique);
    }

    /**
     * Get rubriques
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRubriques()
    {
        return $this->rubriques;
    }

    /**
     * Add article
     *
     * @param \admin\CmsBundle\Entity\Article $article
     *
     * @return Etablissement
     */
    public function addArticle(\admin\CmsBundle\Entity\Article $article)
    {
        $this->articles[] = $article;

        return $this;
    }

    /**
     * Remove article
     *
     * @param \admin\CmsBundle\Entity\Article $article
     */
    public function removeArticle(\admin\CmsBundle\Entity\Article $article)
    {
        $this->articles->removeElement($article);
    }

    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * Add menu
     *
     * @param \admin\CmsBundle\Entity\Menu $menu
     *
     * @return Etablissement
     */
    public function addMenu(\admin\CmsBundle\Entity\Menu $menu)
    {
        $this->menus[] = $menu;

        return $this;
    }

    /**
     * Remove menu
     *
     * @param \admin\CmsBundle\Entity\Menu $menu
     */
    public function removeMenu(\admin\CmsBundle\Entity\Menu $menu)
    {
        $this->menus->removeElement($menu);
    }

    /**
     * Get menus
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMenus()
    {
        return $this->menus;
    }

    /**
     * Set template
     *
     * @param \admin\CmsBundle\Entity\Template $template
     *
     * @return Etablissement
     */
    public function setTemplate(\admin\CmsBundle\Entity\Template $template = null)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template
     *
     * @return \admin\CmsBundle\Entity\Template
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Set titreImage
     *
     * @param string $titreImage
     *
     * @return Etablissement
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
     * Add zonepointeetablissement
     *
     * @param \admin\CmsBundle\Entity\ZonePointeEtablissement $zonepointeetablissement
     *
     * @return Etablissement
     */
    public function addZonepointeetablissement(\admin\CmsBundle\Entity\ZonePointeEtablissement $zonepointeetablissement)
    {
        $this->zonepointeetablissements[] = $zonepointeetablissement;

        return $this;
    }

    /**
     * Remove zonepointeetablissement
     *
     * @param \admin\CmsBundle\Entity\ZonePointeEtablissement $zonepointeetablissement
     */
    public function removeZonepointeetablissement(\admin\CmsBundle\Entity\ZonePointeEtablissement $zonepointeetablissement)
    {
        $this->zonepointeetablissements->removeElement($zonepointeetablissement);
    }

    /**
     * Get zonepointeetablissements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getZonepointeetablissements()
    {
        return $this->zonepointeetablissements;
    }

    /**
     * Set codeEtablissement
     *
     * @param string $codeEtablissement
     *
     * @return Etablissement
     */
    public function setCodeEtablissement($codeEtablissement)
    {
        $this->codeEtablissement = $codeEtablissement;

        return $this;
    }

    /**
     * Get codeEtablissement
     *
     * @return string
     */
    public function getCodeEtablissement()
    {
        return $this->codeEtablissement;
    }
}
