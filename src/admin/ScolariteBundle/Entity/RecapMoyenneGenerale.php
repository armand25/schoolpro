<?php

namespace admin\ScolariteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \admin\UserBundle\Types\TypeEtat;

/**
 * RecapMoyenneGenerale
 *
 * @ORM\Table(name="recap_moyenne_generale")
 * @ORM\Entity(repositoryClass="admin\ScolariteBundle\Entity\RecapMoyenneGeneraleRepository")
 */
class RecapMoyenneGenerale
{
     public function __construct() {
        $this->etatRecapMoyenneGenerale = TypeEtat::ACTIF;
         $this->dateEnregistreRecapMoyenneGenerale = new \DateTime();
       
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
     * @var integer $etatRecapMoyenneGenerale
     *
     * @ORM\Column(name="etat_recap_moyenne_generale", type="integer")
     */
    private $etatRecapMoyenneGenerale;      
    
    /**
     * @var \Datetime $dateEnregistreRecapMoyenneGenerale
     *
     * @ORM\Column(name="date_enregistre_recap_moyenne_generale", type="datetime")
     */
    private $dateEnregistreRecapMoyenneGenerale;         


    /**
     * @var SeTrouver  $setrouver
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\SeTrouver", inversedBy="recapmoyennegenerales")
     * @ORM\JoinColumn(nullable=true)
     */
    private $setrouver;    

    
    /**
     * @var Decoupage  $decoupage
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Decoupage", inversedBy="recapmoyennegenerales")
     * @ORM\JoinColumn(nullable=true)
     */
    private $decoupage;  

    /**
     * @var integer $totalCoef
     *
     * @ORM\Column(name="total_coef", type="integer",nullable=true)
     */
    private $totalCoef;  
    
    /**
     * @var integer $typeMoyenne
     *
     * @ORM\Column(name="type_moyenne", type="integer",nullable=true)
     */
    private $typeMoyenne;  
    
    /**
     * @var decimal $moyenneClasse
     *
     * @ORM\Column(name="moyenne_classe", type="string",nullable=true)
     */
    private $moyenneClasse;  
    
    /**
     * @var string $rangMoyenneClasse
     *
     * @ORM\Column(name="rang_moyenne_classe", type="string",nullable=true)
     */
    private $rangMoyenneClasse; 
    
    /**
     * @var decimal $moyenneCompo
     *
     * @ORM\Column(name="moyenne_compo", type="string",nullable=true)
     */
    
    private $moyenneCompo;  
    
     /**
     * @var string $rangMoyenneCompo
     *
     * @ORM\Column(name="rang_moyenne_compo", type="string",nullable=true)
     */
    private $rangMoyenneCompo; 
    
    /**
     * @var decimal $moyenneGenerale
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
     * Set etatRecapMoyenneGenerale
     *
     * @param integer $etatRecapMoyenneGenerale
     *
     * @return RecapMoyenneGenerale
     */
    public function setEtatRecapMoyenneGenerale($etatRecapMoyenneGenerale)
    {
        $this->etatRecapMoyenneGenerale = $etatRecapMoyenneGenerale;

        return $this;
    }

    /**
     * Get etatRecapMoyenneGenerale
     *
     * @return integer
     */
    public function getEtatRecapMoyenneGenerale()
    {
        return $this->etatRecapMoyenneGenerale;
    }

    /**
     * Set dateEnregistreRecapMoyenneGenerale
     *
     * @param \DateTime $dateEnregistreRecapMoyenneGenerale
     *
     * @return RecapMoyenneGenerale
     */
    public function setDateEnregistreRecapMoyenneGenerale($dateEnregistreRecapMoyenneGenerale)
    {
        $this->dateEnregistreRecapMoyenneGenerale = $dateEnregistreRecapMoyenneGenerale;

        return $this;
    }

    /**
     * Get dateEnregistreRecapMoyenneGenerale
     *
     * @return \DateTime
     */
    public function getDateEnregistreRecapMoyenneGenerale()
    {
        return $this->dateEnregistreRecapMoyenneGenerale;
    }

    /**
     * Set totalCoef
     *
     * @param integer $totalCoef
     *
     * @return RecapMoyenneGenerale
     */
    public function setTotalCoef($totalCoef)
    {
        $this->totalCoef = $totalCoef;

        return $this;
    }

    /**
     * Get totalCoef
     *
     * @return integer
     */
    public function getTotalCoef()
    {
        return $this->totalCoef;
    }

    /**
     * Set typeMoyenne
     *
     * @param integer $typeMoyenne
     *
     * @return RecapMoyenneGenerale
     */
    public function setTypeMoyenne($typeMoyenne)
    {
        $this->typeMoyenne = $typeMoyenne;

        return $this;
    }

    /**
     * Get typeMoyenne
     *
     * @return integer
     */
    public function getTypeMoyenne()
    {
        return $this->typeMoyenne;
    }

    /**
     * Set moyenneClasse
     *
     * @param string $moyenneClasse
     *
     * @return RecapMoyenneGenerale
     */
    public function setMoyenneClasse($moyenneClasse)
    {
        $this->moyenneClasse = $moyenneClasse;

        return $this;
    }

    /**
     * Get moyenneClasse
     *
     * @return string
     */
    public function getMoyenneClasse()
    {
        return $this->moyenneClasse;
    }

    /**
     * Set rangMoyenneClasse
     *
     * @param string $rangMoyenneClasse
     *
     * @return RecapMoyenneGenerale
     */
    public function setRangMoyenneClasse($rangMoyenneClasse)
    {
        $this->rangMoyenneClasse = $rangMoyenneClasse;

        return $this;
    }

    /**
     * Get rangMoyenneClasse
     *
     * @return string
     */
    public function getRangMoyenneClasse()
    {
        return $this->rangMoyenneClasse;
    }

    /**
     * Set moyenneCompo
     *
     * @param string $moyenneCompo
     *
     * @return RecapMoyenneGenerale
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
     * Set rangMoyenneCompo
     *
     * @param string $rangMoyenneCompo
     *
     * @return RecapMoyenneGenerale
     */
    public function setRangMoyenneCompo($rangMoyenneCompo)
    {
        $this->rangMoyenneCompo = $rangMoyenneCompo;

        return $this;
    }

    /**
     * Get rangMoyenneCompo
     *
     * @return string
     */
    public function getRangMoyenneCompo()
    {
        return $this->rangMoyenneCompo;
    }

    /**
     * Set moyenneGenerale
     *
     * @param string $moyenneGenerale
     *
     * @return RecapMoyenneGenerale
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
     * @return RecapMoyenneGenerale
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
     * @return RecapMoyenneGenerale
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
     * @return RecapMoyenneGenerale
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
