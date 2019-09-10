<?php

namespace admin\ScolariteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \admin\UserBundle\Types\TypeEtat;

/**
 * RecapNote
 *
 * @ORM\Table(name="recap_note")
 * @ORM\Entity(repositoryClass="admin\ScolariteBundle\Entity\RecapNoteRepository")
 */
class RecapNote
{
     public function __construct() {
        $this->etatRecapNote = TypeEtat::ACTIF;
         $this->dateEnregistreRecapNote = new \DateTime();
       
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
     * @var integer $etatRecapNote
     *
     * @ORM\Column(name="etat_recap_note", type="integer")
     */
    private $etatRecapNote;      
    
    /**
     * @var \Datetime $dateEnregistreRecapNote
     *
     * @ORM\Column(name="date_enregistre_recap_note", type="datetime")
     */
    private $dateEnregistreRecapNote;         


    /**
     * @var SeTrouver  $setrouver
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\SeTrouver", inversedBy="recapnotes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $setrouver;    
    
    /**
     * @var Decoupage  $decoupage
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Decoupage", inversedBy="recapnotes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $decoupage;  
    
    /**
     * @var Matiere  $matiere
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Matiere", inversedBy="recapnotes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $matiere;   

    /**
     * @var string $moyenneInterro
     *
     * @ORM\Column(name="moyenne_interro", type="string",nullable=true)
     */
    private $moyenneInterro;  
    
    /**
     * @var string $moyenneDevoir
     *
     * @ORM\Column(name="moyenne_devoir", type="string",nullable=true)
     */
    private $moyenneDevoir;  
    
    /**
     * @var string $noteClasse
     *
     * @ORM\Column(name="note_classe", type="string",nullable=true)
     */
    private $noteClasse;  
    
    /**
     * @var string $rangNoteClasse
     *
     * @ORM\Column(name="rang_note_classe", type="string",nullable=true)
     */
    private $rangNoteClasse; 
    
    /**
     * @var string $moyenneCompo
     *
     * @ORM\Column(name="moyenne_compo", type="string",nullable=true)
     */
    
    private $moyenneCompo;  
    /**
     * @var string $moyenneGenerale
     *
     * @ORM\Column(name="moyenne_generale", type="string",nullable=true)
     */
    private $moyenneGenerale;  
    
    /**
     * @var string $rangMoyenneGenerale
     *
     * @ORM\Column(name="rang_moyenne_generale", type="string",nullable=true)
     */
    private $rangMoyenneGenerale;      
    

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
     * Set etatRecapNote
     *
     * @param integer $etatRecapNote
     *
     * @return RecapNote
     */
    public function setEtatRecapNote($etatRecapNote)
    {
        $this->etatRecapNote = $etatRecapNote;

        return $this;
    }

    /**
     * Get etatRecapNote
     *
     * @return integer
     */
    public function getEtatRecapNote()
    {
        return $this->etatRecapNote;
    }

    /**
     * Set dateEnregistreRecapNote
     *
     * @param \DateTime $dateEnregistreRecapNote
     *
     * @return RecapNote
     */
    public function setDateEnregistreRecapNote($dateEnregistreRecapNote)
    {
        $this->dateEnregistreRecapNote = $dateEnregistreRecapNote;

        return $this;
    }

    /**
     * Get dateEnregistreRecapNote
     *
     * @return \DateTime
     */
    public function getDateEnregistreRecapNote()
    {
        return $this->dateEnregistreRecapNote;
    }

    /**
     * Set moyenneInterro
     *
     * @param string $moyenneInterro
     *
     * @return RecapNote
     */
    public function setMoyenneInterro($moyenneInterro)
    {
        $this->moyenneInterro = $moyenneInterro;

        return $this;
    }

    /**
     * Get moyenneInterro
     *
     * @return string
     */
    public function getMoyenneInterro()
    {
        return $this->moyenneInterro;
    }

    /**
     * Set moyenneDevoir
     *
     * @param string $moyenneDevoir
     *
     * @return RecapNote
     */
    public function setMoyenneDevoir($moyenneDevoir)
    {
        $this->moyenneDevoir = $moyenneDevoir;

        return $this;
    }

    /**
     * Get moyenneDevoir
     *
     * @return string
     */
    public function getMoyenneDevoir()
    {
        return $this->moyenneDevoir;
    }

    /**
     * Set noteClasse
     *
     * @param string $noteClasse
     *
     * @return RecapNote
     */
    public function setNoteClasse($noteClasse)
    {
        $this->noteClasse = $noteClasse;

        return $this;
    }

    /**
     * Get noteClasse
     *
     * @return string
     */
    public function getNoteClasse()
    {
        return $this->noteClasse;
    }

    /**
     * Set rangNoteClasse
     *
     * @param string $rangNoteClasse
     *
     * @return RecapNote
     */
    public function setRangNoteClasse($rangNoteClasse)
    {
        $this->rangNoteClasse = $rangNoteClasse;

        return $this;
    }

    /**
     * Get rangNoteClasse
     *
     * @return string
     */
    public function getRangNoteClasse()
    {
        return $this->rangNoteClasse;
    }

    /**
     * Set moyenneCompo
     *
     * @param string $moyenneCompo
     *
     * @return RecapNote
     */
    public function setMoyenneCompo($moyenneCompo)
    {
        $this->moyenneCompo = $moyenneCompo;

        return $this;
    }

    /**
     * Get moyenneCompo
     *
     * @return string
     */
    public function getMoyenneCompo()
    {
        return $this->moyenneCompo;
    }

    /**
     * Set moyenneGenerale
     *
     * @param string $moyenneGenerale
     *
     * @return RecapNote
     */
    public function setMoyenneGenerale($moyenneGenerale)
    {
        $this->moyenneGenerale = $moyenneGenerale;

        return $this;
    }

    /**
     * Get moyenneGenerale
     *
     * @return string
     */
    public function getMoyenneGenerale()
    {
        return $this->moyenneGenerale;
    }

    /**
     * Set rangMoyenneGenerale
     *
     * @param string $rangMoyenneGenerale
     *
     * @return RecapNote
     */
    public function setRangMoyenneGenerale($rangMoyenneGenerale)
    {
        $this->rangMoyenneGenerale = $rangMoyenneGenerale;

        return $this;
    }

    /**
     * Get rangMoyenneGenerale
     *
     * @return string
     */
    public function getRangMoyenneGenerale()
    {
        return $this->rangMoyenneGenerale;
    }

    /**
     * Set setrouver
     *
     * @param \admin\ScolariteBundle\Entity\SeTrouver $setrouver
     *
     * @return RecapNote
     */
    public function setSetrouver(\admin\ScolariteBundle\Entity\SeTrouver $setrouver = null)
    {
        $this->setrouver = $setrouver;

        return $this;
    }

    /**
     * Get setrouver
     *
     * @return \admin\ScolariteBundle\Entity\SeTrouver
     */
    public function getSetrouver()
    {
        return $this->setrouver;
    }

    /**
     * Set decoupage
     *
     * @param \admin\ScolariteBundle\Entity\Decoupage $decoupage
     *
     * @return RecapNote
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
     * Set matiere
     *
     * @param \admin\ScolariteBundle\Entity\Matiere $matiere
     *
     * @return RecapNote
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
}
