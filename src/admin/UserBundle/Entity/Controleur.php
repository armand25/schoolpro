<?php

namespace admin\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \admin\UserBundle\Entity\Action;


/**
 * Controleur
 *
 * @ORM\Table(name="controleur")
 * @ORM\Entity(repositoryClass="admin\UserBundle\Entity\ControleurRepository")
 */
class Controleur
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
     * @ORM\Column(name="nom", type="string", length=50)
     */
    private $nom;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     *
     * @var ArrayCollection $actions 
     * @ORM\OneToMany(targetEntity="admin\UserBundle\Entity\Action", cascade={"persist", "remove"}, mappedBy="controleur")
     * 
     */
     private $actions;
     
    

    /**
     * Constructor
     */
    public function __construct($nom, $description)
    {
        $this->nom = $nom;
        $this->description = $description;
        $this->actions = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Controleur
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
     * @return Controleur
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
     * Add actions
     *
     * @param \admin\UserBundle\Entity\Action $actions
     * @return Controleur
     */
    public function addAction(\admin\UserBundle\Entity\Action $actions)
    {
        $this->actions[] = $actions;

        return $this;
    }

    /**
     * Remove actions
     *
     * @param \admin\UserBundle\Entity\Action $actions
     */
    public function removeAction(\admin\UserBundle\Entity\Action $actions)
    {
        $this->actions->removeElement($actions);
    }

    /**
     * Get actions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getActions()
    {
        return $this->actions;
    }
}
