<?php

namespace admin\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Response;
use \admin\UserBundle\Entity\Module;
use \admin\CmsBundle\Entity\Zone;
use \admin\UserBundle\Services\LoginManager;
use \Symfony\Component\HttpFoundation\Request;

class ZoneController extends Controller {

    
use \admin\UserBundle\ControllerModel\paramUtilTrait;
    
    
    public function __construct() {
        $this->response = new Response();
        $this->response->headers->addCacheControlDirective('no-cache', true);
        $this->response->headers->addCacheControlDirective('max-age', 0);
        $this->response->headers->addCacheControlDirective('must-revalidate', true);
        $this->response->headers->addCacheControlDirective('no-store', true);
        $this->logMessage = " [ ZoneController ] ";
        $this->description = "Controlleur qui gère les utilisateurs";
    }

    /**
     *  Afficle la liste des zones
     * 
     *
     * @author armand.tevi@gmail.com
     *
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     *
     * @version 1
     * 
     *    
     * @access  public
     *
     * @return Response
     */
    public function listeZoneAction(Request $request, $idPage) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage de la liste des zones";
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
            if (!$status['isUser']) {
                return $this->redirect($this->generateUrl('app_admin_user_logout'));
            }
        } else {
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }

        /*
         * Gestion des droits
         */
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des zones");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }
        //Recuperation de l'établissement
        $idEtabl=$sessionData['etablissement'];
        
        $em = $this->getDoctrine()->getManager();
//        $zones = $em->getRepository($this->cmsBundle . 'Zone')->getAllZone();
        $zones = $em->getRepository($this->cmsBundle . 'Zone')->getAllZonePage($idPage,$idEtabl);
        $listeZones = $em->getRepository($this->cmsBundle . 'Zone')->getAllZoneSimplePage($idPage);
        //si la zone n'existe pas genere cela
        if(count($zones)!=count($listeZones)){
            
            //Recuperation Objet Etablissement
            $objetEtablissement = $em->getRepository($this->scolariteBundle . 'Etablissement')->find($idEtabl);
            
            foreach ($listeZones as $uneZone) {
                $objetZonePointeEtablissement = $em->getRepository($this->cmsBundle . 'ZonePointeEtablissement')->findBy(array("etablissement"=>$objetEtablissement,"zone"=>$uneZone));
                if(count($objetZonePointeEtablissement)==0){
                    $uneZoneEtabl = new \admin\CmsBundle\Entity\ZonePointeEtablissement();
                    $uneZoneEtabl->setEtablissement($objetEtablissement);
                    $uneZoneEtabl->setZone($uneZone);
                    $uneZoneEtabl->setTypeElement($uneZone->getTypeElement());
                    $em->persist($uneZoneEtabl);
                    $em->flush();
                }
            }
        }        
       $listeZoneUtilisable = $em->getRepository($this->cmsBundle . 'Zone')->getAllZonePage($idPage,$idEtabl); 
        if($sessionData['siAdminGeneral']){
            $articles = $em->getRepository($this->cmsBundle . 'Article')->getAllArticleActif();
            $rubriques = $em->getRepository($this->cmsBundle . 'Rubrique')->getAllRubriqueActif();
        }else{    
            $articles = $em->getRepository($this->cmsBundle . 'Article')->getAllArticleActifEtablissement($idEtabl,$idPr=0) ;
            $rubriques = $em->getRepository($this->cmsBundle . 'Rubrique')->getAllRubriqueEtablissement($idEtabl);
        }
        $this->data['zones'] = $listeZoneUtilisable;
        $this->data['articles'] = $articles;
        $this->data['rubriques'] = $rubriques;
        $this->data['locale'] = $locale;
        $this->data['idPage'] = $idPage;
        return $this->render($this->cmsBundle . 'Zone:listeZone.html.twig', $this->data, $this->response);
    }


    /**
     *  Formulaire d'ajout de zone
     * 
     *
     * @author armand.tevi@gmail.com
     *
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     *
     * @version 1
     * 
     *    
     * @access  public
     *
     * @return Response
     */
    public function ajoutZoneAction(Request $request, $idPage) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Ajout d'un nouveau zone";
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
            if (!$status['isUser']) {
                return $this->redirect($this->generateUrl('app_admin_user_logout'));
            }
        } else {
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }

        /*
         * Gestion des droits
         */
