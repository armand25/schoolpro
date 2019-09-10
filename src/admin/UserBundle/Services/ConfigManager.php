<?php

/*
 * Description of LoginManager
 * Service de gestion des connexions et des droits des utilisateurs.
 *
 * @author Jerome
 */

namespace admin\UserBundle\Services;

use Symfony\Component\HttpFoundation\Session\Session;
use \Doctrine\ORM\EntityManager;
use \admin\ParamBundle\Types\TypeParametre;
use \admin\ParamBundle\Services\ParametreManager;
use \admin\ParamBundle\Services\DateManager;
use \admin\ParamBundle\Types\TypeDonnees;
use Doctrine\ORM\Tools\Pagination\Paginator;
use \admin\UserBundle\Types\TypeEtat;
use \admin\UserBundle\Types\TypeProfil;
use \admin\UserBundle\Types\TypeSexe;
use \admin\UserBundle\Entity\Utilisateur;
use \admin\UserBundle\Entity\Profil;

/**
 * ConfigManager
 * 
 * Service de gestion des configurations de l'application
 * 
 */
class ConfigManager extends \Twig_Extension {

    /*
     *
     * @var \Doctrine\ORM\EntityManager  $em : gestionnaire d'entité
     * 
     */
    private $em;

    /*
     * 
     * @var string  $paramBundle
     * Nom du Bundle
     */
    private $paramBundle = 'adminParamBundle:';

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
            'isModuleDesactive' => new \Twig_Function_Method($this, 'isModuleDesactive'),
            'isModuleActif' => new \Twig_Function_Method($this, 'isModuleActif'),
            'avoirAuMoinsUnChamp' => new \Twig_Function_Method($this, 'avoirAuMoinsUnChamp'),
        );
    }
    
    private function getEtatModule($moduleName) {
    
            $queryBulder1 = $this->em->getRepository($this->userBundle . 'Module')->createQueryBuilder('m');

                $queryBulder1 = $queryBulder1->select('m.etat')->where('m.nom = :moduleName');
            
            $queryBulder1->setParameter('moduleName', $moduleName);
            $rep = $queryBulder1->getQuery()->getSingleResult();
            $rep = $rep['etat'] ;
        return $rep;
    }

    
    /*
     * 
     * Vérifie si un état est désactivé ou pas
     * 
     * @author jerome.kpeto@Think Global.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @param int $val
     * @return booleen
     */

    public function isModuleDesactive($moduleName) {
        
        $etatModule = $this->getEtatModule($moduleName);
        
        return ($etatModule == \admin\UserBundle\Types\TypeEtat::DESACTIVE);
    }

    /*
     * 
     * Vérifie si un état est activé ou pas
     * 
     * @author jerome.kpeto@Think Global.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @param int $val
     * @return booleen
     */

    public function isModuleActif($moduleName) {
        
        $etatModule = $this->getEtatModule($moduleName);
        return ($etatModule == \admin\UserBundle\Types\TypeEtat::ACTIF);
    }
    
    /*
     * 
     * Vérifie si un état est activé ou pas
     * 
     * @author jerome.kpeto@Think Global.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @param int $val
     * @return booleen
     */

    public function avoirAuMoinsUnChamp($tableName) {
        
        $tableId = $this->em->getConnection()->fetchColumn("SELECT t.id FROM table_lfp t where t.nom_table ='".trim($tableName)."'") ;
        $firstchamplfp = $this->em->getConnection()->fetchColumn("SELECT nom_champ FROM champ_lfp c where tablelfp_id =".$tableId) ;
//        var_dump($tableName,$tableId,$firstchamplfp) ;
        return (is_bool($firstchamplfp) && $firstchamplfp == FALSE)?0:1 ;
        
    }
    
    public function getName() {
        return 'ConfigManager';
    }
    
    
   
}
