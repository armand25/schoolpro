<?php

namespace admin\ScolariteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \admin\UserBundle\Types\TypeEtat;

/**
 * DetailPresence
 *
 * @ORM\Table(name="detail_presence")
 * @ORM\Entity(repositoryClass="admin\ScolariteBundle\Entity\DetailPresenceRepository")
 */
class DetailPresence
{
     public function __construct() {
        $this->etatDetailPresence = TypeEtat::ACTIF;
       
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
     * @var integer $etatDetailPresence
     *
     * @ORM\Column(name="etat_detail_presence", type="integer")
     */
    private $etatDetailPresence;    
    
    /**
     * @var integer $siPresent
     *
     * @ORM\Column(name="si_present", type="integer")
     */
    private $siPresent;       

    /**
     * @var date $dateDetailPresence
     *
     * @ORM\Column(name="date_detail_presence", type="datetime")
     */
    private $dateDetailPresence;
    
    /**
     * @var SeTrouver  $setrouve
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\SeTrouver", inversedBy="detail_presences")
     * @ORM\JoinColumn(nullable=true)
     */
    private $setrouve;    
     
    /**
     * @var Presence  $presence
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Presence", inversedBy="detailpresences")
     * @ORM\JoinColumn(nullable=true)
     */
    private $presence;                   


   

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
     * Set etatDetailPresence
     *
     * @param integer $etatDetailPresence
     *
     * @return DetailPresence
     */
    public function setEtatDetailPresence($etatDetailPresence)
    {
        $this->etatDetailPresence = $etatDetailPresence;

        return $this;
    }

    /**
     * Get etatDetailPresence
     *
     * @return integer
     */
    public function getEtatDetailPresence()
    {
        return $this->etatDetailPresence;
    }

    /**
     * Set siPresent
     *
     * @param integer $siPresent
     *
     * @return DetailPresence
     */
    public function setSiPresent($siPresent)
    {
        $this->siPresent = $siPresent;

        return $this;
    }

    /**
     * Get siPresent
     *
     * @return integer
     */
    public function getSiPresent()
    {
        return $this->siPresent;
    }

    /**
     * Set dateDetailPresence
     *
     * @param \DateTime $dateDetailPresence
     *
     * @return DetailPresence
     */
    public function setDateDetailPresence($dateDetailPresence)
    {
        $this->dateDetailPresence = $dateDetailPresence;

        return $this;
    }

    /**
     * Get dateDetailPresence
     *
     * @return \DateTime
     */
    public function getDateDetailPresence()
    {
        return $this->dateDetailPresence;
    }

    /**
     * Set setrouve
     *
     * @param \admin\ScolariteBundle\Entity\SeTrouver $setrouve
     *
     * @return DetailPresence
     */
    public function setSetrouve(\admin\ScolariteBundle\Entity\SeTrouver $setrouve = null)
    {
        $this->setrouve = $setrouve;

        return $this;
    }

    /**
     * Get setrouve
     *
     * @return \admin\ScolariteBundle\Entity\SeTrouver
     */
    public function getSetrouve()
    {
        return $this->setrouve;
    }

    /**
     * Set fairecours
     *
     * @param \admin\ScolariteBundle\Entity\FaireCours $fairecours
     *
     * @return DetailPresence
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
     * Set presence
     *
     * @param \admin\ScolariteBundle\Entity\Presence $presence
     *
     * @return DetailPresence
     */
    public function setPresence(\admin\ScolariteBundle\Entity\Presence $presence = null)
    {
        $this->presence = $presence;

        return $this;
    }

    /**
     * Get presence
     *
     * @return \admin\ScolariteBundle\Entity\Presence
     */
    public function getPresence()
    {
        return $this->presence;
    }
}
