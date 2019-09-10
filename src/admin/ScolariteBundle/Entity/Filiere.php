<?php

namespace admin\ScolariteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \admin\UserBundle\Types\TypeEtat;

/**
 * Filiere
 *
 * @ORM\Table(name="filiere")
 * @ORM\Entity(repositoryClass="admin\ScolariteBundle\Entity\FiliereRepository")
 */
class Filiere
{
     public function __construct() {
        $this->etatFiliere = TypeEtat::ACTIF;
       
    }
    
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $libelleFiliere
     *
     * @ORM\Column(name="libelle_filiere", type="string")
     */
    private $libelleFiliere;
    /**
     * @var string $descriptionlibelleFiliere
     *
     * @ORM\Column(name="descriptionlibelle_filiere", type="string")
     */
    private $descriptionFiliere;
    /**
     * @var string $etatFiliere
     *
     * @ORM\Column(name="etat_filiere", type="integer")
     */
    private $etatFiliere;
    
    /**
     * @var Enseigement  $enseignement
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Enseignement", inversedBy="filieres")
     * @ORM\JoinColumn(nullable=true)
     */
    private $enseignement;     

    /**
     *
     * @var ArrayCollection $classes 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\Classe", cascade={"persist", "remove"}, mappedBy="filiere")
     * 
     */
    private $classes;     
    
    /**
     *
     * @var ArrayCollection $estenseignes 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\EstEnseigne", cascade={"persist", "remove"}, mappedBy="filiere")
     * 
     */
    private $estenseignes;
    
 /**
     *
     * @var ArrayCollection $rubriques 
     * @ORM\OneToMany(targetEntity="admin\CmsBundle\Entity\Rubrique", cascade={"persist", "remove"}, mappedBy="filiere")
     * 
     */
    private $rubriques; 
    /**
     *
     * @var ArrayCollection $articles 
     * @ORM\OneToMany(targetEntity="admin\CmsBundle\Entity\Article", cascade={"persist", "remove"}, mappedBy="filiere")
     * 
     */
    private $articles;     
    
    
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
     * Set libelleFiliere
     *
     * @param string $libelleFiliere
     *
     * @return Filiere
     */
    public function setLibelleFiliere($libelleFiliere)
    {
        $this->libelleFiliere = $libelleFiliere;

        return $this;
    }

    /**
     * Get libelleFiliere
     *
     * @return string
     */
    public function getLibelleFiliere()
    {
        return $this->libelleFiliere;
    }

    /**
     * Set etatFiliere
     *
     * @param integer $etatFiliere
     *
     * @return Filiere
     */
    public function setEtatFiliere($etatFiliere)
    {
        $this->etatFiliere = $etatFiliere;

        return $this;
    }

    /**
     * Get etatFiliere
     *
     * @return integer
     */
    public function getEtatFiliere()
    {
        return $this->etatFiliere;
    }

    /**
     * Set enseignement
     *
     * @param \admin\ScolariteBundle\Entity\Enseignement $enseignement
     *
     * @return Filiere
     */
    public function setEnseignement(\admin\ScolariteBundle\Entity\Enseignement $enseignement = null)
    {
        $this->enseignement = $enseignement;

        return $this;
    }

    /**
     * Get enseignement
     *
     * @return \admin\ScolariteBundle\Entity\Enseignement
     */
    public function getEnseignement()
    {
        return $this->enseignement;
    }

    /**
     * Set descriptionFiliere
     *
     * @param string $descriptionFiliere
     *
     * @return Filiere
     */
    public function setDescriptionFiliere($descriptionFiliere)
    {
        $this->descriptionFiliere = $descriptionFiliere;

        return $this;
    }

    /**
     * Get descriptionFiliere
     *
     * @return string
     */
    public function getDescriptionFiliere()
    {
        return $this->descriptionFiliere;
    }

    /**
     * Add classe
     *
     * @param \admin\ScolariteBundle\Entity\Classe $classe
     *
     * @return Filiere
     */
    public function addClasse(\admin\ScolariteBundle\Entity\Classe $classe)
    {
        $this->classes[] = $classe;

        return $this;
    }

    /**
     * Remove classe
     *
     * @param \admin\ScolariteBundle\Entity\Classe $classe
     */
    public function removeClasse(\admin\ScolariteBundle\Entity\Classe $classe)
    {
        $this->classes->removeElement($classe);
    }

    /**
     * Get classes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * Add class
     *
     * @param \admin\ScolariteBundle\Entity\Classe $class
     *
     * @return Filiere
     */
    public function addClass(\admin\ScolariteBundle\Entity\Classe $class)
    {
        $this->classes[] = $class;

        return $this;
    }

    /**
     * Remove class
     *
     * @param \admin\ScolariteBundle\Entity\Classe $class
     */
    public function removeClass(\admin\ScolariteBundle\Entity\Classe $class)
    {
        $this->classes->removeElement($class);
    }

    /**
     * Add estenseigne
     *
     * @param \admin\ScolariteBundle\Entity\EstEnseigne $estenseigne
     *
     * @return Filiere
     */
    public function addEstenseigne(\admin\ScolariteBundle\Entity\EstEnseigne $estenseigne)
    {
        $this->estenseignes[] = $estenseigne;

        return $this;
    }

    /**
     * Remove estenseigne
     *
     * @param \admin\ScolariteBundle\Entity\EstEnseigne $estenseigne
     */
    public function removeEstenseigne(\admin\ScolariteBundle\Entity\EstEnseigne $estenseigne)
    {
        $this->estenseignes->removeElement($estenseigne);
    }

    /**
     * Get estenseignes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEstenseignes()
    {
        return $this->estenseignes;
    }

    /**
     * Add rubrique
     *
     * @param \admin\CmsBundle\Entity\Rubrique $rubrique
     *
     * @return Filiere
     */
    public function addRubrique(\admin\CmsBundle\Entity\Rubrique $rubrique)
    {
        $this->rubriques[] = $rubrique;

        return $this;
    }

    /**
     * Remove rubrique
     *
     * @param \admin\CmsBundle\Entity\Rubrique $rubrique
     */
    public function removeRubrique(\admin\CmsBundle\Entity\Rubrique $rubrique)
    {
        $this->rubriques->removeElement($rubrique);
    }

    /**
     * Get rubriques
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRubriques()
    {
        return $this->rubriques;
    }

    /**
     * Add article
     *
     * @param \admin\CmsBundle\Entity\Article $article
     *
     * @return Filiere
     */
    public function addArticle(\admin\CmsBundle\Entity\Article $article)
    {
        $this->articles[] = $article;

        return $this;
    }

    /**
     * Remove article
     *
     * @param \admin\CmsBundle\Entity\Article $article
     */
    public function removeArticle(\admin\CmsBundle\Entity\Article $article)
    {
        $this->articles->removeElement($article);
    }

    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArticles()
    {
        return $this->articles;
    }
}
