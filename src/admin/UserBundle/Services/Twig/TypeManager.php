<?php

/**
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of ApplicationManager
 *
 * @author Edmond
 */

namespace admin\UserBundle\Services\Twig;
use admin\UserBundle\Types\TypeCodeProfil;
use \Doctrine\ORM\EntityManager;

class TypeManager extends \Twig_Extension {
    use \admin\UserBundle\ControllerModel\paramUtilTrait;
    /**
     *
     * @var \Doctrine\ORM\EntityManager  $em : gestionnaire d'entité
     * 
     */
    private $em;    
    
    
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
            'inArray' => new \Twig_Function_Method($this, 'inArray'),
            'convertTypeSexe' => new \Twig_Function_Method($this, 'convertTypeSexe'),
            'isTypeSexeFeminin' => new \Twig_Function_Method($this, 'isTypeSexeFeminin'),
            'convertTypeEtat' => new \Twig_Function_Method($this, 'convertTypeEtat'),
            'isTypeEtatSupprimer' => new \Twig_Function_Method($this, 'isTypeEtatSupprimer'),
            'isTypeEtatBloque' => new \Twig_Function_Method($this, 'isTypeEtatBloque'),
            'isTypeEtatActif' => new \Twig_Function_Method($this, 'isTypeEtatActif'),
            'isTypeEtatDesactive' => new \Twig_Function_Method($this, 'isTypeEtatDesactive'),
            'getLibelleTypeProfil' => new \Twig_Function_Method($this, 'getLibelleTypeProfil'),
            'isTypeProfilUtilisateur' => new \Twig_Function_Method($this, 'isTypeProfilUtilisateur'),
            'isTypeProfilAbonne' => new \Twig_Function_Method($this, 'isTypeProfilAbonne'),
            'isAdmin' => new \Twig_Function_Method($this, 'isAdmin'),
            'isAbonne' => new \Twig_Function_Method($this, 'isAbonne'),
            'isMaintenance' => new \Twig_Function_Method($this, 'isMaintenance'),
            'isEnseignant' => new \Twig_Function_Method($this, 'isEnseignant'),
            'isParentEleve' => new \Twig_Function_Method($this, 'isParentEleve'),
            'isEleve' => new \Twig_Function_Method($this, 'isEleve'),
            'getAdresseServeur' => new \Twig_Function_Method($this, 'getAdresseServeur'),
            'getNomSession' => new \Twig_Function_Method($this, 'getNomSession'),
            'verifActionMenu' => new \Twig_Function_Method($this, 'verifActionMenu'),
        );
    }

    /*
     * 
     *   Retourne le nom de la session en cours
     * 
     * @author armand.tevi@gmail.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * 
     * @return string
     */

    public function getNomSession() {
        return \admin\UserBundle\Services\LoginManager::SESSION_DATA_NAME;
    }

    /*
     * 
     *  Retourne l'adresse du serveur web hebergeant l'application
     * 
     * @author armand.tevi@gmail.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * 
     * @return string
     */

    public function getAdresseServeur() {
        $ad = (@$_SERVER['SERVER_ADDR'] == '::1') ? "localhost" : @$_SERVER['SERVER_ADDR'];

        return $ad;
    }

    /*
     * 
     *  Vérifie si un profil est un profil maintenance à partir du code profil
     * 
     * @author armand.tevi@gmail.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @param string $codeProfil
     * @return boolean
     */

    public function isMaintenance($codeProfil) {
        return ($codeProfil == TypeCodeProfil::DIRECTION);
    }
    
    
    public function isEnseignant($codeProfil) {
        //var_dump(TypeCodeProfil::ENSEIGNANT );
        return ($codeProfil == TypeCodeProfil::ENSEIGNANT);
    }
    public function isParentEleve($codeProfil) {
        //var_dump(TypeCodeProfil::ENSEIGNANT );
        return ($codeProfil == TypeCodeProfil::PARENT_ELEVE);
    }
    public function isEleve($codeProfil) {
        //var_dump(TypeCodeProfil::ENSEIGNANT );
        return ($codeProfil == TypeCodeProfil::ELEVE);
    }

    
    /*
     * 
     *  Vérifie si l'utilisateur courant est de la table Utilisateur ou non
     * 
     * @author armand.tevi@gmail.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @param string $nomTable
     * @return boolean
     */

    public function isUser($nomTable) {
        return ($nomTable == \admin\UserBundle\Types\TypeTable::UTILISATEUR);
    }

    /*
     * 
     *  Vérifie si l'utilisateur courant est de la table Abonne ou non
     * 
     * @author armand.tevi@gmail.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @param string $nomTable
     * @return boolean
     */

    public function isAbonne($nomTable) {
        return ($nomTable == \admin\UserBundle\Types\TypeTable::ABONNE);
    }

    /*
     * 
     *  Vérifie si si un profil est un profil destiné aux abonnés ou pas
     * 
     * @author armand.tevi@gmail.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @param int $val
     * @return boolean
     */

    public function isTypeProfilAbonne($val) {
        return ($val == \admin\UserBundle\Types\TypeProfil::PROFIL_ABONNE);
    }

    /*
     * 
     *  Vérifie si si un profil est un profil destiné aux utilisateurs ou pas
     * 
     * @author armand.tevi@gmail.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @param int $val
     * @return boolean
     */

    public function isTypeProfilUtilisateur($val) {
        return ($val == \admin\UserBundle\Types\TypeProfil::PROFIL_UTILISATEUR);
    }

    /*
     * 
     *   Retourne le libelle d'un type de profil à partir de de sa valeur
     * 
     * @author armand.tevi@gmail.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @param int $val
     * @return string
     */

    public function getLibelleTypeProfil($val) {
        $libelle = 'Administration';
        if ($val == \admin\UserBundle\Types\TypeProfil::PROFIL_ABONNE) {
            $libelle = 'Utilisateur';
        }

        return $libelle;
    }

    /*
     * 
     * Vérifie si un état est désactivé ou pas
     * 
     * @author armand.tevi@gmail.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @param int $val
     * @return booleen
     */

    public function isTypeEtatDesactive($val) {
        return ($val == \admin\UserBundle\Types\TypeEtat::DESACTIVE);
    }

    /*
     * 
     * Vérifie si un état est activé ou pas
     * 
     * @author armand.tevi@gmail.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @param int $val
     * @return booleen
     */

    public function isTypeEtatActif($val) {
        return ($val == \admin\UserBundle\Types\TypeEtat::ACTIF);
    }

    /*
     * 
     * Vérifie si un état est supprimé ou pas
     * 
     * @author armand.tevi@gmail.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @param int $val
     * @return booleen
     */

    public function isTypeEtatSupprimer($val) {
        return ($val == \admin\UserBundle\Types\TypeEtat::SUPPRIME);
    }

    /*
     * 
     * Vérifie si un état est bloqué ou pas
     * 
     * @author armand.tevi@gmail.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @param int $val
     * @return booleen
     */

    public function isTypeEtatBloque($val) {
        return ($val == \admin\UserBundle\Types\TypeEtat::BLOQUE);
    }

    /*
     * 
     * Converti un type d'état en string. Retourne soit INACTIF - ACTIF - SUPPRIMER - BLOQUE
     * 
     * @author armand.tevi@gmail.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @param int $val
     * @return string
     */

    public function convertTypeEtat($val) {
        $rep = 'INACTIF';
        if ($val == \admin\UserBundle\Types\TypeEtat::ACTIF) {
            $rep = 'ACTIF';
        }
        if ($val == \admin\UserBundle\Types\TypeEtat::SUPPRIME) {
            $rep = 'SUPPRIME';
        }
        if ($val == \admin\UserBundle\Types\TypeEtat::BLOQUE) {
            $rep = 'BLOQUE';
        }

        return $rep;
    }

    /*
     * 
     * Vérifie si un élément ($search) se trouve dans le tableau $array
     * 
     * @author armand.tevi@gmail.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @param string $search
     * @param array $array
     * @return booleen
     */

    public function inArray($search, $array) {
        $rep = FALSE;
        if (($search != NULL) && (is_array($array)) && (!empty($search))) {
            if (in_array($search, $array)) {
                $rep = TRUE;
            }
        }
        return $rep;
    }

    /*
     * 
     * Vérifie si un type de sexe est féminin
     * 
     * @author armand.tevi@gmail.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @param int $val
     * @return booleen
     */

    public function isTypeSexeFeminin($val) {
        return ($val == \admin\UserBundle\Types\TypeSexe::FEMININ);
    }

    /*
     * 
     * Converti un type de sese  en string. Retourne soit FEMININ - F - MASCULIN - M
     * 
     * @author armand.tevi@gmail.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @param int $val
     * @param bool $text
     * @return string
     */

    public function convertTypeSexe($val, $text = FALSE) {
        if ($text) {
            return ($val == \admin\UserBundle\Types\TypeSexe::FEMININ ) ? 'FEMININ' : 'MASCULIN';
        }
        return ($val == \admin\UserBundle\Types\TypeSexe::FEMININ ) ? 'F' : 'M';
    }


    public function verifActionMenu($tabIdAction,$nomAction) {
            $ObjetAction = $this->em->getRepository($this->userBundle . 'Action')->findOneBy(array('nom'=>$nomAction));
            if($ObjetAction==null){
                return false;
            }else{
                if(in_array($ObjetAction->getId(), $tabIdAction)){
                    return true;
                }else{
                    return false ;
                }
            }
    }

    public function getName() {
        return 'TypeManager';
    }

}