//        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
//            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
//            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'ajouter un zone");
//            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
//        }
         $em = $this->getDoctrine()->getManager();
        $zone = new Zone();
        $form = $this->createForm('\admin\CmsBundle\Form\ZoneType', $zone);
        $objetPage = $em->getRepository($this->cmsBundle . 'Page')->find($idPage);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $criteria = array('titreZone' => $zone->getTitreZone());
                $oldZone = $em->getRepository($this->cmsBundle . 'Zone')->findOneBy($criteria);
                if ($oldZone == NULL) {
                    $zone->setPage($objetPage);
                    $em->persist($zone);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('zone.ajout.success', 'Ajout effectue avec succès');
                    return $this->redirect($this->generateUrl('app_admin_zones',array('idPage'=>$idPage)));
                } else {
                    if ($oldZone->getEtatZone() == \admin\UserBundle\Types\TypeEtat::SUPPRIME) {
                        $oldZone->setEtatZone(\admin\UserBundle\Types\TypeEtat::ACTIF);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('zone.ajout.success', 'Ajout effectue avec succès');
                        return $this->redirect($this->generateUrl('app_admin_zones',array('idPage'=>$idPage)));
                    }
                    $this->get('session')->getFlashBag()->add('zone.ajout.already.exist', 'Le zone ' . $zone->getTitreZone() . ' existe déjà');
                }
            } else {
                $this->get('session')->getFlashBag()->add('zone.ajout.error', 'Formulaire invalide');
            }
        }
        $locale="";

        $this->data['formuView'] = $form->createView();
        $this->data['zone'] = $zone;
        $this->data['idPage'] = $idPage;
        $this->data['locale'] = $locale;

        return $this->render($this->cmsBundle . 'Zone:ajouterZone.html.twig', $this->data, $this->response);
    }

    /*
     * Modifie un zone
     * @param type $idZone
     * @return type
     */

    public function modifierZoneAction(Request $request, $idZone) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Modification d'un zone";
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
            if (!$status['isUser']) {
                return $this->redirect($this->generateUrl('app_admin_user_logout'));
            }
        } else {
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }

        /*
         * Gestion des droits
         */
