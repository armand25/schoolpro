<?php

namespace admin\UserBundle\Controller;

use admin\UserBundle\Entity\Module;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use \admin\UserBundle\Services\LoginManager;
use \admin\UserBundle\Types\TypeCodeProfil;
use \Symfony\Component\HttpFoundation\Request;

class DroitController extends Controller {

    

use \admin\UserBundle\ControllerModel\paramUtilTrait;    

    private $environnement = 'dev';

//    private $environnement = 'prod';

    public function __construct() {
        $this->response = new Response;
        $this->response->headers->addCacheControlDirective('no-cache', true);
        $this->response->headers->addCacheControlDirective('max-age', 0);
        $this->response->headers->addCacheControlDirective('must-revalidate', true);
        $this->response->headers->addCacheControlDirective('no-store', true);
        $this->logMessage = " [ DroitController ] ";
        $this->description = "Controlleur qui gère les  droits d'accès aux fonctionnalités";
    }

    /*
     * Affiche un message lorsk on refuse l'acces à une page au connecté
     * @return type
     */
    public function accessDeniedAction(Request $request) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage un message lorsqu'on refuse l'accès à une page à un utilisateur";
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
            $msg = $this->get('session')->getFlashBag()->get('access');
            if (is_array($msg) && (count($msg) > 0 )) {
                $this->logMessage .= ' [  ' . $msg[0] . ' ] ';
                // $loginManager->writeLogMessage($this->logMessage);
                $this->data['msg'] = $msg[0];
                return $this->render($this->userBundle . 'DroitUser:accessDenied.html.twig', $this->data, $this->response);
            } else {
                return $this->redirect($this->generateUrl('app_admin_user_home'));
            }
        } else {
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }
    }

    /*
     * Affiche un message lorsk on refuse l'acces à une page au connecté
     * @return type
     */
    public function accessLicenceAction(Request $request) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage un message lorsqu'on refuse l'accès à une page à un utilisateur";
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

                return $this->render($this->userBundle . 'DroitUser:accessLicence.html.twig', $this->data, $this->response);
        
    }

    /*
     * affiche la page d'attribution de droits à un profil d'utiliateur
     * 
     * @param int $idProfil
     * 
     * @return type
     */
    public function profilAccessAction(Request $request,$idProfil) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage de la page d'attribution de droits à un profil d'utiliateur";
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
                return $this->redirect($this->generateUrl('app_admin_user_logout'));
            }
            /*
             * Seuls les utilisateurs admins ont le droit d'acceder à cette action
             */
            if (!$status['isUser'] && ($this->environnement != 'dev')) {
                return $this->redirect($this->generateUrl('app_admin_user_logout'));
            }
        } else {
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }

        /*
         * Gestion des droits
         */
        if (!$loginManager->getOrSetActions(Module::MOD_DROIT, Module::MOD_DROIT_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            /*
             * Le mantenancier peut attribuer les droits à tout le monde.
             * En environnement de developpement, tout le monde peut attribuer les droits
             */
            if (($this->environnement != 'dev')) {
                if ($sessionData['codeProfil'] != TypeCodeProfil::SUPERVISEUR) {
                    $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
                    $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la page d'attribution de droits à un profil d'utiliateur");
                    return ($status['isUser']) ? $this->redirect($this->generateUrl('app_admin_user_access_refuse')) :
                            $this->redirect($this->generateUrl('app_pub_site_user_access_refuse'));
                }
            }
        }

        $em = $this->getDoctrine()->getManager();
        $profil = $em->getRepository($this->userBundle . 'Profil')->find($idProfil);
        if ($profil == NULL) {
            return $this->redirect($this->generateUrl('app_admin_user_home'));
        }
        $this->data['profil'] = $profil;
        $this->data['tabIdActionProfil'] = $profil->getTabIdActions();
        $this->data['modules'] = $em->getRepository($this->userBundle . 'Module')->findBy(array('etat'=>1));
        $this->data['idProfil'] = $idProfil;

        return $this->render($this->userBundle . 'DroitUser:manageDroitProfil.html.twig', $this->data, $this->response);
    }

    /*
     * Mèt à  jour des droits d'un profil par ajax
     * 
     */
    public function updateDroitProfilAction(Request $request) {
        
        $rep = array('etat' => FALSE, 'msg' => 'Erreur survenue');
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Mise à jour des droits d'un profil";
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
                $rep['msg'] .= ". Veillez vous connecter de nouveau";
                $loginManager->logout(LoginManager::SESSION_DATA_NAME);
                return new Response(json_encode($rep));
            }
            /*
             * Seuls les utilisateurs admins ont le droit d'acceder à cette action
             */
            if (!$status['isUser'] && ($this->environnement != 'dev')) {
                return new Response(json_encode($rep));
            }
        } else {
            $rep['msg'] .= ". Veillez vous connecter de nouveau";
            return new Response(json_encode($rep));
        }



        /*
         * Gestion des droits
         */
        if (!$loginManager->getOrSetActions(Module::MOD_DROIT, Module::MOD_DROIT_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            /*
             * Le mantenancier peut attribuer les droits à tout le monde.
             * En environnement de developpement, tout le monde peut attribuer les droits
             */
            if (($this->environnement != 'dev')) {
                if ($sessionData['codeProfil'] != TypeCodeProfil::SUPERVISEUR) {
                    $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
                    $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la page d'attribution de droits à un profil d'utiliateur");
                    $rep['msg'] = "Vous n'avez pas le droit d'attribution des droits à un profil d'utiliateur";
                    return new Response(json_encode($rep));
                }
            }
        }
        
        if ($request->isXmlHttpRequest() && $request->isMethod('POST')) {
            $ids = $request->request->get('idDroits');
            $idProfil = (int) $request->request->get('idProfil');
            $nomTable = 'Profil';
            $em = $this->getDoctrine()->getManager();
            $profil = $em->getRepository($this->userBundle . $nomTable)->find($idProfil);
            if ($profil == NULL) {
                $rep['msg'] = "Informations introuvables";
                return new Response(json_encode($rep));
            }
            $rep['etat'] = $loginManager->updateDroitOfProfil($idProfil, explode('|', trim($ids)), $nomTable);
            if ($rep['etat']) {
                $this->get('session')->getFlashBag()->add('success', 'Modifications réussies');
            }
        }
        return new Response(json_encode($rep));
    }

}
