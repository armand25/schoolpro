<?php

namespace admin\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rubrique
 *
 * @ORM\Table(name="rubrique")
 * @ORM\Entity(repositoryClass="admin\CmsBundle\Repository\RubriqueRepository")
 */
class Rubrique
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
     * @ORM\Column(name="designationRubrique", type="string", length=100)
     */
    private $designationRubrique;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreationRubrique", type="datetime")
     */
    private $dateCreationRubrique;

    /**
     * @var int $actifRubrique
     *
     * @ORM\Column(name="actifRubrique", type="smallint")
     */
    private $actifRubrique;
    
     /**
     * @var int $typeRubrique
     *
     * @ORM\Column(name="typeRubrique", type="smallint")
     */
    private $typeRubrique;
    
    /**
     * @var string
     *
     * @ORM\Column(name="classHtmlRubrique", type="string", length=20, nullable=true)
     */
    private $classHtmlRubrique;
    
    /**
     * @var string
     *
     * @ORM\Column(name="iconeRubrique", type="string",  nullable=true)
     */
    private $iconeRubrique;
    
    /**
     * @var Utilisateur $utilisateur
     * @ORM\ManyToOne(targetEntity="admin\UserBundle\Entity\Utilisateur", inversedBy="rubriques")
     * @ORM\JoinColumn(nullable=true)
     */
    private $utilisateur;
    
    /**
     * @var ArrayCollection articles
     * @ORM\OneToMany(targetEntity="admin\CmsBundle\Entity\Article", mappedBy="rubrique")
     */
    private $articles; // Notez le « s », une annonce est liée à plusieurs candidatures
    
    /**
     * @var ArrayCollection rubriques
     * @ORM\OneToMany(targetEntity="admin\CmsBundle\Entity\Rubrique", mappedBy="rubrique")
     */
    private $rubriques; // Notez le « s », une annonce est liée à plusieurs candidatures
    
    /**
     * @var Rubrique $rubrique
     * @ORM\ManyToOne(targetEntity="admin\CmsBundle\Entity\Rubrique", inversedBy="articles")
     * @ORM\JoinColumn(nullable=true)
     */
    private $rubrique;
    
    /**
     * @var text
     *
     * @ORM\Column(name="descriptionRubrique", type="text")
     */
    private $descriptionRubrique;
    
    /**
     * @var ArrayCollection medias
     * @ORM\OneToMany(targetEntity="admin\CmsBundle\Entity\Media", cascade={"persist", "remove"}, mappedBy="rubrique")
     */
    private $medias; // Notez le « s », une annonce est liée à plusieurs candidatures
    
    
    /**
     * @var Etablissement  $etablissement
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Etablissement", inversedBy="rubriques")
     * @ORM\JoinColumn(nullable=true)
     */
    private $etablissement;

    /**
     * @var Niveau  $niveau
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Niveau", inversedBy="rubriques")
     * @ORM\JoinColumn(nullable=true)
     */
    private $niveau;
    /**
     * @var Filiere  $filiere
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Filiere", inversedBy="rubriques")
     * @ORM\JoinColumn(nullable=true)
     */
    private $filiere;    
    
    
    
    /**
     * Set partenaireConcerne
     *
     * @param string $partenaireConcerne
     *
     * @return Promotion
     */
    public function setPartenaireConcerne($partenaireConcerne)
    {
        $this->partenaireConcerne = $partenaireConcerne;

        return $this;
    }

    /**
    * @return string
    */
    public function getName() {
        return 'admin_cmsbundle_rubrique';
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
     * Set designationRubrique
     *
     * @param string $designationRubrique
     *
     * @return Rubrique
     */
    public function setDesignationRubrique($designationRubrique)
    {
        $this->designationRubrique = $designationRubrique;

        return $this;
    }

    /**
     * Get designationRubrique
     *
     * @return string
     */
    public function getDesignationRubrique()
    {
        return $this->designationRubrique;
    }

    /**
     * Set dateCreationRubrique
     *
     * @param \DateTime $dateCreationRubrique
     *
     * @return Rubrique
     */
    public function setDateCreationRubrique($dateCreationRubrique)
    {
        $this->dateCreationRubrique = $dateCreationRubrique;

        return $this;
    }

    /**
     * Get dateCreationRubrique
     *
     * @return \DateTime
     */
    public function getDateCreationRubrique()
    {
        return $this->dateCreationRubrique;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->articles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->medias = new \Doctrine\Common\Collections\ArrayCollection();
        $this->actifRubrique = \admin\UserBundle\Types\TypeEtat::ACTIF;
        $this->typeRubrique = 1;
    }

    /**
     * Set utilisateur
     *
     * @param \admin\UserBundle\Entity\Utilisateur $utilisateur
     *
     * @return Rubrique
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
     * Add article
     *
     * @param \admin\CmsBundle\Entity\Article $article
     *
     * @return Rubrique
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
     * Add media
     *
     * @param \admin\CmsBundle\Entity\Media $media
     *
     * @return Rubrique
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
     * Add rubrique
     *
     * @param \admin\CmsBundle\Entity\Rubrique $rubrique
     *
     * @return Rubrique
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
     * Set rubrique
     *
     * @param \admin\CmsBundle\Entity\Rubrique $rubrique
     *
     * @return Rubrique
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
     * Set actifRubrique
     *
     * @param integer $actifRubrique
     *
     * @return Rubrique
     */
    public function setActifRubrique($actifRubrique)
    {
        $this->actifRubrique = $actifRubrique;

        return $this;
    }

    /**
     * Get actifRubrique
     *
     * @return integer
     */
    public function getActifRubrique()
    {
        return $this->actifRubrique;
    }

    /**
     * Set descriptionRubrique
     *
     * @param string $descriptionRubrique
     *
     * @return Rubrique
     */
    public function setDescriptionRubrique($descriptionRubrique)
    {
        $this->descriptionRubrique = $descriptionRubrique;

        return $this;
    }

    /**
     * Get descriptionRubrique
     *
     * @return string
     */
    public function getDescriptionRubrique()
    {
        return $this->descriptionRubrique;
    }

    /**
     * Set classHtmlRubrique
     *
     * @param string $classHtmlRubrique
     *
     * @return Rubrique
     */
    public function setClassHtmlRubrique($classHtmlRubrique)
    {
        $this->classHtmlRubrique = $classHtmlRubrique;

        return $this;
    }

    /**
     * Get classHtmlRubrique
     *
     * @return string
     */
    public function getClassHtmlRubrique()
    {
        return $this->classHtmlRubrique;
    }

    /**
     * Set typeRubrique
     *
     * @param integer $typeRubrique
     *
     * @return Rubrique
     */
    public function setTypeRubrique($typeRubrique)
    {
        $this->typeRubrique = $typeRubrique;

        return $this;
    }

    /**
     * Get typeRubrique
     *
     * @return integer
     */
    public function getTypeRubrique()
    {
        return $this->typeRubrique;
    }

    /**
     * Set iconeRubrique
     *
     * @param string $iconeRubrique
     *
     * @return Rubrique
     */
    public function setIconeRubrique($iconeRubrique)
    {
        $this->iconeRubrique = $iconeRubrique;

        return $this;
    }

    /**
     * Get iconeRubrique
     *
     * @return string
     */
    public function getIconeRubrique()
    {
        return $this->iconeRubrique;
    }

    /**
     * Set etablissement
     *
     * @param \admin\ScolariteBundle\Entity\Etablissement $etablissement
     *
     * @return Rubrique
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
     * @return Rubrique
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
     * @return Rubrique
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
}
