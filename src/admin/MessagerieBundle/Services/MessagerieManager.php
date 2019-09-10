<?php

/**
 * Description of AuthManager.
 *
 * @author Aldji
 */
namespace admin\MessagerieBundle\Services;

use Doctrine\ORM\EntityManager;
use admin\MessagerieBundle\Entity\Message;
use admin\MessagerieBundle\Types\TypeEnvoi;
use admin\MessagerieBundle\Entity\Envoi;
use admin\MessagerieBundle\Types\TypeStatutEnvoi;
use admin\UserBundle\Entity\Abonne;
use admin\UserBundle\Types\TypeEtat;

/**
 * MessagerieManager.
 *
 * Cette classe gère tout ce qui concerne le mailling
 */
class MessagerieManager
{
    /*
     *
     * @var \Doctrine\ORM\EntityManager $em : Renseigne l'entité ou l'objet en session
     */
    protected $em;

    /*
     * 
     * @var string  $userBundle
     * Nom du Bundle
     */
    private $userBundle = 'adminUserBundle:';

    /*
     * 
     * @var string  $messagerieBundle
     * Nom du Bundle
     */
    private $messagerieBundle = 'adminMessagerieBundle:';

    /*
     * __construct
     * Methode constructeur de la classe
     * 
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /*
     * Clôture un  ticket
     * 
     * @param Message $message
     */
    public function cloturerTicket(Message $message)
    {
        $messages = $this->getTickeMessage($message->getCodeFil());
        foreach ($messages as $m) {
            $m->setCloturer(true);
            $this->em->flush();
        }
    }

     /*
     * Intique si l'utilisateur courant peut cloturer un message ou pas.
     * Il faudrait k l'utilisateur courant soit l'émetteur du message et en plus le message doit etre le 1er message du ticket
     * 
     * @param Message $message
     * @param type $sessionData
     * @return boolean
     */
    public function canCloturer(Message $message, $sessionData)
    {
        $rep = false;
        $abonne = $message->getAbonne();
        $utilisateur = $message->getUtilisateur();
        if (($sessionData['isUser']) && ($utilisateur != null)) {
            if ($utilisateur->getId() == $sessionData['id']) {
                $rep = true;
            }
        } elseif (($sessionData['isAbonne']) && ($abonne != null)) {
            if ($abonne->getId() == $sessionData['id']) {
                $rep = true;
            }
        }

        return $rep;
    }

    /*
     * Retourne un tableau des messages ayant le même codeFil, c'est à dire un fil de discussion
     * 
     * @param string $codeTicket
     * @return array
     */
    public function getTickeMessage($codeTicket)
    {
        return $this->em->getRepository($this->messagerieBundle.'Message')->getTickeMessage($codeTicket);
    }

    /*
     * Retourne le premier message d'un ticket (fil de discussion)
     * @param type $codeTicket
     * @return type
     */
    public function getMessageDebutTiket($codeTicket)
    {
        $messages = $this->getTickeMessage($codeTicket);
        foreach ($messages as $m) {
            if ($m->getMessageRepondu() == null) {
                return $m;
            }
        }

        return;
    }

    /*
     * Retourne un tableau contenant les informations suivantes relatives à un message
     * fil: tableau contenanu les messages (envoi) du fil de discution  concerant l'utilisateur courant
     * filHasNonLu : indique si le fil a un message non lu.
     * 
     * @param \admin\MessagerieBundle\Entity\Message $message
     * @return type
     */
    public function getFilMessage(Message $message, $sessionData)
    {
        $fil = $this->em->getRepository($this->messagerieBundle.'Envoi')->getFilMessage($message, $sessionData);

        return $fil;
    }

    /*
     * Envoi de message aux abonnés
     * 
     * @param Message $message
     * @param type $tabAbonne
     * @param type $tabAdmin
     * @param type $sessionData
     */
    public function sendMessageToUser(Message $message, $tabAdmin, $sessionData)
    {
        $utilisateur = $this->em->getRepository($this->userBundle.'Utilisateur')->find($sessionData['id']);
        $message->setTypeEnvoi(TypeEnvoi::UTILISATEUR_UTILISATEUR);
        $message->setUtilisateur($utilisateur);
        $this->em->persist($message);
        $this->em->flush();

        foreach ($tabAdmin as $unAdmin) {
            $envoi = new Envoi();
            $envoi->setDateEnvoi($message->getDateEnvoi());
            $envoi->setUtilisateur($unAdmin);
            $envoi->setMessage($message);
            if ($unAdmin->getId() != $sessionData['id']) {
            } else {
                /*
                 * Si c'est c'est l'utilisateur courant, cela suppose qu'il a déjà lu le message. On met donc l'etat de l'envoi à lu pour que le 
                 * présent message ne fausse pas le calcul du nombre de message non lu
                 */
                $envoi->setStatut(TypeStatutEnvoi::MSG_LU);

                if ($message->getMessageRepondu() == null) {
                    /*
                     * Si ce n'est pas une réponse à un autre message, ce envoi ne sera pas affiché
                     */
                    $envoi->setAffichable(false);
                }
            }
            $this->em->persist($envoi);
            $this->em->flush();
        }
    }

