<?php

namespace admin\ScolariteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \admin\UserBundle\Types\TypeEtat;

/**
 * Niveau
 *
 * @ORM\Table(name="niveau")
 * @ORM\Entity(repositoryClass="admin\ScolariteBundle\Entity\NiveauRepository")
 */
class Niveau
{
     public function __construct() {
        $this->etatNiveau = TypeEtat::ACTIF;
       
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
     * @var string $libelleNiveau
     *
     * @ORM\Column(name="libelle_niveau", type="string")
     */
    private $libelleNiveau;
    /**
     * @var string $descriptionNiveau
     *
     * @ORM\Column(name="description_niveau", type="string")
     */
    private $descriptionNiveau;
    /**
     * @var string $etatNiveau
     *
     * @ORM\Column(name="etat_niveau", type="integer")
     */
    private $etatNiveau;
    
  
    
    /**
     *
     * @var ArrayCollection $periodes 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\Periode", cascade={"persist", "remove"}, mappedBy="niveau")
     * 
     */
    private $periodes;    
    /**
     *
     * @var ArrayCollection $classes 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\Classe", cascade={"persist", "remove"}, mappedBy="niveau")
     * 
     */
    private $classes;    

    
    /**
     *
     * @var ArrayCollection $estenseignes 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\EstEnseigne", cascade={"persist", "remove"}, mappedBy="niveau")
     * 
     */
    private $estenseignes;  
    

    /**
     *
     * @var ArrayCollection $ecolages 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\Ecolage", cascade={"persist", "remove"}, mappedBy="niveau")
     * 
     */
    private $ecolages; 
    
    /**
     *
     * @var ArrayCollection $rubriques 
     * @ORM\OneToMany(targetEntity="admin\CmsBundle\Entity\Rubrique", cascade={"persist", "remove"}, mappedBy="niveau")
     * 
     */
    private $rubriques; 
    /**
     *
     * @var ArrayCollection $articles 
     * @ORM\OneToMany(targetEntity="admin\CmsBundle\Entity\Article", cascade={"persist", "remove"}, mappedBy="niveau")
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
     * Set libelleNiveau
     *
     * @param string $libelleNiveau
     *
     * @return Niveau
     */
    public function setLibelleNiveau($libelleNiveau)
    {
        $this->libelleNiveau = $libelleNiveau;

        return $this;
    }

    /**
     * Get libelleNiveau
     *
     * @return string
     */
    public function getLibelleNiveau()
    {
        return $this->libelleNiveau;
    }

    /**
     * Set etatNiveau
     *
     * @param integer $etatNiveau
     *
     * @return Niveau
     */
    public function setEtatNiveau($etatNiveau)
    {
        $this->etatNiveau = $etatNiveau;

        return $this;
    }

    /**
     * Get etatNiveau
     *
     * @return integer
     */
    public function getEtatNiveau()
    {
        return $this->etatNiveau;
    }



    /**
     * Add periode
     *
     * @param \admin\ScolariteBundle\Entity\Periode $periode
     *
     * @return Niveau
     */
    public function addPeriode(\admin\ScolariteBundle\Entity\Periode $periode)
    {
        $this->periodes[] = $periode;

        return $this;
    }

    /**
     * Remove periode
     *
     * @param \admin\ScolariteBundle\Entity\Periode $periode
     */
    public function removePeriode(\admin\ScolariteBundle\Entity\Periode $periode)
    {
        $this->periodes->removeElement($periode);
    }

    /**
     * Get periodes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPeriodes()
    {
        return $this->periodes;
    }


    /**
     * Add estenseigne
     *
     * @param \admin\ScolariteBundle\Entity\EstEnseigne $estenseigne
     *
     * @return Niveau
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
     * Set descriptionNiveau
     *
     * @param string $descriptionNiveau
     *
     * @return Niveau
     */
    public function setDescriptionNiveau($descriptionNiveau)
    {
        $this->descriptionNiveau = $descriptionNiveau;

        return $this;
    }

    /**
     * Get descriptionNiveau
     *
     * @return string
     */
    public function getDescriptionNiveau()
    {
        return $this->descriptionNiveau;
    }

    /**
     * Add class
     *
     * @param \admin\ScolariteBundle\Entity\Classe $classe
     *
     * @return Niveau
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
     * @return Niveau
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
     * Add ecolage
     *
     * @param \admin\ScolariteBundle\Entity\Ecolage $ecolage
     *
     * @return Niveau
     */
    public function addEcolage(\admin\ScolariteBundle\Entity\Ecolage $ecolage)
    {
        $this->ecolages[] = $ecolage;

        return $this;
    }

    /**
     * Remove ecolage
     *
     * @param \admin\ScolariteBundle\Entity\Ecolage $ecolage
     */
    public function removeEcolage(\admin\ScolariteBundle\Entity\Ecolage $ecolage)
    {
        $this->ecolages->removeElement($ecolage);
    }

    /**
     * Get ecolages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEcolages()
    {
        return $this->ecolages;
    }

    /**
     * Add rubrique
     *
     * @param \admin\CmsBundle\Entity\Rubrique $rubrique
     *
     * @return Niveau
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
     * @return Niveau
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
