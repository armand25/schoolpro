<?php

namespace admin\ScolariteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \admin\UserBundle\Types\TypeEtat;

/**
 * Matiere
 *
 * @ORM\Table(name="matiere")
 * @ORM\Entity(repositoryClass="admin\ScolariteBundle\Entity\MatiereRepository")
 */
class Matiere
{
     public function __construct() {
        $this->etatMatiere = TypeEtat::ACTIF;
       
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
     * @var string $libelleMatiere
     *
     * @ORM\Column(name="libelle_matiere", type="string")
     */
    private $libelleMatiere;
    /**
     * @var string $descriptionMatiere
     *
     * @ORM\Column(name="description_matiere", type="string")
     */
    private $descriptionMatiere;
    /**
     * @var string $etatMatiere
     *
     * @ORM\Column(name="etat_matiere", type="integer")
     */
    private $etatMatiere;
    
    /**
     *
     * @var ArrayCollection $estenseignes 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\EstEnseigne", cascade={"persist", "remove"}, mappedBy="matiere")
     * 
     */
    private $estenseignes;  
    
    
    /**
     *
     * @var ArrayCollection $notes 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\Note", cascade={"persist", "remove"}, mappedBy="matiere")
     * 
     */
    private $notes;  
    
    /**
     *
     * @var ArrayCollection $epreuves 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\Epreuve", cascade={"persist", "remove"}, mappedBy="matiere")
     * 
     */
    private $epreuves;  
    

    
     /**
     *
     * @var ArrayCollection $recapnotes 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\RecapNote", cascade={"persist", "remove"}, mappedBy="matiere")
     * 
     */
    private $recapnotes;       
    /**
     *
     * @var ArrayCollection recapmoyennegenerales 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\RecapMoyenneGenerale", cascade={"persist", "remove"}, mappedBy="matiere")
     * 
     */
    private $recapmoyennegenerales; 

    /**
     * @var ArrayCollection Utilisateur $utilisateurs
     * @ORM\ManyToMany(targetEntity="admin\UserBundle\Entity\Utilisateur", inversedBy="matieres",cascade={"persist","merge"})
     * 
     */
    private $utilisateurs; 


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
     * Set libelleMatiere
     *
     * @param string $libelleMatiere
     *
     * @return Matiere
     */
    public function setLibelleMatiere($libelleMatiere)
    {
        $this->libelleMatiere = $libelleMatiere;

        return $this;
    }

    /**
     * Get libelleMatiere
     *
     * @return string
     */
    public function getLibelleMatiere()
    {
        return $this->libelleMatiere;
    }

    /**
     * Set etatMatiere
     *
     * @param integer $etatMatiere
     *
     * @return Matiere
     */
    public function setEtatMatiere($etatMatiere)
    {
        $this->etatMatiere = $etatMatiere;

        return $this;
    }

    /**
     * Get etatMatiere
     *
     * @return integer
     */
    public function getEtatMatiere()
    {
        return $this->etatMatiere;
    }

    /**
     * Add estenseigne
     *
     * @param \admin\ScolariteBundle\Entity\EstEnseigne $estenseigne
     *
     * @return Matiere
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
     * Set descriptionMatiere
     *
     * @param string $descriptionMatiere
     *
     * @return Matiere
     */
    public function setDescriptionMatiere($descriptionMatiere)
    {
        $this->descriptionMatiere = $descriptionMatiere;

        return $this;
    }

    /**
     * Get descriptionMatiere
     *
     * @return string
     */
    public function getDescriptionMatiere()
    {
        return $this->descriptionMatiere;
    }

    /**
     * Add utilisateur
     *
     * @param \admin\UserBundle\Entity\Utilisateur $utilisateur
     *
     * @return Matiere
     */
    public function addUtilisateur(\admin\UserBundle\Entity\Utilisateur $utilisateur)
    {
        $this->utilisateurs[] = $utilisateur;

        return $this;
    }

    /**
     * Remove utilisateur
     *
     * @param \admin\UserBundle\Entity\Utilisateur $utilisateur
     */
    public function removeUtilisateur(\admin\UserBundle\Entity\Utilisateur $utilisateur)
    {
        $this->utilisateurs->removeElement($utilisateur);
    }

    /**
     * Get utilisateurs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUtilisateurs()
    {
        return $this->utilisateurs;
    }

    /**
     * Add note
     *
     * @param \admin\ScolariteBundle\Entity\Note $note
     *
     * @return Matiere
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
     * @return Matiere
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
     * @return Matiere
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
     * @return Matiere
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
}
