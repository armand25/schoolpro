<?php

namespace admin\EconomatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use admin\EconomatBundle\Entity\PlanComptable;
use admin\EconomatBundle\Entity\PlanComptableRepository;
use admin\EconomatBundle\Form\PlanComptableType;
use admin\EconomatBundle\Entity\ParamsOrg;
use admin\EconomatBundle\Form\ParamsOrgType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use admin\UserBundle\Entity\Module;

class PlanComptableController extends Controller {
    
use \admin\UserBundle\ControllerModel\paramUtilTrait;

  /**
     * Nom du controleur.
     */
    protected $nom = 'PlanComptableController';

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

    public function ajouterPlanComptableAction(Request $request,$locale) {

        $em = $this->getDoctrine()->getEntityManager();
       /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Ajouter un plan comptable";
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_PLAN, Module::MOD_GEST_PLAN_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des abonnés");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }
     
        //recuperation de l'objet etablissement
        $objetEtablissement = $sessionData['etablissement'];
        
        /*
         * Création du formulaire par rapport a l'entité Caisse
         */

        $plan = new PlanComptable();
        $plan->setDateCreation(new \DateTime());
        $plan->setSuppr('0');
        $formBuilder = $this->createForm(PlanComptableType::class, $plan);
      
        if ($request->getMethod() == 'POST') {
            
            $formBuilder->handleRequest($request);
            $uncompte = $formBuilder->getData();

            $exitcompte = $this->getDoctrine()->getManager()->getRepository("adminEconomatBundle:PlanComptable")->compteExiste($uncompte->getCompte());
            $exitbib = $this->getDoctrine()->getManager()->getRepository("adminEconomatBundle:PlanComptable")->libExiste($uncompte->getLibelle());
            $exitseguce = $this->getDoctrine()->getManager()->getRepository("adminEconomatBundle:PlanComptable")->seguceExiste($uncompte->getLiea());
//            if ($uncompte->getLiea() == '5' and $exitseguce == 1) {
//                $this->get('session')
//                        ->getFlashBag()
//                        ->add('error', 'Il existe déjà un compte réservé à SEGUCE dans le Plan Comptable!');
//            } else {
                if ($exitcompte == 0 and $exitbib == 0) {
                    $em = $this->getDoctrine()->getManager();
                    $plan->setEtablissement($objetEtablissement) ;//$objetEtablissement
                    $em->persist($plan);
                    $em->flush();
                    $this->get('session')
                            ->getFlashBag()
                            ->add('info', 'Compte ajouté avec succès !');
                    return $this->redirect($this->generateUrl('admin_planliste'));
                } else if ($exitcompte == 1) {
                    $this->get('session')
                            ->getFlashBag()
                            ->add('error', 'Ce Compte existe déjà!');
                } else if ($exitbib == 1) {
                    $this->get('session')
                            ->getFlashBag()
                            ->add('error', 'Le libellé est déjà utilisé !');
//                }
            }
        }
        return $this->render('adminEconomatBundle:PlanComptable:ajouter.html.twig', array('form' => $formBuilder->createView()), $this->response);
    }

    public function modifierPlanComptableAction(Request $request,PlanComptable $plan, $locale) {

       /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "modifier un plan comptable";
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_PLAN, Module::MOD_GEST_PLAN_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des abonnés");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }
        
        
        /*
         * Création du formulaire par rapport a l'entité Caisse
         */

        $em = $this->getDoctrine()->getManager();
        $plan->setDateCreation(new \DateTime());
        $plan->setSuppr('0');
        $formBuilder = $this->createForm(PlanComptableType::class, $plan);
        
        if ($request->getMethod() == 'POST') {
            $formBuilder->bind($request);
            $uncompte = $formBuilder->getData();
            $exitcompte = $em->getRepository("adminEconomatBundle:PlanComptable")->compteExiste($uncompte->getCompte());
            $em->persist($plan);
            $em->flush();
            $this->get('session')
                    ->getFlashBag()
                    ->add('info', 'Compte modifié avec succès !');
            return $this->redirect($this->generateUrl('admin_planliste'));
        }
        return $this->render('adminEconomatBundle:PlanComptable:modifier.html.twig', array('form' => $formBuilder->createView(), 'plan' => $plan), $this->response);
    }

    public function supprimerPlanComptableAction(Request $request, PlanComptable $plan, $locale) {
/*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Supprimer un plan comptable ";
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_PLAN, Module::MOD_GEST_PLAN_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des abonnés");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }
        
        
        /*
         * Création du formulaire par rapport a l'entité Caisse
         */

        $em = $this->getDoctrine()->getManager();
        $plan->setSuppr('1');
        $em->persist($plan);
        $em->flush();
        $this->get('session')
                ->getFlashBag()
                ->add('info', 'suppression effectuée avec succès !');
        return $this->redirect($this->generateUrl('admin_planliste'));
    }

    public function listePlanComptableAction(Request $request, $locale,$page,$nbparpage) {

        $em = $this->getDoctrine()->getManager();
/*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Lister un plan comptable ";
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_PLAN, Module::MOD_GEST_PLAN_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des abonnés");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }
        
       
        /*
         * Création du formulaire par rapport a l'entité Caisse
         */
        $listeplan = $em->getRepository("adminEconomatBundle:PlanComptable")->findBy(array('suppr' => 0, 'liea' => array(0, 1, 2, 3, 4, 5, 6, 8, 9,10)));
        /* total des resultats */
        $total = count($listeplan);
        $articles_per_page = $nbparpage;
        
        $last_page = ceil($total/$articles_per_page);
        $previous_page = $page > 1 ? $page - 1 : 1;
        $next_page = $page < $last_page ? $page + 1 : $last_page;
        $entitiesplan = $em->getRepository("adminEconomatBundle:PlanComptable")->getListePlan($total, $page, $articles_per_page);
         //var_dump('tester');exit;
        return $this->render('adminEconomatBundle:PlanComptable:liste.html.twig', array('listeplan' => $entitiesplan, 
                    'locale' => 'fr',
                    'last_page' => $last_page,
                    'previous_page' => $previous_page,
                    'current_page' => $page,
                    'nbparpage' => $nbparpage,
                    'next_page' => $next_page,
                    'total' => $total), $this->response);
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
                $codeAgence = $currentConnete["codeagence"];
                $libAgence = $currentConnete["libagence"];            
                $this->get('request')->attributes->set('codeagence', $codeAgence);
                $this->get('request')->attributes->set('libagence', $libAgence);
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
