<?php

namespace admin\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use \Doctrine\Common\Collections\ArrayCollection;
use admin\UserBundle\Types\TypeEtat;


/**
 * User
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="template")
 * @ORM\Entity(repositoryClass="admin\CmsBundle\Repository\TemplateRepository")
 */
class Template {

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
     * @var string $titreTemplate
     *
     * @ORM\Column(name="titre_template", type="string", length=50,  nullable=true)
     * @Assert\Length(max=50)
     */
    private $titreTemplate;
    
    /**
     * @var string $nomDossierTemplate
     *
     * @ORM\Column(name="nom_dossier_template", type="string", length=50,  nullable=true)
     * @Assert\Length(max=50)
     */
    private $nomDossierTemplate;

    /**
     * @var string $descriptionTemplate
     *
     * @ORM\Column(name="description_template", type="string",  nullable=true)
     */
    private $descriptionTemplate;
    

    /**
     * @var integer $etatTemplate
     *
     * @ORM\Column(name="etat_template", type="smallint")
     */
    private $etatTemplate;

    /**
     * @var \DateTime $dateAjoutTemplate
     *
     * @ORM\Column(name="date_ajout_template", type="datetime")
     */
    private $dateAjoutTemplate; 
    
    /**
     * @var ArrayCollection pages
     * @ORM\OneToMany(targetEntity="admin\CmsBundle\Entity\Page", cascade={"persist", "remove"}, mappedBy="template")
     */
    private $pages; 
    /**
     * @var ArrayCollection etablissements
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\Etablissement", cascade={"persist", "remove"}, mappedBy="template")
     */
    private $etablissements; 
 
    
    public function __construct() {
        $this->dateAjoutTemplate = new \DateTime();
        $this->etatTemplate = TypeEtat::ACTIF;
        
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
        return 'upload/images/templates';
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
     * @return Template
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
     * Set titreTemplate
     *
     * @param string $titreTemplate
     *
     * @return Template
     */
    public function setTitreTemplate($titreTemplate)
    {
        $this->titreTemplate = $titreTemplate;

        return $this;
    }

    /**
     * Get titreTemplate
     *
     * @return string
     */
    public function getTitreTemplate()
    {
        return $this->titreTemplate;
    }

    /**
     * Set descriptionTemplate
     *
     * @param string $descriptionTemplate
     *
     * @return Template
     */
    public function setDescriptionTemplate($descriptionTemplate)
    {
        $this->descriptionTemplate = $descriptionTemplate;

        return $this;
    }

    /**
     * Get descriptionTemplate
     *
     * @return string
     */
    public function getDescriptionTemplate()
    {
        return $this->descriptionTemplate;
    }

    /**
     * Set etatTemplate
     *
     * @param integer $etatTemplate
     *
     * @return Template
     */
    public function setEtatTemplate($etatTemplate)
    {
        $this->etatTemplate = $etatTemplate;

        return $this;
    }

    /**
     * Get etatTemplate
     *
     * @return integer
     */
    public function getEtatTemplate()
    {
        return $this->etatTemplate;
    }

    /**
     * Set dateAjoutTemplate
     *
     * @param \DateTime $dateAjoutTemplate
     *
     * @return Template
     */
    public function setDateAjoutTemplate($dateAjoutTemplate)
    {
        $this->dateAjoutTemplate = $dateAjoutTemplate;

        return $this;
    }

    /**
     * Get dateAjoutTemplate
     *
     * @return \DateTime
     */
    public function getDateAjoutTemplate()
    {
        return $this->dateAjoutTemplate;
    }

    /**
     * Add page
     *
     * @param \admin\CmsBundle\Entity\Page $page
     *
     * @return Template
     */
    public function addPage(\admin\CmsBundle\Entity\Page $page)
    {
        $this->pages[] = $page;

        return $this;
    }

    /**
     * Remove page
     *
     * @param \admin\CmsBundle\Entity\Page $page
     */
    public function removePage(\admin\CmsBundle\Entity\Page $page)
    {
        $this->pages->removeElement($page);
    }

    /**
     * Get pages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * Set nomDossierTemplate
     *
     * @param string $nomDossierTemplate
     *
     * @return Template
     */
    public function setNomDossierTemplate($nomDossierTemplate)
    {
        $this->nomDossierTemplate = $nomDossierTemplate;

        return $this;
    }

    /**
     * Get nomDossierTemplate
     *
     * @return string
     */
    public function getNomDossierTemplate()
    {
        return $this->nomDossierTemplate;
    }

    /**
     * Add etablissement
     *
     * @param \admin\ScolariteBundle\Entity\Etablissement $etablissement
     *
     * @return Template
     */
    public function addEtablissement(\admin\ScolariteBundle\Entity\Etablissement $etablissement)
    {
        $this->etablissements[] = $etablissement;

        return $this;
    }

    /**
     * Remove etablissement
     *
     * @param \admin\ScolariteBundle\Entity\Etablissement $etablissement
     */
    public function removeEtablissement(\admin\ScolariteBundle\Entity\Etablissement $etablissement)
    {
        $this->etablissements->removeElement($etablissement);
    }

    /**
     * Get etablissements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEtablissements()
    {
        return $this->etablissements;
    }
}
