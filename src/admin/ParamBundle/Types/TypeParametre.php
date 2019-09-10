<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TypeParametre
 *
 * @author Administrateur
 */
namespace admin\ParamBundle\Types;

class TypeParametre {
    /**
     * nombre de minutes d'inactivité après lequel on deconect l'user 
     */
    const DUREE_TIME_OUT_INT = 1;
    
    /**
     * Pour activer / desactiver la déconnection automaitk
     *  0 = desactive
     *  1 = active
     */
    const TIME_OUT_BOOL = 2;
    
    /**
     * Nombre de tentative après laquelle désactive un utilisateur
     */
    const NB_ATTEMPT_INT = 3;
    
    /**
     * Longueur minimale des mots de passe
     */
    const LONGUEUR_MIN_PASSWORD_INT = 4;
    
    /**
     * Longueur des comptes
     */
    const LONGUEUR_COMPTE_INT = 5;
    
    /**
     * Longueur des codes de base
     */
    const LONGUEUR_CODE_BASE_INT = 6;
    
    /**
     * La clé des SMS
     */
    const KEY_SMS_STR = 7;
    
    /**
     * Longueur des numéro de téléphone
     */
    const LONGUEUR_NUM_TEL_INT = 8;
    
    /**
     * Longueur des codes sms envoyé auxx abonnés pour finaliser un ordre de virement
     */
    const LONGUEUR_CODE_SMS_END_ORDRE_VIREMENT_INT = 9;
    
    /**
     * Longueur des code de confirmation des ordres de virement effectués et acquittés par la banque 
     */
    const LONGUEUR_CODE_SMS_CONFIRMATION_ORDRE_VIREMENT_INT = 10;
    
    /**
     * Nombre maximal de messages récents à afficher sur le tableau de bord
     */
    const NB_MAX_MESSAGERIE_BOARD_INT = 11;
    
    /**
     * Nombre maximal d'heures après lesquelles une route de réinitialisation de lot de passe expire 
     */
    const TIME_URL_INIT_PASSWORD_EXPIRE_INT = 12;
    
    /**
     * Indique si les abonné peuvent choisir le destinaire d'un message ou pas
     */
    const CHOIX_DESTINAIRE_MSG_PAR_ABONNE_BOOL = 13;
}
