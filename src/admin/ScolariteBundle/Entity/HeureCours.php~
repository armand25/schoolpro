<?php

namespace admin\ScolariteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \admin\UserBundle\Types\TypeEtat;

/**
 * HeureCours
 *
 * @ORM\Table(name="heure_cours")
 * @ORM\Entity(repositoryClass="admin\ScolariteBundle\Entity\HeureCoursRepository")
 */
class HeureCours
{
     public function __construct() {
        $this->etatHeureCours = TypeEtat::ACTIF;
       
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
     * @var string $etatHeureCours
     *
     * @ORM\Column(name="etat_heure_cours", type="integer")
     */
    private $etatHeureCours;
    
    /**
     * @var HeureCours  $heurecours
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\HeureCours", inversedBy="heurecourss")
     * @ORM\JoinColumn(nullable=true)
     */
    private $heurecours;  
    
    /**
     * @var FaireCours  $fairecours
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\FaireCours", inversedBy="heurecourss")
     * @ORM\JoinColumn(nullable=true)
     */
    private $fairecours;  
    
    
      
    
    


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
     * Set etatHeureCours
     *
     * @param integer $etatHeureCours
     *
     * @return HeureCours
     */
    public function setEtatHeureCours($etatHeureCours)
    {
        $this->etatHeureCours = $etatHeureCours;

        return $this;
    }

    /**
     * Get etatHeureCours
     *
     * @return integer
     */
    public function getEtatHeureCours()
    {
        return $this->etatHeureCours;
    }

    /**
     * Set heurecours
     *
     * @param \admin\ScolariteBundle\Entity\HeureCours $heurecours
     *
     * @return HeureCours
     */
    public function setHeurecours(\admin\ScolariteBundle\Entity\HeureCours $heurecours = null)
    {
        $this->heurecours = $heurecours;

        return $this;
    }

    /**
     * Get heurecours
     *
     * @return \admin\ScolariteBundle\Entity\HeureCours
     */
    public function getHeurecours()
    {
        return $this->heurecours;
    }

    /**
     * Set fairecours
     *
     * @param \admin\ScolariteBundle\Entity\FaireCours $fairecours
     *
     * @return HeureCours
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
}
