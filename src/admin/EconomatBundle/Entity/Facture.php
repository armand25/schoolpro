<?php

namespace admin\EconomatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Facture.
 *
 * @ORM\Table(name="facture")
 * @ORM\Entity(repositoryClass="admin\EconomatBundle\Entity\FactureRepository")
 */
class Facture
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="code_facture", type="string", length=50)
     */
    private $codeFacture;

    /**
     * @var date
     *
     * @ORM\Column(name="date_publication", type="date")
     */
    private $datePublication;    
    
    
    /**
     * @var int
     *
     * @ORM\Column(name="etat_ville", type="smallint")
     */
    private $etatFacture;
    
    /**
     * @var int
     *
     * @ORM\Column(name="type_ville", type="smallint")
     */
    private $typeFacture;


 

 

    public function __construct()
    {
        $this->etatFacture = 1;
    } 




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
     * Set codeFacture
     *
     * @param string $codeFacture
     *
     * @return Facture
     */
    public function setCodeFacture($codeFacture)
    {
        $this->codeFacture = $codeFacture;

        return $this;
    }

    /**
     * Get codeFacture
     *
     * @return string
     */
    public function getCodeFacture()
    {
        return $this->codeFacture;
    }

    /**
     * Set datePublication
     *
     * @param \DateTime $datePublication
     *
     * @return Facture
     */
    public function setDatePublication($datePublication)
    {
        $this->datePublication = $datePublication;

        return $this;
    }

    /**
     * Get datePublication
     *
     * @return \DateTime
     */
    public function getDatePublication()
    {
        return $this->datePublication;
    }

    /**
     * Set etatFacture
     *
     * @param integer $etatFacture
     *
     * @return Facture
     */
    public function setEtatFacture($etatFacture)
    {
        $this->etatFacture = $etatFacture;

        return $this;
    }

    /**
     * Get etatFacture
     *
     * @return integer
     */
    public function getEtatFacture()
    {
        return $this->etatFacture;
    }

    /**
     * Set typeFacture
     *
     * @param integer $typeFacture
     *
     * @return Facture
     */
    public function setTypeFacture($typeFacture)
    {
        $this->typeFacture = $typeFacture;

        return $this;
    }

    /**
     * Get typeFacture
     *
     * @return integer
     */
    public function getTypeFacture()
    {
        return $this->typeFacture;
    }
}
