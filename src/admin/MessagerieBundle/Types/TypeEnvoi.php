<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TypeMessage.
 *
 * Le type d'envoi de message. Il s'agit de l'ensemble des valeurs prises par l'attribut $typeEnvoi de l'entité
 * admin\MessagerieBundle\Entity\Message.php
 * 
 * @author edmond
 */
namespace admin\MessagerieBundle\Types;

class TypeEnvoi
{
    /**
     * Message envoyé par un abonné à un admin.
     */
    const ABONNE_UTILISATEUR = 1;

    /**
     * Message envoyé par un admon à un admin.
     */
    const UTILISATEUR_UTILISATEUR = 2;

    /**
     * Message envoyé par un admin à un abonné.
     */
    const UTILISATEUR_ABONNE = 3;
}
