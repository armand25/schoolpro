<?php

namespace admin\ScolariteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \admin\UserBundle\Types\TypeEtat;

/**
 * EstEnseigne
 *
 * @ORM\Table(name="est_enseigne")
 * @ORM\Entity(repositoryClass="admin\ScolariteBundle\Entity\EstEnseigneRepository")
 */
class EstEnseigne
{
     public function __construct() {
        $this->etatEstEnseigne = TypeEtat::ACTIF;
       
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
     * @var string $etatEstEnseigne
     *
     * @ORM\Column(name="etat_est_enseigne", type="integer")
     */
    private $etatEstEnseigne; 
    
    /**
     * @var integer $coefficient
     *
     * @ORM\Column(name="coefficient", type="integer")
     */
    private $coefficient;       


    /**
     * @var TypeMatiere  $typematiere
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\TypeMatiere", inversedBy="estenseignes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $typematiere;    
    
    /**
     * @var Matiere  $matiere
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Matiere", inversedBy="estenseignes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $matiere;   
    
    /**
     * @var Niveau  $niveau
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Niveau", inversedBy="estenseignes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $niveau;  
    
    /**
     * @var Filiere  $filiere
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Filiere", inversedBy="estenseignes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $filiere;     
  
    /**
     *
     * @var ArrayCollection $fairecourss 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\FaireCours", cascade={"persist", "remove"}, mappedBy="estenseigne")
     * 
     */
    private $fairecourss;     
    
    /**
     *
     * @var ArrayCollection $chapitres 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\Chapitre", cascade={"persist", "remove"}, mappedBy="estenseigne")
     * 
     */
    private $chapitres;    

    /**
     * @var Etablissement  $etablissement
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Etablissement", inversedBy="estenseignes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $etablissement;       
    
    

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
     * Set etatEstEnseigne
     *
     * @param integer $etatEstEnseigne
     *
     * @return EstEnseigne
     */
    public function setEtatEstEnseigne($etatEstEnseigne)
    {
        $this->etatEstEnseigne = $etatEstEnseigne;

        return $this;
    }

    /**
     * Get etatEstEnseigne
     *
     * @return integer
     */
    public function getEtatEstEnseigne()
    {
        return $this->etatEstEnseigne;
    }

    /**
     * Set typematiere
     *
     * @param \admin\ScolariteBundle\Entity\TypeMatiere $typematiere
     *
     * @return EstEnseigne
     */
    public function setTypematiere(\admin\ScolariteBundle\Entity\TypeMatiere $typematiere = null)
    {
        $this->typematiere = $typematiere;

        return $this;
    }

    /**
     * Get typematiere
     *
     * @return \admin\ScolariteBundle\Entity\TypeMatiere
     */
    public function getTypematiere()
    {
        return $this->typematiere;
    }

    /**
     * Set matiere
     *
     * @param \admin\ScolariteBundle\Entity\Matiere $matiere
     *
     * @return EstEnseigne
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
     * Set niveau
     *
     * @param \admin\ScolariteBundle\Entity\Niveau $niveau
     *
     * @return EstEnseigne
     */
    public function setNiveau(\admin\ScolariteBundle\Entity\Niveau $niveau = null)
    {
        $this->niveau = $niveau;

        return $this;
    }

    /**
     * Get niveau
     *
     * @return \admin\ScolariteBundle\Entity\Niveau
     */
    public function getNiveau()
    {
        return $this->niveau;
    }

    /**
     * Add fairecourss
     *
     * @param \admin\ScolariteBundle\Entity\FaireCours $fairecourss
     *
     * @return EstEnseigne
     */
    public function addFairecourss(\admin\ScolariteBundle\Entity\FaireCours $fairecourss)
    {
        $this->fairecourss[] = $fairecourss;

        return $this;
    }

    /**
     * Remove fairecourss
     *
     * @param \admin\ScolariteBundle\Entity\FaireCours $fairecourss
     */
    public function removeFairecourss(\admin\ScolariteBundle\Entity\FaireCours $fairecourss)
    {
        $this->fairecourss->removeElement($fairecourss);
    }

    /**
     * Get fairecourss
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFairecourss()
    {
        return $this->fairecourss;
    }

    /**
     * Set filiere
     *
     * @param \admin\ScolariteBundle\Entity\Filiere $filiere
     *
     * @return EstEnseigne
     */
    public function setFiliere(\admin\ScolariteBundle\Entity\Filiere $filiere = null)
    {
        $this->filiere = $filiere;

        return $this;
    }

    /**
     * Get filiere
     *
     * @return \admin\ScolariteBundle\Entity\Filiere
     */
    public function getFiliere()
    {
        return $this->filiere;
    }

    /**
     * Set coefficient
     *
     * @param integer $coefficient
     *
     * @return EstEnseigne
     */
    public function setCoefficient($coefficient)
    {
        $this->coefficient = $coefficient;

        return $this;
    }

    /**
     * Get coefficient
     *
     * @return integer
     */
    public function getCoefficient()
    {
        return $this->coefficient;
    }

    /**
     * Add chapitre
     *
     * @param \admin\ScolariteBundle\Entity\Chapitre $chapitre
     *
     * @return EstEnseigne
     */
    public function addChapitre(\admin\ScolariteBundle\Entity\Chapitre $chapitre)
    {
        $this->chapitres[] = $chapitre;

        return $this;
    }

    /**
     * Remove chapitre
     *
     * @param \admin\ScolariteBundle\Entity\Chapitre $chapitre
     */
    public function removeChapitre(\admin\ScolariteBundle\Entity\Chapitre $chapitre)
    {
        $this->chapitres->removeElement($chapitre);
    }

    /**
     * Get chapitres
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChapitres()
    {
        return $this->chapitres;
    }

    /**
     * Set etablissement
     *
     * @param \admin\ScolariteBundle\Entity\Etablissement $etablissement
     *
     * @return EstEnseigne
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
}
