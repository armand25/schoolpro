<?php

namespace admin\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Response;
use \admin\UserBundle\Entity\Module;
use \admin\UserBundle\Entity\Utilisateur;
use admin\ScolariteBundle\Entity\EstParent;
use \admin\ParamBundle\Types\TypeParametre;
use \admin\UserBundle\Services\LoginManager;
use \admin\UserBundle\Types\TypeEtat;
use \admin\UserBundle\Types\TypeCodeProfil;
use \Symfony\Component\HttpFoundation\Request;
use admin\UserBundle\Form\ModifierUserType;
use admin\UserBundle\Form\ModifierUserParentType;
use admin\UserBundle\Form\UserType;

/**
 * Controller de gestion des utilisateurs 
 * @author armand.tevi@gmail.com
 * @copyright Master Internationnal  - UTBM/ IAI/CIC/
 * @version 1
 * @property Response $response
 */
class UserController extends Controller {

    use \admin\UserBundle\ControllerModel\paramUtilTrait;

    public function __construct() {
        $this->response = new Response();
        $this->response->headers->addCacheControlDirective('no-cache', true);
        $this->response->headers->addCacheControlDirective('max-age', 0);
        $this->response->headers->addCacheControlDirective('must-revalidate', true);
        $this->response->headers->addCacheControlDirective('no-store', true);
        $this->logMessage = " [ UserController ] ";
        $this->description = "Controlleur qui gère les utilisateurs";
    }

    /**
     * Affiche la liste des utilisateur par parfil. si $idProfil = 0 alors on affiche les utilisateurs de ts les profils
     * 
     * @example listeUserByProfilAction($idProfil =1)
     *
     * @author armand.tevi@gmail.com
     *
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     *
     * @param int $idProfil
     *
     * @version 1
     *
     * @var array $tab tableau des cles du tableau de flashbag 
     * @var array $rep     
     * @var string $nomAction Nom de l'action
     * @var string $descAction Description de l'action
     * @var int $idProfilQuery Identifiant du profil
     * @var UserBundle\Services\LoginManager $loginManager Service Monolog
     * @var array $sessionData  Varaible session
     * @var string $locale Langue 
     * @var array $status tableau sur le statut
     * @var EntityMananger $em 
     *  
     * @var Utilisateur $user,$oldUserByEmail,$oldUser utilisateur 
     * @var Profil $profil  Profil de l'utilisateur
     * @var UserBundle\Services\ParametreManager $parametreManager
     * @var int $longueurTel longueur de telephone
     * @var FormType $form instance du formulaire
     * @var Request $request
     * @var int $minLengthPassword taille minimale du mot de passe
     * @var array $criteria tableau des critÃƒÂ¨res 
     *            
     * @access  public
     *
     * @return View listeUser.html.twig
     */
    public function listeUserByProfilAction(Request $request, $idProfil) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage de la liste des administrateurs";
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
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des administrateurs");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }
        $em = $this->getDoctrine()->getManager();

        $idProfilQuery = (int) $idProfil;
        $profil = null;
        if ($idProfilQuery > 0) {
            $profil = $em->getRepository($this->userBundle . 'Profil')->find($idProfilQuery);
            if ($profil == NULL) {
                $this->get('session')->getFlashBag()->add('non.profil', "Profil non prise en compte");
                return $this->redirect($this->generateUrl('app_admin_user_home'));
            }
        }else{
            $this->get('session')->getFlashBag()->add('non.profil', "Profil non prise en compte");
            return $this->redirect($this->generateUrl('app_admin_user_home'));
        }

        $users = $em->getRepository($this->userBundle . 'Utilisateur')->getAllUser($idProfilQuery);
        $lesProfils = $em->getRepository($this->userBundle . 'Profil')->getAllProfil();
        //var_dump($sessionData['siAdminGeneral']);exit;
        if($sessionData['siAdminGeneral']){            
            $users = $em->getRepository($this->userBundle . 'Utilisateur')->getAllUser($idProfilQuery);
        }else{
            $users = $em->getRepository($this->userBundle . 'Utilisateur')->getAllUserEtablissement($idProfilQuery,$sessionData['etablissement']);
        } 
       
        //var_dump($idProfilQuery);exit;

        $this->data['users'] = $users;
        $this->data['lesProfils'] = $lesProfils;
        $this->data['locale'] = $locale;
        $this->data['idProfil'] = $idProfil;
        $this->data['profil'] = $profil;

