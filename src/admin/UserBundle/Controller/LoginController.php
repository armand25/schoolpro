<?php

namespace admin\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Response;
use \admin\UserBundle\Services\LoginManager;
use \admin\ParamBundle\Types\TypeParametre;
use \admin\UserBundle\Types\TypeCodeProfil;
use \admin\UserBundle\Types\TypeEtat;
use \admin\UserBundle\Entity\Connexion;
use \admin\UserBundle\Entity\Utilisateur;
use \Symfony\Component\HttpFoundation\Request;

/**
 * Controller qui gere l'authentification
 * @author armand.tevi@gmail.com
 * @copyright Master Internationnal  - UTBM/ IAI/CIC/
 * @version 1
 * @property Response $response
 */
class LoginController extends Controller {
    
use \admin\UserBundle\ControllerModel\paramUtilTrait;

    public function __construct() {
        $this->response = new Response();
        $this->response->headers->addCacheControlDirective('no-cache', true);
        $this->response->headers->addCacheControlDirective('max-age', 0);
        $this->response->headers->addCacheControlDirective('must-revalidate', true);
        $this->response->headers->addCacheControlDirective('no-store', true);
        $this->logMessage = " [ LoginController ] ";
        $this->description = "Controlleur qui gère la connexion";
    }

    
    /**
     * Donne acces la page d'accueil après athentification
     * 
     * @example indexAction()
     *
     * @author armand.tevi@gmail.com
     *
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     *
     *
     * @version 1 
     *            
     * @access  public
     *
     */
    public function indexAction(Request $request) {
        $nomAction = __FUNCTION__;

        $class = $this->getNomClassRun(__CLASS__);

        $this->infosConnecte($request);
        $loginManager = $this->get('login_manager');
        $locale = $loginManager->getLocale();
        $status = $loginManager->isConnecte($nomAction);

        //$loginManager->addDefaultParametre();

        $parametreManager = $this->get('parametre_manager');
        /*
         * On vérifie si les parametres par défaut sont crées, si non on les ajoute
         */
        $paramTest = $parametreManager->getValeurParametre(TypeParametre::DUREE_TIME_OUT_INT);
        if ($paramTest == NULL) {
            $loginManager->addDefaultParametre();
        }

        if ($status['isConnecte']) {
            if ($status['isInnactif']) {
                $this->get('session')->getFlashBag()->add('ina', "Vous avez accusé un long moment d'inactivité");
                return ($status['isUser']) ? $this->redirect($this->generateUrl('app_admin_user_logout')) :
                        $this->redirect($this->generateUrl('app_admin_user_logout'));
            }
            if ($status['isUser']) {
                return $this->redirect($this->generateUrl('app_admin_user_home'));
            } else {
                return $this->redirect($this->generateUrl('app_pub_site_user_index'));
            }
        }

        return $this->redirect($this->generateUrl('app_admin_user_login'));
    }

