<?php

/**
 * Définition de l'entité Image
 * Représente le type de champ à presenter à l'utilisateur par rapport au champ choisi
 * nous avons entre autre (Text, bouton radio etc ...)-.
 * 
 * @author TEVI Fessou Atassé <tevi.armand@gmail.com> 
 */

namespace admin\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use admin\UserBundle\Types\TypeEtat;

/**
 * Image.
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="imagepersonne")
 * @ORM\Entity(repositoryClass="admin\UserBundle\Entity\ImagePersonneRepository")
 */
class ImagePersonne {

    public function __construct() {
       $this->etatImage = TypeEtat::ACTIF;
       $this->typeImage = TypeEtat::ACTIF;
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
     * @ORM\Column(name="titre_image", type="string", length=50)
     * @Assert\Length(max=50)
     */
    private $titreImage;

    /**
     * @var date
     *
     * @ORM\Column(name="date_publication", type="date")
     */
    private $datePublication;

    /**
     * @var int
     *
     * @ORM\Column(name="etat_image", type="integer")
     */
    private $etatImage;

    /**
     * @var int
     *
     * @ORM\Column(name="type_image", type="integer")
     */
    private $typeImage;
	
    /**
     * @var string
     * @ORM\Column(name="url_image", type="string",length=200)
     */
    private $urlImage;

    /**
     * @Assert\File(
     * maxSize = "100M",
     * mimeTypes = {"/txt" },
     * mimeTypesMessage = "Format invalide"
     * )
     */
    public $file;
    
    /**
     * @var Abonne  $abonne
     * @ORM\ManyToOne(targetEntity="admin\UserBundle\Entity\Abonne", inversedBy="imagepers")
     * @ORM\JoinColumn(nullable=true)
     */
    private $abonne; 

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
        $this->datePublication = new \DateTime();
        //$nomFichier = ""; 

        if (null !== $this->file) {

                $this->urlImage = uniqid(mt_rand(), true) . '.' . $this->file->guessExtension();
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
        $this->file->move($this->getUploadRootDir(), $this->urlImage);
        //chmod($this->getUploadRootDir(), 0755);
        unset($this->file);
    }

    public function removeUpload($file) {
        unlink($file);
    }

    public function getAbsolutePath() {
        return null === $this->urlImage ? null : $this->getUploadRootDir() . '' . $this->urlImage;
    }

    public function getWebPath() {
        return null === $this->urlImage ? null : $this->getUploadDir() . '' . $this->urlImage;
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
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set titreImage
     *
     * @param string $titreImage
     * @return ImagePersonne
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
     * Set datePublication
     *
     * @param \DateTime $datePublication
     * @return ImagePersonne
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
     * Set etatImage
     *
     * @param integer $etatImage
     * @return ImagePersonne
     */
    public function setEtatImage($etatImage)
    {
        $this->etatImage = $etatImage;
    
        return $this;
    }

    /**
     * Get etatImage
     *
     * @return integer 
     */
    public function getEtatImage()
    {
        return $this->etatImage;
    }

    /**
     * Set typeImage
     *
     * @param integer $typeImage
     * @return ImagePersonne
     */
    public function setTypeImage($typeImage)
    {
        $this->typeImage = $typeImage;
    
        return $this;
    }

    /**
     * Get typeImage
     *
     * @return integer 
     */
    public function getTypeImage()
    {
        return $this->typeImage;
    }

    /**
     * Set urlImage
     *
     * @param string $urlImage
     * @return ImagePersonne
     */
    public function setUrlImage($urlImage)
    {
        $this->urlImage = $urlImage;
    
        return $this;
    }

    /**
     * Get urlImage
     *
     * @return string 
     */
    public function getUrlImage()
    {
        return $this->urlImage;
    }

    /**
     * Set abonne
     *
     * @param \admin\UserBundle\Entity\Abonne $abonne
     * @return ImagePersonne
     */
    public function setAbonne(\admin\UserBundle\Entity\Abonne $abonne = null)
    {
        $this->abonne = $abonne;
    
        return $this;
    }

    /**
     * Get abonne
     *
     * @return \admin\UserBundle\Entity\Abonne 
     */
    public function getAbonne()
    {
        return $this->abonne;
    }
}
