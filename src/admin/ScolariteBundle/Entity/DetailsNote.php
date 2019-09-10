<?php

namespace admin\ScolariteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \admin\UserBundle\Types\TypeEtat;

/**
 * Note
 *
 * @ORM\Table(name="detail_note")
 * @ORM\Entity(repositoryClass="admin\ScolariteBundle\Entity\NoteRepository")
 */
class DetailsNote
{
     public function __construct() {
        $this->etatDetailsNote = TypeEtat::ACTIF;
        $this->dateEnregistreDetailsNote = new \DateTime();
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
     * @ORM\Column(name="etat_detail_note", type="integer")
     */
    private $etatDetailsNote;    
    
    /**
     * @var string $Appreciation
     *
     * @ORM\Column(name="appreciation_note", type="string")
     */
    private $Appreciation;    
    
    /**
     * @var \Datetime $dateEnregistreNote
     *
     * @ORM\Column(name="date_enregistre_detail_note", type="datetime")
     */
    private $dateEnregistreDetailsNote;         

    /**
     * @var Note  $note
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Note", inversedBy="detailnotes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $note; 
    
    /**
     * @var Exercice  $exercice
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Exercice", inversedBy="detailnotes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $exercice; 
    

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
     * Set etatDetailsNote
     *
     * @param integer $etatDetailsNote
     *
     * @return DetailsNote
     */
    public function setEtatDetailsNote($etatDetailsNote)
    {
        $this->etatDetailsNote = $etatDetailsNote;

        return $this;
    }

    /**
     * Get etatDetailsNote
     *
     * @return integer
     */
    public function getEtatDetailsNote()
    {
        return $this->etatDetailsNote;
    }

    /**
     * Set appreciation
     *
     * @param string $appreciation
     *
     * @return DetailsNote
     */
    public function setAppreciation($appreciation)
    {
        $this->Appreciation = $appreciation;

        return $this;
    }

    /**
     * Get appreciation
     *
     * @return string
     */
    public function getAppreciation()
    {
        return $this->Appreciation;
    }

    /**
     * Set dateEnregistreDetailsNote
     *
     * @param \DateTime $dateEnregistreDetailsNote
     *
     * @return DetailsNote
     */
    public function setDateEnregistreDetailsNote($dateEnregistreDetailsNote)
    {
        $this->dateEnregistreDetailsNote = $dateEnregistreDetailsNote;

        return $this;
    }

    /**
     * Get dateEnregistreDetailsNote
     *
     * @return \DateTime
     */
    public function getDateEnregistreDetailsNote()
    {
        return $this->dateEnregistreDetailsNote;
    }

    /**
     * Set note
     *
     * @param \admin\ScolariteBundle\Entity\Note $note
     *
     * @return DetailsNote
     */
    public function setNote(\admin\ScolariteBundle\Entity\Note $note = null)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return \admin\ScolariteBundle\Entity\Note
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set exercice
     *
     * @param \admin\ScolariteBundle\Entity\Exercice $exercice
     *
     * @return DetailsNote
     */
    public function setExercice(\admin\ScolariteBundle\Entity\Exercice $exercice = null)
    {
        $this->exercice = $exercice;

        return $this;
    }

    /**
     * Get exercice
     *
     * @return \admin\ScolariteBundle\Entity\Exercice
     */
    public function getExercice()
    {
        return $this->exercice;
    }
}