        return $this->render($this->userBundle . 'User:listeUser.html.twig', $this->data, $this->response);
    }

    /**
     * Ajout d'utilisateur
     * 
     * @example ajoutUtilisateurAction($idProfil =1)
     *
     * @author armand.tevi@gmail.com
     *
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     *
     * @param int $idProfil
     *
     * @version 1
     *
     * @var array $tab tableau des cles du tableau de flashbag 
     * @var array $rep     
     * @var string $nomAction
     * @var string $descAction
     * @var int $idProfilQuery
     * @var UserBundle\Services\LoginManager $loginManager
     * @var array $sessionData
     * @var string $locale
     * @var array $status
     * @var EntityMananger $em 
     * @var Utilisateur $user,$oldUserByEmail,$oldUser 
     * @var Profil $profil
     * @var UserBundle\Services\ParametreManager $parametreManager
     * @var int $longueurTel
     * @var FormType $form
     * @var Request $request
     * @var int $minLengthPassword
     * @var array $criteria
     * 
     * @throws  NotFoundHttpException
     * @throws  PDOException
     *            
     * @access  public
     *
     * @return View ajouterUser.html.twig
     */
    public function ajoutUtilisateurAction(Request $request, $idProfil, $idEleve, $type,$idEtablissement) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Ajout d'un nouvel administrateur";
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
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'ajouter un administrateur");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }
        $em = $this->getDoctrine()->getManager();
        
        //Recuperation de l'établissement de l'élève
        $objetEtablissement =  $em->getRepository($this->scolariteBundle . 'Etablissement')->find($sessionData['etablissement']);

        /*
         * On recupère le profil si un est défini
         */
        $user = new Utilisateur();
        $idProfilQuery = (int) $idProfil;
        if($idProfilQuery==0) $idProfilQuery=$sessionData['idProfil'];
        $profil = $em->getRepository($this->userBundle . 'Profil')->find($idProfilQuery);
        if ($profil != NULL) {
            $user->setProfil($profil);
        }
        
        
        //etablissemnt utilisateur admin
        $idEtabl = $request->get('etablissement_');
        if($idEtablissement!=0){
            $idEtabl=$idEtablissement;
        }
        if($idEtabl !=0){
            $objetEtablissement = $em->getRepository($this->scolariteBundle . 'Etablissement')->find($idEtabl);
        }
        
        $parametreManager = $this->get('parametre_manager');
        $longueurTel = $parametreManager->getValeurParametre(TypeParametre::LONGUEUR_NUM_TEL_INT);
        if ($longueurTel == NULL) {
            $longueurTel = 8;
        }
        $form = $this->createForm(UserType::class, $user, array('attr' => array('maxlength' => $longueurTel)));
        
        //Recuperation des établissements
        $etablissements = $em->getRepository($this->scolariteBundle . 'Etablissement')->getAllEtablissement();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            // if ($form->isValid()) {
            /*
             * Le mot de passe ne doit pas être vide
             */
            
             if($user->getEmail() == ""){
                $email = $user->getNom().''.$user->getPrenoms().rad2deg(1).'@lequipe.com';
            }else{
                $email = $user->getEmail();
            }
            $parametreManager = $this->get('parametre_manager');
            $minLengthPassword = $parametreManager->getValeurParametre(TypeParametre::LONGUEUR_MIN_PASSWORD_INT);
            if ($minLengthPassword == NULL) {
                $minLengthPassword = 5;
            }
            //Recuperation du profil de l'utilisateur qu'on vien d'enregistré
            $idProfil = $user->getProfil()->getId();
            $user->setEmail($email);
            if (strlen($user->getPassword()) < $minLengthPassword) {
                $this->get('session')->getFlashBag()->add('user.ajout.error', "Vous devez indiquer un mot de passe d'au moins " . $minLengthPassword . " caractères");
            } else {
                /*
                 * Les mots de passe doivent être identiques 
                 */
                if ($user->getPassword() != $user->getCPassword()) {
                    $this->get('session')->getFlashBag()->add('user.ajout.error', 'Les mots de passe doivent être identiques');
                } else {
                    $user->setPassword(md5($user->getPassword()));
                    $user->setCPassword($user->getPassword());
                    $criteria = array('nom' => $user->getNom(), 'prenoms' => $user->getPrenoms());
                    $oldUser = $em->getRepository($this->userBundle . 'Utilisateur')->findOneBy($criteria);
                    /*
                     * Aucun user ne possede le mm non et ls mm prénoms
                     */
                    if ($oldUser == NULL) {
                        $oldUserByEmail = $em->getRepository($this->userBundle . 'Utilisateur')->findOneByEmail($user->getEmail());
                        /*
                         * Aucun user ne possede l'adresse email renseigne
                         */
                        if ($oldUserByEmail == NULL) {
                            $tableauMatiere = $request->get('tab_Matiere');

                            if (count($tableauMatiere) > 0) {
                                foreach ($tableauMatiere as $unMatiere) {
                                    //formation de l'objet domaine
                                    $objetMatiere = $em->getRepository($this->scolariteBundle . 'Matiere')->find($unMatiere);
                                    $objetMatiere->addUtilisateur($user);

                                    $user->addMatiere($objetMatiere);
                                }
                                $em->persist($objetMatiere);
                            }
                            $user->setEtablissement($objetEtablissement);
                            $em->persist($user);
                            $em->flush();

                            if ($idEleve != 0) {
                                $eleve = $em->getRepository($this->scolariteBundle . 'Eleve')->find($idEleve);
                                $objetEstParent = new EstParent();
                                $objetEstParent->setEleve($eleve);
                                $objetEstParent->setEtatEstParent($type);
                                $objetEstParent->setUtilisateur($user);
                                $em->persist($objetEstParent);
                                $em->flush();
                            }


                            $this->get('session')->getFlashBag()->add('user.ajout.success', 'Ajout effectue avec succès');
                            return $this->redirect($this->generateUrl('app_admin_users', array('idProfil' => $idProfil)));
                        } else {
                            $this->get('session')->getFlashBag()->add('user.ajout.error', "L'adresse email (" . $user->getEmail() . ") existe déjà");
                        }
                    } else {
                        /*
                         * Utilisateur avec mm nom et prenoms existe deja et est supprime
                         */
                        if ($oldUser->getEtat() == TypeEtat::SUPPRIME) {
                            $oldUserByEmail = $em->getRepository($this->userBundle . 'Utilisateur')->findOneByEmail($user->getEmail());
                            /*
                             * Aucun user ne possede l'adresse email renseigne
                             */
                            if ($oldUserByEmail == NULL) {
                                $tableauMatiere = $request->get('tab_Matiere');
                                if (count($tableauMatiere) > 0) {
                                    foreach ($tableauMatiere as $unMatiere) {
                                        //formation de l'objet domaine
                                        $objetMatiere = $em->getRepository($this->scolariteBundle . 'Matiere')->find($unMatiere);
                                        $objetMatiere->addUtilisateur($user);
                                        $user->addMatiere($objetMatiere);
                                    }
                                }
                                $em->persist($objetMatiere);

                                $em->persist($user);

                                $em->flush();
                                if ($idEleve != 0) {
                                    $eleve = $em->getRepository($this->scolariteBundle . 'Eleve')->find($idEleve);
                                    $objetEstParent = new EstParent();
                                    $objetEstParent->setEleve($eleve);
                                    $objetEstParent->setEtatEstParent($type);
                                    $objetEstParent->setUtilisateur($user);
                                    $em->persist($objetEstParent);
                                    $em->flush();
                                }
                                $this->get('session')->getFlashBag()->add('user.ajout.success', 'Ajout effectue avec succès');
                                return $this->redirect($this->generateUrl('app_admin_users', array('idProfil' => $idProfil)));
                            } else {
                                $this->get('session')->getFlashBag()->add('user.ajout.error', "L'adresse email (" . $user->getEmail() . ") existe déjà");
                            }
                        } else {
                            /*
                             * Utilisateur avec mm nom et prenoms existe deja et est non supprime
                             */
                            $this->get('session')->getFlashBag()->add('user.ajout.already.exist', "L'utilisateur " . $user->getNom() . " " . $user->getPrenoms() . " existe déjà");
                        }
                    }
                }
            }
