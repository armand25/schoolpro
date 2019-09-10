<?php

namespace admin\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Response;
use \admin\UserBundle\Entity\Module;
use \admin\CmsBundle\Entity\Page;
use \admin\UserBundle\Services\LoginManager;
use \Symfony\Component\HttpFoundation\Request;

class PageController extends Controller {

    
use \admin\UserBundle\ControllerModel\paramUtilTrait;
    
    
    public function __construct() {
        $this->response = new Response();
        $this->response->headers->addCacheControlDirective('no-cache', true);
        $this->response->headers->addCacheControlDirective('max-age', 0);
        $this->response->headers->addCacheControlDirective('must-revalidate', true);
        $this->response->headers->addCacheControlDirective('no-store', true);
        $this->logMessage = " [ PageController ] ";
        $this->description = "Controlleur qui gère les utilisateurs";
    }

    /**
     *  Afficle la liste des pages
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
    public function listePageAction(Request $request, $idTemplate) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage de la liste des pages";
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
//        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idPage'])) {
//            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
//            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des pages");
//            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
//        }

      
        
        $em = $this->getDoctrine()->getManager();
//        $pages = $em->getRepository($this->cmsBundle . 'Page')->getAllPage();
        $pages = $em->getRepository($this->cmsBundle . 'Page')->getAllPageTemplate($idTemplate);

        $this->data['pages'] = $pages;
        $this->data['locale'] = $locale;
        $this->data['idTemplate'] = $idTemplate;
        return $this->render($this->cmsBundle . 'Page:listePage.html.twig', $this->data, $this->response);
    }


    /**
     *  Formulaire d'ajout de page
     * 
     *$idTemplate
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
    public function ajoutPageAction(Request $request, $idTemplate) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Ajout d'un nouveau page";
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
//        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idPage'])) {
//            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
//            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'ajouter un page");
//            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
//        }
         $em = $this->getDoctrine()->getManager();
        $page = new Page();
        $form = $this->createForm('\admin\CmsBundle\Form\PageType', $page);
        $objetTemplate = $em->getRepository($this->cmsBundle . 'Template')->find($idTemplate);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

           

//            if ($form->isValid()) {
                $criteria = array('titrePage' => $page->getTitrePage());
                $oldPage = $em->getRepository($this->cmsBundle . 'Page')->findOneBy($criteria);
                if ($oldPage == NULL) {
                    $page->setTemplate($objetTemplate);
                    $em->persist($page);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('page.ajout.success', 'Ajout effectue avec succès');
                    return $this->redirect($this->generateUrl('app_admin_pages',array('idTemplate'=>$idTemplate)));
                } else {
                    if ($oldPage->getEtat() == \admin\UserBundle\Types\TypeEtat::SUPPRIME) {
                        $oldPage->setEtat(\admin\UserBundle\Types\TypeEtat::ACTIF);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('page.ajout.success', 'Ajout effectue avec succès');
                        return $this->redirect($this->generateUrl('app_admin_pages',array('idTemplate'=>$idTemplate)));
                    }
                    $this->get('session')->getFlashBag()->add('page.ajout.already.exist', 'Le page ' . $page->getTitrePage() . ' existe déjà');
                }
//            } else {
//                $this->get('session')->getFlashBag()->add('page.ajout.error', 'Formulaire invalide');
//            }
        }
        $locale="";

        $this->data['formuView'] = $form->createView();
        $this->data['page'] = $page;
        $this->data['locale'] = $locale;
        $this->data['idTemplate'] = $idTemplate;

        return $this->render($this->cmsBundle . 'Page:ajouterPage.html.twig', $this->data, $this->response);
    }

    /*
     * Modifie un page
     * @param type $idPage
     * @return type
     */

    public function modifierPageAction(Request $request, $idPage) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Modification d'un page";
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
//        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idPage'])) {
//            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
//            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit de modifier un page");
//            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
//        }

        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository($this->cmsBundle . 'Page')->find($idPage);
        if ($page == NULL) {
            return $this->redirect($this->generateUrl('app_admin_pages'));
        }
        $form = $this->createForm('\admin\CmsBundle\Form\PageModifType', $page);

        if ($request->isMethod('POST')) {
             $idTemplate = $request->get('idTemplate');
            $form->handleRequest($request);
            if ($form->isValid()) {
                $criteria = array('titrePage' => $page->getTitrePage());
                $oldPage = $em->getRepository($this->cmsBundle . 'Page')->findOneBy($criteria);
                
                if ($oldPage == NULL) {
                    //$page->
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('page.modifier.success', 'Modifications effectuées avec succès');
                    return $this->redirect($this->generateUrl('app_admin_pages',array('idTemplate'=>$page->getTemplate()->getId())));
                } else {

                    /*
                     * S'il s'agit du mm page ou k le page est supprimé on effecute la modification
                     */
                    if (($oldPage->getId() == $page->getId()) || ($oldPage->getEtat() == \admin\UserBundle\Types\TypeEtat::SUPPRIME)) {
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('page.modifier.success', 'Modifications effectuées avec succès');
                        return $this->redirect($this->generateUrl('app_admin_pages'));
                    }
                    $this->get('session')->getFlashBag()->add('page.modifier.already.exist', 'Le page ' . $page->getTitrePage() . ' existe déjà');
                }
            } else {
                $this->get('session')->getFlashBag()->add('page.modifier.error', 'Formulaire invalide');
            }
        }

        $this->data['formuView'] = $form->createView();
        $this->data['page'] = $page;
        $this->data['locale'] = $locale;
        $this->data['idPage'] = $idPage;

        return $this->render($this->cmsBundle . 'Page:modifierPage.html.twig', $this->data, $this->response);
    }

    /*
     * Activation, suppression, désactivation de page
     * @return Response
     */

    public function changerEtatPageAction(Request $request) {
        $rep = array('etat' => FALSE, 'msg' => 'Erreur survenue lors du traitement');
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Activation, suppression, désactivation de page";
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
            $rep['msg'] = "Vous n'avez pas le droit d'activer, de supprimer ou de désactiver un page";
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
                $page = $em->getRepository($this->cmsBundle . 'Page')->find((int) $idS);
                if ($page != NULL) {
                    $page->setEtatPage($etat);
                    $em->flush();
                    $oneOk = TRUE;
                }
            }
            if ($oneOk) {
                $this->get('session')->getFlashBag()->add('page.modifier.success', 'Opération éffectuée avec succès');
                $rep['msg'] = '';
                $rep['etat'] = TRUE;
            }

            return new Response(json_encode($rep));
        }
        return new Response(json_encode($rep));
    }

}
