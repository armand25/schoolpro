<?php

namespace admin\EconomatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use admin\EconomatBundle\Entity\Schema;
use admin\EconomatBundle\Form\SchemaType;
use admin\EconomatBundle\Entity\TypeOperation;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use admin\UserBundle\Entity\Module;

class SchemaController extends Controller {
    
use \admin\UserBundle\ControllerModel\paramUtilTrait;

  /**
     * Nom du controleur.
     */
    protected $nom = 'SchemaController';

    /**
     * Description  du controleur.
     */
    protected $description = 'Contrôleur qui gère lajout des Caisses';

    /**
     * Declaration de la méthode de recupération de la response.
     */
    protected $response;

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
     * Methode permettant d'ajouter une banque - Espace client
     * 
     * 
     * @var
     * 
     * Les Variables
     * 
     * $banque: Instance de la classe Banque pour l'ajout
     * 
     * 
     *  
     */
    /* public function ajoutSchemaAction($locale) {

      $em = $this->getDoctrine()->getEntityManager();
      $authManager = $this->get('Auth.Manager'); //on recupere le service qui g�re l'authentification = $this->get('Auth.Manager');//on recupere le service qui g�re l'authentification

      $currentID = $authManager->getCurrentId(); //comment r�cup�rer L'id de l'abonne courrant
      $currentConnete = $authManager->getFlash("admin_data");
      $this->infoUtilisateur($em, $authManager, $currentConnete, 'utilisateur',$locale);
      //on verifie si l'abonnee est connect�. sil ne l'est pas on le dirige � la page de connexion
      if (!$authManager->isLogged()) {
      return $this->redirect($this->generateUrl("admin_logout", array('locale' => 'fr')));
      }

      $listeActions = $currentConnete["listeActions_abonne"];

      if (!in_array('ajoutSchemaAction', $listeActions)) {
      $this->get('session')->getFlashBag()->add('accesdenied', "admin.layout.accesdenied");
      return $this->redirect($this->generateUrl("admin_accueil", array('locale' => $locale)));
      }


      $schema1 = new Schema();
      $schema2 = new Schema();

      //$compte1=null;$compte2=null;

      $form = $this->createForm(new SchemaType(), $schema1);
      $request = $this->getRequest();

      $listeCompte = $this->getDoctrine()
      ->getManager()
      ->getRepository("adminEconomatBundle:PlanComptable")->findBy(array('suppr'=>0));

      if ($request->isMethod('POST')) {

      // recuperation du type operation
      $form->handleRequest($request);
      $typeOperation = $form->get('typeoperation')->getData();

      // recuperation des comptes

      $compte1 = $request->request->get('plancompte1');
      $compte2 = $request->request->get('plancompte2');



      //recuperation des sens
      $sens1 = $request->request->get('sens1');
      $sens2 = $request->request->get('sens2');

      $compte1 = $this->getDoctrine()
      ->getManager()
      ->getRepository("adminEconomatBundle:PlanComptable")->find($compte1);

      $compte2 = $this->getDoctrine()
      ->getManager()
      ->getRepository("adminEconomatBundle:PlanComptable")->find($compte2);

      if ($compte1 != null && $compte2 != null ){

      if ( trim($sens1) !='' && trim($sens2) !='' && $sens1 == $sens2){

      $this->get('session')->getFlashBag()->add('notice', 'memesens');

      return $this->render('adminEconomatBundle:Schema:ajoutSchema.html.twig', array(
      'form' => $form->createView(), 'listeCompte'=>$listeCompte,
      ), $this->response);

      }


      $schema1->setPlanComptable($compte1) ;
      $schema1->setSens($sens1);
      $schema1->setTypeoperation($typeOperation);
      $schema1->setCoef(1);
      $em->persist($schema1);


      $schema2->setPlanComptable($compte2) ;
      $schema2->setTypeoperation($typeOperation);
      $schema2->setSens($sens2);
      $schema2->setCoef(1);
      $em->persist($schema2);

      //var_dump($compte1);var_dump($compte2);
      //var_dump($schema1);var_dump($schema2);exit;

      $em->flush();
      $this->get('session')->getFlashBag()->add('notice', 'success');
      }
      return $this->redirect($this->generateUrl("admin_listeschema"));
      }

      return $this->render('adminEconomatBundle:Schema:ajoutSchema.html.twig', array(
      'form' => $form->createView(), 'listeCompte'=>$listeCompte,
      ), $this->response);


      } */