//            } else {
//                $this->get('session')->getFlashBag()->add('user.ajout.error', 'Formulaire invalide');
//            }
        }

        $this->data['formuView'] = $form->createView();
        $this->data['user'] = $user;
        $this->data['profil'] = $profil;
        //$this->data['pass'] = $pass;
        $this->data['idEtabl'] = $sessionData['etablissement'];
        $this->data['idEtablEcole'] = $idEtablissement;
        $this->data['etablissements'] = $etablissements;
        $this->data['idProfil'] = $idProfil;
        $this->data['longueurTel'] = $longueurTel;
        $this->data['locale'] = $locale;

        return $this->render($this->userBundle . 'User:ajouterUser.html.twig', $this->data, $this->response);
    }

    /**
     * Activation, suppression, dÃƒÂ©sactivation d'utilisateur
     * 
     * @example changerEtatUserAction()
     *
     * @author armand.tevi@gmail.com
     *
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     *
     * @version 1
     *
     * @var array $tab tableau des cles du tableau de flashbag 
     * @var array $rep     
     * @var string $nomAction Nom de l'action
     * @var string $descAction Description de l'action
     * @var int $idProfilQuery Identifiant du profil
     * @var UserBundle\Services\LoginManager $loginManager Service Monolog
     * @var array $sessionData  Varaible session
     * @var string $locale Langue 
     * @var array $status tableau sur le statut
     * @var EntityMananger $em  
     * @var Utilisateur $user   Instance de l'Utilisateur dont on veut changer l'etat
     * @var int $tempEtat Etat temporaire
     * @var int $etat Nouvel etat a affecter 
     * @var array $tabIds Tableau des identifiants de d'utilisateurs dont on veut changer les etats
     * @var boolean $oneOk
     *            
     * @access  public
     *
     * @return Response
     */
    public function changerEtatUserAction(Request $request) {
        $rep = array('etat' => FALSE, 'msg' => 'Erreur survenue lors du traitement');
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Activation, suppression, désactivation d'administrateurs";
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
            $rep['msg'] = "Vous n'avez pas le droit d'activer, de supprimer ou de désactiver un administrateur";
            return new Response(json_encode($rep));
        }

        $em = $this->getDoctrine()->getManager();
        if (($request->isXmlHttpRequest()) && ($request->getMethod() == 'POST')) {
            $tempEtat = (int) $request->request->get('etat');
            $tempIds = $request->request->get('sId');

            $etat = TypeEtat::INACTIF;
            if ($tempEtat == 0) {
                $etat = TypeEtat::INACTIF;
            } else if ($tempEtat == 1) {
                $etat = TypeEtat::ACTIF;
            } else if ($tempEtat == 2) {
                $etat = TypeEtat::SUPPRIME;
            } else {
                return new Response(json_encode($rep));
            }

            $tabIds = explode('|', $tempIds);
            $oneOk = FALSE;
            foreach ($tabIds as $idS) {
                $user = $em->getRepository($this->userBundle . 'Utilisateur')->find((int) $idS);
                if ($user != NULL) {
                    if ($user->getId() != $sessionData['id']) {
                        $user->setEtat($etat);
                        if ($etat == TypeEtat::ACTIF) {
                            $user->setAttempt(0);
                        }
                        $em->flush();
                        $oneOk = TRUE;
                    } else {
                        $rep['msg'] = "Vous ne pouvez pas modifier l'utilisateur courant";
                    }
                }
            }
            if ($oneOk) {
                $this->get('session')->getFlashBag()->add('user.modifier.success', 'Opération éffectuée avec succès');
                $rep['msg'] = '';
                $rep['etat'] = TRUE;
            }

            return new Response(json_encode($rep));
        }
        return new Response(json_encode($rep));
    }

    /**
     * Deconnexion d'un utilisateur
     * 
     * @example adminLogoutUserAction()
     *
     * @author armand.tevi@gmail.com
     *
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     *
     * @version 1
     *
     * @var array $tab tableau des cles du tableau de flashbag 
     * @var array $rep     
     * @var string $nomAction Nom de l'action
     * @var string $descAction Description de l'action
     * @var int $idProfilQuery Identifiant du profil
     * @var UserBundle\Services\LoginManager $loginManager Service Monolog
     * @var array $sessionData  Varaible session
     * @var string $locale Langue 
     * @var array $status tableau sur le statut
     * @var EntityMananger $em 
     * @var Utilisateur $user
     * @var int $tempEtat
     * @var int $etat
     * @var array $tabIds
     * @var boolean $oneOk
     *            
     * @access  public
     *
     * @return Response
     */
    public function adminLogoutUserAction(Request $request) {
        $rep = array('etat' => FALSE, 'msg' => 'Erreur survenue lors du traitement', 'logout' => FALSE);
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Déconnection d'un administrateur";
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
            $rep['msg'] = "Vous n'avez pas le droit de déconnecter un administrateur";
            return new Response(json_encode($rep));
        }

        $em = $this->getDoctrine()->getManager();
        if (($request->isXmlHttpRequest()) && ($request->getMethod() == 'POST')) {
            $idUser = (int) $request->request->get('idUser');
            $user = $em->getRepository($this->userBundle . 'Utilisateur')->find($idUser);
            if ($user == NULL) {
                $rep['msg'] = "Informations introuvables";
                return new Response(json_encode($rep));
            }

            $user->setEtatConnecte(FALSE);
            $em->flush();
            $rep['etat'] = TRUE;
            if (($user->getId() == $sessionData['id']) && ($user->getProfil()->getId() == $sessionData['idProfil'])) {
                $rep['logout'] = TRUE;
            } else {
                $this->get('session')->getFlashBag()->add('user.modifier.success', 'Utilisateur ' . $user->getNom() . ' ' . $user->getPrenoms() . ' déconnecté avec succès');
            }
            return new Response(json_encode($rep));
        }
        return new Response(json_encode($rep));
    }

    /**
     * Affihe la liste paginee des historique de connexion d'un utilisateur
     * 
     * @example historiqueConnexionAction(1,0,100,2)
     *
     * @author armand.tevi@gmail.com
     *
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     *
     * @version 1
     * @param int $idUser : identifiant
     * @param int  isUser : 0 = Abonne et 1 = Utilisateur
     * @param int $nbParPage
     * @param int $pageActuelle
     *
     * @var array $tab tableau des cles du tableau de flashbag 
     * @var array $rep     
     * @var string $nomAction Nom de l'action
     * @var string $descAction Description de l'action
     * @var int $idProfilQuery Identifiant du profil
     * @var UserBundle\Services\LoginManager $loginManager Service Monolog
     * @var array $sessionData  Varaible session
     * @var string $locale Langue 
     * @var array $status tableau sur le statut
     * @var EntityMananger $em 
     * 
     * @var int $lastPage Derniere page
     * @var int $finPagination Derniere page
     * @var int $previousPage  Page precedente
     * @var int $nextPage Page suivante
     *            
     * @access  public
     *
     * @return Response
     */
    public function historiqueConnexionAction(Request $request, $idUser, $isUser, $nbParPage, $pageActuelle) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage de l'historique de connexion d'un administrateur";
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
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder aux historiques de connexion");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }

        $results = $loginManager->getHistoriqueConnexion($idUser, $isUser, $nbParPage, $pageActuelle);
        if ($results['user'] == NULL) {
            return $this->redirect($this->generateUrl('app_admin_user_home'));
        }
        $historiques = $results['data'];
        $this->data['nbParPage'] = $results['nbParPage'];
        $this->data['pageActuelle'] = $results['pageActuelle'];
        $this->data['nbTotal'] = $results['nbTotal'];
        $this->data['nbTotalPage'] = $results['nbTotalPage'];
        $this->data['user'] = $results['user'];
        $this->data['details'] = ( ($sessionData['codeProfil'] == TypeCodeProfil::MAINTENANCE)  ) ? TRUE : FALSE;
        $this->data['isUser'] = $isUser;
        $this->data['idUser'] = $idUser;
        $this->data['histos'] = $historiques;
        $this->data['locale'] = $locale;

        $lastPage = $results['nbTotalPage'];

        $nextPage = $results['pageActuelle'] + 1;
        if ($nextPage > $lastPage) {
            $nextPage = $lastPage;
        }
        $previousPage = $results['pageActuelle'] - 1;
        if ($previousPage < 1) {
            $previousPage = 1;
        }

        $debutPagination = $pageActuelle - 2;
        if ($debutPagination < 1) {
            $debutPagination = 1;
        }

        $finPagination = $pageActuelle + 2;
        if ($finPagination > $lastPage) {
            $finPagination = $lastPage;
        }

        $this->data['finPagination'] = $finPagination;
        $this->data['debutPagination'] = $debutPagination;
        $this->data['previousPage'] = $previousPage;
        $this->data['nextPage'] = $nextPage;
        $this->data['lastPage'] = $lastPage;
        return $this->render($this->userBundle . 'User:historiqueConnexion.html.twig', $this->data, $this->response);
    }

    /**
     * Affiche les dÃƒÂ©tails d'une connexion d'un utilisateur
     * 
     * @example detailsConnexionAction(1,0)
     *
     * @author armand.tevi@gmail.com
     *
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     *
     * @version 1
     * 
     * @param int $idConnexion identifiant de connexion
     * @param int isUser  Savoir si le profil connectÃƒÂ© est un utilisateur
     *
     * @var array $tab tableau des cles du tableau de flashbag 
     * @var array $rep     
     * @var string $nomAction Nom de l'action
     * @var string $descAction Description de l'action
     * @var int $idProfilQuery Identifiant du profil
     * @var UserBundle\Services\LoginManager $loginManager Service Monolog
     * @var array $sessionData  Varaible session
     * @var string $locale Langue 
     * @var array $status tableau sur le statut
     * @var EntityMananger $em 
     * 
     * @var Utilisateur $user
     * @var string $routeName nom de la route
     * @var array $routeParams ParamÃƒÂ¨tres de la route
     * @var array $results Details des connexions par utilisateur
     *            
     * @access  public
     *
     * @return Response
     */
    public function detailsConnexionAction(Request $request, $idConnexion, $isUser) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage des détails d'une connexion";
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
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder aux détails d'une connexion");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }

        $results = $loginManager->getDetailsConnexion($idConnexion, (int) $isUser);

        $user = $results['user'];
        $details = $results['details'];
        if (($results == NULL) || ( $user == NULL)) {
            return $this->redirect($this->generateUrl('app_admin_user_home'));
        }

        $this->data['details'] = $details;
        $this->data['user'] = $user;
        $this->data['connexion'] = $results['connexion'];
        $this->data['locale'] = $locale;

        return $this->render($this->userBundle . 'User:detailsConnexion.html.twig', $this->data, $this->response);
    }

    /**
     * Modifier un utilisateur
     * 
     * @example modifierUtilisateurAction(1,0)
     *
     * @author armand.tevi@gmail.com
     *
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     *
     * @version 1
     * @param int idUser
     *
     * @var array $tab tableau des cles du tableau de flashbag 
     * @var array $rep     
     * @var string $nomAction
     * @var string $descAction
     * @var int $idProfilQuery
     * @var UserBundle\Services\LoginManager $loginManager
     * @var array $sessionData
     * @var string $locale
     * @var array $status
     * @var EntityMananger $em 
     * 
     * @var Utilisateur $user
     * @var string $routeName
     * @var array $routeParams
     * @var array $tabIds
     * @var string $details
     * @var array $results
     *            
     * @access  public
     *
     * @return Response
     */
    public function modifierUtilisateurAction(Request $request, $idUser, $type) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Modification d'un administrateur";
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
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit de modifier un administrateur");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }

        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository($this->userBundle . 'Utilisateur')->find($idUser);

        if ($user == NULL) {
            return $this->redirect($this->generateUrl('app_admin_user_home'));
        }

        $parametreManager = $this->get('parametre_manager');
        $longueurTel = $parametreManager->getValeurParametre(TypeParametre::LONGUEUR_NUM_TEL_INT);
        if ($longueurTel == NULL) {
            $longueurTel = 8;
        }



        //var_dump($user->getProfil()) ; exit ;

        if($type == 1){
            $form = $this->createForm(ModifierUserParentType::class, $user);
        }else{
            $form = $this->createForm(ModifierUserType::class, $user);
        }
        $profil = $user->getProfil();
        $idProfil = $profil->getId();

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);

            if ($form->isValid()) {
                $parametreManager = $this->get('parametre_manager');
//                $minLengthPassword = $parametreManager->getValeurParametre(TypeParametre::LONGUEUR_MIN_PASSWORD_INT);
//                if ($minLengthPassword == NULL) {
//                    $minLengthPassword = 5;
//                }

                /*
                 * Les mots de passe doivent être identiques 
                 */
//                    $user->setPassword(md5($user->getPassword()));
//                    $user->setCPassword($user->getPassword());
                    $criteria = array('nom' => $user->getNom(), 'prenoms' => $user->getPrenoms());
                    $oldUser = $em->getRepository($this->userBundle . 'Utilisateur')->findOneBy($criteria);
                    /*
                     * Aucun user ne possede le mm non et ls mm prénoms
                     */
                    if (($oldUser == NULL) || ($oldUser->getId() == $user->getId())) {
                        $oldUserByEmail = $em->getRepository($this->userBundle . 'Utilisateur')->findOneByEmail($user->getEmail());
                        /*
                         * Aucun user ne possede l'adresse email renseigne
                         */
                        if (($oldUserByEmail == NULL) || ($oldUserByEmail->getId() == $user->getId())) {
//                            $em->persist($user);
                            $user->setDateModification(new \DateTime());
                            $em->flush();
                            $this->get('session')->getFlashBag()->add('user.modifier.success', 'Modifications effectuées avec succès');
                            return $this->redirect($this->generateUrl('app_admin_users', array('idProfil' => $idProfil)));
                        } else {
                            $this->get('session')->getFlashBag()->add('user.modifier.error', "L'adresse email (" . $user->getEmail() . ") existe déjà");
                        }
                    } else {
                        /*
                         * Utilisateur avec mm nom et prenoms existe deja et est supprime
                         */
                        if ($oldUser->getEtat() == TypeEtat::SUPPRIME) {
                            $oldUserByEmail = $em->getRepository($this->userBundle . 'Utilisateur')->findOneByEmail($user->getEmail());
                            /*
                             * Aucun user ne possede l'adresse email renseigne
                             */
                            if (($oldUserByEmail == NULL) || ($oldUserByEmail->getId() == $user->getId())) {
//                                $em->persist($user);
                                $user->setDateModification(new \DateTime());
                                $em->flush();
                                $this->get('session')->getFlashBag()->add('user.modifier.success', 'Modifications effectuées avec succès');
                                return $this->redirect($this->generateUrl('app_admin_users', array('idProfil' => $idProfil)));
                            } else {
                                $this->get('session')->getFlashBag()->add('user.modifier.error', "L'adresse email (" . $user->getEmail() . ") existe déjà");
                            }
                        } else {
                            /*
                             * Utilisateur avec mm nom et prenoms existe deja et est non supprime
                             */
                            $this->get('session')->getFlashBag()->add('user.modifier.already.exist', "L'utilisateur " . $user->getNom() . " " . $user->getPrenoms() . " existe déjà");
                        }
                    }
                
            } else {
                $this->get('session')->getFlashBag()->add('user.modifier.error', 'Formulaire invalide');
            }
        }


        $this->data['formuView'] = $form->createView();
        $this->data['user'] = $user;

        $this->data['profil'] = $profil;
        $this->data['idProfil'] = $idProfil;
        $this->data['idUser'] = $idUser;
        $this->data['locale'] = $locale;
        $this->data['type'] = $type;

        return $this->render($this->userBundle . 'User:modifierUser.html.twig', $this->data, $this->response);
    }

    /**
     * Affiche les informations de l'utilisateur connectÃƒÂ©
     * 
     * @example detailsConnexionAction(1,0)
     *
     * @author armand.tevi@gmail.com
     *
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     *
     * @version 1
     * 
     * @param int $idConnexion
     * @param int isUser
     *
     * @var array $tab tableau des cles du tableau de flashbag 
     * @var array $rep     
     * @var string $nomAction
     * @var string $descAction
     * @var int $idProfilQuery
     * @var UserBundle\Services\LoginManager $loginManager
     * @var array $sessionData
     * @var string $locale
     * @var array $status
     * @var EntityMananger $em 
     * 
     * @var Utilisateur $user
     * @var string $routeName
     * @var array $routeParams
     * @var array $tabIds
     * @var string $details
     * @var array $results
     *            
     * @access  public
     *
     * @return  typeAffiche les infos du connecte
     */
    public function monProfilAction(Request $request, $afficherForm, $id) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage des infos de l'administrateur connecté";
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
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit de modifier vos infos");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }


        $em = $this->getDoctrine()->getManager();
        /*
         * On recupère le profil si un est défini
         */
        if ($id == 0) {
            $id = $sessionData['id'];
        }
        $user = $em->getRepository($this->userBundle . 'Utilisateur')->find($id);

        if ($user == NULL) {
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }

        $parametreManager = $this->get('parametre_manager');
        $longueurTel = $parametreManager->getValeurParametre(TypeParametre::LONGUEUR_NUM_TEL_INT);
        if ($longueurTel == NULL) {
            $longueurTel = 8;
        }