    /*
     * Envoi de message aux abonnés
     * 
     * @param Message $message
     * @param type $tabAbonne
     * @param type $tabAdmin
     * @param type $sessionData
     */
    public function sendMessageToAbonne(Message $message, $tabAbonne, $tabAdmin, $sessionData)
    {

        /*
         * Récupération de l'utilisateur courant
         */
        $utilisateur = $this->em->getRepository($this->userBundle.'Utilisateur')->find($sessionData['id']);
        /*
         * Le message à envoyer est persisté
         */
        $message->setTypeEnvoi(TypeEnvoi::UTILISATEUR_ABONNE);
        $message->setUtilisateur($utilisateur);
        $this->em->persist($message);
        $this->em->flush();

        /*
         * Le message est envoyé à tous les abonnés
         */
        foreach ($tabAbonne as $abonne) {
            $envoi = new Envoi();
            $envoi->setDateEnvoi($message->getDateEnvoi());
            $envoi->setAbonne($abonne);
            $envoi->setMessage($message);
            $this->em->persist($envoi);
            $this->em->flush();
        }

        /*
         * Envoi du message aux admin
         */
        foreach ($tabAdmin as $unAdmin) {
            $envoi = new Envoi();
            $envoi->setDateEnvoi($message->getDateEnvoi());
            $envoi->setUtilisateur($unAdmin);
            $envoi->setMessage($message);
            if ($unAdmin->getId() == $sessionData['id']) {
                /*
                 * Si c'est c'est l'utilisateur courant, on suppose qu'il a déjà lu le message. On met donc l'etat de l'envoi à lu pour que le 
                 * présent message ne fausse pas le calcul du nombre de message non lu
                 */
                $envoi->setStatut(TypeStatutEnvoi::MSG_LU);
                if ($message->getMessageRepondu() == null) {
                    /*
                     * Si ce n'est pas une réponse à un autre message, (Il s'agit du premier message du ticket, autremt di l'ouverture d'un nouveau ticket)
                     *  ce envoi ne sera pas affiché à son émetteur en tant k message reçu
                     */
                    $envoi->setAffichable(false);
                }
            }
            $this->em->persist($envoi);
            $this->em->flush();
        }
    }

    /*
     * Envoi de message par un abonné
     * $tabAbonne : tableau contenant les destinaires abonnés au cas ou c'est une réponse à un mail
     * $tabAdmin : tableau contenant les destinaires admin
     * $sessionData :  information de session de l'abonné courant
     * 
     * @param Message $message
     * @param type $tabAbonne
     * @param type $tabAdmin
     * @param type $sessionData
     */
    public function abonneSendMessage(Message $message, $tabAbonne, $tabAdmin, $sessionData)
    {
        /*
         * Récupération de l'utilisateur courant
         */
        $courantAbonne = $this->em->getRepository($this->userBundle.'Abonne')->find($sessionData['id']);
        if ($courantAbonne == null) {
            return;
        }
        /*
         * Le message à envoyer est persisté
         */
        $message->setTypeEnvoi(TypeEnvoi::ABONNE_UTILISATEUR);
        $message->setAbonne($courantAbonne);
        $this->em->persist($message);
        $this->em->flush();

        /*
         * Envoi du message au abonnés
         */
        foreach ($tabAbonne as $abonne) {
            $envoi = new Envoi();
            $envoi->setDateEnvoi($message->getDateEnvoi());
            $envoi->setAbonne($abonne);
            $envoi->setMessage($message);
            if ($abonne->getId() == $sessionData['id']) {
                /*
                 * Si c'est c'est abonné courant, cela suppose qu'il a déjà lu le message. On met donc l'etat de l'envoi à lu pour que le 
                 * présent message ne fausse pas le calcul du nombre de message non lu
                 */
                $envoi->setStatut(TypeStatutEnvoi::MSG_LU);
                if ($message->getMessageRepondu() == null) {
                    $envoi->setAffichable(false);
                }
            }
            $this->em->persist($envoi);
            $this->em->flush();
        }

        /*
         * envoi du message aux admins
         */
        foreach ($tabAdmin as $unAdmin) {
            $envoi = new Envoi();
            $envoi->setDateEnvoi($message->getDateEnvoi());
            $envoi->setUtilisateur($unAdmin);
            $envoi->setMessage($message);
            $this->em->persist($envoi);
            $this->em->flush();
        }
    }

