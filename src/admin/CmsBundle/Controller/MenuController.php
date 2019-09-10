<?php

namespace admin\CmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use admin\CmsBundle\Entity\Menu;
use admin\UserBundle\Entity\Module;
use admin\CmsBundle\Form\MenuType;
use \Symfony\Component\HttpFoundation\Response;


class MenuController extends Controller {
    
    use \admin\UserBundle\ControllerModel\paramUtilTrait; //bibliothèque contenant la fonction pour recuperer les informations de la personne connectée
        
    public function __construct() {
        $this->response = new Response();
        $this->response->headers->addCacheControlDirective('no-cache', true);
        $this->response->headers->addCacheControlDirective('max-age', 0);
        $this->response->headers->addCacheControlDirective('must-revalidate', true);
        $this->response->headers->addCacheControlDirective('no-store', true);
        $this->logMessage = " [ MenuController ] ";
        $this->description = "Controlleur qui gère les menus";
    }
    
    
    /**
     *  Affiche la liste des menus
     * 
     *
     * @author credoesgmail.com
     *
     * @copyright Think Global 2016
     *
     * @version 1
     * 
     *    
     * @access  public
     *
     * @return Response
     */
    public function listeMenuAction(Request $request,$idEtablissement)
    {
       
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage de la liste des menus";
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
        if (!$loginManager->getOrSetActions(Module::MOD_MENU, Module::MOD_MENU_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des menus");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }  //cette partie est developper pour l'attribution de droits
        
        
        $em = $this->getDoctrine()->getManager();

        
        if($sessionData['siAdminGeneral']){
            $menus = $em->getRepository('adminCmsBundle:Menu')->getAllMenu();
        }else{
            $menus = $em->getRepository('adminCmsBundle:Menu')->getAllMenuEtablissement($sessionData['etablissement']);
            
        }
        //$menus = $em->getRepository('adminCmsBundle:Menu')->findAll();
        $this->data['menu'] = $menus;
        return $this->render('adminCmsBundle:Menu:listMenu.html.twig',  $this->data);
    }

    /**
     *  Formulaire d'ajout de menu
     * 
     *
     * @author credoesgmail.com
     *
     * @copyright Think Global 2016
     *
     * @version 1
     * 
     *    
     * @access  public
     *
     * @return Response
     */
    public function ajoutMenuAction(Request $request)
    {
        
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Ajouter un menu";
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
        if (!$loginManager->getOrSetActions(Module::MOD_MENU, Module::MOD_MENU_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des menus");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }  //cette partie est developper pour l'attribution de droits
        
        
        $menu = new Menu();
        $form = $this->createForm('admin\CmsBundle\Form\MenuType', $menu);
        
        
        //Recupération de l'objet utilisateur
        $em = $this->getDoctrine()->getManager();
        $utilisateur = $em->getRepository('adminUserBundle:Utilisateur')->find($sessionData['idProfil']);
        $objetEtablissement = $em->getRepository('adminScolariteBundle:Etablissement')->find($sessionData['etablissement']);
        
        //Recup�rer la liste des rubriques
        $rubriques = $em->getRepository('adminCmsBundle:Rubrique')->getAllRubrique();
        //Recup�rer la liste des articles
        $articles= $em->getRepository('adminCmsBundle:Article')->getAllArticle();
        
        if ($request->isMethod('POST')) {  
            $form->handleRequest($request);
            //recuperation du nom du menu
            $nomMenu = $request->get('nom-menu');
            $lienMenu = $request->get('lien-menu');
            
            $typeMenu = $request->get('type-menu');
            
//            $menu->setTitre($nomMenu);
            $menu->setEtablissement($objetEtablissement);
            $menu->setContenuMenu($lienMenu);
            $menu->setTypeMenu($typeMenu);
            $menu->setDatePublication(new \DateTime());
            $em->persist($menu);
            $em->flush();
           // return $this->redirectToRoute('menu_show', array('id' => $menu->getId()));
            return $this->redirectToRoute('app_admin_menu_list');
        }
        $this->data['articles'] = $articles;
        $this->data['rubriques'] = $rubriques;
        $this->data['menu'] = $menu;
        $this->data['form'] = $form->createView();
        return $this->render('adminCmsBundle:Menu:ajoutMenu.html.twig',$this->data);
    }

    
    /*
     * Modifie un menu
     * @param type $idMenu
     * @return type
     */
    public function modifierMenuAction(Request $request, $id)
    {
        
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Modifier un menu";
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
//        if (!$loginManager->getOrSetActions(Module::MOD_MENU, Module::MOD_MENU_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
//            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
//            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des menus");
//            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
//        }  //cette partie est developper pour l'attribution de droits
        
        
      
        
        $em = $this->getDoctrine()->getManager();
        $menu = $em->getRepository('adminCmsBundle:Menu')->find($id);
        $form = $this->createForm('admin\CmsBundle\Form\MenuType', $menu);
          //Recup�rer la liste des rubriques
        $rubriques = $em->getRepository('adminCmsBundle:Rubrique')->getAllRubrique();
        //Recup�rer la liste des articles
        $articles= $em->getRepository('adminCmsBundle:Article')->getAllArticle();
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lienMenu = $request->get('lien-menu');
            
            $typeMenu = $request->get('type-menu');
            
//            $menu->setTitre($nomMenu);
            
            $menu->setContenuMenu($lienMenu);
            $menu->setTypeMenu($typeMenu);

            $em->persist($menu);
            $em->flush();
           // return $this->redirectToRoute('menu_show', array('id' => $menu->getId()));
            return $this->redirectToRoute('app_admin_menu_list');
        }
        $idTraite =0;
        if($menu->getTypeMenu()==3 || $menu->getTypeMenu()==4 ){
            $tabInfo = explode('|', $menu->getContenuMenu());
            $idTraite = $tabInfo[1];
        }
        $this->data['articles'] = $articles;
        $this->data['idTraite'] = $idTraite;
        $this->data['rubriques'] = $rubriques;
        $this->data['menu'] = $menu;
        $this->data['form'] = $form->createView();
        return $this->render('adminCmsBundle:Menu:modifierMenu.html.twig', $this->data);
    }

    
    /*
     * Activation, suppression, désactivation de menu
     * @return Response
     */
    public function changerEtatMenuAction(Request $request, $id)
    {
        $rep = array('etat' => FALSE, 'msg' => 'Erreur survenue lors du traitement');
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Activation, suppression, désactivation de menu";
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
        if (!$loginManager->getOrSetActions(Module::MOD_MENU, Module::MOD_MENU_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $rep['msg'] = "Vous n'avez pas le droit d'activer, de supprimer ou de désactiver un menu";
            return new Response(json_encode($rep));
        }
        
        
        $em = $this->getDoctrine()->getManager();
        $menu = $em->getRepository('adminCmsBundle:Menu')->find($id);

            $em->remove($menu);
            $em->flush();

        return $this->redirectToRoute('app_admin_menus_list');
    }

    
//    public function operationVenteAction(Request $request){
//        if ($request->isXmlHttpRequest()){
//            $idProd = (int)$request->get("menu");
//            $qte = (int)$request->get("quantite");
//            $em = $this->getDoctrine()->getManager();
//            $menu = $em->getRepository('adminCmsBundle:Menu')->find($idProd);
//
//            $menu->setQuantiteMenu(($menu->getQuantiteMenu()-$qte)); //Décrémentation
//
//            if($menu->getQuantiteMenu()>=0){
//                $em->persist($menu);
//                $em->flush();
//            }
//            $tabMenu = array();
//            $tabMenu['id'] = $menu->getId();
//            $tabMenu['libelleMenu'] = $menu->getLibelleMenu();
//            $tabMenu['prixUnitaireMenu'] = $menu->getPrixUnitaireMenu();
//            $tabMenu['quantiteMenu'] = $menu->getQuantiteMenu();
//            return new Response(json_encode($tabMenu));
//        }
//    }
    
    
    

}
