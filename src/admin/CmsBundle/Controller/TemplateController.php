<?php

namespace admin\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Response;
use \admin\UserBundle\Entity\Module;
use \admin\CmsBundle\Entity\Template;
use \admin\CmsBundle\Entity\Page;
use \admin\UserBundle\Services\LoginManager;
use \Symfony\Component\HttpFoundation\Request;

class TemplateController extends Controller {

    
use \admin\UserBundle\ControllerModel\paramUtilTrait;
    
    
    public function __construct() {
        $this->response = new Response();
        $this->response->headers->addCacheControlDirective('no-cache', true);
        $this->response->headers->addCacheControlDirective('max-age', 0);
        $this->response->headers->addCacheControlDirective('must-revalidate', true);
        $this->response->headers->addCacheControlDirective('no-store', true);
        $this->logMessage = " [ TemplateController ] ";
        $this->description = "Controlleur qui gère les utilisateurs";
    }

    /**
     *  Afficle la liste des templates
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
    public function listeTemplateAction(Request $request) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage de la liste des templates";
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
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des templates");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }

      
        
        $em = $this->getDoctrine()->getManager();
    if($sessionData['siAdminGeneral']){$type =1;}else{$type =0;}
        $templates = $em->getRepository($this->cmsBundle . 'Template')->getAllOrOneTemplate($type);
        
        $etablissement = $em->getRepository($this->scolariteBundle . 'Etablissement')->find($sessionData['etablissement']);

        $this->data['etablissement'] = $etablissement;
        $this->data['templates'] = $templates;
        $this->data['locale'] = $locale;
        return $this->render($this->cmsBundle . 'Template:listeTemplate.html.twig', $this->data, $this->response);
    }


    /**
     *  Formulaire d'ajout de template
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
    public function ajoutTemplateAction(Request $request) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Ajout d'un nouveau template";
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
//            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'ajouter un template");
//            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
//        }

        $template = new Template();
        $form = $this->createForm('\admin\CmsBundle\Form\TemplateType', $template);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();

            //if ($form->isValid()) {
                $criteria = array('titreTemplate' => $template->getTitreTemplate());
                $oldTemplate = $em->getRepository($this->cmsBundle . 'Template')->findOneBy($criteria);
                if ($oldTemplate == NULL) {
                    //$template->setDateInitialTemplate(new \DateTime());
                    $em->persist($template);
                    $em->flush();
                    
                    //Creation de la page d'accueil
                    $objetPage = new Page();
                    $objetPage->setSiPageAccueil(\admin\UserBundle\Types\TypeEtat::ACTIF);
                    $objetPage->setTitrePage('Accueil');
                    $objetPage->setTemplate($template);
                    $objetPage->setTwigPage('index.html.twig');
                    $objetPage->setTypePage(0);
                    $em->persist($objetPage);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('template.ajout.success', 'Ajout effectue avec succès');
                    return $this->redirect($this->generateUrl('app_admin_templates'));
                } else {
                    if ($oldTemplate->getEtat() == \admin\UserBundle\Types\TypeEtat::SUPPRIME) {
                        $oldTemplate->setEtat(\admin\UserBundle\Types\TypeEtat::ACTIF);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('template.ajout.success', 'Ajout effectue avec succès');
                        return $this->redirect($this->generateUrl('app_admin_templates'));
                    }
                    $this->get('session')->getFlashBag()->add('template.ajout.already.exist', 'Le template ' . $template->getTitreTemplate() . ' existe déjà');
                }
//            } else {
//                $this->get('session')->getFlashBag()->add('template.ajout.error', 'Formulaire invalide');
//            }
        }
        $locale="";

        $this->data['formuView'] = $form->createView();
        $this->data['template'] = $template;
        $this->data['locale'] = $locale;

        return $this->render($this->cmsBundle . 'Template:ajouterTemplate.html.twig', $this->data, $this->response);
    }

    /*
     * Modifie un template
     * @param type $idTemplate
     * @return type
     */

    public function modifierTemplateAction(Request $request, $idTemplate) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Modification d'un template";
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
//            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit de modifier un template");
//            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
//        }

        $em = $this->getDoctrine()->getManager();
        $template = $em->getRepository($this->cmsBundle . 'Template')->find($idTemplate);
        if ($template == NULL) {
            return $this->redirect($this->generateUrl('app_admin_templates'));
        }
        $form = $this->createForm('\admin\CmsBundle\Form\TemplateModifType', $template);

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);
            if ($form->isValid()) {
                $criteria = array('titreTemplate' => $template->getTitreTemplate());
                $oldTemplate = $em->getRepository($this->cmsBundle . 'Template')->findOneBy($criteria);
                if ($oldTemplate == NULL) {
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('template.modifier.success', 'Modifications effectuées avec succès');
                    return $this->redirect($this->generateUrl('app_admin_templates'));
                } else {
                    /*
                     * S'il s'agit du mm template ou k le template est supprimé on effecute la modification
                     */
                    if (($oldTemplate->getId() == $template->getId()) || ($oldTemplate->getEtat() == \admin\UserBundle\Types\TypeEtat::SUPPRIME)) {
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('template.modifier.success', 'Modifications effectuées avec succès');
                        return $this->redirect($this->generateUrl('app_admin_templates'));
                    }
                    $this->get('session')->getFlashBag()->add('template.modifier.already.exist', 'Le template ' . $template->getTitreTemplate() . ' existe déjà');
                }
            } else {
                $this->get('session')->getFlashBag()->add('template.modifier.error', 'Formulaire invalide');
            }
        }

        $this->data['formuView'] = $form->createView();
        $this->data['template'] = $template;
        $this->data['locale'] = $locale;
        $this->data['idTemplate'] = $idTemplate;

        return $this->render($this->cmsBundle . 'Template:modifierTemplate.html.twig', $this->data, $this->response);
    }

    /*
     * Activation, suppression, désactivation de template
     * @return Response
     */

    public function changerEtatTemplateAction(Request $request) {
        $rep = array('etat' => FALSE, 'msg' => 'Erreur survenue lors du traitement');
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Activation, suppression, désactivation de template";
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
//        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
//            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
//            $rep['msg'] = "Vous n'avez pas le droit d'activer, de supprimer ou de désactiver un template";
//            return new Response(json_encode($rep));
//        }

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
                $template = $em->getRepository($this->cmsBundle . 'Template')->find((int) $idS);
                if ($template != NULL) {
                    $template->setEtatTemplate($etat);
                    $em->flush();
                    $oneOk = TRUE;
                }
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
    
    
    /*
     * Changer les templates par default
     * @return Response
     */

    public function changerDefaultTemplateAction(Request $request) {
        $rep = array('etat' => FALSE, 'msg' => 'Erreur survenue lors du traitement');
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "définir comme template par defaut de template";
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
            $rep['msg'] = "Vous n'avez pas le droit de définir ce  template comme défault";
            return new Response(json_encode($rep));
        }

        $em = $this->getDoctrine()->getManager();
        if (($request->isXmlHttpRequest()) && ($request->getMethod() == 'POST')) {
            $idTemp = (int) $request->request->get('idTemplate');
//            var_dump($idTemp);exit;
             $template = $em->getRepository($this->cmsBundle . 'Template')->find($idTemp);
             if(count($template)!=0){
                $objetEtablissement= $em->getRepository($this->scolariteBundle . 'Etablissement')->find((int) $sessionData['etablissement']);
                $objetEtablissement->setTemplate($template);
                $em->flush();
                $oneOk = TRUE;
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

}
