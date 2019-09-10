<?php

namespace admin\MessagerieBundle\Entity;

use Doctrine\ORM\EntityRepository;
use admin\MessagerieBundle\Types\TypeStatutEnvoi;
use Doctrine\ORM\Tools\Pagination\Paginator;
use admin\UserBundle\Types\TypeEtat;
use admin\MessagerieBundle\Types\TypeEnvoi;

/**
 * FondsRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EnvoiRepository extends EntityRepository
{
    private $messagerieBundle = 'adminMessagerieBundle:';

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
        $rep = array('data' => array(), 'nbTotal' => 0, 'nbParPage' => $nbParPage, 'pageActulle' => $pageActuelle, 'nbTotalPage' => 1);
        $dql = 'SELECT e FROM '.$this->messagerieBundle.'Envoi e JOIN e.message m ';
        if ($isUtilisateur) {
            $dql .= ' JOIN m.utilisateur u ';
        } else {
            $dql .= ' JOIN m.abonne u  ';
        }

        $dql .= '  WHERE  u.id =:idU  AND m.etat = '.TypeEtat::ACTIF.'  ';

        if ($mot != '') {
            $dql .= " AND ( m.objet like'%".$mot."%' OR  m.contenuClaire like'%".$mot."%' ) ";
        }

        $dql .= ' AND e.statut !=:statut AND  m.dateEnvoi >=:debut  AND  m.dateEnvoi <=:fin GROUP BY m.codeFil ORDER BY m.dateEnvoi DESC ';

        $query = $this->_em->createQuery($dql);
        $query->setParameter('statut', TypeStatutEnvoi::MSG_SUPPRIME);
        $query->setParameter('idU', $idUtilisateur);
        $query->setParameter('debut', $dateDebut);
        $query->setParameter('fin', $dateFin);

        $result = $query->getResult();
        $nbTotal = count($result);

        $pageActuelleInt = (int) $pageActuelle;
        $nbParPageInt = (int) $nbParPage;
        if ($pageActuelleInt < 1) {
            $pageActuelleInt = 1;
        }
        if ($nbParPageInt < 1) {
            $nbParPageInt = 20;
        }

        $nbTotalPage = (int) ceil($nbTotal / $nbParPage);
        if ($nbTotalPage < 1) {
            $nbTotalPage = 1;
        }
        if ($pageActuelleInt > $nbTotalPage) {
            $pageActuelleInt = $nbTotalPage;
        }

        $query->setFirstResult(($pageActuelleInt - 1) * $nbParPageInt);
        $query->setMaxResults($nbParPageInt);
        $rep['nbParPage'] = $nbParPageInt;
        $rep['pageActuelle'] = $pageActuelleInt;
        $rep['nbTotal'] = $nbTotal;
        $rep['nbTotalPage'] = $nbTotalPage;
        $rep['data'] = new Paginator($query);

        return $rep;
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
        $rep = array('data' => array(), 'nbTotal' => 0, 'nbParPage' => $nbParPage, 'pageActulle' => $pageActuelle, 'nbTotalPage' => 1);
        $dql = 'SELECT e FROM '.$this->messagerieBundle.'Envoi e  ';
        if ($isUtilisateur) {
            $dql .= ' JOIN e.utilisateur u ';
        } else {
            $dql .= ' JOIN e.abonne u ';
        }

        $dql .= ' JOIN e.message m WHERE  u.id =:idU AND e.affichable =:affichable AND  m.etat = '.TypeEtat::ACTIF.' ';
        if ($typeMessage > 0) {
            $tempEntity = ($typeMessage == 1) ? ' abonne ' : ' utilisateur ';
            $dql .= ' AND m.'.$tempEntity.' is not NULL ';
        }

        if ($mot != '') {
            $dql .= " AND ( m.objet like'%".$mot."%' OR  m.contenuClaire like'%".$mot."%' ) ";
        }

        $dql .= ' AND e.statut !=:statut  AND  m.dateEnvoi >=:debut AND  m.dateEnvoi <=:fin    GROUP BY m.codeFil ORDER BY m.dateEnvoi DESC ';

        $query = $this->_em->createQuery($dql);
        $query->setParameter('affichable', true);
        $query->setParameter('statut', TypeStatutEnvoi::MSG_SUPPRIME);
        $query->setParameter('idU', $idUtilisateur);

        $query->setParameter('debut', $dateDebut);
        $query->setParameter('fin', $dateFin);

        $result = $query->getResult();
        $nbTotal = count($result);

        $pageActuelleInt = (int) $pageActuelle;
        $nbParPageInt = (int) $nbParPage;
        if ($pageActuelleInt < 1) {
            $pageActuelleInt = 1;
        }
        if ($nbParPageInt < 1) {
            $nbParPageInt = 20;
        }

        $nbTotalPage = (int) ceil($nbTotal / $nbParPage);
        if ($nbTotalPage < 1) {
            $nbTotalPage = 1;
        }
        if ($pageActuelleInt > $nbTotalPage) {
            $pageActuelleInt = $nbTotalPage;
        }

        $query->setFirstResult(($pageActuelleInt - 1) * $nbParPageInt);
        $query->setMaxResults($nbParPageInt);
        $rep['nbParPage'] = $nbParPageInt;
        $rep['pageActuelle'] = $pageActuelleInt;
        $rep['nbTotal'] = $nbTotal;
        $rep['nbTotalPage'] = $nbTotalPage;
        $rep['data'] = new Paginator($query);

        return $rep;
    }

    /*
     * Retourne un tableau contenant les destinaires d'un message. 
     * Les entrées du tableau sont les suivantes:
     * - tabAdmin : tableau contenant les admins destinataires du message
     * - tabAbonne : tableau contenant les abonnés destinataires du message
     * 
     * @param \admin\MessagerieBundle\Entity\Message $message
     * @return type
     */
    public function getDestinairesOfMessage(Message $message)
    {
        $dql = 'SELECT e FROM '.$this->messagerieBundle.'Envoi e JOIN e.message m WHERE m.id  =:idMessage AND e.affichable =:affichable';
        $query = $this->_em->createQuery($dql);
        $query->setParameter('idMessage', $message->getId());
        $query->setParameter('affichable', true);
        $result = $query->getResult();

        $tabAbonne = array();
        $tabAdmin = array();
        foreach ($result as $e) {
            $abonne = $e->getAbonne();
            if ($abonne != null) {
                $tabAbonne[] = $abonne;
            } else {
                $user = $e->getUtilisateur();
                if ($user != null) {
                    $tabAdmin[] = $user;
                }
            }
//            $typeEnvoi = $message->getTypeEnvoi();
//            $tmp = (($typeEnvoi == TypeEnvoi::ABONNE_UTILISATEUR) || (TypeEnvoi::UTILISATEUR_UTILISATEUR)) ? $e->getUtilisateur() : $e->getAbonne();
//            if ($tmp != NULL) {
//                if (($typeEnvoi == TypeEnvoi::ABONNE_UTILISATEUR) || (TypeEnvoi::UTILISATEUR_UTILISATEUR)) {
//                    $tabAdmin[] = $tmp;
//                } else {
//                    $tabAbonne[] = $tmp;
//                }
//            }
        }

        return array('tabAdmin' => $tabAdmin, 'tabAbonne' => $tabAbonne);
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
        $dql = 'SELECT e FROM '.$this->messagerieBundle.'Envoi e JOIN e.message m ';
        if ($isAbonne) {
            $dql .= ' JOIN e.abonne a WHERE a.id =:id';
        } else {
            $dql .= ' JOIN e.utilisateur a WHERE a.id =:id';
        }

        $dql .= ' AND m.etat !='.TypeEtat::SUPPRIME.' AND  e.statut ='.TypeStatutEnvoi::MSG_NON_LU.' AND m.etat ='.TypeEtat::ACTIF.' ';

        $query = $this->_em->createQuery($dql);
        $query->setParameter('id', $idUser);
        $result = $query->getResult();

        return count($result);
    }

    /*
     * Retourne un tableau contenant les informations suivantes relatives à un message
     * fil: tableau contenanu les messages du fil de discution  concerant l'utilisateur courant
     * filHasNonLu : indique si le fil a un message non lu.
     * $idFirstEnvoiNonLu : Identifiant du premier message (id d'un envoi) non lu du fil relatif au message $message
     * firstMessageNonLu : Premier message non lu du fil relatif au message $message
     * 
     * @param \admin\MessagerieBundle\Entity\Message $message
     * @return type
     */
    public function getFilMessage(Message $message, $sessionData)
    {
        $dql = 'SELECT e FROM '.$this->messagerieBundle.'Envoi e JOIN  e.message m  ';
        if ($sessionData['isUser']) {
            $dql .= ' JOIN e.utilisateur u WHERE u.id ='.$sessionData['id'];
        } else {
            $dql .= ' JOIN e.abonne u WHERE u.id ='.$sessionData['id'];
        }
        $dql .= 'AND  m.codeFil  =:code AND e.statut !=:statut AND m.etat !=:etatM ORDER BY e.dateEnvoi ASC ';
        $query = $this->_em->createQuery($dql);
        $query->setParameter('code', $message->getCodeFil());
        $query->setParameter('statut', TypeStatutEnvoi::MSG_SUPPRIME);
        $query->setParameter('etatM', TypeEtat::SUPPRIME);
        $result = $query->getResult();

        $filHasNonLu = false;
        $idFirstEnvoiNonLu = 0;
        $firstMessageNonLu = null;
        foreach ($result as $e) {
            if ($e->getStatut() == TypeStatutEnvoi::MSG_NON_LU) {
                $filHasNonLu = true;
                if ($idFirstEnvoiNonLu == 0) {
                    $idFirstEnvoiNonLu = $e->getId();
                    $firstMessageNonLu = $e->getMessage();
                }

                continue;
            }
        }

        return array('fil' => $result, 'filHasNonLu' => $filHasNonLu, 'idFirstEnvoiNonLu' => $idFirstEnvoiNonLu, 'firstMessageNonLu' => $firstMessageNonLu);
    }
    
    //Recuperation des messages non lus
         
    public function getListeMessageNonLu($id)
    {
        $dql = 'SELECT e.id, u.nom, m.objet FROM '.$this->messagerieBundle.'Envoi e JOIN  e.message m  ';
        
        $dql .= ' JOIN e.utilisateur u WHERE u.id =:id ';
        
        $dql .= 'AND   e.statut !=:statut  ORDER BY e.dateEnvoi ASC ';
        $query = $this->_em->createQuery($dql);
        
        $query->setParameter('statut', TypeStatutEnvoi::MSG_NON_LU);
        $query->setParameter('id', $id);
        
        $result = $query->getResult();
        
        return $result;
    }
}
