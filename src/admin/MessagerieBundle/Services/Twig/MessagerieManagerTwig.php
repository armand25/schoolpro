<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ParametreManager.
 *
 * @author Edmond
 */
namespace admin\MessagerieBundle\Services\Twig;

use admin\MessagerieBundle\Services\MessagerieManager;
use admin\MessagerieBundle\Entity\Message;
use admin\MessagerieBundle\Types\TypeEnvoi;

class MessagerieManagerTwig extends \Twig_Extension
{
    private $messagerieManager;

    public function __construct(MessagerieManager $messagerieManager)
    {
        $this->messagerieManager = $messagerieManager;
    }

    public function getFunctions()
    {
        return array(
            'getDestinairesOfMessage' => new \Twig_Function_Method($this, 'getDestinairesOfMessage'),
            'getEmetteurOfMessage' => new \Twig_Function_Method($this, 'getEmetteurOfMessage'),
            'getFilMessage' => new \Twig_Function_Method($this, 'getFilMessage'),
            'isMsgNonLu' => new \Twig_Function_Method($this, 'isMsgNonLu'),
            'isMsgLu' => new \Twig_Function_Method($this, 'isMsgLu'),
            'isMsgSupprimer' => new \Twig_Function_Method($this, 'isMsgSupprimer'),
            'getLibelleStatutMsg' => new \Twig_Function_Method($this, 'getLibelleStatutMsg'),
            'isAbonneToUser' => new \Twig_Function_Method($this, 'isAbonneToUser'),
            'isUserToUser' => new \Twig_Function_Method($this, 'isUserToUser'),
            'isUserToAbonne' => new \Twig_Function_Method($this, 'isUserToAbonne'),
            'getLibelleAMessageEnvois' => new \Twig_Function_Method($this, 'getLibelleAMessageEnvois'),
            'canCloturer' => new \Twig_Function_Method($this, 'canCloturer'),
            'getMessageDebutTiket' => new \Twig_Function_Method($this, 'getMessageDebutTiket'),
        );
    }

    /*
     * Retourne le premier message d'un ticket (fil de discussion)
     * 
     * @param type $codeTicket : codeFil du message
     * @return type
     */
    public function getMessageDebutTiket($codeTicket)
    {
        return $this->messagerieManager->getMessageDebutTiket($codeTicket);
    }

    /*
     * Intique si l'utilisateur courant peut cloturer un message ou pas.
     * Il faudrait k l'utilisateur courant soit l'émetteur du message et en plus le message doit etre le 1er message du ticket, et être
     * non clôturé
     * 
     * @param Message $message
     * @param type $sessionData
     * @return boolean
     */
    public function canCloturer(Message $message, $sessionData)
    {
        return $this->messagerieManager->canCloturer($message, $sessionData);
    }

     /*
     * Indique si un message est envoyé à un abonné par un admin à partir de l'attribut typeEnvoi du message
     * 
     * @param int $typeEnvoi : le type d'envoi d'un message. Se référer au fichier : admin\MessagerieBundle\Types\TypeEnvoi.php
     * @return booleen
     */
    public function isUserToAbonne($typeEnvoi)
    {
        return ($typeEnvoi == TypeEnvoi::UTILISATEUR_ABONNE);
    }

     /*
     * Indique si un message est envoyé à un admin par un admin à partir de l'attribut typeEnvoi du message
     * 
     * @param int $typeEnvoi : le type d'envoi d'un message. Se référer au fichier : admin\MessagerieBundle\Types\TypeEnvoi.php
     * @return booleen
     */
    public function isUserToUser($typeEnvoi)
    {
        return ($typeEnvoi == TypeEnvoi::UTILISATEUR_UTILISATEUR);
    }

    /*
     * Indique si un message est envoyé à un admin par un abonné à partir de l'attribut typeEnvoi du message
     * 
     * @param int $typeEnvoi : le type d'envoi d'un message. Se référer au fichier : admin\MessagerieBundle\Types\TypeEnvoi.php
     * @return booleen
     */
    public function isAbonneToUser($typeEnvoi)
    {
        return ($typeEnvoi == TypeEnvoi::ABONNE_UTILISATEUR);
    }

