<?php

namespace admin\ParamBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
//use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * parametre
 *
 * @ORM\Table(name="fichier")
 * @ORM\Entity(repositoryClass="admin\ParamBundle\Entity\FichierRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Fichier {

    /**
     * @var integer
     *
     * @ORM\Column(name="idfile", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $nomfile
     *
     * @ORM\Column(name="nomfile", type="string", length=100, nullable=true)
     */
    private $nomFile;

    /**
     * @var string $path
     *
     * @ORM\Column(name="path", type="string", length=300, nullable=true)
     */
    private $path;

    /**
     * @var integer $iduser
     *
     * @ORM\Column(name="userid", type="integer", nullable=true)
     */
    private $iduser;

    /**
     * @var integer $typeFile
     *
     * @ORM\Column(name="type_file", type="integer", nullable=true)
     */
    private $typeFile;
    
    /**
     * @Assert\File(maxSize="8M",mimeTypes={"application/xml"})
     */
    public $file;    
    
    /**
     * @ORM\Column(name="etat_file", type="integer", nullable=true)
     */
    public $etatFile;


    /**
     * @var integer $typeCharge
     *
     * @ORM\Column(name="type_charge", type="integer", nullable=true)
     */
    private $typeCharge;
    
    
    /**
     * @var \DateTime $addDate
     *
     * @ORM\Column(name="add_date", type="datetime", nullable=true)
     */
    private $addDate;    
    
    public function __construct() {
		$d = new DateTime();
        $this->typeFile = 0;
        $this->etatFile = 0;
        $this->addDate = $d;
    }


    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {

        if (null !== $this->file) {
            // faites ce que vous voulez pour générer un nom unique
            $this->path = 'file_' . uniqid(). '.' . $this->file->guessExtension();
            // faites ce que vous voulez pour générer un nom unique
            $this->nomFile = $this->file->getClientOriginalName() ;//. $this->file->guessExtension();
        }
    }

//    /**
//     * @ORM\PostPersist()
//     * @ORM\PostUpdate()
//     */
//    public function upload() {
//        //
//        if (null === $this->path) {
//            return;
//        }
//        $this->file->move($this->getUploadRootDir(), $this->path);
//        chmod($this->getUploadRootDir(), 0755);
//        unset($this->file);
//    }

    public function removeUpload($file) {
        unlink($file);
    }

    public function getAbsolutePath() {
        return null === $this->path ? null : $this->getUploadRootDir() . '' . $this->path;
    }

    public function getWebPath() {
        return null === $this->path ? null : $this->getUploadDir() . '' . $this->path;
    }

    public function getUploadRootDir() {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    public function getUploadDir() {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        if($this->typeFile=== 0){
            return 'upload/files/msc/';
        }elseif($this->typeFile== 1){
            return 'upload/files/comparateur/msc';
        }elseif($this->typeFile== 2){
            return 'upload/files/comparateur/wct';
        }
        
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
     * Set nomFile
     *
     * @param string $nomFile
     * @return Fichier
     */
    public function setNomFile($nomFile)
    {
        $this->nomFile = $nomFile;
    
        return $this;
    }

    /**
     * Get nomFile
     *
     * @return string 
     */
    public function getNomFile()
    {
        return $this->nomFile;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Fichier
     */
    public function setPath($path)
    {
        $this->path = $path;
    
        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set iduser
     *
     * @param integer $iduser
     * @return Fichier
     */
    public function setIduser($iduser)
    {
        $this->iduser = $iduser;
    
        return $this;
    }

    /**
     * Get iduser
     *
     * @return integer 
     */
    public function getIduser()
    {
        return $this->iduser;
    }

    /**
     * Set typeCharge
     *
     * @param integer $typeCharge
     * @return Fichier
     */
    public function setTypeCharge($typeCharge)
    {
        $this->typeCharge = $typeCharge;
    
        return $this;
    }

    /**
     * Get $typeCharge
     *
     * @return integer 
     */
    public function getTypeCharge()
    {
        return $this->typeCharge;
    }    
    
    
    /**
     * Set typeFile
     *
     * @param integer $typeFile
     * @return Fichier
     */
    public function setTypeFile($typeFile)
    {
        $this->typeFile = $typeFile;
    
        return $this;
    }

    /**
     * Get typeFile
     *
     * @return integer 
     */
    public function getTypeFile()
    {
        return $this->typeFile;
    }

    /**
     * Set etatFile
     *
     * @param integer $etatFile
     * @return Fichier
     */
    public function setEtatFile($etatFile)
    {
        $this->etatFile = $etatFile;
    
        return $this;
    }

    /**
     * Get etatFile
     *
     * @return integer 
     */
    public function getEtatFile()
    {
        return $this->etatFile;
    }

    /**
     * Set addDate
     *
     * @param \DateTime $addDate
     * @return Fichier
     */
    public function setAddDate($addDate)
    {
        $this->addDate = $addDate;
    
        return $this;
    }

    /**
     * Get addDate
     *
     * @return \DateTime 
     */
    public function getAddDate()
    {
        return $this->addDate;
    }
}
