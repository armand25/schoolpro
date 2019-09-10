<?php

namespace admin\ScolariteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \admin\UserBundle\Types\TypeEtat;

/**
 * Epreuve
 *
 * @ORM\Table(name="epreuve")
 * @ORM\Entity(repositoryClass="admin\ScolariteBundle\Entity\EpreuveRepository")
 */
class Epreuve
{
     public function __construct() {
        $this->etatEpreuve = TypeEtat::ACTIF;
       $this->exercices = new ArrayCollection();
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
     * @var string $libelleEpreuve
     *
     * @ORM\Column(name="libelle_epreuve", type="string")
     */
    private $libelleEpreuve;
    /**
     * @var string $descriptionEpreuve
     *
     * @ORM\Column(name="description_epreuve", type="string")
     */
    private $descriptionEpreuve;
    /**
     * @var string $etatEpreuve
     *
     * @ORM\Column(name="etat_epreuve", type="integer")
     */
    private $etatEpreuve;
 
        /**
     * @var AnneeScolaire  $anneescolaire
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\AnneeScolaire", inversedBy="epreuves")
     * @ORM\JoinColumn(nullable=true)
     */
    private $anneescolaire;  
    
       /**
     * @var ArrayCollection Classe $classes
     * @ORM\ManyToMany(targetEntity="admin\ScolariteBundle\Entity\Classe", inversedBy="epreuves",cascade={"persist","merge"})
     * 
     */
    private $classes;  
    
    /**
     * @var Matiere  $matiere
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Matiere", inversedBy="epreuves")
     * @ORM\JoinColumn(nullable=true)
     */
    private $matiere;  
    
    
        /**
     * @var Decoupage  $decoupage
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Decoupage", inversedBy="epreuves")
     * @ORM\JoinColumn(nullable=true)
     */
    private $decoupage;  
    
        /**
     * @var TypeExamen  $typeexamen
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\TypeExamen", inversedBy="epreuves")
     * @ORM\JoinColumn(nullable=true)
     */
    private $typeexamen;  
        /**
     *
     * @var ArrayCollection $exercices 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\Exercice", cascade={"persist", "remove"}, mappedBy="epreuve")
     * 
     */
    private $exercices;    

    /**
     *
     * @var ArrayCollection $notes
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\Note", cascade={"persist", "remove"}, mappedBy="epreuve")
     * 
     */
    private $notes;    

    /**
     *
     * @var Utilisateur $utilisateur;
     * @ORM\ManyToOne(targetEntity="admin\UserBundle\Entity\Utilisateur", inversedBy="epreuves")
     * @ORM\JoinColumn(nullable=true)
     */
    private $utilisateur;    
    
    
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
     * Set libelleEpreuve
     *
     * @param string $libelleEpreuve
     *
     * @return Epreuve
     */
    public function setLibelleEpreuve($libelleEpreuve)
    {
        $this->libelleEpreuve = $libelleEpreuve;

        return $this;
    }

    /**
     * Get libelleEpreuve
     *
     * @return string
     */
    public function getLibelleEpreuve()
    {
        return $this->libelleEpreuve;
    }

    /**
     * Set descriptionEpreuve
     *
     * @param string $descriptionEpreuve
     *
     * @return Epreuve
     */
    public function setDescriptionEpreuve($descriptionEpreuve)
    {
        $this->descriptionEpreuve = $descriptionEpreuve;

        return $this;
    }

    /**
     * Get descriptionEpreuve
     *
     * @return string
     */
    public function getDescriptionEpreuve()
    {
        return $this->descriptionEpreuve;
    }

    /**
     * Set etatEpreuve
     *
     * @param integer $etatEpreuve
     *
     * @return Epreuve
     */
    public function setEtatEpreuve($etatEpreuve)
    {
        $this->etatEpreuve = $etatEpreuve;

        return $this;
    }

    /**
     * Get etatEpreuve
     *
     * @return integer
     */
    public function getEtatEpreuve()
    {
        return $this->etatEpreuve;
    }   

    /**
     * Set anneescolaire
     *
     * @param \admin\ScolariteBundle\Entity\AnneeScolaire $anneescolaire
     *
     * @return Epreuve
     */
    public function setAnneescolaire(\admin\ScolariteBundle\Entity\AnneeScolaire $anneescolaire = null)
    {
        $this->anneescolaire = $anneescolaire;

        return $this;
    }

    /**
     * Get anneescolaire
     *
     * @return \admin\ScolariteBundle\Entity\AnneeScolaire
     */
    public function getAnneescolaire()
    {
        return $this->anneescolaire;
    }


    /**
     * Set decoupage
     *
     * @param \admin\ScolariteBundle\Entity\Decoupage $decoupage
     *
     * @return Epreuve
     */
    public function setDecoupage(\admin\ScolariteBundle\Entity\Decoupage $decoupage = null)
    {
        $this->decoupage = $decoupage;

        return $this;
    }

    /**
     * Get decoupage
     *
     * @return \admin\ScolariteBundle\Entity\Decoupage
     */
    public function getDecoupage()
    {
        return $this->decoupage;
    }

    /**
     * Set typeexamen
     *
     * @param \admin\ScolariteBundle\Entity\TypeExamen $typeexamen
     *
     * @return Epreuve
     */
    public function setTypeexamen(\admin\ScolariteBundle\Entity\TypeExamen $typeexamen = null)
    {
        $this->typeexamen = $typeexamen;

        return $this;
    }

    /**
     * Get typeexamen
     *
     * @return \admin\ScolariteBundle\Entity\TypeExamen
     */
    public function getTypeexamen()
    {
        return $this->typeexamen;
    }

    /**
     * Add exercice
     *
     * @param \admin\ScolariteBundle\Entity\Exercice $exercice
     *
     * @return Epreuve
     */
    public function addExercice(\admin\ScolariteBundle\Entity\Exercice $exercice)
    {
        $this->exercices[] = $exercice;

        return $this;
    }

    /**
     * Remove exercice
     *
     * @param \admin\ScolariteBundle\Entity\Exercice $exercice
     */
    public function removeExercice(\admin\ScolariteBundle\Entity\Exercice $exercice)
    {
        $this->exercices->removeElement($exercice);
    }

    /**
     * Get exercices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExercices()
    {
        return $this->exercices;
    }
    
    public function setExercices(\Doctrine\Common\Collections\ArrayCollection $exercices){
        
        $this->exercices = $exercices;
        foreach ($exercices as $ligne){
            $ligne->setEpreuve($this);
        }
    }    
    

    /**
     * Add note
     *
     * @param \admin\ScolariteBundle\Entity\Note $note
     *
     * @return Epreuve
     */
    public function addNote(\admin\ScolariteBundle\Entity\Note $note)
    {
        $this->notes[] = $note;

        return $this;
    }

    /**
     * Remove note
     *
     * @param \admin\ScolariteBundle\Entity\Note $note
     */
    public function removeNote(\admin\ScolariteBundle\Entity\Note $note)
    {
        $this->notes->removeElement($note);
    }

    /**
     * Get notes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set matiere
     *
     * @param \admin\ScolariteBundle\Entity\Matiere $matiere
     *
     * @return Epreuve
     */
    public function setMatiere(\admin\ScolariteBundle\Entity\Matiere $matiere = null)
    {
        $this->matiere = $matiere;

        return $this;
    }

    /**
     * Get matiere
     *
     * @return \admin\ScolariteBundle\Entity\Matiere
     */
    public function getMatiere()
    {
        return $this->matiere;
    }

    /**
     * Add class
     *
     * @param \admin\ScolariteBundle\Entity\Classe $class
     *
     * @return Epreuve
     */
    public function addClasse(\admin\ScolariteBundle\Entity\Classe $class)
    {
        $this->classes[] = $class;

        return $this;
    }

    /**
     * Remove class
     *
     * @param \admin\ScolariteBundle\Entity\Classe $class
     */
    public function removeClasse(\admin\ScolariteBundle\Entity\Classe $class)
    {
        $this->classes->removeElement($class);
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
     * @return Epreuve
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
     * Set utilisateur
     *
     * @param \admin\UserBundle\Entity\Utilisateur $utilisateur
     *
     * @return Epreuve
     */
    public function setUtilisateur(\admin\UserBundle\Entity\Utilisateur $utilisateur = null)
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * Get utilisateur
     *
     * @return \admin\UserBundle\Entity\Utilisateur
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }
}
