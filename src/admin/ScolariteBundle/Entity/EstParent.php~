<?php

namespace admin\ScolariteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \admin\UserBundle\Types\TypeEtat;

/**
 * EstParent
 *
 * @ORM\Table(name="est_parent")
 * @ORM\Entity(repositoryClass="admin\ScolariteBundle\Entity\EstParentRepository")
 */
class EstParent
{
     public function __construct() {
        $this->etatEstParent = TypeEtat::ACTIF;
       
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
     * @var string $etatEstParent
     *
     * @ORM\Column(name="etat_est_parent", type="integer")
     */
    private $etatEstParent;       


    /**
     * @var Eleve  $eleve
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Eleve", inversedBy="estparents")
     * @ORM\JoinColumn(nullable=true)
     */
    private $eleve;    
    
    /**
     * @var Utilisateur  $utilisateur
     * @ORM\ManyToOne(targetEntity="admin\UserBundle\Entity\Utilisateur", inversedBy="estparents")
     * @ORM\JoinColumn(nullable=true)
     */
    private $utilisateur;   
    
 

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
     * Set etatEstParent
     *
     * @param integer $etatEstParent
     *
     * @return EstParent
     */
    public function setEtatEstParent($etatEstParent)
    {
        $this->etatEstParent = $etatEstParent;

        return $this;
    }

    /**
     * Get etatEstParent
     *
     * @return integer
     */
    public function getEtatEstParent()
    {
        return $this->etatEstParent;
    }

    /**
     * Set eleve
     *
     * @param \admin\ScolariteBundle\Entity\Eleve $eleve
     *
     * @return EstParent
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
     * Set utilisateur
     *
     * @param \admin\UserBundle\Entity\Utilisateur $utilisateur
     *
     * @return EstParent
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
}