//        $facturier = $user->getFacturier();

        $oldProfil = $user->getProfil();
        $form = $this->createForm( ModifierUserType::class, $user);


        $openForm = (( (int) $afficherForm) == 1) ? TRUE : FALSE;
        if ($request->isMethod('POST')) {
            $user->setProfil($oldProfil);
            $form->handleRequest($request);
            $openForm = TRUE;

//            

            if ($form->isValid()) {
                $parametreManager = $this->get('parametre_manager');
//                $minLengthPassword = $parametreManager->getValeurParametre(TypeParametre::LONGUEUR_MIN_PASSWORD_INT);
//                if ($minLengthPassword == NULL) {
//                    $minLengthPassword = 5;
//                }
//                $user->setPassword(md5($user->getPassword()));
//                $user->setCPassword($user->getPassword());
                $criteria = array('nom' => $user->getNom(), 'prenoms' => $user->getPrenoms());
                $oldUser = $em->getRepository($this->userBundle . 'Utilisateur')->findOneBy($criteria);
                /*
                 * Aucun user ne possede le mm non et ls mm prénoms
                 */
                if ($oldUser == NULL) {
                    $oldUserByEmail = $em->getRepository($this->userBundle . 'Utilisateur')->findOneByEmail($user->getEmail());
                    /*
                     * Aucun user ne possede l'adresse email renseigne
                     */
                    if ($oldUserByEmail == NULL) {
//                                $em->persist($user);
                        $user->setDateModification(new \DateTime());
                        $em->flush();
                        $openForm = FALSE;
                        $this->get('session')->getFlashBag()->add('user.modifier.success', 'Modifications effectuées avec succès');
                        return $this->redirect($this->generateUrl('app_admin_user_mon_profil'));
                    } else {
                        /*
                         * Il s'agit du mm utilisateur
                         */
                        if ($oldUserByEmail->getId() == $user->getId()) {
                            $user->setDateModification(new \DateTime());
                            $em->flush();
                            $openForm = FALSE;
                            $this->get('session')->getFlashBag()->add('user.modifier.success', 'Modifications effectuées avec succès');
                            return $this->redirect($this->generateUrl('app_admin_user_mon_profil'));
                        } else {
                            $this->get('session')->getFlashBag()->add('user.modifier.error', "L'adresse email (" . $user->getEmail() . ") existe déjà");
                        }
                    }
                } else {
                    /*
                     * Utilisateur avec mm nom et prenoms existe deja et est supprime
                     */
                    if ($oldUser->getEtat() == TypeEtat::SUPPRIME) {
                        $oldUserByEmail = $em->getRepository($this->userBundle . 'Utilisateur')->findOneByEmail($user->getEmail());
                        /*
                         * Aucun user ne possede l'adresse email renseigne
                         */
                        if ($oldUserByEmail == NULL) {
//                                    $em->persist($user);
                            $em->flush();
                            $openForm = FALSE;
                            $this->get('session')->getFlashBag()->add('user.modifier.success', 'Modifications effectuées avec succès');
                            return $this->redirect($this->generateUrl('app_admin_user_mon_profil'));
                        } else {
                            /*
                             * Il s'agit du mm utilisateur
                             */
                            if ($oldUserByEmail->getId() == $user->getId()) {
                                $em->flush();
                                $openForm = FALSE;
                                $this->get('session')->getFlashBag()->add('user.modifier.success', 'Modifications effectuées avec succès');
                                return $this->redirect($this->generateUrl('app_admin_user_mon_profil'));
                            } else {
                                $this->get('session')->getFlashBag()->add('user.modifier.error', "L'adresse email (" . $user->getEmail() . ") existe déjà");
                            }
                        }
                    } else {
                        /*
                         * Utilisateur avec mm nom et prenoms existe deja et est non supprime
                         */
                        if ($oldUser->getId() == $user->getId()) {
                            /*
                             * Il sagit du mm utilisateur
                             */
                            $oldUserByEmail = $em->getRepository($this->userBundle . 'Utilisateur')->findOneByEmail($user->getEmail());
                            if (($oldUserByEmail == NULL) || ($oldUserByEmail->getId() == $user->getId())) {
                                $em->flush();
                                $openForm = FALSE;
                                $this->get('session')->getFlashBag()->add('user.modifier.success', 'Modifications effectuées avec succès');
                                return $this->redirect($this->generateUrl('app_admin_user_mon_profil'));
                            }
                            $this->get('session')->getFlashBag()->add('profil.modifier.already.exist', "L'utilisateur " . $user->getNom() . " " . $user->getPrenoms() . "existe déjà");
                        } else {
                            $this->get('session')->getFlashBag()->add('profil.modifier.already.exist', "L'utilisateur " . $user->getNom() . " " . $user->getPrenoms() . "existe déjà");
                        }
                    }
                }
            } else {
                $this->get('session')->getFlashBag()->add('user.modifier.error', 'Formulaire invalide');
            }
        }

        $this->data['formuView'] = $form->createView();
        $this->data['user'] = $user;
        $this->data['profil'] = $oldProfil;
