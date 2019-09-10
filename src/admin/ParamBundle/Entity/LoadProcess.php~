<?php

namespace admin\ParamBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Balise
 *
 * @ORM\Table(name="load_process")
 * @ORM\Entity(repositoryClass="admin\ParamBundle\Entity\LoadProcessRepository")
 */
class LoadProcess {

    /**
     * @var integer
     *
     * @ORM\Column(name="idprocessload", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    

    /**
     * @var integer
     *
     * @ORM\Column(name="iduser", type="integer")
     */
    private $idUser;

    /**
     * @var \DateTime $dateProcess
     *
     * @ORM\Column(name="dateprocess", type="datetime", nullable=true)
     */
    private $dateProcess;


    
    public function __construct() {
        $d = new \DateTime();
        $this->dateProcess = $d;
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
     * Set idUser
     *
     * @param integer $idUser
     * @return LoadProcess
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;
    
        return $this;
    }

    /**
     * Get idUser
     *
     * @return integer 
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * Set dateProcess
     *
     * @param \DateTime $dateProcess
     * @return LoadProcess
     */
    public function setDateProcess($dateProcess)
    {
        $this->dateProcess = $dateProcess;
    
        return $this;
    }

    /**
     * Get dateProcess
     *
     * @return \DateTime 
     */
    public function getDateProcess()
    {
        return $this->dateProcess;
    }
}
