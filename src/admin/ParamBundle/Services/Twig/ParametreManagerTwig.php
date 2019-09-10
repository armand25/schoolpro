<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ParametreManager
 *
 * @author Edmond
 */

namespace admin\ParamBundle\Services\Twig;

use \admin\ParamBundle\Services\ParametreManager;

class ParametreManagerTwig extends \Twig_Extension {
    /*
     * Service de gestion des parametres
     */

    private $parametreManager;

    public function __construct(ParametreManager $paramManager) {
        $this->parametreManager = $paramManager;
    }

    public function getFunctions() {
        return array(
            'getValeurParametre' => new \Twig_Function_Method($this, 'getValeurParametre'),
            'isBoolean' => new \Twig_Function_Method($this, 'isBoolean'),
        );
    }

    /*
     * 
     *  retoutne la valeur d'un parametre
     * 
     * @author armand.tevi@gmail.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @param int $paramtreName
     * @param boolean $objetDate
     * @return 
     */

    public function getValeurParametre($paramtreName, $objetDate = 1) {
        return $this->parametreManager->getValeurParametre($paramtreName, $objetDate);
    }

    /*
     * 
     *  Vérifie si un type de parametre est un boolean
     * 
     * @author armand.tevi@gmail.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @param int $typeDonnee 
     * @return boolean
     */

    public function isBoolean($typeDonnee) {
        return ($typeDonnee == \admin\ParamBundle\Types\TypeDonnees::BOOLEAN);
    }

    public function getName() {
        return 'parametre_manager_twig';
    }

}
