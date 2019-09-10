<?php

namespace admin\ScolariteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \admin\UserBundle\Types\TypeEtat;

/**
 * Concerner
 *
 * @ORM\Table(name="concerner")
 * @ORM\Entity(repositoryClass="admin\ScolariteBundle\Entity\ConcernerRepository")
 */
class Concerner
{
     public function __construct() {
        $this->etatConcerner = TypeEtat::ACTIF;
       
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
     * @var string $etatConcerner
     *
     * @ORM\Column(name="etat_concerner", type="integer")
     */
    private $etatConcerner;       

    /**
     * @var TypeDecoupage  $typedecoupage
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\TypeDecoupage", inversedBy="concerners")
     * @ORM\JoinColumn(nullable=true)
     */
    private $typedecoupage;    
      
    
    /**
     * @var Enseignement  $enseignement
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Enseignement", inversedBy="concerners")
     * @ORM\JoinColumn(nullable=true)
     */
    private $enseignement;     
    /**
     * @var AnneeScolaire  $anneescolaire
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\AnneeScolaire", inversedBy="concerners")
     * @ORM\JoinColumn(nullable=true)
     */
    private $anneescolaire;     
    
    /**
     * @var Etablissement  $etablissement
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Etablissement", inversedBy="concerners")
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
     * Set etatConcerner
     *
     * @param integer $etatConcerner
     *
     * @return Concerner
     */
    public function setEtatConcerner($etatConcerner)
    {
        $this->etatConcerner = $etatConcerner;

        return $this;
    }

    /**
     * Get etatConcerner
     *
     * @return integer
     */
    public function getEtatConcerner()
    {
        return $this->etatConcerner;
    }

    /**
     * Set typedecoupage
     *
     * @param \admin\ScolariteBundle\Entity\TypeDecoupage $typedecoupage
     *
     * @return Concerner
     */
    public function setTypeDecoupage(\admin\ScolariteBundle\Entity\TypeDecoupage $typedecoupage = null)
    {
        $this->typedecoupage = $typedecoupage;

        return $this;
    }

    /**
     * Get typedecoupage
     *
     * @return \admin\ScolariteBundle\Entity\TypeDecoupage
     */
    public function getTypeDecoupage()
    {
        return $this->typedecoupage;
    }

   
    /**
     * Set anneescolaire
     *
     * @param \admin\ScolariteBundle\Entity\AnneeScolaire $anneescolaire
     *
     * @return Concerner
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
     * Set enseignement
     *
     * @param \admin\ScolariteBundle\Entity\Enseignement $enseignement
     *
     * @return Concerner
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
     * Set etablissement
     *
     * @param \admin\ScolariteBundle\Entity\Etablissement $etablissement
     *
     * @return Concerner
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
