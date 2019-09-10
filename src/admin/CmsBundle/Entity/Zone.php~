<?php

namespace admin\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use \Doctrine\Common\Collections\ArrayCollection;
use admin\UserBundle\Types\TypeEtat;


/**
 * User
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="zone")
 * @ORM\Entity(repositoryClass="admin\CmsBundle\Repository\ZoneRepository")
 */
class Zone {

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
     * @var string $titreZone
     *
     * @ORM\Column(name="titre_zone", type="string", length=50,  nullable=true)
     * @Assert\Length(max=50)
     */
    private $titreZone;

    /**
     * @var string $descriptionZone
     *
     * @ORM\Column(name="description_zone", type="string", length=50, nullable=true)
     * @Assert\Length(max=50)
     */
    private $descriptionZone;
    

    /**
     * @var integer $etatZone
     *
     * @ORM\Column(name="etat_zone", type="smallint")
     */
    private $etatZone;
    
    /**
     * @var integer $typeElement
     *
     * @ORM\Column(name="type_element", type="smallint")
     */
    private $typeElement;
    
    /**
     * @var integer $pointeVers
     *
     * @ORM\Column(name="point_vers", type="smallint")
     */
    private $pointeVers;
    
    /**
     * @var ArrayCollection zonepointeetablissements
     * @ORM\OneToMany(targetEntity="admin\CmsBundle\Entity\ZonePointeEtablissement", cascade={"persist", "remove"}, mappedBy="zone")
     */
    private $zonepointeetablissements;     
    
    /**
     * @var \DateTime $dateAjoutZone
     *
     * @ORM\Column(name="date_ajout_zone", type="datetime")
     */
    private $dateAjoutZone; 
 
    
    public function __construct() {
        $this->dateAjoutZone = new \DateTime();
        $this->etatZone = TypeEtat::ACTIF;
        $this->pointeVers = TypeEtat::ACTIF;
        $this->connexions = new ArrayCollection();
    }
    
    /**
     * @var Page  $page
     * @ORM\ManyToOne(targetEntity="admin\CmsBundle\Entity\Page", inversedBy="zones")
     * @ORM\JoinColumn(nullable=true)
     */
    private $page;    
    
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

        if (null !== $this->file) {

                $this->titreImage = uniqid(mt_rand(), true) . '.' . $this->file->guessExtension();
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
            $this->file->move($this->getUploadRootDir()."/".$this->id, $this->titreImage);
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
        return 'upload/images/zones';
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
     *
     * @return Zone
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
     * Set titreZone
     *
     * @param string $titreZone
     *
     * @return Zone
     */
    public function setTitreZone($titreZone)
    {
        $this->titreZone = $titreZone;

        return $this;
    }

    /**
     * Get titreZone
     *
     * @return string
     */
    public function getTitreZone()
    {
        return $this->titreZone;
    }

    /**
     * Set descriptionZone
     *
     * @param string $descriptionZone
     *
     * @return Zone
     */
    public function setDescriptionZone($descriptionZone)
    {
        $this->descriptionZone = $descriptionZone;

        return $this;
    }

    /**
     * Get descriptionZone
     *
     * @return string
     */
    public function getDescriptionZone()
    {
        return $this->descriptionZone;
    }

    /**
     * Set etatZone
     *
     * @param integer $etatZone
     *
     * @return Zone
     */
    public function setEtatZone($etatZone)
    {
        $this->etatZone = $etatZone;

        return $this;
    }

    /**
     * Get etatZone
     *
     * @return integer
     */
    public function getEtatZone()
    {
        return $this->etatZone;
    }

    /**
     * Set typeElement
     *
     * @param integer $typeElement
     *
     * @return Zone
     */
    public function setTypeElement($typeElement)
    {
        $this->typeElement = $typeElement;

        return $this;
    }

    /**
     * Get typeElement
     *
     * @return integer
     */
    public function getTypeElement()
    {
        return $this->typeElement;
    }

    /**
     * Set pointeVers
     *
     * @param integer $pointeVers
     *
     * @return Zone
     */
    public function setPointeVers($pointeVers)
    {
        $this->pointeVers = $pointeVers;

        return $this;
    }

    /**
     * Get pointeVers
     *
     * @return integer
     */
    public function getPointeVers()
    {
        return $this->pointeVers;
    }

    /**
     * Set dateAjoutZone
     *
     * @param \DateTime $dateAjoutZone
     *
     * @return Zone
     */
    public function setDateAjoutZone($dateAjoutZone)
    {
        $this->dateAjoutZone = $dateAjoutZone;

        return $this;
    }

    /**
     * Get dateAjoutZone
     *
     * @return \DateTime
     */
    public function getDateAjoutZone()
    {
        return $this->dateAjoutZone;
    }

    /**
     * Set page
     *
     * @param \admin\CmsBundle\Entity\Page $page
     *
     * @return Zone
     */
    public function setPage(\admin\CmsBundle\Entity\Page $page = null)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return \admin\CmsBundle\Entity\Page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Add zonepointeetablissement
     *
     * @param \admin\CmsBundle\Entity\ZonePointeEtablissement $zonepointeetablissement
     *
     * @return Zone
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
}
