<?php

namespace admin\EconomatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use admin\EconomatBundle\Entity\TypeOperation;
use admin\EconomatBundle\Form\TypeOperationType;
use admin\EconomatBundle\Entity\ParamsOrg;
use admin\EconomatBundle\Form\ParamsOrgType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use admin\UserBundle\Entity\Module;

class TypeOperationController extends Controller {

    use \admin\UserBundle\ControllerModel\paramUtilTrait;

    /**
     * Nom du controleur.
     */
    protected $nom = 'TypeOperationController';

    /**
     * Description  du controleur.
     */
    protected $description = 'Contrôleur qui gère lajout des Caisses';

    /**
     * Declaration de la méthode de recupération de la response.
     */
    public $response;

    /**
     * Declaration de la méthode de recupération de la resquest.
     */
    protected $request;

    /**
     * Declaration de la méthode de recupération de la session.
     */
    protected $session;

    /**
     * Declaration des messages flash.
     */
    protected $flashMessage;

    /**
     * Information permettant d'identifier la méthode dans les fichiers de log.
     */
    protected $logMessage = ' [ CaisseController ] ';

    /**
     * Déclaration de l'entity manager.
     *
     * @var
     */
    protected $em;

    /**
     * @var string
     *             Le comtroleur 
     */
    public function __construct() {
        $this->response = new Response;
        $this->response->headers->addCacheControlDirective('no-cache', true);
        $this->response->headers->addCacheControlDirective('max-age', 0);
        $this->response->headers->addCacheControlDirective('must-revalidate', true);
        $this->response->headers->addCacheControlDirective('no-store', true);
    }

    /**
     * Methode permettant d'ajouter une typeOperation - Espace client
     * 
     * 
     * @var
     * 
     * Les Variables
     * 
     * $unetypeOperation: Instance de la classe TypeOperation pour l'ajout
     * 
     * 
     * @param <string> $locale Variable passee pour gerer le multilingue sur le site
     * 
     * @return <string> return le twig ajoutTypeOperation.html.twig avec les variables $locale,$listestat
     *  
     */
    public function ajoutTypeOperationAction(Request $request, $locale) {
        $em = $this->getDoctrine()->getEntityManager();
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;

        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Ajouter un type operation";
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_TYPOP, Module::MOD_GEST_TYPOP_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des abonnés");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }
        //recuperation de l'objet etablissement
        $objetEtablissement = $sessionData['etablissement'];

        $unetypeOperation = new TypeOperation();

        $form = $this->createForm(TypeOperationType::class, $unetypeOperation);


        if ($request->isMethod('POST')) {

            $form->handleRequest($request);
            $unetypeOperation = $form->getData();

            $siexiste = $this->getDoctrine()
                    ->getManager()
                    ->getRepository("adminEconomatBundle:TypeOperation")
                    ->getSiTypeOperationExiste($unetypeOperation->getCodeOperation(), $locale, $unetypeOperation->getLibTypeOperation());

            $sideleted = $this->getDoctrine()
                    ->getManager()
                    ->getRepository("adminEconomatBundle:TypeOperation")
                    ->getSiTypeOperationDeleted($unetypeOperation->getCodeOperation(), $locale, $unetypeOperation->getLibTypeOperation());

            if ($siexiste != 0) {

                $this->get('session')->getFlashBag()->add('notice', 'existedeja');

                return $this->redirect($this->generateUrl("admin_listetypeOperation", array('locale' => $locale,)));
            } else {
                // var_dump($sideleted) ; exit ;
                if ($sideleted != 0) {
                    $unetypeOperation->setSuppr(0);
                    // $nbreLigne = $unetypeOperation->gettNbreLigne() ;
                    // $unetypeOperation->setNbreLigne($nbreLigne) ;
                    $em->persist($unetypeOperation);
                }
            }
            $unetypeOperation->setEtattypeOperation(0);
            $unetypeOperation->setSuppr(0);
            $unetypeOperation->setEtablissement($objetEtablissement);
            /* $ladate= new \Datetime();
              $unetypeOperation->setDateCreation( $ladate); */
            $em->persist($unetypeOperation);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'success');

            //var_dump($unetypeOperation->getNbreLigne()) ; exit ;
            //return $this->redirect($this->generateUrl("admin_listetypeOperation", array('locale' => $locale,)));
            return $this->redirect($this->generateUrl("admin_ajoutschema", array('locale' => $locale, 'idType' => $unetypeOperation->getId(), 'nbreLigne' => $unetypeOperation->getNbreLigne())));
        }

