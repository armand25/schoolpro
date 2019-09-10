<?php

namespace admin\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Media
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="media_cms")
 * @ORM\Entity(repositoryClass="admin\CmsBundle\Repository\MediaRepository")
 */
class Media
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
     * @ORM\Column(name="nomMedia", type="string", length=50)
     */
    private $nomMedia;
    
    /**
     * @var string
     *
     * @ORM\Column(name="pathMedia", type="string")
     */
    private $pathMedia;
    
    /**
     * @Assert\File(
     * maxSize = "100M",
     * mimeTypes = {"/txt" },
     * mimeTypesMessage = "Format invalide"
     * )
     */
    public $file;
    
    /**
     * @var TypeMedia $typeMedia
     * @ORM\ManyToOne(targetEntity="admin\CmsBundle\Entity\TypeMedia", inversedBy="medias")
     * @ORM\JoinColumn(nullable=true)
     */
    private $TypeMedia;
    
    /**
     * @var interger
     *
     * @ORM\Column(name="typeMedia", type="smallint")
     */
    private $typeMedia;
    


    /**
     * @var Utilisateur $utlisateur
     * @ORM\ManyToOne(targetEntity="admin\UserBundle\Entity\Utilisateur", inversedBy="medias")
     * @ORM\JoinColumn(nullable=true)
     */
    private $utilisateur;
    
    /**
     * @var Article $article
     * @ORM\ManyToOne(targetEntity="admin\CmsBundle\Entity\Article", inversedBy="medias")
     * @ORM\JoinColumn(nullable=true)
     */
    private $article;
    
    /**
     * @var Rubrique $rubrique
     * @ORM\ManyToOne(targetEntity="admin\CmsBundle\Entity\Rubrique", inversedBy="medias")
     * @ORM\JoinColumn(nullable=true)
     */
    private $rubrique;

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
        $this->datePublication = new \DateTime();
        //$nomFichier = ""; 

        if (null !== $this->file) {

                $this->pathMedia = $this->getUploadDir().'/'.uniqid(mt_rand(), true) . '.' . $this->file->guessExtension();
                
                if($this->file->guessExtension()=='jpg' || $this->file->guessExtension()=='png' || $this->file->guessExtension()=='gif'){
                    $this->typeMedia = 1;
                }elseif($this->file->guessExtension()=='docx' || $this->file->guessExtension()=='doc'){
                    $this->typeMedia = 2;
                }else{
                    $this->typeMedia = 0;
                }
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
        $this->file->move($this->getUploadRootDir(), $this->pathMedia);
        //chmod($this->getUploadRootDir(), 0755);
        unset($this->file);
    }

    public function removeUpload($file) {
        unlink($file);
    }

    public function getAbsolutePath() {
        return null === $this->pathMedia ? null : $this->getUploadRootDir() . '' . $this->pathMedia;
    }

    public function getWebPath() {
        return null === $this->pathMedia ? null : $this->getUploadDir() . '' . $this->pathMedia;
    }

    public function getUploadRootDir() {
        // le chemin absolu du r�pertoire o� les documents upload�s doivent �tre sauvegard�s
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    public function getUploadDir() {
        // on se d�barrasse de � __DIR__ � afin de ne pas avoir de probl�me lorsqu'on affiche
        // le document/image dans la vue.
        return 'upload/chargement';
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
     * Set nomMedia
     *
     * @param string $nomMedia
     *
     * @return Media
     */
    public function setNomMedia($nomMedia)
    {
        $this->nomMedia = $nomMedia;

        return $this;
    }

    /**
     * Get nomMedia
     *
     * @return string
     */
    public function getNomMedia()
    {
        return $this->nomMedia;
    }

    /**
     * Set typeMedia
     *
     * @param \admin\CmsBundle\Entity\TypeMedia $typeMedia
     *
     * @return Media
     */
    public function setTypeMedia(\admin\CmsBundle\Entity\TypeMedia $typeMedia = null)
    {
        $this->TypeMedia = $typeMedia;

        return $this;
    }

    /**
     * Get typeMedia
     *
     * @return \admin\CmsBundle\Entity\TypeMedia
     */
    public function getTypeMedia()
    {
        return $this->TypeMedia;
    }

    /**
     * Set utilisateur
     *
     * @param \admin\UserBundle\Entity\Utilisateur $utilisateur
     *
     * @return Media
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
     * Set article
     *
     * @param \admin\CmsBundle\Entity\Article $article
     *
     * @return Media
     */
    public function setArticle(\admin\CmsBundle\Entity\Article $article = null)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return \admin\CmsBundle\Entity\Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * Set rubrique
     *
     * @param \admin\CmsBundle\Entity\Rubrique $rubrique
     *
     * @return Media
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
     * Set pathMedia
     *
     * @param string $pathMedia
     *
     * @return Media
     */
    public function setPathMedia($pathMedia)
    {
        $this->pathMedia = $pathMedia;

        return $this;
    }

    /**
     * Get pathMedia
     *
     * @return string
     */
    public function getPathMedia()
    {
        return $this->pathMedia;
    }
}