    /*
     * Retourne tous les utilisateurs pouvant etre choisit comme destinaire d'un message
     * $iUtilisateurCourant : identofiant de l'utilisateur (admin) courant. Vaut 0 pour les abonnés. Ce utilisateur est exclu de la 
     * liste des destinataires au cas ou c'est un nouveau messsage kon veu ecrire ( pas la réponse à un autre msg)  
     * $sesseionData : information de session
     * $codeProfil : Le code du profil des utilisateurs qu'on souhaite recuperer comme destinataire
     * 
     * @param type $iUtilisateurCourant
     * @param type $codeProfilUtilisateur
     * @return type
     */
    public function getAllUserMessage($iUtilisateurCourant, $codeProfilUtilisateur = '', $etabl = 1)
    {
        $qb = $this->em->getRepository($this->userBundle.'Utilisateur')->createQueryBuilder('a')
                ->leftJoin('a.etablissement', 'e')
                ->where('a.etat !=:etatU')
                ->andWhere('a.siAdminGeneral !=:siAdminGeneral')
                ->andWhere('e.id =:id')
                ->setParameters(array('etatU'=> TypeEtat::SUPPRIME,'id'=> $etabl,'siAdminGeneral'=>TypeEtat::ACTIF));
        if ($iUtilisateurCourant > 0) {
            $qb->andWhere('a.id !=:idUser')
                    ->setParameter('idUser', $iUtilisateurCourant);
        }
        $qb->addSelect('p')
                ->leftJoin('a.profil', 'p')
                ->andWhere('p.etat !=:etatP ')
                ->setParameter('etatP', TypeEtat::SUPPRIME);
//                ->andWhere('p.typeProfil ='.\admin\UserBundle\Types\TypeProfil::PROFIL_UTILISATEUR);
        //var_dump($codeProfilUtilisateur);exit;
        if($codeProfilUtilisateur==4){
            $codeProfilUtilisateur =1;
        }else{
            $codeProfilUtilisateur='';
        }
        if ($codeProfilUtilisateur != '') {
            $qb->andWhere('p.id =:codeProfil')
                    ->setParameter('codeProfil', $codeProfilUtilisateur);
        }
        $qb->orderBy('a.nom')
        ;

        return $qb->getQuery()->getResult();
    }

    /*
     * Retourne tous les abonnés pouvant etre choisit comme destinaire d'un message
     * 
     * @return type
     */
    public function getAllAbonneMessage()
    {
        $qb = $this->em->getRepository($this->userBundle.'Abonne')->createQueryBuilder('a')
                 ->where('a.etat !=:etatU')
                ->setParameter('etatU', TypeEtat::SUPPRIME)
                ->addSelect('p')
                ->leftJoin('a.profil', 'p')
                ->andWhere('p.etat !=:etatP ')
                ->setParameter('etatP', TypeEtat::SUPPRIME)
                ->andWhere('p.typeProfil ='.\admin\UserBundle\Types\TypeProfil::PROFIL_ABONNE)
                ->orderBy('a.nom')
        ;

        return $qb->getQuery()->getResult();
    }

    /*
     * Retourne un tableau contenant les destinaires  abonne d'un message
     * 
     * @param \admin\MessagerieBundle\Entity\Message $message
     * @return type
     */
    public function getDestinairesOfMessage(Message $message)
    {
        $query = $this->em->getRepository($this->messagerieBundle.'Envoi')->getDestinairesOfMessage($message);
        $tabIdAbonne = array();
        $tabIdAdmin = array();

//        $abonneEmetteur = $message->getAbonne();
//        $utilisateurEmetteur = $message->getUtilisateur();

        foreach ($query['tabAdmin'] as $ad) {
            $tabIdAdmin[] = $ad->getId();
//            $id = $ad->getId();
//            if ($utilisateurEmetteur->getId() != $id) {
//               
//            }
        }
        foreach ($query['tabAbonne'] as $ad) {
            $tabIdAbonne[] = $ad->getId();
        }

        return array('tabAdmin' => $query['tabAdmin'], 'tabAbonne' => $query['tabAbonne'], 'tabIdAdmin' => $tabIdAdmin, 'tabIdAbonne' => $tabIdAbonne);
    }

