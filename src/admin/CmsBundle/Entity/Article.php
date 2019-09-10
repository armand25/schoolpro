<?php

namespace admin\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Article
 *
 * @ORM\Table(name="article")
 * @ORM\HasLifecycleCallbacks() 
 * @ORM\Entity(repositoryClass="admin\CmsBundle\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string")
     */
    private $titre;

    /**
     * @var integer
     *
     * @ORM\Column(name="actif_article", type="integer")
     */
    private $actifArticle;

     /**
     * @var string
     *
     * @ORM\Column(name="titre_image", type="string",  nullable=true)
     */
    private $titreImage;

    
     /**
     * @var integer
     *
     * @ORM\Column(name="type_article", type="integer")
     */
    private $typeArticle;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datePublication", type="date")
     */
    private $datePublication;

    /**
     * @var ArrayCollection medias
     * @ORM\OneToMany(targetEntity="admin\CmsBundle\Entity\Media", cascade={"persist", "remove"}, mappedBy="article")
     */
    private $medias; // Notez le « s », une annonce est liée à plusieurs candidatures
    
    /**
     * @var Rubrique $rubrique
     * @ORM\ManyToOne(targetEntity="admin\CmsBundle\Entity\Rubrique", inversedBy="articles")
     * @ORM\JoinColumn(nullable=true)
     */
    private $rubrique;
    
    /**
     * @var Utilisateur $utilisateur
     * @ORM\ManyToOne(targetEntity="admin\UserBundle\Entity\Utilisateur", inversedBy="articles")
     * @ORM\JoinColumn(nullable=true)
     */
    private $utilisateur;
    
    /**
     * @var text
     *
     * @ORM\Column(name="contenuArticle", type="text")
     */
    private $contenuArticle;
    
    /**
     * @var text
     *
     * @ORM\Column(name="resumeArticle", type="text")
     */
    private $resumeArticle;    
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="classHtmlArticle", type="string", length=30, nullable=true)
     */
    private $classHtmlArticle;
    
    /**
     * @var Etablissement  $etablissement
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Etablissement", inversedBy="articles")
     * @ORM\JoinColumn(nullable=true)
     */
    private $etablissement;
    /**
     * @var Niveau  $niveau
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Niveau", inversedBy="articles")
     * @ORM\JoinColumn(nullable=true)
     */
    private $niveau;
    /**
     * @var Filiere  $filiere
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Filiere", inversedBy="articles")
     * @ORM\JoinColumn(nullable=true)
     */
    private $filiere;

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
        return 'upload/chargement/article/illust';
    }    


    

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set titre
     *
     * @param string $titre
     *
     * @return Article
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set datePublication
     *
     * @param \DateTime $datePublication
     *
     * @return Article
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
     * Constructor
     */
    public function __construct()
    {
        $this->medias = new \Doctrine\Common\Collections\ArrayCollection();
        $this->actifArticle =  \admin\UserBundle\Types\TypeEtat::ACTIF;
    }

    /**
     * Add media
     *
     * @param \admin\CmsBundle\Entity\Media $media
     *
     * @return Article
     */
    public function addMedia(\admin\CmsBundle\Entity\Media $media)
    {
        $this->medias[] = $media;

        return $this;
    }

    /**
     * Remove media
     *
     * @param \admin\CmsBundle\Entity\Media $media
     */
    public function removeMedia(\admin\CmsBundle\Entity\Media $media)
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
     * Set rubrique
     *
     * @param \admin\CmsBundle\Entity\Rubrique $rubrique
     *
     * @return Article
     */
    public function setRubrique(\admin\CmsBundle\Entity\Rubrique $rubrique = null)
    {
        $this->rubrique = $rubrique;

        return $this;
    }

    /**
     * Get rubrique
     *
     * @return \admin\CmsBundle\Entity\Rubrique
     */
    public function getRubrique()
    {
        return $this->rubrique;
    }

    /**
     * Set utilisateur
     *
     * @param \admin\UserBundle\Entity\Utilisateur $utilisateur
     *
     * @return Article
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
     * Set contenuArticle
     *
     * @param string $contenuArticle
     *
     * @return Article
     */
    public function setContenuArticle($contenuArticle)
    {
        $this->contenuArticle = $contenuArticle;

        return $this;
    }

    /**
     * Get contenuArticle
     *
     * @return string
     */
    public function getContenuArticle()
    {
        return $this->contenuArticle;
    }

    /**
     * Set classHtmlArticle
     *
     * @param string $classHtmlArticle
     *
     * @return Article
     */
    public function setClassHtmlArticle($classHtmlArticle)
    {
        $this->classHtmlArticle = $classHtmlArticle;

        return $this;
    }

    /**
     * Get classHtmlArticle
     *
     * @return string
     */
    public function getClassHtmlArticle()
    {
        return $this->classHtmlArticle;
    }

    /**
     * Set etablissement
     *
     * @param \admin\ScolariteBundle\Entity\Etablissement $etablissement
     *
     * @return Article
     */
    public function setEtablissement(\admin\ScolariteBundle\Entity\Etablissement $etablissement = null)
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
     * Set niveau
     *
     * @param \admin\ScolariteBundle\Entity\Niveau $niveau
     *
     * @return Article
     */
    public function setNiveau(\admin\ScolariteBundle\Entity\Niveau $niveau = null)
    {
        $this->niveau = $niveau;

        return $this;
    }

    /**
     * Get niveau
     *
     * @return \admin\ScolariteBundle\Entity\Niveau
     */
    public function getNiveau()
    {
        return $this->niveau;
    }

    /**
     * Set filiere
     *
     * @param \admin\ScolariteBundle\Entity\Filiere $filiere
     *
     * @return Article
     */
    public function setFiliere(\admin\ScolariteBundle\Entity\Filiere $filiere = null)
    {
        $this->filiere = $filiere;

        return $this;
    }

    /**
     * Get filiere
     *
     * @return \admin\ScolariteBundle\Entity\Filiere
     */
    public function getFiliere()
    {
        return $this->filiere;
    }

    /**
     * Set actifArticle
     *
     * @param integer $actifArticle
     *
     * @return Article
     */
    public function setActifArticle($actifArticle)
    {
        $this->actifArticle = $actifArticle;

        return $this;
    }

    /**
     * Get actifArticle
     *
     * @return integer
     */
    public function getActifArticle()
    {
        return $this->actifArticle;
    }

    /**
     * Set typeArticle
     *
     * @param integer $typeArticle
     *
     * @return Article
     */
    public function setTypeArticle($typeArticle)
    {
        $this->typeArticle = $typeArticle;

        return $this;
    }

    /**
     * Get typeArticle
     *
     * @return integer
     */
    public function getTypeArticle()
    {
        return $this->typeArticle;
    }

    /**
     * Set resumeArticle
     *
     * @param string $resumeArticle
     *
     * @return Article
     */
    public function setResumeArticle($resumeArticle)
    {
        $this->resumeArticle = $resumeArticle;

        return $this;
    }

    /**
     * Get resumeArticle
     *
     * @return string
     */
    public function getResumeArticle()
    {
        return $this->resumeArticle;
    }

    /**
     * Set titreImage
     *
     * @param string $titreImage
     *
     * @return Article
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
}
