<?php

/**
 * Définition de l'entité HistoDateOuverture
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
 * HistoDateOuverture.
 *
 * @ORM\Table(name="histodateouverture")
 * @ORM\Entity(repositoryClass="admin\EconomatBundle\Entity\HistoDateOuvertureRepository")
 */
class HistoDateOuverture
{
    public function __construct()
    {
        $this->montantHtAchat = 0;
        $this->montantHtVente = 0;
    }

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    
    /**
     * @var date
     *
     * @ORM\Column(name="date_jour", type="date")
     */
    private $dateJour;

    
    /**
     * @var date
     *
     * @ORM\Column(name="date_ouverte", type="date")
     */
    private $dateOuverte;
    
      


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
     * Set dateJour
     *
     * @param \DateTime $dateJour
     *
     * @return HistoDateOuverture
     */
    public function setDateJour($dateJour)
    {
        $this->dateJour = $dateJour;

        return $this;
    }

    /**
     * Get dateJour
     *
     * @return \DateTime
     */
    public function getDateJour()
    {
        return $this->dateJour;
    }

    /**
     * Set dateOuverte
     *
     * @param \DateTime $dateOuverte
     *
     * @return HistoDateOuverture
     */
    public function setDateOuverte($dateOuverte)
    {
        $this->dateOuverte = $dateOuverte;

        return $this;
    }

    /**
     * Get dateOuverte
     *
     * @return \DateTime
     */
    public function getDateOuverte()
    {
        return $this->dateOuverte;
    }
}