        return $this->render('adminEconomatBundle:TypeOperation:ajoutTypeOperation.html.twig', array(
                    'form' => $form->createView(), 'locale' => $locale, //'infos'=>$boxinfos,
                        ), $this->response);
    }

    /**
     * Methode permettant d'avoir la liste des typeOperations - Espace client
     * 
     * 
     * @var
     * 
     * Les Variables
     * 
     * $listetypeOperation: Liste des instances de la classe TypeOperation
     * 
     * 
     * @param <string> $locale Variable passee pour gerer le multilingue sur le site
     * 
     * @return <string> return le twig listeTypeOperation.html.twig 
     *  
     */
    public function listeTypeOperationAction(Request $request, $locale, $ajoutprof, $page) {

        $em = $this->getDoctrine()->getEntityManager();
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Liste des types operations";
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_TYPOP, Module::MOD_GEST_TYPOP_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des abonnés");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }

        $listetypeOperation = $em->getRepository("adminEconomatBundle:TypeOperation")->findAll();
        /* total des résultats */
        $total = count($listetypeOperation);
        $articles_per_page = 100000; // $this->container->getParameter('max_articles_on_listepage');
        $last_page = ceil($total / $articles_per_page);
        $previous_page = $page > 1 ? $page - 1 : 1;
        $next_page = $page < $last_page ? $page + 1 : $last_page;

        $entitiestypeOperation = $em->getRepository("adminEconomatBundle:TypeOperation")->getListeTypeOperation($total, $page, $articles_per_page);
        //var_dump($entitiestypeOperation);exit;
        return $this->render('adminEconomatBundle:TypeOperation:listeTypeOperation.html.twig', array(
                    'listetypeOperation' => $entitiestypeOperation,
                    'locale' => $locale,
                    'last_page' => $last_page,
                    'previous_page' => $previous_page,
                    'current_page' => $page,
                    'next_page' => $next_page,
                    'total' => $total), $this->response);
    }

    /**
     * Methode permettant de supprimer un profil  - Espace client
     * 
     * 
     * @var
     * 
     * Les Variables
     * 
     * $unetypeOperation: Instance de la classe TypeOperation a supprimer
     * 
     * $unUser: Instance de la classe User relative au profil $unetypeOperation.S'assure si le profil contient un user
     * 
     * 
     * @param <string> $locale Variable passee pour gerer le multilingue sur le site
     * @param <string> $id identifiant du profil 
     * 
     * @return <string> return le twig listeTypeOperation.html.twig 
     *  
     */
    public function supprTypeOperationAction($id, $locale) {
        $em = $this->getDoctrine()->getEntityManager();
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Supprimer un plan comptable";
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_TYPOP, Module::MOD_GEST_TYPOP_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des abonnés");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }


        $unetypeOperation = $em->getRepository("adminEconomatBundle:TypeOperation")->find($id);

        $this->get('request')->attributes->set('type_user', 'utilisateur');

        /* Enfin on supprime le categorie ... */
        /* ... et on redirige vers la page d'administration des profils */
        $unetypeOperation->setSuppr(1);
        $em->persist($unetypeOperation);
        $em->flush();

        return $this->redirect($this->generateUrl('admin_listetypeOperation', array(
                            'locale' => $locale)));
    }

    /*
     * $locale=fr est mis pr donner une valeur par defaut à $locale;
     *  sans quoi l'activation|désactivation ne marche pas;
     *  pcke $locale est aussi utilisée ds infoUtilisateur.
     */

    function gererAllTypeOperationAction($locale = "fr") {

        $em = $this->getDoctrine()->getEntityManager();
        $authManager = $this->get('Auth.Manager'); //on recupere le service qui gère l'authentification = $this->get('Auth.Manager');//on recupere le service qui gère l'authentification
        $this->get('request')->setLocale($locale);

        $currentID = $authManager->getCurrentId(); //comment récupérer L'id de l'abonne courrant
        $currentConnete = $authManager->getFlash("admin_data");
        $this->infoUtilisateur($em, $authManager, $currentConnete, 'utilisateur', $locale);
        //on verifie si l'abonnee est connecté. sil ne l'est pas on le dirige à la page de connexion
        if (!$authManager->isLogged())
            return $this->redirect($this->generateUrl("admin_logout", array('locale' => $locale)));

        $listeActions = $currentConnete["listeActions_abonne"];
        //var_dump($currentConnete["listeActions_abonne"]);exit;

        if (!in_array('gererAllTypeOperationAction', $listeActions)) {
            $this->get('session')->getFlashBag()->add('accesdenied', "admin.layout.accesdenied");
            return $this->redirect($this->generateUrl("admin_accueil", array('locale' => $locale)));
        }

        $this->get('request')->attributes->set('type_user', 'utilisateur');
        $request = $this->container->get('request');
        $profilIds = $request->request->get('idprofil');

        $etat = $request->request->get('etatprofil');

        $profilIds = explode("|", $profilIds);
        //$utilisateur = $this->container->get('security.context')->getToken()->getUtilisateur()->getId();
        //boucle sur les ids articles
        $typeOperationIds = $request->request->get('idtypeOperation');

        $etat = $request->request->get('etattypeOperation');

        $typeOperationIds = explode("|", $typeOperationIds);
        //$utilisateur = $this->container->get('security.context')->getToken()->getUtilisateur()->getId();
        //boucle sur les ids articles
        foreach ($typeOperationIds as $key => $value) {
            if (!empty($value)) {
                $unetypeOperation = $em->getRepository("adminEconomatBundle:TypeOperation")->find($value);

                $unetypeOperation->setEtatTypeOperation($etat);
                $em->persist($unetypeOperation);
            }
            //Désactivation  
        }
        //$em->flush();   
        return new Response(json_encode(array("result" => "success")));
    }

    /**
     * Methode permettant de modifier un profil - Espace client
     * 
     * 
     * @var
     * 
     * Les Variables
     * 
     * $unetypeOperation: Instance de la classe TypeOperation a modifier
     * 
     * @param <integer> $id     Identifiant  du profil
     * @param <string>  $locale, Variable passee pour gerer le multilingue sur le site
     * 
     * @return <string> return sur le twig modifTypeOperation
     *  
     */
    public function modifierTypeOperationAction(Request $request, $id, $locale) {

        $em = $this->getDoctrine()->getEntityManager();

        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Modifier un type operation";
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_TYPOP, Module::MOD_GEST_TYPOP_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des abonnés");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }


        // Récupération du profil 
        $unetypeOperation = $em->getRepository("adminEconomatBundle:TypeOperation")->find($id);

        // Création d'un forumaire pour lequel on spécifie qu'il doit correspondre avec une entité profil 
        $form = $this->createForm(TypeOperationType::class, $unetypeOperation);



        // On traite les données passées en méthode POST 

        if ($request->getMethod() == 'POST') {

            // On applique les données récupérées au formulaire */
            //var_dump($request->request->get('nbreLigne')) ;
            $form->handleRequest($request);

            //var_dump($unetypeOperation->getLibTypeOperation());exit;
            /* Si le formulaire est valide, on valide et on redirige vers la liste des profils */
            if ($form->isValid()) {

                $em->persist($unetypeOperation);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', 'modifsuccess');

                return $this->redirect($this->generateUrl("admin_listetypeOperation"));
            }
        }
        return $this->render('adminEconomatBundle:TypeOperation:modifTypeOperation.html.twig', array(
                    'form' => $form->createView(), 'id' => $id, 'locale' => $locale), $this->response);
    }

    /**
     * Methode permettant de supprimer definitivement des typeOperations selectionnes - Backoffice
     * 
     * @var
     * 
     * Les Variables
     * 
     * $usersIds: Tableau regoupants les Ids des instances de la classe TypeOperation selectionnes
     * 
     * $unuser: Instance de la classe TypeOperation a supprimer definitivement
     * 
     * @return <json> return etat du traitement effectue
     *  
     */
    function supprAlltypeOperationsAction() {

        $em = $this->getDoctrine()->getEntityManager();
        $authManager = $this->get('Auth.Manager'); //on recupere le service qui gère l'authentification = $this->get('Auth.Manager');//on recupere le service qui gère l'authentification
        $this->get('request')->setLocale($locale);

        $currentID = $authManager->getCurrentId(); //comment récupérer L'id de l'abonne courrant
        $currentConnete = $authManager->getFlash("admin_data");
        $this->infoUtilisateur($em, $authManager, $currentConnete, 'utilisateur', $locale);
        //on verifie si l'abonnee est connecté. sil ne l'est pas on le dirige à la page de connexion
        if (!$authManager->isLogged())
            return $this->redirect($this->generateUrl("admin_logout", array('locale' => $locale)));

        $listeActions = $currentConnete["listeActions_abonne"];
        //var_dump($currentConnete["listeActions_abonne"]);exit;

        if (!in_array('supprAllprofilsAction', $listeActions)) {
            $this->get('session')->getFlashBag()->add('accesdenied', "admin.layout.accesdenied");
            return $this->redirect($this->generateUrl("admin_accueil", array('locale' => $locale)));
        }

        $this->get('request')->attributes->set('type_user', 'utilisateur');

        $request = $this->container->get('request');
        $usersIds = $request->request->get('ds');
        $usersIds = explode("|", $usersIds);

        foreach ($usersIds as $key => $value) {

            if (!empty($value)) {
                $unuser = $em->getRepository("adminEconomatBundle:TypeOperation")->find($value);
                /* Enfin on supprime l typeOperation... */
                $em->remove($unuser);
            }
        }

        $em->flush();
        return new Response(json_encode(array("result" => "success")));
    }

    public function infoUtilisateur($em, $authManager, $currentConnete, $user, $locale) {
        //$currentConnete = $authManager->getFlash("admin_data");
        if (isset($currentConnete["id_abonne"]) && $currentConnete["id_abonne"] != "") {
            $id_abonne = $currentConnete["id_abonne"];
            $type_user = $currentConnete["type_user"];
            $nomPrenom = $currentConnete["nomPrenom_abonne"];
            $monnaie = $currentConnete["profil_abonne"];
            $last_connexion = $currentConnete["last_connexion"];
            $listeActions = $currentConnete["listeActions_abonne"];

            /*     $maxIdleTime = $this->container->getParameter('maxIdleTime');
              $session = $this->get('session');
              if (time() - $session->getMetadataBag()->getLastUsed() > $maxIdleTime) {
              /*         * *******  Maj historique **********
              $histo = null;
              $unuser = null;
              $unabonne = null;
              $idhisto = 0;
              $currentConnete = $authManager->getFlash("admin_data");
              //var_dump();exit;
              if (isset($currentConnete["id_abonne"])) {
              $em = $this->getDoctrine()->getEntityManager();

              if ($currentConnete["type_user"] == "abonne") {
              $unabonne = $em->getRepository('adminEconomatBundle:Abonne')->find($currentConnete["id_abonne"]);
              $idhisto = $em->getRepository('adminEconomatBundle:HistoriqueConnexion')->getMaxHistorique($unabonne->getId(), 0);
              if (isset($idhisto) && ($idhisto != 0)) {
              $histo = $em->getRepository("adminEconomatBundle:HistoriqueConnexion")->find($idhisto);
              }
              }

              if ($currentConnete["type_user"] == "utilisateur") {
              $unuser = $em->getRepository("adminEconomatBundle:Utilisateur")->find($currentConnete['id_abonne']);
              $idhisto = $em->getRepository('adminEconomatBundle:HistoriqueConnexion')->getMaxHistorique($unuser->getId(), 1);
              if (isset($idhisto) && ($idhisto != 0)) {
              $histo = $em->getRepository("adminEconomatBundle:HistoriqueConnexion")->find($idhisto);
              }
              }

              if ($histo != null) {

              $lafin = new \Datetime();
              $ledebut = $histo->getDateDeb();
              $laduree = $lafin->diff($ledebut);
              $laduree->format('%h heures %i minutes %s secondes');
              $histo->setDateFin($lafin);
              $histo->setDuree($laduree->format('%h h %i min %s sec'));
              $em->persist($histo);
              $em->flush();
              }
              }

              $_SESSION["admin_data"] = array();

              }

              }else
              return $this->redirect($this->generateUrl("admin_accueil", array('locale' => $locale))); */

            //transformation de $type_user en variable globale
            $this->get('session')->set('_locale', $locale); // gautier 404
            //Determination des paramètres de l'organisation
            $org = $this->getDoctrine()
                    ->getManager()
                    ->getRepository("adminEconomatBundle:ParamsOrg")
                    ->getInfosOrg($locale);
            $this->get('request')->attributes->set('org', $org);

            $this->get('request')->attributes->set('id_abonne', $id_abonne);
            $this->get('request')->attributes->set('type_user', $type_user);
            $this->get('request')->attributes->set('nomPrenom', $nomPrenom);
            $this->get('request')->attributes->set('monnaie', $monnaie);
            $this->get('request')->attributes->set('last_connexion', $last_connexion);
            $this->get('request')->attributes->set('listeActions', $listeActions);
            $caisse_ouv = $currentConnete["caisse_ouv"];
            $this->get('request')->attributes->set('caisse_ouvr', $caisse_ouv);
            $accueil = $currentConnete["accueil"];
            $this->get('request')->attributes->set('accueil', $accueil);
            $codeAgence = $currentConnete["codeagence"];
            $libAgence = $currentConnete["libagence"];
            $this->get('request')->attributes->set('codeagence', $codeAgence);
            $this->get('request')->attributes->set('libagence', $libAgence);
            /*
              $listeMessageNonLu = $this->get('message.Manager');
              $nbrelu = $listeMessageNonLu->getNombreMsgNonLuUtil($em, $id_abonne);
              $this->get('request')->attributes->set('nbreluutil', $nbrelu);
              //Info non lu abonne
              $listeMessageNonLu = $this->get('message.Manager');
              $nbrelu = $listeMessageNonLu->getNombreMsgNonLuAbonne($em, $id_abonne);

              $this->get('request')->attributes->set('nbrelu', $nbrelu);

              $this->get('request')->attributes->set('type_user', $user); */
        }
    }

}
