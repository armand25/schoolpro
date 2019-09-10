<?php

namespace admin\ScolariteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Response;
use \admin\UserBundle\Entity\Module;
use \admin\ScolariteBundle\Entity\HeureCours;
use \admin\UserBundle\Services\LoginManager;
use \Symfony\Component\HttpFoundation\Request;

class HeureCoursController extends Controller {

    
use \admin\UserBundle\ControllerModel\paramUtilTrait;
    
    
    public function __construct() {
        $this->response = new Response();
        $this->response->headers->addCacheControlDirective('no-cache', true);
        $this->response->headers->addCacheControlDirective('max-age', 0);
        $this->response->headers->addCacheControlDirective('must-revalidate', true);
        $this->response->headers->addCacheControlDirective('no-store', true);
        $this->logMessage = " [ HeureCoursController ] ";
        $this->description = "Controlleur qui gère les utilisateurs";
    }

    /**
     *  Afficle la liste des heurecourss
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
    public function listeHeureCoursAction(Request $request) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage de la liste des heurecourss";
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
//        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idHeureCours'])) {
//            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
//            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des heurecourss");
//            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
//        }

      
        
        $em = $this->getDoctrine()->getManager();
        $heurecourss = $em->getRepository($this->scolariteBundle . 'HeureCours')->getAllHeureCours();

        $this->data['heurecourss'] = $heurecourss;
        $this->data['locale'] = $locale;
        return $this->render($this->scolariteBundle . 'HeureCours:listeHeureCours.html.twig', $this->data, $this->response);
    }


    /**
     *  Formulaire d'ajout de heurecours
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
    public function ajoutHeureCoursAction(Request $request) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Ajout d'un nouveau heurecours";
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
//        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idHeureCours'])) {
//            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
//            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'ajouter un heurecours");
//            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
//        }

        $heurecours = new HeureCours();
        $form = $this->createForm('\admin\ScolariteBundle\Form\HeureCoursType', $heurecours);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();

            if ($form->isValid()) {
                $criteria = array('libelleHeureCours' => $heurecours->getLibelleHeureCours());
                $oldHeureCours = $em->getRepository($this->scolariteBundle . 'HeureCours')->findOneBy($criteria);
                if ($oldHeureCours == NULL) {
                    //$heurecours->setDateInitialHeureCours(new \DateTime());
                    $em->persist($heurecours);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('heurecours.ajout.success', 'Ajout effectue avec succès');
                    return $this->redirect($this->generateUrl('app_admin_heurecourss'));
                } else {
                    if ($oldHeureCours->getEtat() == \admin\UserBundle\Types\TypeEtat::SUPPRIME) {
                        $oldHeureCours->setEtat(\admin\UserBundle\Types\TypeEtat::ACTIF);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('heurecours.ajout.success', 'Ajout effectue avec succès');
                        return $this->redirect($this->generateUrl('app_admin_heurecourss'));
                    }
                    $this->get('session')->getFlashBag()->add('heurecours.ajout.already.exist', 'Le heurecours ' . $heurecours->getNom() . ' existe déjà');
                }
            } else {
                $this->get('session')->getFlashBag()->add('heurecours.ajout.error', 'Formulaire invalide');
            }
        }
        $locale="";

        $this->data['formuView'] = $form->createView();
        $this->data['heurecours'] = $heurecours;
        $this->data['locale'] = $locale;

        return $this->render($this->scolariteBundle . 'HeureCours:ajouterHeureCours.html.twig', $this->data, $this->response);
    }

    /*
     * Modifie un heurecours
     * @param type $idHeureCours
     * @return type
     */

    public function modifierHeureCoursAction(Request $request, $idHeureCours) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Modification d'un heurecours";
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
//        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idHeureCours'])) {
//            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
//            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit de modifier un heurecours");
//            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
//        }

        $em = $this->getDoctrine()->getManager();
        $heurecours = $em->getRepository($this->scolariteBundle . 'HeureCours')->find($idHeureCours);
        if ($heurecours == NULL) {
            return $this->redirect($this->generateUrl('app_admin_heurecourss'));
        }
        $form = $this->createForm('\admin\ScolariteBundle\Form\HeureCoursType', $heurecours);

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);
            if ($form->isValid()) {
                $criteria = array('libelleHeureCours' => $heurecours->getLibelleHeureCours());
                $oldHeureCours = $em->getRepository($this->scolariteBundle . 'HeureCours')->findOneBy($criteria);
                if ($oldHeureCours == NULL) {
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('heurecours.modifier.success', 'Modifications effectuées avec succès');
                    return $this->redirect($this->generateUrl('app_admin_heurecourss'));
                } else {

                    /*
                     * S'il s'agit du mm heurecours ou k le heurecours est supprimé on effecute la modification
                     */
                    if (($oldHeureCours->getId() == $heurecours->getId()) || ($oldHeureCours->getEtat() == \admin\UserBundle\Types\TypeEtat::SUPPRIME)) {
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('heurecours.modifier.success', 'Modifications effectuées avec succès');
                        return $this->redirect($this->generateUrl('app_admin_heurecourss'));
                    }
                    $this->get('session')->getFlashBag()->add('heurecours.modifier.already.exist', 'Le heurecours ' . $heurecours->getNom() . ' existe déjà');
                }
            } else {
                $this->get('session')->getFlashBag()->add('heurecours.modifier.error', 'Formulaire invalide');
            }
        }

        $this->data['formuView'] = $form->createView();
        $this->data['heurecours'] = $heurecours;
        $this->data['locale'] = $locale;
        $this->data['idHeureCours'] = $idHeureCours;

        return $this->render($this->scolariteBundle . 'HeureCours:modifierHeureCours.html.twig', $this->data, $this->response);
    }

    /*
     * Activation, suppression, désactivation de heurecours
     * @return Response
     */

    public function changerEtatHeureCoursAction(Request $request) {
        $rep = array('etat' => FALSE, 'msg' => 'Erreur survenue lors du traitement');
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Activation, suppression, désactivation de heurecours";
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idHeureCours'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $rep['msg'] = "Vous n'avez pas le droit d'activer, de supprimer ou de désactiver un heurecours";
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
                $heurecours = $em->getRepository($this->scolariteBundle . 'HeureCours')->find((int) $idS);
                if ($heurecours != NULL) {
                    $heurecours->setEtat($etat);
                    $em->flush();
                    $oneOk = TRUE;
                }
            }
            if ($oneOk) {
                $this->get('session')->getFlashBag()->add('heurecours.modifier.success', 'Opération éffectuée avec succès');
                $rep['msg'] = '';
                $rep['etat'] = TRUE;
            }

            return new Response(json_encode($rep));
        }
        return new Response(json_encode($rep));
    }

}
