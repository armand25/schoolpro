<?php

namespace admin\ScolariteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \admin\UserBundle\Types\TypeEtat;

/**
 * Classe
 *
 * @ORM\Table(name="classe")
 * @ORM\Entity(repositoryClass="admin\ScolariteBundle\Entity\ClasseRepository")
 */
class Classe
{
     public function __construct() {
        $this->etatClasse = TypeEtat::ACTIF;
       
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
     * @var string $libelleClasse
     *
     * @ORM\Column(name="libelle_classe", type="string")
     */
    private $libelleClasse;
    /**
     * @var string $descriptionClasse
     *
     * @ORM\Column(name="description_classe", type="string", nullable=true)
     */
    private $descriptionClasse;
    
    /**
     * @var string $etatClasse
     *
     * @ORM\Column(name="etat_classe", type="integer")
     */
    private $etatClasse;
    
     /**
     * @var Indice  $indice
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Indice", inversedBy="classes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $indice;  
    
     /**
     * @var Niveau  $niveau
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Niveau", inversedBy="classes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $niveau;
    
     /**
     * @var Filiere  $filiere
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Filiere", inversedBy="classes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $filiere;     
    
    /**
     *
     * @var ArrayCollection $fairecourss 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\FaireCours", cascade={"persist", "remove"}, mappedBy="classe")
     * 
     */
    private $fairecourss;     
    /**
     *
     * @var ArrayCollection $setrouvers 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\SeTrouver", cascade={"persist", "remove"}, mappedBy="classe")
     * 
     */
    private $setrouvers; 
    
    /**
     * @var Etablissement  $etablissement
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Etablissement", inversedBy="classes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $etablissement;     
    
    /**
     * @var ArrayCollection operations
     * @ORM\OneToMany(targetEntity="admin\EconomatBundle\Entity\Operation", cascade={"persist", "remove"}, mappedBy="classe")
     */
    private $operations;  
    
    /**
     * @var ArrayCollection Epreuve $epreuves
     * @ORM\ManyToMany(targetEntity="admin\ScolariteBundle\Entity\Epreuve", inversedBy="classes",cascade={"persist","merge"})
     * 
     */
    private $epreuves;  
   

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
     * Set libelleClasse
     *
     * @param string $libelleClasse
     *
     * @return Classe
     */
    public function setLibelleClasse($libelleClasse)
    {
        $this->libelleClasse = $libelleClasse;

        return $this;
    }

    /**
     * Get libelleClasse
     *
     * @return string
     */
    public function getLibelleClasse()
    {
        return $this->libelleClasse;
    }

    /**
     * Set etatClasse
     *
     * @param integer $etatClasse
     *
     * @return Classe
     */
    public function setEtatClasse($etatClasse)
    {
        $this->etatClasse = $etatClasse;

        return $this;
    }

    /**
     * Get etatClasse
     *
     * @return integer
     */
    public function getEtatClasse()
    {
        return $this->etatClasse;
    }

    /**
     * Set indice
     *
     * @param \admin\ScolariteBundle\Entity\Indice $indice
     *
     * @return Classe
     */
    public function setIndice(\admin\ScolariteBundle\Entity\Indice $indice = null)
    {
        $this->indice = $indice;

        return $this;
    }

    /**
     * Get indice
     *
     * @return \admin\ScolariteBundle\Entity\Indice
     */
    public function getIndice()
    {
        return $this->indice;
    }

    /**
     * Add fairecourss
     *
     * @param \admin\ScolariteBundle\Entity\FaireCours $fairecourss
     *
     * @return Classe
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
     * Add setrouver
     *
     * @param \admin\ScolariteBundle\Entity\SeTrouver $setrouver
     *
     * @return Classe
     */
    public function addSetrouver(\admin\ScolariteBundle\Entity\SeTrouver $setrouver)
    {
        $this->setrouvers[] = $setrouver;

        return $this;
    }

    /**
     * Remove setrouver
     *
     * @param \admin\ScolariteBundle\Entity\SeTrouver $setrouver
     */
    public function removeSetrouver(\admin\ScolariteBundle\Entity\SeTrouver $setrouver)
    {
        $this->setrouvers->removeElement($setrouver);
    }

    /**
     * Get setrouvers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSetrouvers()
    {
        return $this->setrouvers;
    }

    /**
     * Set etablissement
     *
     * @param \admin\ScolariteBundle\Entity\Etablissement $etablissement
     *
     * @return Classe
     */
    public function setEtablissement(\admin\ScolariteBundle\Entity\Etablissement $etablissement)
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
     * Set niveau
     *
     * @param \admin\ScolariteBundle\Entity\Niveau $niveau
     *
     * @return Classe
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
     * Set filiere
     *
     * @param \admin\ScolariteBundle\Entity\Filiere $filiere
     *
     * @return Classe
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
     * Set descriptionClasse
     *
     * @param string $descriptionClasse
     *
     * @return Classe
     */
    public function setDescriptionClasse($descriptionClasse)
    {
        $this->descriptionClasse = $descriptionClasse;

        return $this;
    }

    /**
     * Get descriptionClasse
     *
     * @return string
     */
    public function getDescriptionClasse()
    {
        return $this->descriptionClasse;
    }

    /**
     * Add operation
     *
     * @param \admin\EconomatBundle\Entity\Operation $operation
     *
     * @return Classe
     */
    public function addOperation(\admin\EconomatBundle\Entity\Operation $operation)
    {
        $this->operations[] = $operation;

        return $this;
    }

    /**
     * Remove operation
     *
     * @param \admin\EconomatBundle\Entity\Operation $operation
     */
    public function removeOperation(\admin\EconomatBundle\Entity\Operation $operation)
    {
        $this->operations->removeElement($operation);
    }

    /**
     * Get operations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOperations()
    {
        return $this->operations;
    }


    /**
     * Add epreuve
     *
     * @param \admin\ScolariteBundle\Entity\Epreuve $epreuve
     *
     * @return Classe
     */
    public function addEpreuve(\admin\ScolariteBundle\Entity\Epreuve $epreuve)
    {
        $this->epreuves[] = $epreuve;

        return $this;
    }

    /**
     * Remove epreuve
     *
     * @param \admin\ScolariteBundle\Entity\Epreuve $epreuve
     */
    public function removeEpreuve(\admin\ScolariteBundle\Entity\Epreuve $epreuve)
    {
        $this->epreuves->removeElement($epreuve);
    }

    /**
     * Get epreuves
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEpreuves()
    {
        return $this->epreuves;
    }

    /**
     * Add epreufe
     *
     * @param \admin\ScolariteBundle\Entity\Epreuve $epreufe
     *
     * @return Classe
     */
    public function addEpreufe(\admin\ScolariteBundle\Entity\Epreuve $epreufe)
    {
        $this->epreuves[] = $epreufe;

        return $this;
    }

    /**
     * Remove epreufe
     *
     * @param \admin\ScolariteBundle\Entity\Epreuve $epreufe
     */
    public function removeEpreufe(\admin\ScolariteBundle\Entity\Epreuve $epreufe)
    {
        $this->epreuves->removeElement($epreufe);
    }
}
