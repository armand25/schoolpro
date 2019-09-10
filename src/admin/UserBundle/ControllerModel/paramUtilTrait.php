<?php

namespace admin\UserBundle\ControllerModel;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Doctrine\ORM\EntityManager;
use admin\StockBundle\Entity\Compteur;
use \Symfony\Component\HttpFoundation\Request;

use \admin\UserBundle\Services\LoginManager;
use \admin\ParamBundle\Controller\ParametreController;
use PDO;


Trait paramUtilTrait 
{
    
    
     /**
     * @var string
     *             Nom du Bundle
     */
    protected $stockBundle = 'adminEconomatBundle:';

    /**
     * @var string
     *             Nom du Bundle
     */
    protected $paramBundle = 'adminParamBundle:'; 
    
    
    protected $userBundle = 'adminUserBundle:'; 
    protected $cmsBundle = 'adminCmsBundle:'; 
    
    protected $scolariteBundle = 'adminScolariteBundle:'; 
    
    /**
     * @var string
     *      Nom du Bundle
     */
    protected $messagerieBundle = 'adminMessagerieBundle:';    
    
    
   // use \Symfony\Component\DependencyInjection\ContainerAwareTrait;
      
    public function infosConnecte(Request $request) {
        //$this->updateMessageNonLu();
        $loginManager = $this->get('login_manager');
        $sessionData = $loginManager->getSessionData(LoginManager::SESSION_DATA_NAME);
        $status = $loginManager->isConnecte('');
//        $banque = $loginManager->getBanque();
        $request->attributes->set('isConnecte', $status['isConnecte']);
        $request->attributes->set('isUser', $status['isUser']);
        $request->attributes->set('logoUrl', ParametreController::DEFAULT_LOGO_NAME);
        $request->attributes->set('nbMessageNonLu', 0);
//        $request->attributes->set('infos', $banque);
//        if($banque instanceof  Banque){
//            $request->attributes->set('logoUrl',$banque->getUrlLogo());
//        }

        if (($sessionData != NULL) && (is_array($sessionData)) && ($status['isConnecte'])) {
            if (array_key_exists('nbMessageNonLu', $sessionData)) {
                $request->attributes->set('nbMessageNonLu', $sessionData['nbMessageNonLu']);
            }
            if (array_key_exists('nomTableConnecte', $sessionData)) {
                $request->attributes->set('nomTableConnecte', $sessionData['nomTableConnecte']);
            }
            if (array_key_exists('isUser', $sessionData)) {
                $request->attributes->set('isUser', $sessionData['isUser']);
            }
            if (array_key_exists('isAbonne', $sessionData)) {
                $request->attributes->set('isAbonne', $sessionData['isAbonne']);
            }
            if (array_key_exists('id', $sessionData)) {
                $request->attributes->set('id', $sessionData['id']);
            }
            if (array_key_exists('prenoms', $sessionData)) {
                $request->attributes->set('prenoms', $sessionData['prenoms']);
            }
            if (array_key_exists('nom', $sessionData)) {
                $request->attributes->set('nom', $sessionData['nom']);
            }
            if (array_key_exists('sexe', $sessionData)) {
                $request->attributes->set('sexe', $sessionData['sexe']);
            }
            if (array_key_exists('idConnexion', $sessionData)) {
                $request->attributes->set('idConnexion', $sessionData['idConnexion']);
            }
            if (array_key_exists('username', $sessionData)) {
                $request->attributes->set('username', $sessionData['username']);
            }
            if (array_key_exists('idProfil', $sessionData)) {
                $request->attributes->set('idProfil', $sessionData['idProfil']);
            }
            if (array_key_exists('libelleProfil', $sessionData)) {
                $request->attributes->set('libelleProfil', $sessionData['libelleProfil']);
            }
            if (array_key_exists('codeProfil', $sessionData)) {
                $request->attributes->set('codeProfil', $sessionData['codeProfil']);
            }
            $this->data['tabIdActions'] = array();
            if (array_key_exists('tabIdActions', $sessionData)) {
                $request->attributes->set('tabIdActions', $sessionData['tabIdActions']);
                $this->data['tabIdActions'] = $sessionData['tabIdActions'];
            }
            if (array_key_exists('locale', $sessionData)) {
                $request->attributes->set('locale', $sessionData['locale']);
                $this->data['locale'] = $sessionData['locale'];
                $request->setLocale($sessionData['locale']);
            } else {
                $locale = $loginManager->getLocale();
                $request->attributes->set('locale', $locale);
                $this->data['locale'] = $locale;
                $request->setLocale($locale);
            }

            if ($status['isUser']) {
//                $profilsAbonne = $loginManager->getAllProfilAbonne();
            }
        }

//        $this->data['profilsAbonne'] = $profilsAbonne;
        return $sessionData;
    }

    /**
     * Récupération du service de gestion des logs;
     * @return LogManager
     */
    private function getLogManager() {
        return $this->get('logManager');
    }
        /**
     * Retourne le nom d'une classe à partir de l'espace de nom complet de la classe
     * @param type $nameSpace
     * @return type
     */
    public function getNomClassRun($nameSpace) {
        $nom = '';
        if (strlen($nameSpace) > 0) {
            $tab = explode("\\", $nameSpace);
            $taille = count($tab);
            if ($taille > 0) {
                $nom = $tab[$taille - 1];
            }
        }

        return $nom;
    }
    
 
    public function getRefCommande(EntityManager $em, $type = 0, $an = 0, $mois = 0, $entite = 'COMMANDE', $taille = 5,$jour=""){

       $res = ''; 
       $cp = $this->getcompteur($em, $type , $an , $mois, $entite , $taille );
       ( array_key_exists('compteur',$cp))? $cp =$cp['compteur'] : $cp ='';
       ($type === 1)? $res .='CDF' : $res .='CDC';
       return $res.'-'.substr($an,-2).$mois.$jour.'-'.$cp;
    }
    public function getRefLivrer(EntityManager $em, $type = 0, $an = 0, $mois = 0, $entite = 'COMMANDE', $taille = 5,$jour=""){

       $res = ''; 
       $cp = $this->getcompteur($em, $type , $an , $mois, $entite , $taille );
       ( array_key_exists('compteur',$cp))? $cp =$cp['compteur'] : $cp ='';
       ($type === 1)? $res .='REC' : $res .='LIV';
       return $res.'-'.substr($an,-2).$mois.$jour.'-'.$cp;
    }
    
    public function getcompteur(EntityManager $em, $type = 0, $an = 0, $mois = 0, $entite = 'COMMANDE', $taille = 5){

        try {        
                $sqlrech = ' SELECT getcompteur(:p_type ,:p_an ,:p_mois ,:p_entite ,:p_taille) as compteur; ';           
                $stmt = $this->em->getConnection()->prepare($sqlrech);
                $stmt->bindValue(':p_type', $type, PDO::PARAM_INT);
                $stmt->bindValue(':p_an', $an, PDO::PARAM_INT);
                $stmt->bindValue(':p_mois', $mois, PDO::PARAM_INT);
                $stmt->bindValue(':p_entite', $entite, PDO::PARAM_STR);
                $stmt->bindValue(':p_taille', $taille, PDO::PARAM_INT);
                $stmt->execute();
                $res = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\Symfony\Component\Form\Exception\Exception $e) {
                $stmt = null;
                $res = nnull;
                var_dump($e->getMessage()) ;
        } 
        
       return $res;
    }
    
    public function siHeureEnCours(EntityManager $em, $dateJ){

        try {        
                $sqlrech = " select * from heure_journee where debut_heure_journee < :date1 and fin_heure_journee  > :date2 ";           
                $stmt = $em->getConnection()->prepare($sqlrech);
                $stmt->bindValue(':date1', $dateJ, PDO::PARAM_STR);
                $stmt->bindValue(':date2', $dateJ, PDO::PARAM_STR);
                $stmt->execute();
               
                //
                $res = $stmt->fetch(PDO::FETCH_ASSOC);
                
        } catch (\Symfony\Component\Form\Exception\Exception $e) {
                $stmt = null;
                $res = nnull;
                var_dump($e->getMessage()) ;
        } 
        //var_dump($res);exit;
       return $res;
    }
    
    public function updateAllDecoupage(EntityManager $em, $idtype ='' ){

        try {        
                $sqlrech = ' UPDATE decoupage set etat_decoupage = 2 where typedecoupage_id = :type ';           
                $stmt = $em->getConnection()->prepare($sqlrech);
                $stmt->bindValue(':type', $idtype, PDO::PARAM_INT);
               
                $stmt->execute();
               // $res = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\Symfony\Component\Form\Exception\Exception $e) {
                $stmt = null;
                $res = nnull;
                var_dump($e->getMessage()) ;
        } 
        
       return $idtype;
    }
    public function updateAllAnnnee(EntityManager $em, $id ='' ){

        try {        
                $sqlrech = ' UPDATE annee_scolaire set etat_decoupage = 2 where id = :id ';           
                $stmt = $em->getConnection()->prepare($sqlrech);
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
               
                $stmt->execute();
               // $res = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\Symfony\Component\Form\Exception\Exception $e) {
                $stmt = null;
                $res = nnull;
                var_dump($e->getMessage()) ;
        } 
        
       return $id;
    }
    public function getNumMvt(EntityManager $em, $type = 0, $an = 0, $mois = 0, $entite = 'COMMANDE', $taille = 5){

       $res = ''; 
       $cp = $this->getcompteur($em, $type , $an , $mois, $entite , $taille );
       ( array_key_exists('compteur',$cp))? $cp =$cp['compteur'] : $cp ='';
       ($type === 1)? $res .='MVT' : $res .='MVT';
       return $res.''.substr($an,-2).$mois.''.$cp;
    }   
    
    
    public function gestionDroitUtil($request,$nomAction, $descAction,$module, $moduledescript){
                /*
         * Préparation du message de log 
         */
        $this->logMessage .= ' [ ' . $nomAction . ' ] ';
        /*
         * Service de gestion des droits
         */
        $loginManager = $this->get('login_manager');
        /*
         * Informations de session
         */
        $sessionData = $this->infosConnecte($request);
        /*
         * Locale en cours
         */
        $locale = $loginManager->getLocale();
        $this->data['locale'] = $locale;
        /*
         * On vérifie si l'utilisateur est connecté
         */
        $status = $loginManager->isConnecte($nomAction);
        if ($status['isConnecte']) {
            /*
             * Au cas ou l'utilisateur est connecté on vérifie s'il nest pas innactif. S'il est innactif
             * on garde en mémoire flash la route actuelle pour effectuer une redirection lors de la prochaine connexion
             */
            if ($status['isInnactif']) {
                $routeName = $request->get('_route');
                $routeParams = $request->get('_route_params');
                $this->get('session')->getFlashBag()->add('restoreUrl', $this->generateUrl($routeName, $routeParams));
                $this->get('session')->getFlashBag()->add('ina', "Vous avez accusé un long moment d'inactivité");
                return 1;// $this->redirect($this->generateUrl('app_admin_user_logout'));
            }
            /*
             * Seuls les utilisateurs admins ont le droit d'acceder à cette action
             */
            if (!$status['isUser']) {
                return 1;//$this->redirect($this->generateUrl('app_admin_user_logout'));
            }
        } else {
            return 1;//return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }

        /*
         * Gestion des droits
         */
        if (!$loginManager->getOrSetActions($module, $moduledescript, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à cette page");
            return 2;//$this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }
        return 0;
    }
    
}
