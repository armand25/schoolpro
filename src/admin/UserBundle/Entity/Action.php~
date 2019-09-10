<?php

namespace admin\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \admin\UserBundle\Entity\Controlleur;
use \admin\UserBundle\Entity\Profil;

/**
 * Action
 *
 * @ORM\Table(name="action")
 * @ORM\Entity(repositoryClass="admin\UserBundle\Entity\ActionRepository")
 */
class Action
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $nom
     *
     * @ORM\Column(name="nom", type="string", length=50, unique=true)
     */
    private $nom;
    
    

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;
   
    /**
     * @var Controleur $controleur 
     * @ORM\ManyToOne(targetEntity="admin\UserBundle\Entity\Controleur", inversedBy="actions")
     * @ORM\JoinColumn(nullable=true)
     */
     private $controleur;
   
    
    /**
     * @var Controleur $module 
     * @ORM\ManyToOne(targetEntity="admin\UserBundle\Entity\Module", inversedBy="actions")
     * @ORM\JoinColumn(nullable=true)
     */
     private $module;
   
    
    
    /**
     * Constructor
     */
    public function __construct(){
       
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
     * Set nom
     *
     * @param string $nom
     * @return Action
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Action
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set controleur
     *
     * @param \admin\UserBundle\Entity\Controleur $controleur
     * @return Action
     */
    public function setControleur(\admin\UserBundle\Entity\Controleur $controleur)
    {
        $this->controleur = $controleur;

        return $this;
    }

    /**
     * Get controleur
     *
     * @return \admin\UserBundle\Entity\Controleur 
     */
    public function getControleur()
    {
        return $this->controleur;
    }

    /**
     * Set module
     *
     * @param \admin\UserBundle\Entity\Module $module
     * @return Action
     */
    public function setModule(\admin\UserBundle\Entity\Module $module)
    {
        $this->module = $module;

        return $this;
    }

    /**
     * Get module
     *
     * @return \admin\UserBundle\Entity\Module 
     */
    public function getModule()
    {
        return $this->module;
    }
}
