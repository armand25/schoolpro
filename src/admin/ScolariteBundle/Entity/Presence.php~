<?php

namespace admin\ScolariteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \admin\UserBundle\Types\TypeEtat;

/**
 * Presence
 *
 * @ORM\Table(name="presence")
 * @ORM\Entity(repositoryClass="admin\ScolariteBundle\Entity\PresenceRepository")
 */
class Presence
{
     public function __construct() {
        $this->etatPresence = TypeEtat::ACTIF;
        $this->typePresence = TypeEtat::ACTIF;
       
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
     * @var integer $etatPresence
     *
     * @ORM\Column(name="etat_presence", type="integer")
     */
    private $etatPresence;    
    
    /**
     * @var date $datePresence
     *
     * @ORM\Column(name="date_presence", type="datetime")
     */
    private $datePresence;
    
    /**
     *
     * @var ArrayCollection $detailpresences 
     * @ORM\OneToMany(targetEntity="admin\ScolariteBundle\Entity\DetailPresence", cascade={"persist", "remove"}, mappedBy="presence")
     * 
     */
    private $detailpresences; 
    
    /**
     * @var FaireCours  $fairecours
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\FaireCours", inversedBy="presences")
     * @ORM\JoinColumn(nullable=true)
     */
    private $fairecours;  
                 
    /**
     *
     * @var Utilisateur $utilisateur;
     * @ORM\ManyToOne(targetEntity="admin\UserBundle\Entity\Utilisateur", inversedBy="presences")
     * @ORM\JoinColumn(nullable=true)
     */
    private $utilisateur;
    
        /**
     * @var Decoupage  $decoupage
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Decoupage", inversedBy="presences")
     * @ORM\JoinColumn(nullable=true)
     */
    private $decoupage;      
    
    
    /**
     * @var integer $typePresence
     *
     * @ORM\Column(name="type_presence", type="integer")
     */
    private $typePresence;    
   


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
     * Set etatPresence
     *
     * @param integer $etatPresence
     *
     * @return Presence
     */
    public function setEtatPresence($etatPresence)
    {
        $this->etatPresence = $etatPresence;

        return $this;
    }

    /**
     * Get etatPresence
     *
     * @return integer
     */
    public function getEtatPresence()
    {
        return $this->etatPresence;
    }

    /**
     * Set datePresence
     *
     * @param \DateTime $datePresence
     *
     * @return Presence
     */
    public function setDatePresence($datePresence)
    {
        $this->datePresence = $datePresence;

        return $this;
    }

    /**
     * Get datePresence
     *
     * @return \DateTime
     */
    public function getDatePresence()
    {
        return $this->datePresence;
    }

    /**
     * Set typePresence
     *
     * @param integer $typePresence
     *
     * @return Presence
     */
    public function setTypePresence($typePresence)
    {
        $this->typePresence = $typePresence;

        return $this;
    }

    /**
     * Get typePresence
     *
     * @return integer
     */
    public function getTypePresence()
    {
        return $this->typePresence;
    }

    /**
     * Set fairecours
     *
     * @param \admin\ScolariteBundle\Entity\FaireCours $fairecours
     *
     * @return Presence
     */
    public function setFairecours(\admin\ScolariteBundle\Entity\FaireCours $fairecours = null)
    {
        $this->fairecours = $fairecours;

        return $this;
    }

    /**
     * Get fairecours
     *
     * @return \admin\ScolariteBundle\Entity\FaireCours
     */
    public function getFairecours()
    {
        return $this->fairecours;
    }

    /**
     * Add detailpresence
     *
     * @param \admin\ScolariteBundle\Entity\DetailPresence $detailpresence
     *
     * @return Presence
     */
    public function addDetailpresence(\admin\ScolariteBundle\Entity\DetailPresence $detailpresence)
    {
        $this->detailpresences[] = $detailpresence;

        return $this;
    }

    /**
     * Remove detailpresence
     *
     * @param \admin\ScolariteBundle\Entity\DetailPresence $detailpresence
     */
    public function removeDetailpresence(\admin\ScolariteBundle\Entity\DetailPresence $detailpresence)
    {
        $this->detailpresences->removeElement($detailpresence);
    }

    /**
     * Get detailpresences
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDetailpresences()
    {
        return $this->detailpresences;
    }

    /**
     * Set utilisateur
     *
     * @param \admin\UserBundle\Entity\Utilisateur $utilisateur
     *
     * @return Presence
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
     * Set decoupage
     *
     * @param \admin\ScolariteBundle\Entity\Decoupage $decoupage
     *
     * @return Presence
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
}
