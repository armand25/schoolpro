<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * Description of RandCodeManager
 * 
 *
 * @author Edmond
 */

namespace admin\ParamBundle\Services;

class RandCodeManager {

    
    private $alpha = 'ABCDEFGHIJKLNPQRSTUVWXYZ';
    private $alphaNum = 'ABCDEFGHIJKLNPQRSTUVWXYZ0123456789';
    private $num = '0123456789';
    private $consonnes = "BCDFGHJKLMNPQRSTVWXZ";
    private $voyelles = "AEIOUY";
    private $speciaux = '@_#';

//    public function __construct(EntityManager $em) {
//        $this->em = $em;
//    }
    public function __construct() {
        
    }
    
    /*
     * Retourne un code pour la génération de la route en vue de la récupération de mot de passe par les abonnés
     * @param \admin\UserBundle\Entity\Abonne $abonne
     * @return type
     */
    public function getCodeLossPassword(\admin\UserBundle\Entity\Abonne $abonne){
        $codeRand = $this->randAlpha(10) ;
        return strtolower( $codeRand.date('YmdHis').$abonne->getCodeBase().  uniqid());
    }

    /*
     * génération de chaine de caractères alphanumérique de longueur $longueur
     * @param type $longueur
     * @return type
     */
    public function randAlphaNumerique($longueur = 1) {
        $longueurInt = (int) $longueur;
        $fin = strlen($this->alphaNum) - $longueurInt - 1;
        if ($fin < 0) {
            $fin = 0;
        }

        return substr(str_shuffle($this->alphaNum), rand(0, $fin), $longueurInt);
    }

    /*
     * génération de chaine de caractères alphabétique de longueur $longueur
     * @param type $longueur
     * @return type
     */
    public function randAlpha($longueur = 1) {
        $longueurInt = (int) $longueur;
        $fin = strlen($this->alpha) - $longueurInt - 1;
        if ($fin < 0) {
            $fin = 0;
        }

        return substr(str_shuffle($this->alpha), rand(0, $fin), $longueurInt);
    }

    /*
     * génération de chaine de caractères numériquede de longueur $longueur
     * @param type $longueur
     * @return type
     */
    public function randNumerique($longueur = 1) {
        $longueurInt = (int) $longueur;
        $fin = strlen($this->num) - $longueurInt - 1;
        if ($fin < 0) {
            $fin = 0;
        }

        return substr(str_shuffle($this->num), rand(0, $fin), $longueurInt);
    }

    /*
     * génération de mot de passe
     * @param int $longueur
     * @return string
     */
    function createPassword($longueur = 5) {
        $mdp = '';
        $consonnnes = $this->consonnes . $this->speciaux . $this->num;
        for ($i = 0; $i < $longueur; $i++) {
            /* L'opérateur % permet le changement entre voyelle et consonne */
            $mdp = (($i % 2) == 0) ? $mdp = $mdp . substr(str_shuffle($this->voyelles), rand(0, strlen($this->voyelles) - 1), 1) :
                    $mdp = $mdp . substr(str_shuffle($consonnnes), rand(0, strlen($consonnnes) - 1), 1);
        }

        return $mdp;
    }

}
