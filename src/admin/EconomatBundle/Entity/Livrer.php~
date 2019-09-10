<?php

namespace admin\EconomatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Livrer.
 *
 * @ORM\Table(name="livrer")
 * @ORM\Entity(repositoryClass="admin\EconomatBundle\Entity\LivrerRepository")
 */
class Livrer
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
     * @var integer
     *
     * @ORM\Column(name="nbre_libre", type="integer")
     */
    private $nbreLivre;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbre_reste", type="integer")
     */
    private $nbreReste;
    
    /**
     * @var date
     *
     * @ORM\Column(name="date_livraison", type="date")
     */
    private $dateLivraison;    
    
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="ref_bon_livraison", type="string", length=50)
     */
    private $refBonLivraison;
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="ref_bon_reception", type="string", length=50)
     */
    private $refBonReception;
    

    /**
     * @var int
     *
     * @ORM\Column(name="etatLivraison", type="smallint")
     */
    private $etatLivraison;


    /**
     * @var LigneCommande  lignecommande
     * @ORM\ManyToOne(targetEntity="admin\EconomatBundle\Entity\LigneCommande", inversedBy="livrers")
     * @ORM\JoinColumn(nullable=true)
     */
    private $lignecommande; 
 
    /**
     * @var Entrepot  entrepot
     * @ORM\ManyToOne(targetEntity="admin\EconomatBundle\Entity\Entrepot", inversedBy="livrers")
     * @ORM\JoinColumn(nullable=true)
     */
    private $entrepot;
    

    public function __construct()
    {
        // $this->actions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->livrers = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nbreLivre
     *
     * @param integer $nbreLivre
     * @return Livrer
     */
    public function setNbreLivre($nbreLivre)
    {
        $this->nbreLivre = $nbreLivre;
    
        return $this;
    }

    /**
     * Get nbreLivre
     *
     * @return integer 
     */
    public function getNbreLivre()
    {
        return $this->nbreLivre;
    }

    /**
     * Set nbreReste
     *
     * @param integer $nbreReste
     * @return Livrer
     */
    public function setNbreReste($nbreReste)
    {
        $this->nbreReste = $nbreReste;
    
        return $this;
    }

    /**
     * Get nbreReste
     *
     * @return integer 
     */
    public function getNbreReste()
    {
        return $this->nbreReste;
    }

    /**
     * Set dateLivraison
     *
     * @param \DateTime $dateLivraison
     * @return Livrer
     */
    public function setDateLivraison($dateLivraison)
    {
        $this->dateLivraison = $dateLivraison;
    
        return $this;
    }

    /**
     * Get dateLivraison
     *
     * @return \DateTime 
     */
    public function getDateLivraison()
    {
        return $this->dateLivraison;
    }

    /**
     * Set refBonLivraison
     *
     * @param string $refBonLivraison
     * @return Livrer
     */
    public function setRefBonLivraison($refBonLivraison)
    {
        $this->refBonLivraison = $refBonLivraison;
    
        return $this;
    }

    /**
     * Get refBonLivraison
     *
     * @return string 
     */
    public function getRefBonLivraison()
    {
        return $this->refBonLivraison;
    }

    /**
     * Set refBonReception
     *
     * @param string $refBonReception
     * @return Livrer
     */
    public function setRefBonReception($refBonReception)
    {
        $this->refBonReception = $refBonReception;
    
        return $this;
    }

    /**
     * Get refBonReception
     *
     * @return string 
     */
    public function getRefBonReception()
    {
        return $this->refBonReception;
    }

    /**
     * Set etatLivraison
     *
     * @param integer $etatLivraison
     * @return Livrer
     */
    public function setEtatLivraison($etatLivraison)
    {
        $this->etatLivraison = $etatLivraison;
    
        return $this;
    }

    /**
     * Get etatLivraison
     *
     * @return integer 
     */
    public function getEtatLivraison()
    {
        return $this->etatLivraison;
    }

    /**
     * Set lignecommande
     *
     * @param \admin\EconomatBundle\Entity\LigneCommande $lignecommande
     * @return Livrer
     */
    public function setLignecommande(\admin\EconomatBundle\Entity\LigneCommande $lignecommande)
    {
        $this->lignecommande = $lignecommande;
    
        return $this;
    }

    /**
     * Get lignecommande
     *
     * @return \admin\EconomatBundle\Entity\LigneCommande 
     */
    public function getLignecommande()
    {
        return $this->lignecommande;
    }

    /**
     * Set entrepot
     *
     * @param \admin\EconomatBundle\Entity\Entrepot $entrepot
     * @return Livrer
     */
    public function setEntrepot(\admin\EconomatBundle\Entity\Entrepot $entrepot)
    {
        $this->entrepot = $entrepot;
    
        return $this;
    }

    /**
     * Get entrepot
     *
     * @return \admin\EconomatBundle\Entity\Entrepot 
     */
    public function getEntrepot()
    {
        return $this->entrepot;
    }
}