    public function ajoutSchemaAction(Request $request,$idType, $nbreLigne, $locale) {

        $em = $this->getDoctrine()->getEntityManager();
       /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Ajouter un schema comptable";
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_SCHE, Module::MOD_GEST_SCHE_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des abonnés");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }
        

        //recuperation de l'objet etablissement
        $objetEtablissement = $sessionData['etablissement'];

        // var_dump($nbreLigne) ;  exit ;
        // initialisation 
        $schema = array();
        $sens = array();
        $compte = array();
        $balance = array();
        $balance['D'] = 0;
        $balance['C'] = 0;

        $typeOperation = $em->getRepository("adminEconomatBundle:TypeOperation")->find($idType);
        //$nbreLigne = 0; if ($typeOperation != null) $nbreLigne = $typeOperation->getNbreLigne();               
       

        $listeTypeOp = $this->getDoctrine()
                        ->getManager()
                        ->getRepository("adminEconomatBundle:TypeOperation")->findBy(array('suppr' => 0));

        $listeCompte = $this->getDoctrine()
                        ->getManager()
                        ->getRepository("adminEconomatBundle:PlanComptable")->findBy(array('suppr' => 0));
       

        if ($request->isMethod('POST')) {
             
            if ($nbreLigne > 0) {
                

                for ($i = 1; $i <= $nbreLigne; $i++) {
                    
                    $compte[$i] = $request->request->get('plancompte' . $i);
                    $sens[$i] = $request->request->get('sens' . $i);
                    $valeur[$i] = $request->request->get('typecpte' . $i);
                    
                    if ($compte[$i] == null) {
                        //libération de la memoire
                        $this->get('session')->getFlashBag()->add('notice', 'cptenotexiste');
                        return $this->render('adminEconomatBundle:Schema:ajoutSchema.html.twig', array(
                                    'listeCompte' => $listeCompte, 'id' => $idType, 'nbreLg' => $nbreLigne, 'listeTypeOp' => $listeTypeOp,
                        ), $this->response);
                    }
                    $leCompte[$i] = $em->getRepository($this->stockBundle . 'PlanComptable')->findOneBy(array('compte'=>$compte[$i]));
        
                    if ($compte[$i] != null) {
                        $schema[$i] = new Schema();
                        $schema[$i]->setPlanComptable($leCompte[$i]);
                        $schema[$i]->setSens($sens[$i]);
                        $schema[$i]->setTypeoperation($typeOperation);
                        $schema[$i]->setEtablissement($objetEtablissement);
                        $schema[$i]->setValeur($valeur[$i]);
                        $schema[$i]->setCoef(1);
                        $em->persist($schema[$i]);
                    }
                }
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', 'success');
            }
            return $this->redirect($this->generateUrl("admin_listeschema"));
        }
        return $this->render('adminEconomatBundle:Schema:ajoutSchema.html.twig', array(
                    'listeCompte' => $listeCompte, 'id' => $idType, 'nbreLg' => $nbreLigne, 'listeTypeOp' => $listeTypeOp,
        ), $this->response);
    }

