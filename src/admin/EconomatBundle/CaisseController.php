<?php

namespace admin\StockBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use admin\UserBundle\Types;
use admin\StockBundle\Entity\Caisse;
use admin\UserBundle\Entity\Module;
use admin\StockBundle\Form\CaisseType;
use admin\UserBundle\Types\TypeEtat;
use admin\UserBundle\Services\LoginManager;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controlleur qui permet de gérer les operations sur les rubriques.
 *
 * @author armand.tevi@gmail.com
 * @copyright ADMIN 2015
 *
 * @version 1
 */
class CaisseController extends Controller {

    /**
     * Nom du controleur.
     */
    protected $nom = 'CaisseController';

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
     * Nom du Bundle
     */
    private $stockBundle = 'adminStockBundle:';

    /**
     * @var string
     *             Le comtroleur 
     */
    public function __construct() {
       // parent::__construct();
        $this->response = new Response();
        
        $this->response->headers->addCacheControlDirective('no-cache', true);
        $this->response->headers->addCacheControlDirective('max-age', TypeEtat::INACTIF);
        $this->response->headers->addCacheControlDirective('must-revalidate', true);
        $this->response->headers->addCacheControlDirective('no-store', true);
    }

    /**
     * Methode s'occupant de l'ajout du caisse.
     *
     * @author armand.tevi@gmail.com
     * @copyright ADMIN 2015
     *
     * @version 1
     *
     * @return twig d'ajout d'un abonne ajouterCaisse.html.twig
     */
    public function ajouterCaisseAction(Request $request) {
    /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Ajouter un caisse";
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
        $sessionData = $this->infosConnecte();
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_CAIS, Module::MOD_GEST_CAIS_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des abonnés");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }
        
        
        /*
         * Création du formulaire par rapport a l'entité Caisse
         */
        $unCaisse = new Caisse();
        
        $form = $this->createForm(new CaisseType(), $unCaisse);
        $this->em = $this->getDoctrine()->getManager();
   /*
         * Declaration des principales methodes Symfony2 pour traiter les informations
         */
        $this->em = $this->getDoctrine()->getManager();
        $this->request = $request;
        $this->session = $this->get('session');
        $this->flashMessage = $this->get('session')->getFlashBag();
        
        //Recuperation de la liste des Caisses que Symfony 2 accepte
        $tableauCaisseSymfony = array();
        
        /*
         * Donnée à afficher sur le twig
         * 
         */
        $this->data['formuView'] = $form->createView();
        $this->data['Caisse'] = $unCaisse;
        $this->data['locale'] = $locale;

        if ($this->request->isMethod('POST')) {
            $form->handleRequest($this->request);
            /*
             * Vérifier si les élément du formulaire sont valides
             * 
             */
            //var_dump($form);exit;
            if ($form->isSubmitted() ) {
                /*
                 * On cherche si une Caisse existe deja avec le mm nom et  a un code
                 */
                try {
                    $criteria = array('nomCaisse' => $unCaisse->getNomCaisse());
                    $ancienCaisse = $this->em->getRepository($this->stockBundle . 'Caisse')->findOneBy($criteria);
                    //$criteriaCode = array('codeCaisse' => $unCaisse->getCodeCaisse());
                    $ancienCaisseCode = $this->em->getRepository($this->stockBundle . 'Caisse')->findOneBy($criteria);
                    if ($ancienCaisseCode != null) {
                        /*
                         * Un Caisse existe mais est supprimé, on le reactive alors
                         */
                        if ($ancienCaisseCode->getEtatCaisse() == Types\TypeEtat::SUPPRIME) {
                            $unCaisse->setEtatCaisse(Types\TypeEtat::ACTIF);
                            $unCaisse->setNomCaisse($form->getData()->getNomCaisse());
                            $this->em->persist($unCaisse);
                            $this->em->flush();
                            $this->get('session')->getFlashBag()->add('caisse.ajout.success', 'Ajout effectue avec succès');

                            return $this->redirect($this->generateUrl('admin_caisses'));
                        } else {
                            $this->get('session')->getFlashBag()->add('caisse.ajout.already.exist', 'Le code  Caisse ' . $ancienCaisseCode->getCodeCaisse() . ' existe déjà');
                        }
                    } else {
                        /*
                         * Vérification du libellé du critère
                         */
                        if ($ancienCaisse != null) {
                            /*
                             * Un Caisse existe mais est supprimé, on le reactive alors
                             */
                            $this->get('session')->getFlashBag()->add('caisse.ajout.already.exist', 'Le Caisse ' . $ancienCaisse->getNomCaisse() . ' existe déjà');
                        } else {
//                            $unCaisse->setDateModification(new \DateTime());
                            $unCaisse->setDatePublication(new \DateTime());

                       
                            
                            $unCaisse->setEtatCaisse(1);
                            $this->em->persist($unCaisse);
                            $this->em->flush();
                            $this->flashMessage->add('caisse.ajout.success', 'Ajout effectué avec succès');

                            return $this->redirect($this->generateUrl('admin_caisses'));
                        }
                    }
                } catch (\Exception $e) {
                    var_dump($e);
                    exit;
                }
            } else {
                $this->flashMessage->add('caisse.ajout.error', 'Formulaire invalide');
            }
        }

        return $this->render($this->stockBundle . 'Caisse:ajouterCaisse.html.twig', $this->data, $this->response);
    }

    
    
    
    /**
     * Methode s'occupant de la modification du caisse.
     *
     * @author armand.tevi@gmail.com
     * @copyright ADMIN 2015
     *
     * @version 1
     *
     * @return twig d'ajout d'un abonne modifierCaisse.html.twig
     */
    public function modifierCaisseAction(Request $request, $id) {
       /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Modifier un caisse ";
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
        $sessionData = $this->infosConnecte();
        /*
         * Locale en cours
         */
        $locale = $loginManager->getLocale();
        $this->data['locale'] = $locale;
        /*
         * On vérifie si l'utilisateur est connecté
         */
        $this->flashMessage = $this->get('session')->getFlashBag();
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_CAIS, Module::MOD_GEST_CAIS_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des abonnés");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }
        $this->em = $this->getDoctrine()->getManager();
        /*
         * On vérifie si l'utilisateur est connecté
         */
        /*
         * Création du formulaire par rapport a l'entité Caisse
         */
        $unCaisse = $this->em->getRepository($this->stockBundle . 'Caisse')->find($id);
        //$ancienCaisseAModif = $unCaisse->getNomCaisse();
        $form = $this->createForm(new CaisseType(), $unCaisse);
        // Vérification de véracité des informations envoyées
        
        //var_dump($unCaisse,$id);exit;
        if ($unCaisse == null) {
            return $this->redirect($this->generateUrl('admin_caisses'));
        }
        // Vérifier si la méthode d'envoie
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            /*
             * Vérifier si les éléments du formulaire sont valides et que le formulaire a ete soumis
             */
            
            if ($form->isSubmitted()) {
                
                try {
                    
                    /*
                     * On cherche si une Caisse existe deja avec le même nom
                     */
                    $criteria = array('nomCaisse' => $unCaisse->getNomCaisse());
                    $ancienCaisse = $this->em->getRepository($this->stockBundle . 'Caisse')->findOneBy($criteria);
                    /*
                     * On cherche si une Caisse existe deja avec le même code
                     */
                    //$unCaisse->setIdAuteur(1);
                   // $criteriaCode = array('codeCaisse' => $unCaisse->getCodeCaisse());
                    $ancienCaisseCode = $this->em->getRepository($this->stockBundle . 'Caisse')->findBy($criteria);
                    if (count($ancienCaisseCode) > 1) {
                        $this->flashMessage->add('caisse.modifier.already.exist', 'Le code  Caisse ' . $ancienCaisseCode[0]->getCodeCaisse() . ' existe déjà');
                    } else {
                        if ($ancienCaisse != null) {
                            /*
                             * Traitement du cas d'un Caisse qui existe mais est 
                             * supprimé, on le reactive alors pour eviter 
                             * les doublons dans la base de données
                             */
                            if ($ancienCaisse->getEtatCaisse() == Types\TypeEtat::SUPPRIME) {
                                /*
                                 * Activation du critère supprimé
                                 */
                                $ancienCaisse->getEtatCaisse(Types\TypeEtat::ACTIF);
                            } else {
                                /*
                                 * Persistence de l'objet
                                 */
                                $this->em->persist($unCaisse);
                            }
                        } else {
                            $this->em->persist($unCaisse);
                        }
                        /*
                         * Mise a jour des informations dans la base de donnée
                         */
                        $this->em->flush();
                        $this->flashMessage->add('caisse.modifier.success', 'Modification effectuées avec succès');

                        return $this->redirect($this->generateUrl('admin_caisses'));
                    }
                } catch (\Exception $e) {
                    var_dump($e);
                    exit;
                }
            } else {
                $this->flashMessage->add('caisse.ajout.error', 'Formulaire invalide');
            }
        }
        /*
         * Preparation des informations à traiter sur les twig 
         */
        $this->data['formuView'] = $form->createView();
        $this->data['Caisse'] = $unCaisse;
        $this->data['locale'] = $locale;
        $this->data['id'] = $id;

