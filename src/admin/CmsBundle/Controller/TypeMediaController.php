<?php

namespace admin\CmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use admin\CmsBundle\Entity\TypeMedia;
use admin\CmsBundle\Form\TypeMediaType;
use \Symfony\Component\HttpFoundation\Response;


class TypeMediaController extends Controller {
    
    
    use \admin\UserBundle\ControllerModel\paramUtilTrait; //bibliothèque contenant la fonction pour recuperer les informations de la personne connectée
    
    
    public function __construct() {
        $this->response = new Response();
        $this->response->headers->addCacheControlDirective('no-cache', true);
        $this->response->headers->addCacheControlDirective('max-age', 0);
        $this->response->headers->addCacheControlDirective('must-revalidate', true);
        $this->response->headers->addCacheControlDirective('no-store', true);
        $this->logMessage = " [ TypeMediaController ] ";
        $this->description = "Controlleur qui gère les utilisateurs";
    }
    
    
    
    /**
     *  Affiche la liste des typeMedias
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
    public function listTypeMediaAction(Request $request)
    {
       
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage de la liste des typeMedias";
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
//        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['id'])) {
//            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
//            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des typeMedias");
//            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
//        }  //cette partie est developper pour l'attribution de droits
        
        
        $em = $this->getDoctrine()->getManager();

        $typeMedias = $em->getRepository('adminCmsBundle:TypeMedia')->findAll();

        return $this->render('adminCmsBundle:TypeMedia:listTypeMedia.html.twig', array(
            'typemedia' => $typeMedias,
        ));
    }

    
    /**
     *  Formulaire d'ajout de typeMedia
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
    public function ajoutTypeMediaAction(Request $request)
    {
        
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage de la liste des typeMedias";
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
//        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idTypeMedia'])) {
//            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
//            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des typeMedias");
//            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
//        }  //cette partie est developper pour l'attribution de droits
        
        
        $typeMedia = new TypeMedia();
        $form = $this->createForm('admin\CmsBundle\Form\TypeMediaType', $typeMedia);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($typeMedia);
            $em->flush();

           // return $this->redirectToRoute('typeMedia_show', array('id' => $typeMedia->getId()));
            return $this->redirectToRoute('app_admin_typeMedias_list');
        }

        return $this->render('adminCmsBundle:TypeMedia:ajoutTypeMedia.html.twig', array(
            'typeMedia' => $typeMedia,
            'form' => $form->createView(),
        ));
    }

    
    /*
     * Modifie un typeMedia
     * @param type $idTypeMedia
     * @return type
     */
    public function modifierTypeMediaAction(Request $request, $id)
    {
        
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage de la liste des typeMedias";
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
//        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idTypeMedia'])) {
//            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
//            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des typeMedias");
//            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
//        }  //cette partie est developper pour l'attribution de droits
        
        
        $em = $this->getDoctrine()->getManager();
        $typeMedia = $em->getRepository('adminCmsBundle:TypeMedia')->find($id);
        $form = $this->createForm('admin\CmsBundle\Form\TypeMediaType', $typeMedia);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($typeMedia);
            $em->flush();

           // return $this->redirectToRoute('typeMedia_show', array('id' => $typeMedia->getId()));
            return $this->redirectToRoute('app_admin_typeMedias_list');
        }

        return $this->render('adminCmsBundle:TypeMedia:modifierTypeMedia.html.twig', array(
            'typeMedia' => $typeMedia,
            'form' => $form->createView(),
        ));
    }

    
    /*
     * Activation, suppression, désactivation de typeMedia
     * @return Response
     */
    public function suppressionTypeMediaAction(Request $request, $id)
    {
        $rep = array('etat' => FALSE, 'msg' => 'Erreur survenue lors du traitement');
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Activation, suppression, désactivation de typeMedia";
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idTypeMedia'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $rep['msg'] = "Vous n'avez pas le droit d'activer, de supprimer ou de désactiver un typeMedia";
            return new Response(json_encode($rep));
        }
        
        
        $em = $this->getDoctrine()->getManager();
        $typeMedia = $em->getRepository('adminCmsBundle:TypeMedia')->find($id);

            $em->remove($typeMedia);
            $em->flush();

        return $this->redirectToRoute('app_admin_typeMedias_list');
    }

    
//    public function operationVenteAction(Request $request){
//        if ($request->isXmlHttpRequest()){
//            $idProd = (int)$request->get("typeMedia");
//            $qte = (int)$request->get("quantite");
//            $em = $this->getDoctrine()->getManager();
//            $typeMedia = $em->getRepository('adminCmsBundle:TypeMedia')->find($idProd);
//
//            $typeMedia->setQuantiteTypeMedia(($typeMedia->getQuantiteTypeMedia()-$qte)); //Décrémentation
//
//            if($typeMedia->getQuantiteTypeMedia()>=0){
//                $em->persist($typeMedia);
//                $em->flush();
//            }
//            $tabTypeMedia = array();
//            $tabTypeMedia['id'] = $typeMedia->getId();
//            $tabTypeMedia['libelleTypeMedia'] = $typeMedia->getLibelleTypeMedia();
//            $tabTypeMedia['prixUnitaireTypeMedia'] = $typeMedia->getPrixUnitaireTypeMedia();
//            $tabTypeMedia['quantiteTypeMedia'] = $typeMedia->getQuantiteTypeMedia();
//            return new Response(json_encode($tabTypeMedia));
//        }
//    }
}