    /**
     *  Affiche la page de connexion des users
     * 
     * @example indexAction()
     *
     * @author armand.tevi@gmail.com
     *
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     *
     *
     * @version 1 
     *            
     * @access  public
     *
     * @return Response
     */
    public function loginAction(Request $request) {
        //$this->infosConnecte();
        $em = $this->getDoctrine()->getManager();
        // $this->addUserTmp();
        $this->logMessage .= ' [ loginAction ] ';
        $loginManager = $this->get('login_manager');
//          $loginManager->addDefaultParametre();
//          $loginManager->logout(LoginManager::SESSION_DATA_NAME); exit;
        $locale = $loginManager->getLocale();
        $loginManager->autoLogOutAfterTimeOutAll(); // mis à 0 des etatConnecte
        $status = $loginManager->isConnecte('loginAction');

        $parametreManager = $this->get('parametre_manager');
        /*
         * On vérifie si les parametres par défaut sont crées, si non on les ajoute
         */
        
        $paramTest = $parametreManager->getValeurParametre(TypeParametre::DUREE_TIME_OUT_INT);
        //var_dump($paramTest);exit;
        if ($paramTest == NULL) {
            //var_dump('ok');exit;
            $loginManager->addDefaultParametre();
        }

        if ($status['isConnecte'] && $status['isUser']) {
            if ($status['isInnactif']) {
                $this->get('session')->getFlashBag()->add('ina', "Vous avez accusé un long moment d'inactivité");
                return $this->redirect($this->generateUrl('app_admin_user_logout'));
            }
            //return $this->redirect($this->generateUrl('app_admin_user_home'));
            return $this->redirect($this->generateUrl('app_admin_user_home'));
        }

        $loginManager->logout(LoginManager::SESSION_DATA_NAME);
        $utilsManager = $this->get('utils_manager');
        $maxAttempt = $loginManager->getMaxAttempt();
        $longueurTel = $loginManager->getLengthTel();

        if ($request->getMethod() === 'POST') { // formulaire soumis
            $email = trim($request->request->get('email')); // email saisie
            $password = trim($request->request->get('password')); // password saisie
            $restoreUrl = $request->request->get('restoreUrl'); // route de redirection (au cas ou l'utilisateur aurait été déconnecté suite à une période d'innactivité)

            
            if (($email != NULL) && ($password != NULL) && !empty($email) && !empty($password) ) {
                
                // formulaire rempli
                $user = $em->getRepository($this->userBundle . 'Utilisateur')->getOneUserOnLogin($email, md5($password));
                
                if ($user == NULL) { // utilisateur introuvable avec le email et l password saisie
                    $this->logMessage .=' [ USER INTROUVABLE AVEC PASS ET TEL ] ';
                    
                    // on cherche l'utilisateur avec l emaol seulement
                    $user = $em->getRepository($this->userBundle . 'Utilisateur')->getOneUserOnLoginForAttempt($email);
                    
                    if ($user != NULL) { // L'utilisateur existe ds la db,il y a erreur de saisie de  password
                        $this->logMessage .=' [ USER TROUVE AVEC  TEL UNIQUEMENT ] ';
                        if ($user->getAttempt() >= $maxAttempt) { // user deja bloque
                            $this->logMessage .= ' [ USER ( ' . $email . ' ) BLOQUE TENTE DE SE CONNECTE ] ';
                            $this->get('session')->getFlashBag()->add('login.user.lock', 'Votre compte est bloqué');
                            // $loginManager->writeLogMessage($this->logMessage);
                            return $this->redirect($this->generateUrl('app_admin_user_login'));
                        }
                        
                        // si ce n'est pas  l'administrateur
                        $profil = $user->getProfil();
                        if ($profil->getCode() != TypeCodeProfil::MAINTENANCE) {
                            $user->setAttempt($user->getAttempt() + 1);
                        }
                        
                        if ($user->getAttempt() >= $maxAttempt) { // nombre de tentatives > 3
                            $user->setEtat(TypeEtat::BLOQUE);
                            $this->logMessage .= ' [ USER ( ' . $email . ' ) BLOQUE ] ';
                            $this->get('session')->getFlashBag()->add('login.user.lock', 'Votre compte est bloqué');
                            // $loginManager->writeLogMessage($this->logMessage);
                            return $this->redirect($this->generateUrl('app_admin_user_login'));
                        }
                        
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('login.form.ivalide', 'Email et/ou mot de passe invalides');
                    } else {
                        $this->get('session')->getFlashBag()->add('login.form.ivalide', 'Email et/ou mot de passe invalides');
                    }
                    $this->logMessage .=' [ USER INTROUVABLE AVEC EMAIl UNIQUEMENT ] ';
                    //$loginManager->writeLogMessage($this->logMessage);
                } else { // utilisateur trouve avec tel et password saisie
                    if (($user->getAttempt() >= $maxAttempt) || ($user->getEtat() == TypeEtat::BLOQUE)) {
                        $this->logMessage .= ' [ USER ( ' . $email . ' ) BLOQUE TENTE DE SE CONNECTER ] ';
                        $this->get('session')->getFlashBag()->add('login.user.lock', 'Votre compte est bloqué');
                        //$loginManager->writeLogMessage($this->logMessage);
                        return $this->redirect($this->generateUrl('app_admin_user_login'));
                    }

                    /*
                     * Utilisateur deja connecté
                     */
                    if ($user->getEtatConnecte()) {
                        /*
                         * On ne bloque pas le super administrateur
                         */
                        if ($user->getProfil()->getCode() != TypeCodeProfil::MAINTENANCE) {
                            $this->get('session')->getFlashBag()->add('login.user.already.login', 'Utilisateur déjà connecté');
                            return $this->redirect($this->generateUrl('app_admin_user_login'));
                        }
                    }
                    /*
                     * Le profil de l'utilisateur est desactivé
                     */
                    if (($user->getProfil()->getEtat() == TypeEtat::INACTIF) || ($user->getEtat() == TypeEtat::INACTIF)) {
                        $this->get('session')->getFlashBag()->add('login.user.lock', 'Votre compte est désactivé');
                        return $this->redirect($this->generateUrl('app_admin_user_login'));
                    }
                    
                    $adresseIp = $_SERVER['REMOTE_ADDR'];
                    $user->setAdresseIp($adresseIp);
                    $con = new Connexion($adresseIp);
                    $con->setUtilisateur($user);
                    $con->setAdresseIp($adresseIp);
                    $user->addConnexion($con);
                    

                    $em->persist($con);
                    $em->flush();
                    
                    $sessionData = $this->createSessionDataUser($user, $con->getId());

                    $loginManager->setSessionData(LoginManager::SESSION_DATA_NAME, $sessionData);

                    $user->setAttempt(0);
                    $user->setEtatConnecte(TRUE);
                                      
                    $em->flush();
                    $this->logMessage .= ' [ CONNEXION RÉUSSIE ] ';
                    if (($restoreUrl != NULL) && (!empty($restoreUrl)) && (strlen($restoreUrl) > 0)) {
                        return $this->redirect($restoreUrl);
                    }                                       
                    //Verifier si vous avez le droit d'utilisaer le produit
//                    $objetCodeOp = $em->getRepository($this->stockBundle . 'Xcdsctxsl')->find(1);
//                    $codeOp =$objetCodeOp->getNomXcdsctxsl();
                    
//                    if($codeOp != $this->getParameter('secret')){
//                        return $this->redirect($this->generateUrl('app_admin_user_licence_refuse'));
//                    }else{
                         return $this->redirect($this->generateUrl('app_admin_user_home'));
//                    }
                }
            } else { // formulaire nom rempli
                $msg = "Formulaire Invalide. Les champs Email et Mot de passe sont obligatoires";
                $this->get('session')->getFlashBag()->add('login.form.ivalide', 'Les champs Email et Mot de passe sont obligatoires');
                $this->logMessage .= ' [ ' . $msg . ' ] ';
                // $loginManager->writeLogMessage($this->logMessage);
            }
        }
        
        
        
        $this->logMessage .= ' [ affichage du formualire de login ] ';
        $this->data['email'] = '';
        $this->data['maxAttempt'] = $maxAttempt;
        $this->data['longueurTel'] = $longueurTel;
        $this->data['password'] = '';
        $this->data['locale'] = $locale;
        return $this->render($this->userBundle . 'Login:formLogin.html.twig', $this->data, $this->response);
    }