    /*
     * Retourne la liste paginé des message recus. Il s'agit uniquement des tickets, c'est à dire un tableau contenant 
     * le premier message de chaque fil de discussion.
     * Le tableau retourné contient les entrées suivantes :
     * 
     * - data : tableau contenant la liste paginés des objet Envoi en utilisation la class Doctrine\ORM\Tools\Pagination\Paginator de Symfony2
     * - nbTotal : Nombre total d'élement trouvé
     * - nbParPage : Nombre d'élément à afficher par page
     * - pageActulle : La page actuelle de la pagination
     * - nbTotalPage : Nombre total de page
     * 
     * @param type $idUtilisateur
     * @param type $dateDebut
     * @param type $dateFin
     * @param type $mot
     * @param type $nbParPage
     * @param type $pageActuelle
     * @param type $isUtilisateur
     * @param type typeMessage : 0 = Message recu d'abonné + message recu d'utilisateur; 1 = Message Abonne uniquement, 2 = Message utilisateur uniquement
     * 
     * @return array
     */
    public function getListeMessageRecu($idUtilisateur, $dateDebut, $dateFin, $mot = '', $nbParPage = 20, $pageActuelle = 1, $isUtilisateur = true, $typeMessage = 0)
    {
        return $this->em->getRepository($this->messagerieBundle.'Envoi')->getListeMessageRecu($idUtilisateur, $dateDebut, $dateFin, $mot, $nbParPage, $pageActuelle, $isUtilisateur, $typeMessage);
    }

    /*
     * Retourne la liste paginé des messages envoyés
     *  Il s'agit uniquement des tickets, c'est à dire un tableau contenant le premier message de chaque fil de discussion initié par l'utilisateur courant 
     * d'id $idUtilisateur.
     * Le tableau retourné contient les entrées suivantes :
     * 
     * - data : tableau contenant la liste paginés des objet Envoi en utilisation la class Doctrine\ORM\Tools\Pagination\Paginator de Symfony2
     * - nbTotal : Nombre total d'élement trouvé
     * - nbParPage : Nombre d'élément à afficher par page
     * - pageActulle : La page actuelle de la pagination
     * - nbTotalPage : Nombre total de page
     * 
     * @param type $idUtilisateur
     * @param type $dateDebut
     * @param type $dateFin
     * @param type $mot
     * @param type $nbParPage
     * @param type $pageActuelle
     * @param type $isUtilisateur
     * @param type typeMessage : 0 = Message recu d'abonné + message recu d'utilisateur; 1 = Message Abonne uniquement, 2 = Message utilisateur uniquement
     * 
     * @return array
     * 
     * @param type $idUtilisateur
     * @param type $dateDebut
     * @param type $dateFin
     * @param type $mot
     * @param type $nbParPage
     * @param type $pageActuelle
     * @param type $isUtilisateur
     * @return array
     */
    public function getListeMessageEnvoyes($idUtilisateur, $dateDebut, $dateFin, $mot = '', $nbParPage = 20, $pageActuelle = 1, $isUtilisateur = true)
    {
        return $this->em->getRepository($this->messagerieBundle.'Envoi')->getListeMessageEnvoyes($idUtilisateur, $dateDebut, $dateFin, $mot, $nbParPage, $pageActuelle, $isUtilisateur);
    }

    /*
     * retoutne la valeur d'un parametre
     * @param \ParamBundle\Entity\Param $paramtre
     * @param type $int
     */
    public function getEmetteurOfMessage(Message $message)
    {
        $typeEnvoi = $message->getTypeEnvoi();

        return ($typeEnvoi == TypeEnvoi::ABONNE_UTILISATEUR) ? $message->getAbonne() : $message->getUtilisateur();
    }

     /*
     * Retourne le nombre de message non lu du connecté
     * 
     * @param type $isAbonne
     * @param type $idUser
     * @return type
     */
    public function getNbMessageNonLu($isAbonne, $idUser)
    {
        return $this->em->getRepository($this->messagerieBundle.'Envoi')->getNbMessageNonLu($isAbonne, $idUser);
    }
}