        return $this->render($this->stockBundle . 'Caisse:modifierCaisse.html.twig', $this->data, $this->response);
    }

    /**
     * Methode s'occupant de lister les Caisses.
     *
     * @author armand.tevi@gmail.com
     * @copyright ADMIN 2015
     *
     * @version 1
     *
     * @return twig d'ajout d'un abonne listeCaisse.html.twig
     */
    public function listerCaisseAction(Request $request) {
          /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage de la liste des caisses";
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
        $sessionData = $this->infosConnecte();
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_CAIS, Module::MOD_GEST_CAIS_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des abonnés");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }

        try {
            /*
             * Récupération des la liste des critères
             */
            $this->em = $this->getDoctrine()->getManager();
            $listeCaisse = $this->em->getRepository($this->stockBundle . 'Caisse')->getAllCaisse();
        } catch (\Exception $e) {
            var_dump($e);
            exit;
        }
        /*
         * Préparation des informations que nous allons traiter  sur le twig
         */
        $this->data['listeCaisse'] = $listeCaisse;
        $this->data['locale'] = $locale;

        return $this->render($this->stockBundle . 'Caisse:listeCaisse.html.twig', $this->data, $this->response);
    }

 /**
     * Methode s'occupant de la gestion des états du critère
     * Activation, suppression, désactivation de pays.
     *
     * @author armand.tevi@gmail.com
     * @copyright ADMIN 2015
     *
     * @version 1
     *
     * @return Response
     */
    public function changerEtatCaisseAction(Request $request) {
        $rep = array('etat' => FALSE, 'msg' => 'Erreur survenue lors du traitement', 'logout' => FALSE);
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Changer l'etat de la ligne des caisses";
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
        $sessionData = $this->infosConnecte();
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_CAIS, Module::MOD_GEST_CAIS_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $rep['msg'] = "Vous n'avez pas le droit de déconnecter un utilisateur";
            return new Response(json_encode($rep));
        }
        /*
         * Traitement de la requete qui vient de la vue
         * on vérifie si la requete est Ajax et si la methode est post
         */
        if (($request->isXmlHttpRequest()) && ($request->getMethod() == 'POST')) {
            /*
             * on recupere l'état actuel qu'on veut donner au(x) Caisse(s)
             * cette variable est envoyée depuis la vue a travers la requete post ajax
             */
            $tempEtat = (int) $request->get('etat');
            /*
             * on recupere les ids des Caisses qui ont été choisis sur la vue pour subir une modification d'états
             * cette variable est envoyé depuis la vue a travers la requete post ajax
             */
            $tempIds = $request->get('sId');
            /*
             * verification si l'etat envoyé est un état INACTIF , ACTIF ou SUPPRIME 
             * déclaration d'une variable pour prendre l'état comme constante de classe TypeEtat
             */
            $etat = Types\TypeEtat::INACTIF;
            if ($tempEtat == TypeEtat::INACTIF) {
                $etat = Types\TypeEtat::INACTIF;
            } elseif ($tempEtat == TypeEtat::ACTIF) {
                $etat = Types\TypeEtat::ACTIF;
            } elseif ($tempEtat == TypeEtat::SUPPRIME) {
                $etat = Types\TypeEtat::SUPPRIME;
            } else {
                return new Response(json_encode($rep));
            }
            /*
             * éclatement de la chaine d'ids séparé par des | en tableau
             * afin de parcourir le tabelau et faire le changement d'etat par compte
             */
            $tabIds = explode('|', $tempIds);
            /*
             * variable boolean initialisé a false .elle va être modifier à true si tout se passe bien
             */
            $oneOk = false;
            /*
             * Boucle de changement d'état à tout les comptes choisis
             */
            foreach ($tabIds as $idS) {
                /*
                 * Recuperation du Caisse dont l'id est l'id courant de la boucle
                 */
                $unCaisse = $this->em->getRepository($this->stockBundle . 'Caisse')->find((int) $idS);
                if ($unCaisse != null) {
                    $unCaisse->setEtatCaisse($etat);
                    /*
                     * Mise à jour des informations dans la base de donnée
                     */
                    $this->em->flush();
                    $oneOk = true;
                }
            }
            if ($oneOk) {
                $this->flashMessage->add('caisse.gerer.success', 'Opération éffectuée avec succès');
                $rep['msg'] = '';
                $rep['etat'] = true;
            }

            return new Response(json_encode($rep));
        }

        return new Response(json_encode($rep));
    }

    /**
     * Methode qui verifie si la personne est connectée ou pas.
     *
     * @author armand.tevi@gmail.com
     * @copyright ADMIN 2015
     *
     * @version 1
     *
     * @return entier
     */
    public function controlerDroit($status, $loginManager, $tabInfoDroit, $sessionData) {

        //var_dump($status);exit;
        if ($status['isConnecte']) { //Passer le tableau

            /*
             * 
             * Au cas ou l'utilisateur est connecté on vérifie s'il nest pas innactif. S'il est innactif
             * on garde en mémoire flash la route actuelle pour effectuer une redirection lors de la prochaine connexion
             * 
             * 0 pour envoyer l'utilisateur sur la page de deconnexion
             * 1 en cas de perte de connexion
             */

            if ($status['isInnactif']) {
                $routeName = $this->getRequest()->get('_route');
                $routeParams = $this->getRequest()->get('_route_params');
                $this->get('session')->getFlashBag()->add('restoreUrl', $this->generateUrl($routeName, $routeParams));

                $this->get('session')->getFlashBag()->add('ina', "Vous avez accusé un long moment d'inactivité");

                return TypeEtat::INACTIF; //$this->redirect($this->generateUrl('app_admin_user_logout'));
            }
            /*
             * Seuls les utilisateurs admins ont le droit d'acceder à cette action
             */
            if (!$status['isUser']) {
                return TypeEtat::INACTIF; //$this->redirect($this->generateUrl('app_admin_user_logout'));
            }
        } else {
            return TypeEtat::INACTIF; //$this->redirect($this->generateUrl('app_admin_user_logout'));            
        }
        return TypeEtat::SUPPRIME;
    }

    /**
     * Methode pour traiter le libelle du Caisse envoye il permet de remplace les espaces par "_".
     *
     * @author armand.tevi@gmail.com
     * @copyright ADMIN 2015
     *
     * @version 1
     *
     * @return entier
     */
    public function remplacerEspece($donnee) {
        $donneNouvelle = str_replace(' ', '_', $donnee);

        return $donneNouvelle;
    }

}
