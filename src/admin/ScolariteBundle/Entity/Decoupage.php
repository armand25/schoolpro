<?php

namespace admin\ScolariteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \admin\UserBundle\Types\TypeEtat;

/**
 * Decoupage
 *
 * @ORM\Table(name="decoupage")
 * @ORM\Entity(repositoryClass="admin\ScolariteBundle\Entity\DecoupageRepository")
 */
class Decoupage
{
     public function __construct() {
        $this->etatDecoupage = TypeEtat::INACTIF;
       
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
     * @var string $libelleDecoupage
     *
     * @ORM\Column(name="libelle_decoupage", type="string")
     */
    private $libelleDecoupage;
    /**
     * @var string $descriptionDecoupage
     *
     * @ORM\Column(name="description_decoupage", type="string")
     */
    private $descriptionDecoupage;
    /**
     * @var string $etatDecoupage
     *
     * @ORM\Column(name="etat_decoupage", type="integer")
     */
    private $etatDecoupage;
    
    /**
     * @var TypeDecoupage  $typedecoupage
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\TypeDecoupage", inversedBy="decoupages")
     * @ORM\JoinColumn(nullable=true)
     */
    private $typedecoupage;     
    
    
      /**
     *
     * @var ArrayCollection $epreuves 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\Epreuve", cascade={"persist", "remove"}, mappedBy="decoupage")
     * 
     */
    private $epreuves;  
      /**
     *
     * @var ArrayCollection $presences 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\Presence", cascade={"persist", "remove"}, mappedBy="decoupage")
     * 
     */
    private $presences;  
   
    
    /**
     *
     * @var ArrayCollection $periodes 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\Periode", cascade={"persist", "remove"}, mappedBy="decoupage")
     * 
     */
    private $periodes;
    
    /**
     *
     * @var ArrayCollection $notes
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\Note", cascade={"persist", "remove"}, mappedBy="decoupage")
     * 
     */
    private $notes;
    
    /**
     *
     * @var ArrayCollection $recapnotes 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\RecapNote", cascade={"persist", "remove"}, mappedBy="decoupage")
     * 
     */
    private $recapnotes;       
    /**
     *
     * @var ArrayCollection recapmoyennegenerales 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\RecapMoyenneGenerale", cascade={"persist", "remove"}, mappedBy="decoupage")
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
     * Set libelleDecoupage
     *
     * @param string $libelleDecoupage
     *
     * @return Decoupage
     */
    public function setLibelleDecoupage($libelleDecoupage)
    {
        $this->libelleDecoupage = $libelleDecoupage;

        return $this;
    }

    /**
     * Get libelleDecoupage
     *
     * @return string
     */
    public function getLibelleDecoupage()
    {
        return $this->libelleDecoupage;
    }

    /**
     * Set etatDecoupage
     *
     * @param integer $etatDecoupage
     *
     * @return Decoupage
     */
    public function setEtatDecoupage($etatDecoupage)
    {
        $this->etatDecoupage = $etatDecoupage;

        return $this;
    }

    /**
     * Get etatDecoupage
     *
     * @return integer
     */
    public function getEtatDecoupage()
    {
        return $this->etatDecoupage;
    }

    /**
     * Set typedecoupage
     *
     * @param \admin\ScolariteBundle\Entity\TypeDecoupage $typedecoupage
     *
     * @return Decoupage
     */
    public function setTypedecoupage(\admin\ScolariteBundle\Entity\TypeDecoupage $typedecoupage = null)
    {
        $this->typedecoupage = $typedecoupage;

        return $this;
    }

    /**
     * Get typedecoupage
     *
     * @return \admin\ScolariteBundle\Entity\TypeDecoupage
     */
    public function getTypedecoupage()
    {
        return $this->typedecoupage;
    }

    /**
     * Add periode
     *
     * @param \admin\ScolariteBundle\Entity\Periode $periode
     *
     * @return Decoupage
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
     * Add note
     *
     * @param \admin\ScolariteBundle\Entity\Note $note
     *
     * @return Decoupage
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
     * Set descriptionDecoupage
     *
     * @param string $descriptionDecoupage
     *
     * @return Decoupage
     */
    public function setDescriptionDecoupage($descriptionDecoupage)
    {
        $this->descriptionDecoupage = $descriptionDecoupage;

        return $this;
    }

    /**
     * Get descriptionDecoupage
     *
     * @return string
     */
    public function getDescriptionDecoupage()
    {
        return $this->descriptionDecoupage;
    }

    /**
     * Add recapnote
     *
     * @param \admin\ScolariteBundle\Entity\RecapNote $recapnote
     *
     * @return Decoupage
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
     * @return Decoupage
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

    /**
     * Add epreufe
     *
     * @param \admin\ScolariteBundle\Entity\Epreuve $epreufe
     *
     * @return Decoupage
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
     * Add presence
     *
     * @param \admin\ScolariteBundle\Entity\Presence $presence
     *
     * @return Decoupage
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
}