    /**
     *  déconnexion du connecté
     * 
     * @example logoutAction()
     *
     * @author armand.tevi@gmail.com
     *
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     *
     *
     * @version 1 
     *            
     * @access  public
     *
     * @return Response
     */
    public function logoutAction() {
        $nomAction = __FUNCTION__;
        $em = $this->getDoctrine()->getManager();
        $class = $this->getNomClassRun(__CLASS__);
        $this->logMessage .= ' [ ' . $nomAction . ' ] ';
        $loginManager = $this->get('login_manager');
        $locale = $loginManager->getLocale();
        $status = $loginManager->isConnecte($nomAction);
        $isUser = $status['isUser'];
        if ($status['isConnecte']) {
            $loginManager->logout(LoginManager::SESSION_DATA_NAME);                        
            $this->get('session')->getFlashBag()->add('logout.success', 'Vous êtes déconnecté');
        }
        if ($isUser) {           
            return $this->redirect($this->generateUrl('app_admin_user_login'));
        }else{
            return $this->redirect($this->generateUrl('app_admin_user_login'));
        }
    }
    /**
     *  déconnexion du connecté
     * 
     * @example logoutAction()
     *
     * @author armand.tevi@gmail.com
     *
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     *
     *
     * @version 1 
     *            
     * @access  public
     *
     * @return Response
     */
    public function logoutChoixDateAction() {
        $nomAction = __FUNCTION__;
        $em = $this->getDoctrine()->getManager();
        $class = $this->getNomClassRun(__CLASS__);
        $this->logMessage .= ' [ ' . $nomAction . ' ] ';
        $loginManager = $this->get('login_manager');
        $locale = $loginManager->getLocale();
        $status = $loginManager->isConnecte($nomAction);
        $isUser = $status['isUser'];
        if ($status['isConnecte']) {
            $loginManager->logout(LoginManager::SESSION_DATA_NAME);
            
            
            $this->get('session')->getFlashBag()->add('logout.success', 'Vous êtes déconnecté');
        }
        if ($isUser) {           
            return $this->redirect($this->generateUrl('app_admin_user_login'));
        }else{
            return $this->redirect($this->generateUrl('app_admin_user_login'));
        }
    }

