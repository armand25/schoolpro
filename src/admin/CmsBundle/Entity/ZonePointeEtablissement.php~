<?php

namespace admin\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use \Doctrine\Common\Collections\ArrayCollection;
use admin\UserBundle\Types\TypeEtat;


/**
 * User
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="zone_pointe_etablissement")
 * @ORM\Entity(repositoryClass="admin\CmsBundle\Repository\ZonePointeEtablissementRepository")
 */
class ZonePointeEtablissement {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    

    /**
     * @var integer $etatZone
     *
     * @ORM\Column(name="etat_zone", type="smallint")
     */
    private $etatZone;
    
    /**
     * @var integer $typeElement
     *
     * @ORM\Column(name="type_element", type="smallint")
     */
    private $typeElement;
    
    /**
     * @var integer $pointeVers
     *
     * @ORM\Column(name="point_vers", type="smallint")
     */
    private $pointeVers;
    
 
    
    public function __construct() {
        $this->dateAjoutZone = new \DateTime();
        $this->etatZone = TypeEtat::ACTIF;
        $this->pointeVers = TypeEtat::ACTIF;
        
    }
    
    /**
     * @var Zone  $zone
     * @ORM\ManyToOne(targetEntity="admin\CmsBundle\Entity\Zone", inversedBy="zonepointeetablissements")
     * @ORM\JoinColumn(nullable=true)
     */
    private $zone;    
    /**
     * @var Etablissement  $etablissement
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Etablissement", inversedBy="zonepointeetablissements")
     * @ORM\JoinColumn(nullable=true)
     */
    private $etablissement;    
    

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
     * Set etatZone
     *
     * @param integer $etatZone
     *
     * @return ZonePointeEtablissement
     */
    public function setEtatZone($etatZone)
    {
        $this->etatZone = $etatZone;

        return $this;
    }

    /**
     * Get etatZone
     *
     * @return integer
     */
    public function getEtatZone()
    {
        return $this->etatZone;
    }

    /**
     * Set typeElement
     *
     * @param integer $typeElement
     *
     * @return ZonePointeEtablissement
     */
    public function setTypeElement($typeElement)
    {
        $this->typeElement = $typeElement;

        return $this;
    }

    /**
     * Get typeElement
     *
     * @return integer
     */
    public function getTypeElement()
    {
        return $this->typeElement;
    }

    /**
     * Set pointeVers
     *
     * @param integer $pointeVers
     *
     * @return ZonePointeEtablissement
     */
    public function setPointeVers($pointeVers)
    {
        $this->pointeVers = $pointeVers;

        return $this;
    }

    /**
     * Get pointeVers
     *
     * @return integer
     */
    public function getPointeVers()
    {
        return $this->pointeVers;
    }

    /**
     * Set zone
     *
     * @param \admin\CmsBundle\Entity\Zone $zone
     *
     * @return ZonePointeEtablissement
     */
    public function setZone(\admin\CmsBundle\Entity\Zone $zone = null)
    {
        $this->zone = $zone;

        return $this;
    }

    /**
     * Get zone
     *
     * @return \admin\CmsBundle\Entity\Zone
     */
    public function getZone()
    {
        return $this->zone;
    }

    /**
     * Set etablissement
     *
     * @param \admin\ScolariteBundle\Entity\Etablissement $etablissement
     *
     * @return ZonePointeEtablissement
     */
    public function setEtablissement(\admin\ScolariteBundle\Entity\Etablissement $etablissement = null)
    {
        $this->etablissement = $etablissement;

        return $this;
    }

    /**
     * Get etablissement
     *
     * @return \admin\ScolariteBundle\Entity\Etablissement
     */
    public function getEtablissement()
    {
        return $this->etablissement;
    }
}
