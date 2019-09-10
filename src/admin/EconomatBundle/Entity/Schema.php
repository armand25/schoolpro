<?php

namespace admin\EconomatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="admin\EconomatBundle\Entity\SchemaRepository")
 * @ORM\Table(name="schemacomptable")
 */
class Schema {
    
    
    /**
     * @var integer $id
     * @ORM\Column(name="idschema", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
 
    /**
     * @var PlanComptable $planComptable
     * @ORM\ManyToOne(targetEntity="admin\EconomatBundle\Entity\PlanComptable", inversedBy="schemas", cascade={"persist","merge"})
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="compte", referencedColumnName="compte")
     * })
     */
    private $plancomptable;      
    
    /**
     * @var string $sens
     * @ORM\Column(name="sens", type="string",length=1)
     */
    private $sens;

    /**
     * @var integer $coef
     * @ORM\Column(name="coef", type="integer")
     */
    private $coef;
    /**
     * @var sting $valeur
     * @ORM\Column(name="valeur", type="string",length=30)
     */
    private $valeur;

    /**
     * @var Typeoperation $typeoperation
     * @ORM\ManyToOne(targetEntity="admin\EconomatBundle\Entity\TypeOperation", inversedBy="schemas", cascade={"persist","merge"})
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="idtypeoperation", referencedColumnName="idtypeoperation")
     * })
     */
    private $typeoperation;
    
    /**
     * @var Etablissement  $etablissement
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Etablissement", inversedBy="schemas")
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
     * Set sens
     *
     * @param string $sens
     * @return Schema
     */
    public function setSens($sens)
    {
        $this->sens = $sens;
    
        return $this;
    }

    /**
     * Get sens
     *
     * @return string 
     */
    public function getSens()
    {
        return $this->sens;
    }

    /**
     * Set coef
     *
     * @param integer $coef
     * @return Schema
     */
    public function setCoef($coef)
    {
        $this->coef = $coef;
    
        return $this;
    }

    /**
     * Get coef
     *
     * @return integer 
     */
    public function getCoef()
    {
        return $this->coef;
    }

    /**
     * Set valeur
     *
     * @param string $valeur
     * @return Schema
     */
    public function setValeur($valeur)
    {
        $this->valeur = $valeur;
    
        return $this;
    }

    /**
     * Get valeur
     *
     * @return string 
     */
    public function getValeur()
    {
        return $this->valeur;
    }

    /**
     * Set plancomptable
     *
     * @param \admin\EconomatBundle\Entity\PlanComptable $plancomptable
     * @return Schema
     */
    public function setPlancomptable(\admin\EconomatBundle\Entity\PlanComptable $plancomptable = null)
    {
        $this->plancomptable = $plancomptable;
    
        return $this;
    }

    /**
     * Get plancomptable
     *
     * @return \admin\EconomatBundle\Entity\PlanComptable 
     */
    public function getPlancomptable()
    {
        return $this->plancomptable;
    }

    /**
     * Set typeoperation
     *
     * @param \admin\EconomatBundle\Entity\TypeOperation $typeoperation
     * @return Schema
     */
    public function setTypeoperation(\admin\EconomatBundle\Entity\TypeOperation $typeoperation = null)
    {
        $this->typeoperation = $typeoperation;
    
        return $this;
    }

    /**
     * Get typeoperation
     *
     * @return \admin\EconomatBundle\Entity\TypeOperation 
     */
    public function getTypeoperation()
    {
        return $this->typeoperation;
    }

    /**
     * Set etablissement
     *
     * @param \admin\ScolariteBundle\Entity\Etablissement $etablissement
     *
     * @return Schema
     */
    public function setEtablissement(\admin\ScolariteBundle\Entity\Etablissement $etablissement)
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