    /*
     * Création des variables de sessions
     * 
     * @param Utilisateur $user
     * @param type $idConnexion
     * @return type
     */

    private function createSessionDataUser(Utilisateur $user, $idConnexion) {
        $em = $this->getDoctrine()->getManager();
        $typeManager = $this->get('type_manager');
        $rep = array();
        $tabArticle = array();
        $rep['isUser'] = TRUE;
        $rep['isAbonne'] = FALSE;
        $rep['nomTableConnecte'] = \admin\UserBundle\Types\TypeTable::UTILISATEUR;
        $rep['id'] = $user->getId();
        $rep['prenoms'] = $user->getPrenoms();
        $rep['nom'] = $user->getNom();
        $rep['tel1'] = $user->getTel1();
        $rep['idConnexion'] = $idConnexion;
        $rep['sexe'] = $typeManager->convertTypeSexe($user->getSexe());
        $rep['username'] = $user->getUsername();
        if($user->getSiAdminGeneral()==1){
            $rep['siAdminGeneral'] = TRUE;
        }else{
            $rep['siAdminGeneral'] = FALSE; 
        }
        $rep['idProfil'] = $user->getProfil()->getId();
        $rep['nbMessageNonLu'] = $em->getRepository($this->messagerieBundle . 'Envoi')->getNbMessageNonLu(FALSE, $user->getId());
        $rep['libelleProfil'] = $user->getProfil()->getNom();
        $tabMsgNonLu = array();
        $i = 0;
        $listeConnexion = $em->getRepository($this->userBundle . 'Connexion')->getDerniereConnexion($user->getId());
        
        $listeMessageNonLu = $em->getRepository($this->messagerieBundle . 'Envoi')->getListeMessageNonLu($user->getId());
        $rep['etablissement'] = $user->getEtablissement()->getId();
        $rep['nomEtablissement'] = $user->getEtablissement()->getLibelleEtablissement();
        $listeArticle = $em->getRepository($this->cmsBundle . 'Article')->getAllArticleEtablissement($rep['etablissement'],$rep['idProfil']);
        
        foreach ($listeMessageNonLu as $unMessageNonLu) {
            $tabMsgNonLu[$i]['titre']= $unMessageNonLu['objet'];
           // $tabMsgNonLu[$i]=
            $i++;
        }
        $i = 0;
        foreach ($listeArticle as $unArticle) {
            $tabArticle[$i]['titre']= $unArticle->getTitre();
            $tabArticle[$i]['id']= $unArticle->getId();
           // $tabMsgNonLu[$i]=
            $i++;
        }
        if(count($listeConnexion)>1){
           $rep['dateLastConnexion']=$listeConnexion[1]->getDateConnexion() ;
           $rep['idLastConnexion']=$listeConnexion[1]->getId() ;
            
        }else{
            $rep['dateLastConnexion']= new \DateTime();
            $rep['idLastConnexion']=  0;
        }
        
        
        $rep['codeProfil'] = $user->getProfil()->getCode();
        
        if($user->getProfil()->getId()== 5){
            $rep['idEleve']=$user->getEleve()->getId();
        }
        $rep['idannee'] = 1; //$user->getProfil()->getCode();
        $rep['tabIdActions'] = $user->getProfil()->getTabIdActions();
        $rep['homeUrl'] = $this->generateUrl('app_admin_user_index');
        $rep['tabMsgNonLu'] = $tabMsgNonLu;
        $rep['tabArticle'] = $tabArticle;
        //$rep['tabIdActions'] = $user->getProfil()->getTabIdActions();                
        return $rep;
    }

}
