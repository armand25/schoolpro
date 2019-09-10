<?php

namespace admin\EconomatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="admin\EconomatBundle\Entity\TypeOperationRepository")
 * @ORM\Table(name="typeoperation")
 */
class TypeOperation {

    function __construct() {
        $this->mouvFonds = 0;
    }

    /**
     * @var integer $id
     * @ORM\Column(name="idtypeoperation", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * 
     * @var string $libTypeOperation
     * @ORM\Column(name="libtypeoperation",type="string",length=30)
     * @Assert\NotBlank(message=" Le libellé du profil ne peut être vide ")
     */
    private $libTypeOperation;

    /**
     * 
     * @var string $codeOperation
     * @ORM\Column(name="codeoperation",type="string",length=5)
     * @Assert\NotBlank(message=" Le libellé du profil ne peut être vide ")
     */
    private $codeOperation;

    /**
     * @var ArrayCollection Operation $operations
     * @ORM\OneToMany(targetEntity="admin\EconomatBundle\Entity\Operation", mappedBy="TypeOperation")
     * 
     */
    private $operations;
    
    /**
     * @var integer $etatTypeOperation
     * @ORM\Column(name="etattypeOperation", type="integer")
     * @Assert\NotBlank()  
     */
    private $etatTypeOperation;
     

    /**
     * @var integer $suppr
     * @ORM\Column(name="suppr", type="integer")
     * @Assert\NotBlank()  
     */
    private $suppr;
    
    /**
     * @var integer $mouvFonds
     * @ORM\Column(name="mouvFonds", type="integer")
     * @Assert\NotBlank()  
     */
    private $mouvFonds;
    
    /**
     * @var ArrayCollection  $schemas
     * @ORM\OneToMany(targetEntity="admin\EconomatBundle\Entity\Schema", mappedBy="typeoperation")
     * 
     */
    private $schemas;
    
   
   
    /**
     * @var integer $nbreLigne
     * @ORM\Column(name="nbreligne", type="integer")
     * @Assert\NotBlank()  
     */
    private $nbreLigne;  
    
     /**
     * @var string $codeOpInt
     * @ORM\Column(name="codeopint",type="string", length=2, nullable=True)
     */
    private $codeOpInt; 
    
    
    /**
     * @var Etablissement  $etablissement
     * @ORM\ManyToOne(targetEntity="admin\ScolariteBundle\Entity\Etablissement", inversedBy="typeoperations")
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
     * Set libTypeOperation
     *
     * @param string $libTypeOperation
     * @return TypeOperation
     */
    public function setLibTypeOperation($libTypeOperation)
    {
        $this->libTypeOperation = $libTypeOperation;
    
        return $this;
    }

    /**
     * Get libTypeOperation
     *
     * @return string 
     */
    public function getLibTypeOperation()
    {
        return $this->libTypeOperation;
    }

    /**
     * Set codeOperation
     *
     * @param string $codeOperation
     * @return TypeOperation
     */
    public function setCodeOperation($codeOperation)
    {
        $this->codeOperation = $codeOperation;
    
        return $this;
    }

    /**
     * Get codeOperation
     *
     * @return string 
     */
    public function getCodeOperation()
    {
        return $this->codeOperation;
    }

    /**
     * Set etatTypeOperation
     *
     * @param integer $etatTypeOperation
     * @return TypeOperation
     */
    public function setEtatTypeOperation($etatTypeOperation)
    {
        $this->etatTypeOperation = $etatTypeOperation;
    
        return $this;
    }

    /**
     * Get etatTypeOperation
     *
     * @return integer 
     */
    public function getEtatTypeOperation()
    {
        return $this->etatTypeOperation;
    }

    /**
     * Set suppr
     *
     * @param integer $suppr
     * @return TypeOperation
     */
    public function setSuppr($suppr)
    {
        $this->suppr = $suppr;
    
        return $this;
    }

    /**
     * Get suppr
     *
     * @return integer 
     */
    public function getSuppr()
    {
        return $this->suppr;
    }

    /**
     * Set mouvFonds
     *
     * @param integer $mouvFonds
     * @return TypeOperation
     */
    public function setMouvFonds($mouvFonds)
    {
        $this->mouvFonds = $mouvFonds;
    
        return $this;
    }

    /**
     * Get mouvFonds
     *
     * @return integer 
     */
    public function getMouvFonds()
    {
        return $this->mouvFonds;
    }

    /**
     * Set nbreLigne
     *
     * @param integer $nbreLigne
     * @return TypeOperation
     */
    public function setNbreLigne($nbreLigne)
    {
        $this->nbreLigne = $nbreLigne;
    
        return $this;
    }

    /**
     * Get nbreLigne
     *
     * @return integer 
     */
    public function getNbreLigne()
    {
        return $this->nbreLigne;
    }

    /**
     * Set codeOpInt
     *
     * @param string $codeOpInt
     * @return TypeOperation
     */
    public function setCodeOpInt($codeOpInt)
    {
        $this->codeOpInt = $codeOpInt;
    
        return $this;
    }

    /**
     * Get codeOpInt
     *
     * @return string 
     */
    public function getCodeOpInt()
    {
        return $this->codeOpInt;
    }

    /**
     * Add operations
     *
     * @param \admin\EconomatBundle\Entity\Operation $operations
     * @return TypeOperation
     */
    public function addOperationcaiss(\admin\EconomatBundle\Entity\Operation $operations)
    {
        $this->operations[] = $operations;
    
        return $this;
    }

    /**
     * Remove operations
     *
     * @param \admin\EconomatBundle\Entity\Operation $operations
     */
    public function removeOperationcaiss(\admin\EconomatBundle\Entity\Operation $operations)
    {
        $this->operations->removeElement($operations);
    }

    /**
     * Get operations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOperationcaisses()
    {
        return $this->operations;
    }

    /**
     * Add schemas
     *
     * @param \admin\EconomatBundle\Entity\Schema $schemas
     * @return TypeOperation
     */
    public function addSchema(\admin\EconomatBundle\Entity\Schema $schemas)
    {
        $this->schemas[] = $schemas;
    
        return $this;
    }

    /**
     * Remove schemas
     *
     * @param \admin\EconomatBundle\Entity\Schema $schemas
     */
    public function removeSchema(\admin\EconomatBundle\Entity\Schema $schemas)
    {
        $this->schemas->removeElement($schemas);
    }

    /**
     * Get schemas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSchemas()
    {
        return $this->schemas;
    }

    /**
     * Add operations
     *
     * @param \admin\EconomatBundle\Entity\Operation $operations
     * @return TypeOperation
     */
    public function addOperation(\admin\EconomatBundle\Entity\Operation $operations)
    {
        $this->operations[] = $operations;
    
        return $this;
    }

    /**
     * Remove operations
     *
     * @param \admin\EconomatBundle\Entity\Operation $operations
     */
    public function removeOperation(\admin\EconomatBundle\Entity\Operation $operations)
    {
        $this->operations->removeElement($operations);
    }

    /**
     * Get operations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOperations()
    {
        return $this->operations;
    }
  

    /**
     * Set etablissement
     *
     * @param \admin\ScolariteBundle\Entity\Etablissement $etablissement
     *
     * @return TypeOperation
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