    /*
     * Retourne une des trois phrases suivantes, relative à un envoi (message, mai se référer à la table envoi )
     * - Message non lu
     * - Message  lu
     * - Message  supprimer
     * 
     * @param int $statut : correspond au statut d'un envoi. Se référer à la classe : admin\MessagerieBundle\Types\TypeStatutEnvoi.php
     * 
     * @return string $rep
     */
    public function getLibelleStatutMsg($statut)
    {
        $rep = 'Message';
        if ($statut == \admin\MessagerieBundle\Types\TypeStatutEnvoi::MSG_NON_LU) {
            $rep .= ' non lu';
        }
        if ($statut == \admin\MessagerieBundle\Types\TypeStatutEnvoi::MSG_LU) {
            $rep .= ' lu';
        }
        if ($statut == \admin\MessagerieBundle\Types\TypeStatutEnvoi::MSG_SUPPRIME) {
            $rep .= ' supprimer';
        }

        return $rep;
    }

    /*
     * Indique si un envoi (message, mai se référer à la table envoi ) est supprimé ou pas à partir du statut de l'envoi en question
     * 
     * @param int $statut : correspond au statut d'un envoi. Se référer à la classe : admin\MessagerieBundle\Types\TypeStatutEnvoi.php
     * 
     * @return boolean
     */
    public function isMsgSupprimer($statut)
    {
        return ($statut == \admin\MessagerieBundle\Types\TypeStatutEnvoi::MSG_SUPPRIME);
    }

    /*
     * Indique si un envoi (message, mai se référer à la table envoi ) est non lu ou pas à partir du statut de l'envoi en question
     * 
     * @param int $statut : correspond au statut d'un envoi. Se référer à la classe : admin\MessagerieBundle\Types\TypeStatutEnvoi.php
     * 
     * @return boolean
     */
    public function isMsgLu($statut)
    {
        return ($statut == \admin\MessagerieBundle\Types\TypeStatutEnvoi::MSG_LU);
    }

    /*
     * Indique si un envoi (message, mai se référer à la table envoi ) est non lu ou pas à partir du statut de l'envoi en question
     * 
     * @param int $statut : correspond au statut d'un envoi. Se référer à la classe : admin\MessagerieBundle\Types\TypeStatutEnvoi.php
     * 
     * @return boolean
     */
    public function isMsgNonLu($statut)
    {
        return ($statut == \admin\MessagerieBundle\Types\TypeStatutEnvoi::MSG_NON_LU);
    }

    /*
     * Retourne un tableau contenant les informations suivantes relatives à un message
     * fil: tableau contenanu les messages du fil de discution  concerant l'utilisateur courant
     * filHasNonLu : indique si le fil a un message non lu.
     * 
     * @param \admin\MessagerieBundle\Entity\Message $message
     * @return type
     */
    public function getFilMessage(Message $message, $sessionData)
    {
        return $this->messagerieManager->getFilMessage($message, $sessionData);
    }

    /*
     * retoutne la valeur d'un parametre
     * @param \ParamBundle\Entity\Param $paramtre
     * @param type $int
     */
    public function getEmetteurOfMessage(Message $message)
    {
        return $this->messagerieManager->getEmetteurOfMessage($message);
    }

    /*
     * Retourne un tableau contenant les destinaires  abonne d'un message
     * 
     * @param \admin\MessagerieBundle\Entity\Message $message
     * @return type
     */
    public function getDestinairesOfMessage(Message $message)
    {
        return $this->messagerieManager->getDestinairesOfMessage($message);
    }

    /*
     *  Retourne un tableau contenant le libelle des destinaires d'un message pour affichage dans la liste des messages envoyés
     * Le tableau a deux entrées :
     * - libelle : (string) contient le nom et prénom des destinaires séparés par un virgule
     * - libelleCourt : (string) contient le nom et prénoms du premier destinaires, suvant du nombre total de destinaires entre
     * parentheses.
     * 
     * @param type $destinataires : tableau contenant les destinataires d'un message, il est obtenu en apellant
     *  le fonction getDestinairesOfMessage()
     * 
     * @return type
     */
    public function getLibelleAMessageEnvois($destinataires)
    {
        $libCourt = '';
        $lib = '';

        $tabAbonne = $destinataires['tabAbonne'];
        $tabAdmin = $destinataires['tabAdmin'];
        $i = 0;
        foreach ($tabAbonne as $a) {
            $lib .= $a->getNom().'  '.$a->getPrenoms().', ';
            if ($i == 0) {
                $libCourt = $lib;
            }
            ++$i;
        }

        foreach ($tabAdmin as $a) {
            $lib .= $a->getNom().' '.$a->getPrenoms().', ';
            if ($i == 0) {
                $libCourt = $lib;
            }

            ++$i;
        }
        if ($i > 1) {
            $libCourt .= ' ('.$i.')';
        }

        return array('libelle' => $lib, 'libelleCourt' => $libCourt);
    }

    public function getName()
    {
        return 'messagerie_manager_twig';
    }
}
