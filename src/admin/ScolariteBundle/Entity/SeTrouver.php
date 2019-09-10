<?php

namespace admin\ScolariteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \admin\UserBundle\Types\TypeEtat;

/**
 * SeTrouver
 *
 * @ORM\Table(name="se_trouver")
 * @ORM\Entity(repositoryClass="admin\ScolariteBundle\Entity\SeTrouverRepository")
 */
class SeTrouver
{
     public function __construct() {
        $this->etatSeTrouver = TypeEtat::ACTIF;
        $this->siReussir = 0;
       
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
     * @var integer $etatSeTrouver
     *
     * @ORM\Column(name="etat_se_trouver", type="integer")
     */
    private $etatSeTrouver;    
    
    /**
     * @var integer $siReussir
     *
     * @ORM\Column(name="si_reussir", type="integer")
     */
    private $siReussir;       


    /**
     * @var Eleve  $eleve
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Eleve", inversedBy="setrouvers")
     * @ORM\JoinColumn(nullable=true)
     */
    private $eleve;    
    
    /**
     * @var Classe  $classe
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Classe", inversedBy="setrouvers")
     * @ORM\JoinColumn(nullable=true)
     */
    private $classe;  
    
    
    /**
     * @var AnneeScolaire  $anneescolaire
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\AnneeScolaire", inversedBy="setrouvers")
     * @ORM\JoinColumn(nullable=true)
     */
    private $anneescolaire;   
    /**
     *
     * @var ArrayCollection $presences 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\Presence", cascade={"persist", "remove"}, mappedBy="setrouver")
     * 
     */
    private $presences; 
    
    /**
     *
     * @var ArrayCollection $notes 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\Note", cascade={"persist", "remove"}, mappedBy="setrouver")
     * 
     */
    private $notes;  
    
    /**
     *
     * @var ArrayCollection $recapnotes 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\RecapNote", cascade={"persist", "remove"}, mappedBy="setrouver")
     * 
     */
    private $recapnotes;       
    /**
     *
     * @var ArrayCollection recapmoyennegenerales 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\RecapMoyenneGenerale", cascade={"persist", "remove"}, mappedBy="setrouver")
     * 
     */
    private $recapmoyennegenerales;       

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
     * Set etatSeTrouver
     *
     * @param integer $etatSeTrouver
     *
     * @return SeTrouver
     */
    public function setEtatSeTrouver($etatSeTrouver)
    {
        $this->etatSeTrouver = $etatSeTrouver;

        return $this;
    }

    /**
     * Get etatSeTrouver
     *
     * @return integer
     */
    public function getEtatSeTrouver()
    {
        return $this->etatSeTrouver;
    }

    /**
     * Set siReussir
     *
     * @param integer $siReussir
     *
     * @return SeTrouver
     */
    public function setSiReussir($siReussir)
    {
        $this->siReussir = $siReussir;

        return $this;
    }

    /**
     * Get siReussir
     *
     * @return integer
     */
    public function getSiReussir()
    {
        return $this->siReussir;
    }

    /**
     * Set eleve
     *
     * @param \admin\ScolariteBundle\Entity\Eleve $eleve
     *
     * @return SeTrouver
     */
    public function setEleve(\admin\ScolariteBundle\Entity\Eleve $eleve = null)
    {
        $this->eleve = $eleve;

        return $this;
    }

    /**
     * Get eleve
     *
     * @return \admin\ScolariteBundle\Entity\Eleve
     */
    public function getEleve()
    {
        return $this->eleve;
    }

    /**
     * Set classe
     *
     * @param \admin\ScolariteBundle\Entity\Classe $classe
     *
     * @return SeTrouver
     */
    public function setClasse(\admin\ScolariteBundle\Entity\Classe $classe = null)
    {
        $this->classe = $classe;

        return $this;
    }

    /**
     * Get classe
     *
     * @return \admin\ScolariteBundle\Entity\Classe
     */
    public function getClasse()
    {
        return $this->classe;
    }

    /**
     * Set anneescolaire
     *
     * @param \admin\ScolariteBundle\Entity\AnneeScolaire $anneescolaire
     *
     * @return SeTrouver
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
     * Add presence
     *
     * @param \admin\ScolariteBundle\Entity\Presence $presence
     *
     * @return SeTrouver
     */
    public function addPresence(\admin\ScolariteBundle\Entity\Presence $presence)
    {
        $this->presences[] = $presence;

        return $this;
    }

    /**
     * Remove presence
     *
     * @param \admin\ScolariteBundle\Entity\Presence $presence
     */
    public function removePresence(\admin\ScolariteBundle\Entity\Presence $presence)
    {
        $this->presences->removeElement($presence);
    }

    /**
     * Get presences
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPresences()
    {
        return $this->presences;
    }

    /**
     * Add note
     *
     * @param \admin\ScolariteBundle\Entity\Note $note
     *
     * @return SeTrouver
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
     * Add recapnote
     *
     * @param \admin\ScolariteBundle\Entity\RecapNote $recapnote
     *
     * @return SeTrouver
     */
    public function addRecapnote(\admin\ScolariteBundle\Entity\RecapNote $recapnote)
    {
        $this->recapnotes[] = $recapnote;

        return $this;
    }

    /**
     * Remove recapnote
     *
     * @param \admin\ScolariteBundle\Entity\RecapNote $recapnote
     */
    public function removeRecapnote(\admin\ScolariteBundle\Entity\RecapNote $recapnote)
    {
        $this->recapnotes->removeElement($recapnote);
    }

    /**
     * Get recapnotes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRecapnotes()
    {
        return $this->recapnotes;
    }

    /**
     * Add recapmoyennegenerale
     *
     * @param \admin\ScolariteBundle\Entity\RecapMoyenneGenerale $recapmoyennegenerale
     *
     * @return SeTrouver
     */
    public function addRecapmoyennegenerale(\admin\ScolariteBundle\Entity\RecapMoyenneGenerale $recapmoyennegenerale)
    {
        $this->recapmoyennegenerales[] = $recapmoyennegenerale;

        return $this;
    }

    /**
     * Remove recapmoyennegenerale
     *
     * @param \admin\ScolariteBundle\Entity\RecapMoyenneGenerale $recapmoyennegenerale
     */
    public function removeRecapmoyennegenerale(\admin\ScolariteBundle\Entity\RecapMoyenneGenerale $recapmoyennegenerale)
    {
        $this->recapmoyennegenerales->removeElement($recapmoyennegenerale);
    }

    /**
     * Get recapmoyennegenerales
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRecapmoyennegenerales()
    {
        return $this->recapmoyennegenerales;
    }
}
