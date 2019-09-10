<?php

namespace admin\CmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use admin\CmsBundle\Entity\Article;
use admin\UserBundle\Entity\Module;
use admin\CmsBundle\Form\ArticleType;
use \Symfony\Component\HttpFoundation\Response;
use admin\CmsBundle\Entity\Media;
use \Doctrine\Common\Collections\ArrayCollection;


class ArticleController extends Controller {
    
    use \admin\UserBundle\ControllerModel\paramUtilTrait; //bibliothèque contenant la fonction pour recuperer les informations de la personne connectée
    
    
    public function __construct() {
        $this->response = new Response();
        $this->response->headers->addCacheControlDirective('no-cache', true);
        $this->response->headers->addCacheControlDirective('max-age', 0);
        $this->response->headers->addCacheControlDirective('must-revalidate', true);
        $this->response->headers->addCacheControlDirective('no-store', true);
        $this->logMessage = " [ ArticleController ] ";
        $this->description = "Controlleur qui gère les utilisateurs";
    }
    
    
    /**
     *  Affiche la liste des articles
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
    public function listArticleAction(Request $request)
    {
       
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage de la liste des articles";
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_ART, Module::MOD_GEST_ART_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des articles");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }  //cette partie est developper pour l'attribution de droits
        
        
        $em = $this->getDoctrine()->getManager();
        $idProfil=$sessionData['idProfil'];
       // var_dump($idProfil);exit;
        if($sessionData['siAdminGeneral']){
            $articles = $em->getRepository('adminCmsBundle:Article')->getAllArticle();
        }else{
            
            $articles = $em->getRepository('adminCmsBundle:Article')->getAllArticleEtablissement($sessionData['etablissement'],$idProfil);
            
        }
        return $this->render('adminCmsBundle:Article:listArticle.html.twig', array(
            'article' => $articles,
        ));
    }

    
    /**
     *  Formulaire d'ajout de article
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
    public function ajoutArticleAction(Request $request)
    {
        
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage de la liste des articles";
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_ART, Module::MOD_GEST_ART_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des articles");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }  //cette partie est developper pour l'attribution de droits
        
       //recuperation de l'objet etablissement
       $em = $this->getDoctrine()->getManager();
       $objetEtablissement =  $em->getRepository('adminScolariteBundle:Etablissement')->find($sessionData['etablissement']);  
       
        $article = new Article();
        
         if($sessionData['siAdminGeneral']){
             
             $form = $this->createForm('admin\CmsBundle\Form\ArticleType', $article);
        }else{
            
             $form = $this->createForm('admin\CmsBundle\Form\ArticleAutreType', $article);
            
        }
    
        
        
        //$form->handleRequest($request);
        
        $media = new Media();
        $originalImages = new ArrayCollection();
        
        //Recupération de l'objet utilisateur
        
        $utilisateur = $em->getRepository('adminUserBundle:Utilisateur')->find($sessionData['idProfil']);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
        
            if ($article->getMedias() != null) {
                 foreach ($article->getMedias() as $media) {
                     $originalImages->add($media);
                 }
            }
           
            foreach ($originalImages as $media) {
                $media->setArticle($article);
            }
            //$em = $this->getDoctrine()->getManager();
            $article->setUtilisateur($utilisateur);
            $article->setEtablissement($objetEtablissement);
            $article->setDatePublication(new \DateTime());
            if ($article->getMedias() != null) {
                if ($article->getMedias()[0] != null) {
                    
                    $article->addMedia($article->getMedias()[0]);
                }
            }
            $em->persist($article);
            $em->flush();

           // return $this->redirectToRoute('article_show', array('id' => $article->getId()));
            return $this->redirectToRoute('app_admin_articles_list');
        }

        return $this->render('adminCmsBundle:Article:ajoutArticle.html.twig', array(
            'article' => $article,
            'form' => $form->createView(),
        ));
    }

    
    /*
     * Modifie un article
     * @param type $idArticle
     * @return type
     */
    public function modifierArticleAction(Request $request, $id)
    {
        
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage de la liste des articles";
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_ART, Module::MOD_GEST_ART_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des articles");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }  //cette partie est developper pour l'attribution de droits
        
        
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('adminCmsBundle:Article')->find($id);
         if($sessionData['siAdminGeneral']){
             $form = $this->createForm('admin\CmsBundle\Form\ArticleType', $article);
        }else{
            
             $form = $this->createForm('admin\CmsBundle\Form\ArticleAutreType', $article);
            
        }
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($article);
            $em->flush();

           // return $this->redirectToRoute('article_show', array('id' => $article->getId()));
            return $this->redirectToRoute('app_admin_articles_list');
        }

        return $this->render('adminCmsBundle:Article:modifierArticle.html.twig', array(
            'article' => $article,
            'form' => $form->createView(),
        ));
    }

    
    /*
     * Activation, suppression, désactivation de article
     * @return Response
     */
    public function changerEtatArticleAction(Request $request, $id)
    {
        $rep = array('etat' => FALSE, 'msg' => 'Erreur survenue lors du traitement');
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Activation, suppression, désactivation de article";
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_ART, Module::MOD_GEST_ART_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $rep['msg'] = "Vous n'avez pas le droit d'activer, de supprimer ou de désactiver un article";
            return new Response(json_encode($rep));
        }
        
        
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('adminCmsBundle:Article')->find($id);

            $em->remove($article);
            $em->flush();

        return $this->redirectToRoute('app_admin_articles_list');
    }

    
//    public function operationVenteAction(Request $request){
//        if ($request->isXmlHttpRequest()){
//            $idProd = (int)$request->get("article");
//            $qte = (int)$request->get("quantite");
//            $em = $this->getDoctrine()->getManager();
//            $article = $em->getRepository('adminCmsBundle:Article')->find($idProd);
//
//            $article->setQuantiteArticle(($article->getQuantiteArticle()-$qte)); //Décrémentation
//
//            if($article->getQuantiteArticle()>=0){
//                $em->persist($article);
//                $em->flush();
//            }
//            $tabArticle = array();
//            $tabArticle['id'] = $article->getId();
//            $tabArticle['libelleArticle'] = $article->getLibelleArticle();
//            $tabArticle['prixUnitaireArticle'] = $article->getPrixUnitaireArticle();
//            $tabArticle['quantiteArticle'] = $article->getQuantiteArticle();
//            return new Response(json_encode($tabArticle));
//        }
//    }
}
