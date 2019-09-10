<?php

namespace admin\MessagerieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use admin\MessagerieBundle\Types\TypeStatutEnvoi;

/**
 * admin\MessagerieBundle\Entity.
 *
 * @ORM\Table(name="envoi")
 * @ORM\Entity(repositoryClass="admin\MessagerieBundle\Entity\EnvoiRepository")
 */
class Envoi
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var int
     * @ORM\Column(name="statut", type="integer")  
     */
    private $statut;

    /**
     * @var bool
     *
     * @ORM\Column(name="affichable", type="boolean")
     */
    private $affichable;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_envoi", type="datetime")
     */
    private $dateEnvoi;

    /**
     * @var Message
     * 
     * @ORM\ManyToOne(targetEntity="admin\MessagerieBundle\Entity\Message", inversedBy="envois")
     */
    private $message;

    /**
     * @var Utulisateur
     * @ORM\ManyToOne(targetEntity="admin\UserBundle\Entity\Utilisateur")
     * @ORM\JoinColumn(nullable=true)
     */
    private $utilisateur;

    /**
     * @var Abonne
     * @ORM\ManyToOne(targetEntity="admin\UserBundle\Entity\Abonne")
     * @ORM\JoinColumn(nullable=true)
     */
    private $abonne;

    public function __construct()
    {
        $this->statut = TypeStatutEnvoi::MSG_NON_LU;
        $this->affichable = true;
        $this->dateEnvoi = new \DateTime();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set statut.
     *
     * @param int $statut
     *
     * @return Envoi
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut.
     *
     * @return int
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set message.
     *
     * @param \admin\MessagerieBundle\Entity\Message $message
     *
     * @return Envoi
     */
    public function setMessage(\admin\MessagerieBundle\Entity\Message $message = null)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message.
     *
     * @return \admin\MessagerieBundle\Entity\Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set utilisateur.
     *
     * @param \admin\UserBundle\Entity\Utilisateur $utilisateur
     *
     * @return Envoi
     */
    public function setUtilisateur(\admin\UserBundle\Entity\Utilisateur $utilisateur = null)
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * Get utilisateur.
     *
     * @return \admin\UserBundle\Entity\Utilisateur
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * Set abonne.
     *
     * @param \admin\UserBundle\Entity\Abonne $abonne
     *
     * @return Envoi
     */
    public function setAbonne(\admin\UserBundle\Entity\Abonne $abonne = null)
    {
        $this->abonne = $abonne;

        return $this;
    }

    /**
     * Get abonne.
     *
     * @return \admin\UserBundle\Entity\Abonne
     */
    public function getAbonne()
    {
        return $this->abonne;
    }

    /**
     * Set affichable.
     *
     * @param bool $affichable
     *
     * @return Envoi
     */
    public function setAffichable($affichable)
    {
        $this->affichable = $affichable;

        return $this;
    }

    /**
     * Get affichable.
     *
     * @return bool
     */
    public function getAffichable()
    {
        return $this->affichable;
    }

    /**
     * Set dateEnvoi.
     *
     * @param \DateTime $dateEnvoi
     *
     * @return Envoi
     */
    public function setDateEnvoi($dateEnvoi)
    {
        $this->dateEnvoi = $dateEnvoi;

        return $this;
    }

    /**
     * Get dateEnvoi.
     *
     * @return \DateTime
     */
    public function getDateEnvoi()
    {
        return $this->dateEnvoi;
    }
}
