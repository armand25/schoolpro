<?php

/**
 * Définition de l'entité Entrepot
 * Représente le type de champ à presenter à l'utilisateur par rapport au champ choisi
 * nous avons entre autre (Text, bouton radio etc ...)-.
 * 
 * @author TEVI Fessou Atassé <tevi.armand@gmail.com> 
 */
namespace admin\EconomatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Entrepot.
 *
 * @ORM\Table(name="Entrepot")
 * @ORM\Entity(repositoryClass="admin\EconomatBundle\Entity\EntrepotRepository")
 */
class Entrepot
{


    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

 /**
     * @var string
     *
     * @ORM\Column(name="code_entrepot", type="string", length=50)
     * @Assert\Length(max=50)
     */
    private $codeEntrepot;	
	
    /**
     * @var string
     *
     * @ORM\Column(name="nom_entrepot", type="string", length=50)
     * @Assert\Length(max=50)
     * @Assert\NotBlank(message="Libellé modéle requis !")
     */
    private $nomEntrepot;
    
    /**
     * @var string
     *
     * @ORM\Column(name="contact_entrepot", type="string")
     */
    private $contactEntrepot;
    
    /**
     * @var string
     *
     * @ORM\Column(name="adresse_entrepot", type="string")
     */
    private $adresseEntrepot;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="seuil_entrepot", type="integer")
     */
    private $seuilEntrepot;    
    
    /**
     * @var date
     *
     * @ORM\Column(name="date_publication", type="date")
     */
    private $datePublication;
    
    
    /**
     * @var date
     *
     * @ORM\Column(name="date_modification", type="date")
     */
    private $dateModification;
    
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id_auteur", type="integer")
     */
    private $idAuteur;  
    
    
    /**
     * @var int
     *
     * @ORM\Column(name="etat_entrepot", type="integer")
     */
    private $etatEntrepot;

    /**
     * @var Ville  ville
     * @ORM\ManyToOne(targetEntity="admin\EconomatBundle\Entity\Ville", inversedBy="entrepots")
     * @ORM\JoinColumn(nullable=true)
     */
    private $ville;  
    
    /**
     * @var ArrayCollection livrers
     * @ORM\OneToMany(targetEntity="admin\EconomatBundle\Entity\Livrer", cascade={"persist", "remove"}, mappedBy="entrepot")
     */
    private $livrers;    
    
        
    /**
     * Constructor
     */
    public function __construct()
    {
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
     * Set codeEntrepot
     *
     * @param string $codeEntrepot
     *
     * @return Entrepot
     */
    public function setCodeEntrepot($codeEntrepot)
    {
        $this->codeEntrepot = $codeEntrepot;

        return $this;
    }

    /**
     * Get codeEntrepot
     *
     * @return string
     */
    public function getCodeEntrepot()
    {
        return $this->codeEntrepot;
    }

    /**
     * Set nomEntrepot
     *
     * @param string $nomEntrepot
     *
     * @return Entrepot
     */
    public function setNomEntrepot($nomEntrepot)
    {
        $this->nomEntrepot = $nomEntrepot;

        return $this;
    }

    /**
     * Get nomEntrepot
     *
     * @return string
     */
    public function getNomEntrepot()
    {
        return $this->nomEntrepot;
    }

    /**
     * Set contactEntrepot
     *
     * @param string $contactEntrepot
     *
     * @return Entrepot
     */
    public function setContactEntrepot($contactEntrepot)
    {
        $this->contactEntrepot = $contactEntrepot;

        return $this;
    }

    /**
     * Get contactEntrepot
     *
     * @return string
     */
    public function getContactEntrepot()
    {
        return $this->contactEntrepot;
    }

    /**
     * Set adresseEntrepot
     *
     * @param string $adresseEntrepot
     *
     * @return Entrepot
     */
    public function setAdresseEntrepot($adresseEntrepot)
    {
        $this->adresseEntrepot = $adresseEntrepot;

        return $this;
    }

    /**
     * Get adresseEntrepot
     *
     * @return string
     */
    public function getAdresseEntrepot()
    {
        return $this->adresseEntrepot;
    }

    /**
     * Set seuilEntrepot
     *
     * @param integer $seuilEntrepot
     *
     * @return Entrepot
     */
    public function setSeuilEntrepot($seuilEntrepot)
    {
        $this->seuilEntrepot = $seuilEntrepot;

        return $this;
    }

    /**
     * Get seuilEntrepot
     *
     * @return integer
     */
    public function getSeuilEntrepot()
    {
        return $this->seuilEntrepot;
    }

    /**
     * Set datePublication
     *
     * @param \DateTime $datePublication
     *
     * @return Entrepot
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
     * Set dateModification
     *
     * @param \DateTime $dateModification
     *
     * @return Entrepot
     */
    public function setDateModification($dateModification)
    {
        $this->dateModification = $dateModification;

        return $this;
    }

    /**
     * Get dateModification
     *
     * @return \DateTime
     */
    public function getDateModification()
    {
        return $this->dateModification;
    }

    /**
     * Set idAuteur
     *
     * @param integer $idAuteur
     *
     * @return Entrepot
     */
    public function setIdAuteur($idAuteur)
    {
        $this->idAuteur = $idAuteur;

        return $this;
    }

    /**
     * Get idAuteur
     *
     * @return integer
     */
    public function getIdAuteur()
    {
        return $this->idAuteur;
    }

    /**
     * Set etatEntrepot
     *
     * @param integer $etatEntrepot
     *
     * @return Entrepot
     */
    public function setEtatEntrepot($etatEntrepot)
    {
        $this->etatEntrepot = $etatEntrepot;

        return $this;
    }

    /**
     * Get etatEntrepot
     *
     * @return integer
     */
    public function getEtatEntrepot()
    {
        return $this->etatEntrepot;
    }

    /**
     * Set ville
     *
     * @param \admin\EconomatBundle\Entity\Ville $ville
     *
     * @return Entrepot
     */
    public function setVille(\admin\EconomatBundle\Entity\Ville $ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return \admin\EconomatBundle\Entity\Ville
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Add livrer
     *
     * @param \admin\EconomatBundle\Entity\Livrer $livrer
     *
     * @return Entrepot
     */
    public function addLivrer(\admin\EconomatBundle\Entity\Livrer $livrer)
    {
        $this->livrers[] = $livrer;

        return $this;
    }

    /**
     * Remove livrer
     *
     * @param \admin\EconomatBundle\Entity\Livrer $livrer
     */
    public function removeLivrer(\admin\EconomatBundle\Entity\Livrer $livrer)
    {
        $this->livrers->removeElement($livrer);
    }

    /**
     * Get livrers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLivrers()
    {
        return $this->livrers;
    }
}
