<?php

namespace admin\ScolariteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \admin\UserBundle\Types\TypeEtat;

/**
 * Note
 *
 * @ORM\Table(name="note")
 * @ORM\Entity(repositoryClass="admin\ScolariteBundle\Entity\NoteRepository")
 */
class Note
{
     public function __construct() {
        $this->etatNote = TypeEtat::ACTIF;
         $this->dateEnregistreNote = new \DateTime();
       
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
     * @var integer $etatNote
     *
     * @ORM\Column(name="etat_note", type="integer")
     */
    private $etatNote;    
    
    /**
     * @var string $Note
     *
     * @ORM\Column(name="note", type="string")
     */
    private $Note;    
    
    /**
     * @var \Datetime $dateEnregistreNote
     *
     * @ORM\Column(name="date_enregistre_note", type="datetime")
     */
    private $dateEnregistreNote;         


    /**
     * @var SeTrouver  $setrouver
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\SeTrouver", inversedBy="notes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $setrouver;    
    
 
    /**
     * @var Eleve  $eleve
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Eleve", inversedBy="notes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $eleve; 
    
    /**
     * @var Epreuve  $epreuve
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Epreuve", inversedBy="notes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $epreuve; 
    
    /**
     * @var Decoupage  $decoupage
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Decoupage", inversedBy="notes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $decoupage;  
    
    /**
     * @var Matiere  $matiere
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Matiere", inversedBy="notes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $matiere;  
    /**
     * @var Utilisateur  $utilisateur
     * @ORM\ManyToOne(targetEntity="admin\UserBundle\Entity\Utilisateur", inversedBy="notes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $utilisateur;  
    
    /**
     * @var TypeExamen  $typeexamen
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\TypeExamen", inversedBy="notes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $typeexamen;  
    /**
     *
     * @var ArrayCollection $detailnotes 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\DetailsNote", cascade={"persist", "remove"}, mappedBy="note")
     * 
     */
    private $detailnotes; 
                 

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
     * Set etatNote
     *
     * @param integer $etatNote
     *
     * @return Note
     */
    public function setEtatNote($etatNote)
    {
        $this->etatNote = $etatNote;

        return $this;
    }

    /**
     * Get etatNote
     *
     * @return integer
     */
    public function getEtatNote()
    {
        return $this->etatNote;
    }

    /**
     * Set note
     *
     * @param \string $note
     *
     * @return Note
     */
    public function setNote($note)
    {
        $this->Note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return \string
     */
    public function getNote()
    {
        return $this->Note;
    }

    /**
     * Set dateEnregistreNote
     *
     * @param \Date $dateEnregistreNote
     *
     * @return Note
     */
    public function setDateEnregistreNote(\Date $dateEnregistreNote)
    {
        $this->dateEnregistreNote = $dateEnregistreNote;

        return $this;
    }

    /**
     * Get dateEnregistreNote
     *
     * @return \Date
     */
    public function getDateEnregistreNote()
    {
        return $this->dateEnregistreNote;
    }

    /**
     * Set setrouver
     *
     * @param \admin\ScolariteBundle\Entity\SeTrouver $setrouver
     *
     * @return Note
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
     * Set eleve
     *
     * @param \admin\ScolariteBundle\Entity\Eleve $eleve
     *
     * @return Note
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
     * Set decoupage
     *
     * @param \admin\ScolariteBundle\Entity\Decoupage $decoupage
     *
     * @return Note
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
     * @return Note
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
     * Set utilisateur
     *
     * @param \admin\UserBundle\Entity\Utilisateur $utilisateur
     *
     * @return Note
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

    /**
     * Set matiere
     *
     * @param \admin\ScolariteBundle\Entity\Matiere $matiere
     *
     * @return Note
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
     * Set epreuve
     *
     * @param \admin\ScolariteBundle\Entity\Epreuve $epreuve
     *
     * @return Note
     */
    public function setEpreuve(\admin\ScolariteBundle\Entity\Epreuve $epreuve = null)
    {
        $this->epreuve = $epreuve;

        return $this;
    }

    /**
     * Get epreuve
     *
     * @return \admin\ScolariteBundle\Entity\Epreuve
     */
    public function getEpreuve()
    {
        return $this->epreuve;
    }

    /**
     * Add detailnote
     *
     * @param \admin\ScolariteBundle\Entity\DetailsNote $detailnote
     *
     * @return Note
     */
    public function addDetailnote(\admin\ScolariteBundle\Entity\DetailsNote $detailnote)
    {
        $this->detailnotes[] = $detailnote;

        return $this;
    }

    /**
     * Remove detailnote
     *
     * @param \admin\ScolariteBundle\Entity\DetailsNote $detailnote
     */
    public function removeDetailnote(\admin\ScolariteBundle\Entity\DetailsNote $detailnote)
    {
        $this->detailnotes->removeElement($detailnote);
    }

    /**
     * Get detailnotes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDetailnotes()
    {
        return $this->detailnotes;
    }
}
