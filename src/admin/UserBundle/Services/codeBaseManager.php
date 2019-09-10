<?php

/**
 * Description of LoginManager
 * Service de gestion des connexions et des droits des utilisateurs.
 *
 * @author Edmond
 */

namespace admin\UserBundle\Services;

use \Doctrine\ORM\EntityManager;
use \admin\ParamBundle\Types\TypeParametre;
use \admin\ParamBundle\Services\ParametreManager;
use \admin\ParamBundle\Services\RandCodeManager;
use \admin\UserBundle\Entity\CodeBase;


/**
 * codeBaseManager
 * 
 * 
 */
class codeBaseManager {

    /*
     *
     * @var \Doctrine\ORM\EntityManager  $em : gestionnaire d'entité
     * 
     */
    private $em;
    
    /*
     *
     * @var \admin\ParamBundle\Services\RandCodeManager  $randCodeManager 
     * 
     */
    private $randCodeManager;
    
     /*
     *
     * @var admin\ParamBundle\Services\ParametreManager  $parametreManager
     * Une instance du service ParametreManager permetant la gestion des paramètres de l'application
     */
    private $parametreManager;

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

    
    public function __construct(EntityManager $em, ParametreManager $p, RandCodeManager $rand) {
        $this->em = $em;
        $this->randCodeManager = $rand;
        $this->parametreManager = $p;
    }
    
    /*
     * Enregistre un code de base deja utilise dans la bd
     * 
     * @param type $code
     * @return boolean
     */
    public function persistCodeBase($code){
        if(!$this->codeBaseExiste($code)){
            $objetCode = new CodeBase($code);
            $this->em->persist($objetCode);
            $this->em->flush();
            return TRUE;
        }
        
        return FALSE;
    }

        /*
     * Crée un nouvel code de base
     * @return type
     */
    public function getNewCodeBase(){
        $longueurCodebaseBase = $this->parametreManager->getValeurParametre(TypeParametre::LONGUEUR_CODE_BASE_INT);
        if($longueurCodebaseBase == NULL){
            $longueurCodebaseBase = 10;
        }
        
        $tempCode = $this->randCodeManager->randAlphaNumerique($longueurCodebaseBase);
        while ($this->codeBaseExiste($tempCode)){
            $tempCode = $this->randCodeManager->randAlphaNumerique($longueurCodebaseBase);
        }
        
        
        return $tempCode;
    }
    
    /*
     * Vérifie si un code de base est deja utilisé
     * 
     * @param type $code
     * @return boolean
     */
    public function codeBaseExiste($code){
        $rep = TRUE;
        
        $objetCodeBase = $this->em->getRepository($this->userBundle.'CodeBase')->findOneByCode($code);
        if($objetCodeBase == NULL){
            $rep = FALSE;
        }
        return $rep;
    }
    
}
