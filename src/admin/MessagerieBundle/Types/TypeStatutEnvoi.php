<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TypeStatutEnvoi
 * Indique le statut d'un envoi. Il s'agit de l'ensemble des valeurs prises par l'attribut $statut de l'entité 
 * admin\MessagerieBundle\Entity\Envoi.php.
 *
 * @author edmond
 */
namespace admin\MessagerieBundle\Types;

class TypeStatutEnvoi
{
    /**
     * Message non lu.
     */
    const MSG_NON_LU = 1;

    /**
     * Message supprimé.
     */
    const MSG_SUPPRIME = 2;

    /**
     * Message lu.
     */
    const MSG_LU = 3;
}
