<?php

namespace admin\CmsBundle\Entity;
use admin\UserBundle\Types\TypeEtat;

use Doctrine\ORM\Mapping as ORM;

/**
 * Menu
 *
 * @ORM\Table(name="menu")
 * @ORM\Entity(repositoryClass="admin\CmsBundle\Repository\MenuRepository")
 */
class Menu
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
     * @ORM\Column(name="titre", type="string", length=35)
     */
    private $titre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datePublication", type="date")
     */
    private $datePublication;

  
    
    /**
     * @var string
     *
     * @ORM\Column(name="contenuMenu", type="string")
     */
    private $contenuMenu;
    /**
     * @var integer
     *
     * @ORM\Column(name="type_menu", type="integer")
     */
    private $typeMenu;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="etat_menu", type="integer")
     */
    private $etatMenu;
    
    /**
     * @var string
     *
     * @ORM\Column(name="classHtmlMenu", type="string", length=30, nullable=true)
     */
    private $classHtmlMenu;

    /**
     * @var Etablissement  $etablissement
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Etablissement", inversedBy="menus")
     * @ORM\JoinColumn(nullable=true)
     */
    private $etablissement;    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->etatMenu = TypeEtat::ACTIF;
        //$this->medias = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set titre
     *
     * @param string $titre
     *
     * @return Menu
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set datePublication
     *
     * @param \DateTime $datePublication
     *
     * @return Menu
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
     * Set contenuMenu
     *
     * @param string $contenuMenu
     *
     * @return Menu
     */
    public function setContenuMenu($contenuMenu)
    {
        $this->contenuMenu = $contenuMenu;

        return $this;
    }

    /**
     * Get contenuMenu
     *
     * @return string
     */
    public function getContenuMenu()
    {
        return $this->contenuMenu;
    }

    /**
     * Set classHtmlMenu
     *
     * @param string $classHtmlMenu
     *
     * @return Menu
     */
    public function setClassHtmlMenu($classHtmlMenu)
    {
        $this->classHtmlMenu = $classHtmlMenu;

        return $this;
    }

    /**
     * Get classHtmlMenu
     *
     * @return string
     */
    public function getClassHtmlMenu()
    {
        return $this->classHtmlMenu;
    }

    /**
     * Set typeMenu
     *
     * @param integer $typeMenu
     *
     * @return Menu
     */
    public function setTypeMenu($typeMenu)
    {
        $this->typeMenu = $typeMenu;

        return $this;
    }

    /**
     * Get typeMenu
     *
     * @return integer
     */
    public function getTypeMenu()
    {
        return $this->typeMenu;
    }

    /**
     * Set etablissement
     *
     * @param \admin\ScolariteBundle\Entity\Etablissement $etablissement
     *
     * @return Menu
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
     * Set etatMenu
     *
     * @param integer $etatMenu
     *
     * @return Menu
     */
    public function setEtatMenu($etatMenu)
    {
        $this->etatMenu = $etatMenu;

        return $this;
    }

    /**
     * Get etatMenu
     *
     * @return integer
     */
    public function getEtatMenu()
    {
        return $this->etatMenu;
    }
}
