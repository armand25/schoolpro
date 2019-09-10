<?php

namespace admin\MessagerieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use admin\MessagerieBundle\Types\TypeEnvoi;
use admin\UserBundle\Types\TypeEtat;

/**
 * admin\MessagerieBundle\Entity.
 *
 * @ORM\Table(name="message")
 * @ORM\Entity(repositoryClass="admin\MessagerieBundle\Entity\MessageRepository")
 */
class Message
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="objet",type="string",length=100)
     */
    private $objet;

    /**
     * @var text
     * @ORM\Column(name="contenu",type="text",nullable=true)
     */
    private $contenu;

    /**
     * @var text
     * @ORM\Column(name="contenu_claire",type="text",nullable=true)
     */
    private $contenuClaire;

    /**
     * @var int
     *
     * @ORM\Column(name="etat", type="smallint")
     */
    private $etat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_envoi", type="datetime")
     */
    private $dateEnvoi;

    /**
     * @var bool
     *
     * @ORM\Column(name="cloturer", type="boolean")
     */
    private $cloturer;

    /**
     * @var int
     * @ORM\Column(name="type_envoi", type="integer") 
     */
    private $typeEnvoi;

    /**
     * @var string
     * @ORM\Column(name="code_fil",type="string",length=20, nullable=true)
     */
    private $codeFil;

    /**
     * @var Utulisateur
     * @ORM\ManyToOne(targetEntity="admin\UserBundle\Entity\Utilisateur", inversedBy="messagesEnvois")
     * @ORM\JoinColumn(nullable=true, unique=false)
     */
    private $utilisateur;

    /**
     * @var Abonne
     * @ORM\ManyToOne(targetEntity="admin\UserBundle\Entity\Abonne", inversedBy="messagesEnvois")
     * @ORM\JoinColumn(nullable=true, unique=false)
     */
    private $abonne;

    /**
     * @var ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="admin\MessagerieBundle\Entity\Envoi", mappedBy="message")
     */
    private $envois;

    /**
     * @var Message
     * 
     * @ORM\ManyToOne(targetEntity="admin\MessagerieBundle\Entity\Message")
     */
    private $messageTransfere;

    /**
     * @var Message
     * 
     * @ORM\ManyToOne(targetEntity="admin\MessagerieBundle\Entity\Message")
     */
    private $messageRepondu;

    public function __construct()
    {
        $this->envois = new ArrayCollection();
        $this->typeEnvoi = TypeEnvoi::ABONNE_UTILISATEUR;
        $this->dateEnvoi = new \DateTime();
        $this->etat = TypeEtat::ACTIF;
        $this->cloturer = false;
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
     * Set objet.
     *
     * @param string $objet
     *
     * @return Message
     */
    public function setObjet($objet)
    {
        $this->objet = $objet;

        return $this;
    }

    /**
     * Get objet.
     *
     * @return string
     */
    public function getObjet()
    {
        return $this->objet;
    }

    /**
     * Set contenu.
     *
     * @param string $contenu
     *
     * @return Message
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu.
     *
     * @return string
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set contenuClaire.
     *
     * @param string $contenuClaire
     *
     * @return Message
     */
    public function setContenuClaire($contenuClaire)
    {
        $this->contenuClaire = $contenuClaire;

        return $this;
    }

    /**
     * Get contenuClaire.
     *
     * @return string
     */
    public function getContenuClaire()
    {
        return $this->contenuClaire;
    }

    /**
     * Set etat.
     *
     * @param int $etat
     *
     * @return Message
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat.
     *
     * @return int
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Set dateEnvoi.
     *
     * @param \DateTime $dateEnvoi
     *
     * @return Message
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

    /**
     * Set cloturer.
     *
     * @param bool $cloturer
     *
     * @return Message
     */
    public function setCloturer($cloturer)
    {
        $this->cloturer = $cloturer;

        return $this;
    }

    /**
     * Get cloturer.
     *
     * @return bool
     */
    public function getCloturer()
    {
        return $this->cloturer;
    }

    /**
     * Set typeEnvoi.
     *
     * @param int $typeEnvoi
     *
     * @return Message
     */
    public function setTypeEnvoi($typeEnvoi)
    {
        $this->typeEnvoi = $typeEnvoi;

        return $this;
    }

    /**
     * Get typeEnvoi.
     *
     * @return int
     */
    public function getTypeEnvoi()
    {
        return $this->typeEnvoi;
    }

    /**
     * Set utilisateur.
     *
     * @param \admin\UserBundle\Entity\Utilisateur $utilisateur
     *
     * @return Message
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
     * @return Message
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
     * Add envois.
     *
     * @param \admin\MessagerieBundle\Entity\Envoi $envois
     *
     * @return Message
     */
    public function addEnvois(\admin\MessagerieBundle\Entity\Envoi $envois)
    {
        $this->envois[] = $envois;

        return $this;
    }

    /**
     * Remove envois.
     *
     * @param \admin\MessagerieBundle\Entity\Envoi $envois
     */
    public function removeEnvois(\admin\MessagerieBundle\Entity\Envoi $envois)
    {
        $this->envois->removeElement($envois);
    }

    /**
     * Get envois.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEnvois()
    {
        return $this->envois;
    }

    /**
     * Set messageTransfere.
     *
     * @param \admin\MessagerieBundle\Entity\Message $messageTransfere
     *
     * @return Message
     */
    public function setMessageTransfere(\admin\MessagerieBundle\Entity\Message $messageTransfere = null)
    {
        $this->messageTransfere = $messageTransfere;

        return $this;
    }

    /**
     * Get messageTransfere.
     *
     * @return \admin\MessagerieBundle\Entity\Message
     */
    public function getMessageTransfere()
    {
        return $this->messageTransfere;
    }

    /**
     * Set messageRepondu.
     *
     * @param \admin\MessagerieBundle\Entity\Message $messageRepondu
     *
     * @return Message
     */
    public function setMessageRepondu(\admin\MessagerieBundle\Entity\Message $messageRepondu = null)
    {
        $this->messageRepondu = $messageRepondu;

        return $this;
    }

    /**
     * Get messageRepondu.
     *
     * @return \admin\MessagerieBundle\Entity\Message
     */
    public function getMessageRepondu()
    {
        return $this->messageRepondu;
    }

    /**
     * Set codeFil.
     *
     * @param string $codeFil
     *
     * @return Message
     */
    public function setCodeFil($codeFil)
    {
        $this->codeFil = $codeFil;

        return $this;
    }

    /**
     * Get codeFil.
     *
     * @return string
     */
    public function getCodeFil()
    {
        return $this->codeFil;
    }
}
