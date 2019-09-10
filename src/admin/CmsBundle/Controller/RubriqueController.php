<?php

namespace admin\CmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use admin\CmsBundle\Entity\Rubrique;
use admin\UserBundle\Entity\Module;
use admin\CmsBundle\Form\RubriqueType;
use admin\CmsBundle\Entity\Media;
use \Doctrine\Common\Collections\ArrayCollection;
use \Symfony\Component\HttpFoundation\Response;

class RubriqueController extends Controller {
    
    use \admin\UserBundle\ControllerModel\paramUtilTrait; //bibliothèque contenant la fonction pour recuperer les informations de la personne connectée
    
    
    public function __construct() {
        $this->response = new Response();
        $this->response->headers->addCacheControlDirective('no-cache', true);
        $this->response->headers->addCacheControlDirective('max-age', 0);
        $this->response->headers->addCacheControlDirective('must-revalidate', true);
        $this->response->headers->addCacheControlDirective('no-store', true);
        $this->logMessage = " [ RubriqueController ] ";
        $this->description = "Controlleur qui gère les utilisateurs";
    }

    
    /**
     *  Affiche la liste des rubriques
     * 
     *
     * @author credoesgmail.com
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
    public function listRubriqueAction(Request $request)
    {
       
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage de la liste des rubriques";
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_RUB, Module::MOD_GEST_RUB_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des rubriques");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }  //cette partie est developper pour l'attribution de droits
        
        
        $em = $this->getDoctrine()->getManager();
        if($sessionData['siAdminGeneral']){
            $rubriques = $em->getRepository('adminCmsBundle:Rubrique')->getAllRubrique();
        }else{
            
            $rubriques = $em->getRepository('adminCmsBundle:Rubrique')->getAllRubriqueEtablissement($sessionData['etablissement']);
            
        }
        

        return $this->render('adminCmsBundle:Rubrique:listRubrique.html.twig', array(
            'rubrique' => $rubriques,
        ));
    }

    
    /**
     *  Formulaire d'ajout de rubrique
     * 
     *
     * @author credoesgmail.com
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
    public function ajoutRubriqueAction(Request $request)
    {
        
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage de la liste des rubriques";
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_RUB, Module::MOD_GEST_RUB_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des rubriques");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }  //cette partie est developper pour l'attribution de droits
        
        //recuperation de l'objet etablissement
        $em = $this->getDoctrine()->getManager();
       $objetEtablissement =  $em->getRepository('adminScolariteBundle:Etablissement')->find($sessionData['etablissement']);  
       
        
        $rubrique = new Rubrique();
        $form = $this->createForm('admin\CmsBundle\Form\RubriqueType', $rubrique);
       
        
        //Recupération de l'objet utilisateur
        
        $utilisateur = $em->getRepository('adminUserBundle:Utilisateur')->find($sessionData['idProfil']);
        
        $media = new Media();
        
        $rubrique->setDateCreationRubrique(new \DateTime());
        
        $originalImages = new ArrayCollection();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
        
            if ($rubrique->getMedias() != null) {
                 foreach ($rubrique->getMedias() as $media) {
                     $originalImages->add($media);
                 }
            }
           
            foreach ($originalImages as $media) {
                $media->setRubrique($rubrique);
            }
            $typeRubrique = $request->get('typeRubrique');
            //$em = $this->getDoctrine()->getManager();
            $rubrique->setUtilisateur($utilisateur);
            $rubrique->setTypeRubrique($typeRubrique);
            //var_dump($objetEtablissement);exit;
            $rubrique->setEtablissement($objetEtablissement);
            $rubrique->setDateCreationRubrique(new \DateTime());
            if ($rubrique->getMedias() != null) {
                if ($rubrique->getMedias()[0] != null) {
                    $rubrique->addMedia($rubrique->getMedias()[0]);
                }
            }
            
            $em->persist($rubrique);
            $em->flush();

           // return $this->redirectToRoute('rubrique_show', array('id' => $rubrique->getId()));
            return $this->redirectToRoute('app_admin_rubriques_list');
        }

        return $this->render('adminCmsBundle:Rubrique:ajoutRubrique.html.twig', array(
            'rubrique' => $rubrique,
            'form' => $form->createView(),
        ));
    }

    
    /*
     * Modifie un rubrique
     * @param type $idRubrique
     * @return type
     */
    public function modifierRubriqueAction(Request $request, $id)
    {
        
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage de la liste des rubriques";
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_RUB, Module::MOD_GEST_RUB_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des rubriques");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }  //cette partie est developper pour l'attribution de droits
        
        
        $em = $this->getDoctrine()->getManager();
        $rubrique = $em->getRepository('adminCmsBundle:Rubrique')->find($id);
        $form = $this->createForm('admin\CmsBundle\Form\RubriqueType', $rubrique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($rubrique);
            $em->flush();

           // return $this->redirectToRoute('rubrique_show', array('id' => $rubrique->getId()));
            return $this->redirectToRoute('app_admin_rubriques_list');
        }

        return $this->render('adminCmsBundle:Rubrique:modifierRubrique.html.twig', array(
            'rubrique' => $rubrique,
            'form' => $form->createView(),
        ));
    }

    
    /*
     * Activation, suppression, désactivation de rubrique
     * @return Response
     */
    public function changerEtatRubriqueAction(Request $request)
    {
        
        $rep = array('etat' => FALSE, 'msg' => 'Erreur survenue lors du traitement');
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Activation, suppression, désactivation de rubrique";
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_RUB, Module::MOD_GEST_RUB_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $rep['msg'] = "Vous n'avez pas le droit d'activer, de supprimer ou de désactiver un rubrique";
            return new Response(json_encode($rep));
        }
//        
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
                $rubrique = $em->getRepository($this->cmsBundle . 'Rubrique')->find((int) $idS);
                if ($rubrique != NULL) {
                    $rubrique->setActifRubrique($etat);
                    $em->flush();
                    $oneOk = TRUE;
                }
            }
            if ($oneOk) {
                $this->get('session')->getFlashBag()->add('rubrique.modifier.success', 'Opération éffectuée avec succès');
                $rep['msg'] = '';
                $rep['etat'] = TRUE;
            }

            return new Response(json_encode($rep));
        }
        return new Response(json_encode($rep));
    }
    
 /*
     * Afficher les informations  d'un abonné
     * 
     * @param type $idExercice
     * @return type
     */

    public function infosRubriqueAction(Request $request, $id) {
        /**
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /**
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage des informations d'une rubrique";
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_RUB, Module::MOD_GEST_RUB_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder aux informations des abonnés");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }


        $em = $this->getDoctrine()->getManager();
        $rubrique = $em->getRepository($this->cmsBundle . 'Rubrique')->find($id);
        if ($rubrique == NULL) {
            return $this->redirect($this->generateUrl('app_admin_rubriques'));
        }
        /*
         * Le mot de passe ne doit pas être vide
         */
        $this->data['rubrique'] = $rubrique;
        $this->data['idRubrique'] = $id;
        $this->data['locale'] = $locale;
        $this->data['isAdmin'] = $sessionData['isUser'];
        return $this->render($this->cmsBundle . 'Rubrique:afficherinfosRubriqpe.html.twig', $this->data, $this->response);
    }   
                    
    
}