    public function processSchemaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) { // pour vérifier la présence d'une requete Ajax
            $name = $request->request->get('nom');
            $cas = $request->request->get('cas');
            $name = str_replace('typecpte', '', $name);
            $name = trim($name);

            $listcompte = $em->getRepository('adminEconomatBundle:PlanComptable')->getListeCpte($cas, $name);
            // var_dump($listcompte) ;exit ;
            $response = new Response();
            $listcompte = json_encode(array('tcpte' => $listcompte));
            //var_dump($listcompte) ; 
            $response->headers->set('Content-Type', 'application/json');
            $response->setContent($listcompte);

            return $response;
        }
        return new Response("Nonnn ....");
    }

    public function listeSchemaAction(Request $request,$page, $locale) {

        $em = $this->getDoctrine()->getEntityManager();

       /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Lister un Schema comptable";
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_SCHE, Module::MOD_GEST_SCHE_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des abonnés");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }
        



        $listeSchemas = array();
        // var_dump(count($listeSchemas)) ; exit ;

        $listeTypeOperations = $em->getRepository("adminEconomatBundle:TypeOperation")->findAll();
        // var_dump($listeSchemas) ; exit ;
        $total = count($listeSchemas);
        ;
        $articles_per_page = 10000;//$this->container->getParameter('max_articles_on_listepage');
        $last_page = ceil($total / $articles_per_page);
        $previous_page = $page > 1 ? $page - 1 : 1;
        $next_page = $page < $last_page ? $page + 1 : $last_page;

        $entitiesschema = $em->getRepository("adminEconomatBundle:Schema")->getListeSchema($page, $articles_per_page);


        return $this->render('adminEconomatBundle:Schema:listeSchema.html.twig', array(
                    'listeschema' => $entitiesschema,
                    'listetypeoperations' => $listeTypeOperations,
                    'last_page' => $last_page,
                    'previous_page' => $previous_page,
                    'current_page' => $page,
                    'next_page' => $next_page,
                    'total' => $total), $this->response);
    }

    public function modifierSchemaAction(Request $request,$id, $locale) {
        $em = $this->getDoctrine()->getEntityManager();

       /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Modifier un schema comptable";
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_SCHE, Module::MOD_GEST_SCHE_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des abonnés");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }
        



        $schema = array();
        $sens = array();
        $compte = array();
        $balance = array();
        $balance['D'] = 0;
        $balance['C'] = 0;

        //ON recupere le typeOperation concerne
        $typeOperation = $em->getRepository("adminEconomatBundle:TypeOperation")->find($id);
        $idType = $typeOperation->getId();
        $nbreLigne = $typeOperation->getNbreLigne();

        $listeschemas = $this->getDoctrine()
                        ->getManager()
                        ->getRepository("adminEconomatBundle:Schema")->findBy(array('typeoperation' => $id));
        //ON recupere les ligenes de schema comptables associees a ce type doperation
        foreach ($listeschemas as $schema) {
            $compte[] = $schema->getPlancomptable();
            $sens[] = $schema->getSens();
        }

        //  var_dump($compte) ; exit ;
        //$request = $this->getRequest();

        $listeTypeOp = $this->getDoctrine()
                        ->getManager()
                        ->getRepository("adminEconomatBundle:TypeOperation")->findBy(array('suppr' => 0));

        $listeCompte = $this->getDoctrine()
                        ->getManager()
                        ->getRepository("adminEconomatBundle:PlanComptable")->findBy(array('suppr' => 0));
        //Si le bouton modifier a ete clique
        if ($request->isMethod('POST')) {
            if ($nbreLigne > 0) {
                //var_dump($nbreLigne) ; exit ;
                for ($i = 1; $i <= $nbreLigne; $i++) {
                    $compte[$i] = $request->request->get('plancompte' . $i);

                    $sens[$i] = $request->request->get('sens' . $i);
                    if (strtoupper(trim($sens[$i])) == 'D')
                        $balance['D'] .= 1;
                    if (strtoupper(trim($sens[$i])) == 'C')
                        $balance['C'] .= 1;

                    $compte[$i] = $this->getDoctrine()
                                    ->getManager()
                                    ->getRepository("adminEconomatBundle:PlanComptable")->find($compte[$i]);

                    if ($compte[$i] != null) {
                        //On fait des set datas par rapport aux donnees envoyees et aux lignes de schemas comptables crees
                        $listeschemas[$i - 1]->setPlanComptable($compte[$i]);
                        $listeschemas[$i - 1]->setSens($sens[$i]);
                        $listeschemas[$i - 1]->setTypeoperation($typeOperation);
                        $listeschemas[$i - 1]->setCoef(1);

                        $em->persist($listeschemas[$i - 1]);
                        $em->flush();
                    }



                    // var_dump($listeschemas) ; exit ; 
                }



                $this->get('session')->getFlashBag()->add('notice', 'modifsuccess');
            }
            return $this->redirect($this->generateUrl("admin_listeschema"));
        }
        return $this->render('adminEconomatBundle:Schema:modifschema.html.twig', array(
                    'listeCompte' => $listeCompte, 'listeschemas' => $listeschemas, 'id' => $idType, 'nbreLg' => $nbreLigne, 'listeTypeOp' => $listeTypeOp, 'comptes' => $compte, 'sens' => $sens,
        ), $this->response);
    }

    public function supprimerSchemaAction(Request $request,$id) {

        $em = $this->getDoctrine()->getEntityManager();

       /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Supprimer un schema comptable";
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_SCHE, Module::MOD_GEST_SCHE_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des abonnés");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }
        

        $schema = $em->getRepository("adminEconomatBundle:Schema")->find($id);

        $this->get('request')->attributes->set('type_user', 'utilisateur');

        $em->remove($schema);
        $em->flush($schema);

        $this->get('session')->getFlashBag()->add('notice', 'Banque suppirmé avec succès');
        return $this->redirect($this->generateUrl('admin_listeschema'));
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