//        $this->data['idProfil'] = $idProfil;
        $this->data['idUser'] = $sessionData['id'];
        $this->data['locale'] = $locale;
        $this->data['openForm'] = $openForm;

        return $this->render($this->userBundle . 'User:monProfilUser.html.twig', $this->data, $this->response);
    }

    /**
     * Modification du mot de passe par l'utilisateur connectÃƒÂ© 
     * 
     * @example modifierUtilisateurAction(1,0)
     *
     * @author armand.tevi@gmail.com
     *
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     *
     * @version 1
     * @param int idUser
     *
     * @var array $rep     
     * @var string $nomAction
     * @var string $descAction
     * @var int $idProfilQuery
     * @var UserBundle\Services\LoginManager $loginManager
     * @var array $sessionData
     * @var string $locale
     * @var array $status
     * @var EntityMananger $em 
     * 
     * @var Utilisateur $user
     * @var string $routeName
     * @var array $routeParams
     * @var string $old Ancien mot de passe
     * @var string $pass1 nouveau mot de passe
     * @var string $pass2 confirmation du nouveau mot de passe
     * @var array $results
     *            
     * @access  public
     *
     * @return Response
     */
    public function modifierMyPasswordUtilisateurAction(Request $request) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Modification du mot de passe par l'administrateur connecté ";
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
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit de modifier votre mot de passe");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }

        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository($this->userBundle . 'Utilisateur')->find($sessionData['id']);
        if ($user == NULL) {
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }

        $parametreManager = $this->get('parametre_manager');
        $minLengthPassword = $parametreManager->getValeurParametre(TypeParametre::LONGUEUR_MIN_PASSWORD_INT);
        if ($minLengthPassword == NULL) {
            $minLengthPassword = 5;
        }


        $pass1 = '';
        $pass2 = '';
        if ($request->isMethod('POST')) {
            $pass1 = $request->request->get('pass1');
            $pass2 = $request->request->get('pass2');
            $old = $request->request->get('old');
            if (($pass1 == NULL) || ($pass2 == NULL) || ($old == NULL) || (empty($old)) || (empty($pass1)) || (empty($pass2))) {
                $this->get('session')->getFlashBag()->add('user.modifier.error', 'Vous devez remplire tous les champs');
            } else {
                if (md5($old) != $user->getPassword()) {
                    $this->get('session')->getFlashBag()->add('user.modifier.error', 'Ancient mot de passe incorrect');
                } else {
                    if (strlen($pass1) < $minLengthPassword) {
                        $this->get('session')->getFlashBag()->add('user.modifier.error', 'Les mots de passe doivent contenir au moins ' . $minLengthPassword . ' caractères');
                    } else {
                        if ($pass1 != $pass2) {
                            $this->get('session')->getFlashBag()->add('user.modifier.error', 'Les mots de passe doivent être identiques');
                        } else {
                            $user->setPassword(md5($pass1));
                            $user->setCPassword(md5($pass1));
                            $em->flush();
                            $this->get('session')->getFlashBag()->add('user.modifier.password.success', 'Mot de passe modifié avec succès');
                           if( $sessionData['idProfil']==5){
                            return $this->redirect($this->generateUrl('app_admin_eleve_infos'));
                           }else{
                             return $this->redirect($this->generateUrl('app_admin_user_mon_profil'));  
                           }
                        }
                    }
                }
            }
        }

        $this->data['pattern'] = ".{" . $minLengthPassword . ",}";
        $this->data['pass1'] = $pass1;
        $this->data['pass2'] = $pass2;
        $this->data['minLengthPassword'] = $minLengthPassword;

        $this->data['user'] = $user;
        $this->data['profil'] = $user->getProfil();
        $this->data['idUser'] = $sessionData['id'];
        $this->data['locale'] = $locale;

        return $this->render($this->userBundle . 'User:modifierPasswordUser.html.twig', $this->data, $this->response);
    }

    public function getUserAction(Request $request) {
        $rep = array('etat' => FALSE, 'msg' => 'Erreur survenue lors du traitement');
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "recupération des informations concernant les utilisateurs ";
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
            $rep['msg'] = "Vous n'avez pas le droit d'activer, de supprimer ou de désactiver un matiere";
            return new Response(json_encode($rep));
        }
        $idmat = $request->get('idmat');
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository($this->userBundle . 'Utilisateur')->getAllUserMatiere($idmat);
        //var_dump($user);exit;
        return new Response(json_encode(array('donnee' => $user)));
    }
    
    
    
    
    public function getMatiereUserAction(Request $request) {
        
        
        $rep = array('etat' => FALSE, 'msg' => 'Erreur survenue lors du traitement');
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "recupération des informations concernant les matières d'un enseignant ";
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
            $rep['msg'] = "Vous n'avez pas le droit d'activer, de supprimer ou de désactiver un matiere";
            return new Response(json_encode($rep));
        }
        $idens = $request->get('idens');
        $em = $this->getDoctrine()->getManager();
        $matiere = $em->getRepository($this->userBundle . 'Utilisateur')->getAllMatiereDoUser($idens);
        //var_dump($user);exit;
        return new Response(json_encode(array('donnee' => $matiere)));
    }

}