//        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
//            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
//            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit de modifier un zone");
//            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
//        }
		$idEtabl = $sessionData['etablissement'];
		
        $em = $this->getDoctrine()->getManager();
		
        $zone = $em->getRepository($this->cmsBundle . 'Zone')->find((int)$idZone);
		$objetEtabl = $em->getRepository($this->scolariteBundle . 'Etablissement')->find((int)$idEtabl);
        if ($zone == NULL) {
            return $this->redirect($this->generateUrl('app_admin_zones',array('idPage'=>$zone->getPage()->getId())));
        }
        $form = $this->createForm('\admin\CmsBundle\Form\ZoneType', $zone);

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);
            if ($form->isValid()) {
                $criteria = array('titreZone' => $zone->getTitreZone());
                $oldZone = $em->getRepository($this->cmsBundle . 'Zone')->findOneBy($criteria);
				
				$criteriaPointe = array('zone' => $zone, 'etablissement' => $objetEtabl);
				$zonePointe = $em->getRepository($this->cmsBundle . 'ZonePointeEtablissement')->findOneBy($criteriaPointe);
				$zonePointe->setTypeElement($zone->getTypeElement());
				$zonePointe->setPointeVers($zone->getPointeVers());
                if ($oldZone == NULL) {
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('zone.modifier.success', 'Modifications effectuées avec succès');
                    return $this->redirect($this->generateUrl('app_admin_zones',array('idPage'=>$zone->getPage()->getId())));
                } else {

                    /*
                     * S'il s'agit du mm zone ou k le zone est supprimé on effecute la modification
                     */
                    if (($oldZone->getId() == $zone->getId()) || ($oldZone->getEtat() == \admin\UserBundle\Types\TypeEtat::SUPPRIME)) {
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('zone.modifier.success', 'Modifications effectuées avec succès');
                        return $this->redirect($this->generateUrl('app_admin_zones',array('idPage'=>$zone->getPage()->getId())));
                    }
                    $this->get('session')->getFlashBag()->add('zone.modifier.already.exist', 'Le zone ' . $zone->getTitreZone() . ' existe déjà');
                }
            } else {
                $this->get('session')->getFlashBag()->add('zone.modifier.error', 'Formulaire invalide');
            }
        }

        $this->data['formuView'] = $form->createView();
        $this->data['zone'] = $zone;
        $this->data['locale'] = $locale;
        $this->data['idZone'] = $idZone;

        return $this->render($this->cmsBundle . 'Zone:modifierZone.html.twig', $this->data, $this->response);
    }

    /*
     * Activation, suppression, désactivation de zone
     * @return Response
     */

    public function changerEtatZoneAction(Request $request) {
        $rep = array('etat' => FALSE, 'msg' => 'Erreur survenue lors du traitement');
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Activation, suppression, désactivation de zone";
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
                $rep['msg'] = "Vous avez accusé un long moment d'inactivité. Veuillez-vous connecter de nouveau";
                $loginManager->logout(LoginManager::SESSION_DATA_NAME);
                return new Response(json_encode($rep));
            }
            /*
             * Seuls les utilisateurs admins ont le droit d'acceder à cette action
             */
            if (!$status['isUser']) {
                return new Response(json_encode($rep));
            }
        } else {
            $rep['msg'] = "Vous êtes déconnecté. Veuillez-vous connecter de nouveau";
            return new Response(json_encode($rep));
        }

        /*
         * Gestion des droits
         */
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $rep['msg'] = "Vous n'avez pas le droit d'activer, de supprimer ou de désactiver un zone";
            return new Response(json_encode($rep));
        }

        $em = $this->getDoctrine()->getManager();
        if (($request->isXmlHttpRequest()) && ($request->getMethod() == 'POST')) {
            $tempEtat = (int) $request->request->get('etat');
            $tempIds = $request->request->get('sId');

            $etat = \admin\UserBundle\Types\TypeEtat::INACTIF;
            if ($tempEtat == 0) {
                $etat = \admin\UserBundle\Types\TypeEtat::INACTIF;
            } else if ($tempEtat == 1) {
                $etat = \admin\UserBundle\Types\TypeEtat::ACTIF;
            } else if ($tempEtat == 2) {
                $etat = \admin\UserBundle\Types\TypeEtat::SUPPRIME;
            } else {
                return new Response(json_encode($rep));
            }

            $tabIds = explode('|', $tempIds);
            $oneOk = FALSE;
            foreach ($tabIds as $idS) {
                $zone = $em->getRepository($this->cmsBundle . 'Zone')->find((int) $idS);
                if ($zone != NULL) {
                    $zone->setEtat($etat);
                    $em->flush();
                    $oneOk = TRUE;
                }
            }
            if ($oneOk) {
                $this->get('session')->getFlashBag()->add('zone.modifier.success', 'Opération éffectuée avec succès');
                $rep['msg'] = '';
                $rep['etat'] = TRUE;
            }

            return new Response(json_encode($rep));
        }
        return new Response(json_encode($rep));
    }

    
 /*
     * Changer les zones les élèments
     * @return Response
     */

    public function pointElementZoneAction(Request $request) {
        $rep = array('etat' => FALSE, 'msg' => 'Erreur survenue lors du traitement');
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Définir élément où pointe la zone ";
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
                $rep['msg'] = "Vous avez accusé un long moment d'inactivité. Veuillez-vous connecter de nouveau";
                $loginManager->logout(LoginManager::SESSION_DATA_NAME);
                return new Response(json_encode($rep));
            }
            /*
             * Seuls les utilisateurs admins ont le droit d'acceder à cette action
             */
            if (!$status['isUser']) {
                return new Response(json_encode($rep));
            }
        } else {
            $rep['msg'] = "Vous êtes déconnecté. Veuillez-vous connecter de nouveau";
            return new Response(json_encode($rep));
        }

        /*
         * Gestion des droits
         */
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $rep['msg'] = "Vous n'avez pas le droit de définir élément où pointe la zone";
            return new Response(json_encode($rep));
        }

        $em = $this->getDoctrine()->getManager();
        if (($request->isXmlHttpRequest()) && ($request->getMethod() == 'POST')) {
            $idPoint = (int) $request->request->get('idPointer');
            $idZone = (int) $request->request->get('idZone');
//            var_dump($idTemp);exit;
             $objetZone = $em->getRepository($this->cmsBundle . 'ZonePointeEtablissement')->find($idZone);  
             if($idPoint!=0){
                if(count($objetZone)!=0){               
                   $objetZone->setPointeVers($idPoint);
                   $em->flush();
                   $oneOk = TRUE;
                }else{
                   $oneOk = FALSE;
                }
             }else{
                 $oneOk = FALSE;
             }
            if ($oneOk) {
                $this->get('session')->getFlashBag()->add('template.modifier.success', 'Opération éffectuée avec succès');
                $rep['msg'] = '';
                $rep['etat'] = TRUE;
            }

            return new Response(json_encode($rep));
        }
        return new Response(json_encode($rep));
    }
    
//    public function mettrePropreZone($listeZone){
//        $tabZone = array();
//        foreach($listeZone as $uneZone){
//           $tabZone[] 
//        }
//        
//    }
    
}
