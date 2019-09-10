<?php

/**
 * Description of LoginManager
 * Service de gestion des connexions et des droits des utilisateurs.
 *
 * @author Edmond
 */

namespace admin\UserBundle\Services\Twig;

use \Doctrine\ORM\EntityManager;

/*
 * DataBaseTwigManager
 * 
 * 
 */

class DataBaseTwigManager extends \Twig_Extension {
    /*
     *
     * @var \Doctrine\ORM\EntityManager  $em : gestionnaire d'entité
     * 
     */

    private $em;



    /*
     * 
     * @var string  $userBundle
     * Nom du Bundle
     */
    private $userBundle = 'adminUserBundle:';

    /*
     * le constructeur qui initialise les attributs
     * 
     * @param \Doctrine\ORM\EntityManager $em
     */

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function getFunctions() {
        return array(
            'getAbonneByTel1' => new \Twig_Function_Method($this, 'getAbonneByTel1'),
        );
    }

    /*
     * 
     *  Retrourne l'abonné de tel $tel1
     * 
     * @author armand.tevi@gmail.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @param string $tel1
     * @return Abonne|NULL
     */

    public function getAbonneByTel1($tel1) {
        return $this->em->getRepository($this->userBundle . 'Abonne')->findOneByTel1($tel1);
    }

    public function getName() {
        return 'DataBaseTwigManager';
    }

}
