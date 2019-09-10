<?php

namespace admin\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use \Doctrine\Common\Collections\ArrayCollection;
use admin\UserBundle\Types\TypeEtat;


/**
 * User
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="page")
 * @ORM\Entity(repositoryClass="admin\CmsBundle\Repository\PageRepository")
 */
class Page {

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
     * @var string $titrePage
     *
     * @ORM\Column(name="titre_page", type="string", length=50,  nullable=true)
     * @Assert\Length(max=50)
     */
    private $titrePage;
    
    /**
     * @var string $twigPage
     *
     * @ORM\Column(name="twig_page", type="string", length=50,  nullable=true)
     * @Assert\Length(max=50)
     */
    private $twigPage;

    /**
     * @var string $descriptionPage
     *
     * @ORM\Column(name="description_page", type="string", length=50, nullable=true)
     * @Assert\Length(max=50)
     */
    private $descriptionPage;
    

    /**
     * @var integer $etatPage
     *
     * @ORM\Column(name="etat_page", type="smallint")
     */
    private $etatPage;
    
    /**
     * @var integer $typePage
     *
     * @ORM\Column(name="type_page", type="smallint")
     */
    private $typePage;
    
    /**
     * @var integer $siPageAccueil
     *
     * @ORM\Column(name="si_page_accueil", type="smallint")
     */
    private $siPageAccueil;
    
    /**
     * @var \DateTime $dateAjoutPage
     *
     * @ORM\Column(name="date_ajout_page", type="datetime")
     */
    private $dateAjoutPage; 
 
    
    public function __construct() {
        $this->dateAjoutPage = new \DateTime();
        $this->etatPage = TypeEtat::ACTIF;
        $this->siPageAccueil = TypeEtat::APRODUIRE;
        $this->pages = new ArrayCollection();
    }
    
    /**
     * @var Template  $template
     * @ORM\ManyToOne(targetEntity="admin\CmsBundle\Entity\Template", inversedBy="pages")
     * @ORM\JoinColumn(nullable=true)
     */
    private $template;   
    
    /**
     * @var ArrayCollection zones
     * @ORM\OneToMany(targetEntity="admin\CmsBundle\Entity\Zone", cascade={"persist", "remove"}, mappedBy="page")
     */
    private $zones;     
    
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
        return 'upload/images/pages';
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
     * @return Page
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
     * Set titrePage
     *
     * @param string $titrePage
     *
     * @return Page
     */
    public function setTitrePage($titrePage)
    {
        $this->titrePage = $titrePage;

        return $this;
    }

    /**
     * Get titrePage
     *
     * @return string
     */
    public function getTitrePage()
    {
        return $this->titrePage;
    }

    /**
     * Set twigPage
     *
     * @param string $twigPage
     *
     * @return Page
     */
    public function setTwigPage($twigPage)
    {
        $this->twigPage = $twigPage;

        return $this;
    }

    /**
     * Get twigPage
     *
     * @return string
     */
    public function getTwigPage()
    {
        return $this->twigPage;
    }

    /**
     * Set descriptionPage
     *
     * @param string $descriptionPage
     *
     * @return Page
     */
    public function setDescriptionPage($descriptionPage)
    {
        $this->descriptionPage = $descriptionPage;

        return $this;
    }

    /**
     * Get descriptionPage
     *
     * @return string
     */
    public function getDescriptionPage()
    {
        return $this->descriptionPage;
    }

    /**
     * Set etatPage
     *
     * @param integer $etatPage
     *
     * @return Page
     */
    public function setEtatPage($etatPage)
    {
        $this->etatPage = $etatPage;

        return $this;
    }

    /**
     * Get etatPage
     *
     * @return integer
     */
    public function getEtatPage()
    {
        return $this->etatPage;
    }

    /**
     * Set typePage
     *
     * @param integer $typePage
     *
     * @return Page
     */
    public function setTypePage($typePage)
    {
        $this->typePage = $typePage;

        return $this;
    }

    /**
     * Get typePage
     *
     * @return integer
     */
    public function getTypePage()
    {
        return $this->typePage;
    }

    /**
     * Set siPageAccueil
     *
     * @param integer $siPageAccueil
     *
     * @return Page
     */
    public function setSiPageAccueil($siPageAccueil)
    {
        $this->siPageAccueil = $siPageAccueil;

        return $this;
    }

    /**
     * Get siPageAccueil
     *
     * @return integer
     */
    public function getSiPageAccueil()
    {
        return $this->siPageAccueil;
    }

    /**
     * Set dateAjoutPage
     *
     * @param \DateTime $dateAjoutPage
     *
     * @return Page
     */
    public function setDateAjoutPage($dateAjoutPage)
    {
        $this->dateAjoutPage = $dateAjoutPage;

        return $this;
    }

    /**
     * Get dateAjoutPage
     *
     * @return \DateTime
     */
    public function getDateAjoutPage()
    {
        return $this->dateAjoutPage;
    }

    /**
     * Set template
     *
     * @param \admin\CmsBundle\Entity\Template $template
     *
     * @return Page
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
     * Add zone
     *
     * @param \admin\CmsBundle\Entity\Zone $zone
     *
     * @return Page
     */
    public function addZone(\admin\CmsBundle\Entity\Zone $zone)
    {
        $this->zones[] = $zone;

        return $this;
    }

    /**
     * Remove zone
     *
     * @param \admin\CmsBundle\Entity\Zone $zone
     */
    public function removeZone(\admin\CmsBundle\Entity\Zone $zone)
    {
        $this->zones->removeElement($zone);
    }

    /**
     * Get zones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getZones()
    {
        return $this->zones;
    }
}
