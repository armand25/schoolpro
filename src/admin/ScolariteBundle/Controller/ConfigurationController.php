<?php

namespace admin\ScolariteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Response;
use \admin\UserBundle\Entity\Module;
use \admin\ScolariteBundle\Entity\Configuration;
use admin\ScolariteBundle\Entity\EstEnseigne;
use admin\ScolariteBundle\Entity\Ecolage;
use \admin\UserBundle\Services\LoginManager;
use \Symfony\Component\HttpFoundation\Request;
use admin\ScolariteBundle\Entity\Concerner;

class ConfigurationController extends Controller {

    use \admin\UserBundle\ControllerModel\paramUtilTrait;

    public function __construct() {
        $this->response = new Response();
        $this->response->headers->addCacheControlDirective('no-cache', true);
        $this->response->headers->addCacheControlDirective('max-age', 0);
        $this->response->headers->addCacheControlDirective('must-revalidate', true);
        $this->response->headers->addCacheControlDirective('no-store', true);
        $this->logMessage = " [ ConfigurationController ] ";
        $this->description = "Controlleur qui gère les utilisateurs";
    }

    /**
     *  Afficle la liste des classes
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
    public function listeConfigurationAction(Request $request) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage de la liste des classes";
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
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des classes");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }



        $em = $this->getDoctrine()->getManager();
        $classes = $em->getRepository($this->scolariteBundle . 'Configuration')->getAllConfiguration();

        $this->data['classes'] = $classes;
        $this->data['locale'] = $locale;
        return $this->render($this->scolariteBundle . 'Configuration:listeConfiguration.html.twig', $this->data, $this->response);
    }

    /**
     *  Formulaire d'ajout de le configuration
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
    public function ajoutConfigurationAction(Request $request) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Modification des configurations";
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
        $em = $this->getDoctrine()->getManager();

        /*
         * Gestion des droits
         */
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'ajouter un classe");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }
        // $classe = new Configuration();
        //recuperation de la liste des découpages
        $listeDecoupage = $em->getRepository($this->scolariteBundle . 'Decoupage')->getAllActifDecoupage();

        // Liste des niveaux
        $listeNiveau = $em->getRepository($this->scolariteBundle . 'Niveau')->getAllActifNiveau();

        // Liste des niveaux
        $listeEns = $em->getRepository($this->scolariteBundle . 'Enseignement')->getAllActifEnseignement();

        $listeFiliere = $em->getRepository($this->scolariteBundle . 'Filiere')->getAllActifFiliere();

        // Liste des types matieres
        $listeTypeMatiere = $em->getRepository($this->scolariteBundle . 'TypeMatiere')->getAllActifTypeMatiere();
        // Liste des types decoupages
        $listeTypeDecoupage = $em->getRepository($this->scolariteBundle . 'TypeDecoupage')->getAllActifTypeDecoupage();

        //Liste  des matieres
        $listeMatiere = $em->getRepository($this->scolariteBundle . 'Matiere')->getAllActifMatiere();

        //Liste  des matieres
        $listeAnneeScolaire = $em->getRepository($this->scolariteBundle . 'AnneeScolaire')->getAllAnneeScolaire();
        //recuperation de l'annee scolaire en cours
        $anneeScolaire = $em->getRepository($this->scolariteBundle . 'AnneeScolaire')->find(1);
        $anneeScolaireActive = $em->getRepository($this->scolariteBundle . 'AnneeScolaire')->findOneBy(array('etatAnneeScolaire'=>  \admin\UserBundle\Types\TypeEtat::ACTIF));

        //tableau pour contenir lliste de matiere par filiere
        $tableauDonneFiliereByMat = array();
        $filiereTabNiveau = array();
        
        foreach($listeFiliere as $unFiliere){
            $i = 0;
            foreach($listeNiveau as $unNiveau){
                 $objetEstEnseigne= $em->getRepository($this->scolariteBundle . 'EstEnseigne')->findBy(array('niveau'=>$unNiveau,'filiere'=>$unFiliere));
                if(count($objetEstEnseigne)!=0){
                  $tableauDonneFiliereByMat[$unFiliere->getId()][$unNiveau->getId()]= $objetEstEnseigne;
                  //NiveauByFiliere
                  $filiereTabNiveau[$unFiliere->getId()][$i] = $unNiveau;
                  $i++;
                }else{
                  $tableauDonneFiliereByMat[$unFiliere->getId()][$unNiveau->getId()]=NULL; 
                  //$filiereTabNiveau[$unFiliere->getId()] = NULL;
                }
            }    
        }


        $tabTypeDecoupConcerner = array();
        foreach ($listeEns as $unEns) {         
            $typeEncoursConcerner = $em->getRepository($this->scolariteBundle . 'Concerner')->findOneBy(array('enseignement' => $unEns, 'anneescolaire' => $anneeScolaire));
            if($typeEncoursConcerner != NULL){
                $tabTypeDecoupConcerner[$unEns->getId()]= $typeEncoursConcerner->getTypeDecoupage()->getId();
            }
            
        }      
        $tabTypeDecoupEcolage = array();
        foreach ($listeNiveau as $unNiveau) {         
            $typeEncoursEcolage = $em->getRepository($this->scolariteBundle . 'Ecolage')->findOneBy(array('niveau' => $unNiveau, 'anneescolaire' => $anneeScolaire));
            if($typeEncoursEcolage != NULL){
                $tabTypeDecoupEcolage[$unNiveau->getId()]['montant']= $typeEncoursEcolage->getMontantEcolage();
                $tabTypeDecoupEcolage[$unNiveau->getId()]['tranche']= $typeEncoursEcolage->getTrancheEcolage();
            }
            
        } 
        
        $tabTypeDecoupage = array();
         $decoupePar = array();
        foreach ($listeTypeDecoupage as $unTypeDecoupage) {         
            $decoupage= $em->getRepository($this->scolariteBundle . 'Decoupage')->findOneBy(array('typedecoupage' => $unTypeDecoupage, 'etatDecoupage' => \admin\UserBundle\Types\TypeEtat::ACTIF));
            if($decoupage != NULL){
                $tabTypeDecoupage[$unTypeDecoupage->getId()]= $decoupage->getId();
                
            }
            $decoupePar[$unTypeDecoupage->getId()]= $em->getRepository($this->scolariteBundle . 'Decoupage')->findBy(array('typedecoupage' => $unTypeDecoupage));
          
        }      
        //var_dump($tabTypeDecoupEcolage);exit;
        $locale = "";

        //$this->data['formuView'] = $form->createView();
        //$this->data['classe'] = $classe;
        $this->data['listeDecoupage'] = $listeDecoupage;
        $this->data['filiereTabNiveau'] = $filiereTabNiveau;
        $this->data['tabTypeDecoupage'] = $tabTypeDecoupage ;
        $this->data['tableauDonneFiliereByMat'] = $tableauDonneFiliereByMat ;
        $this->data['anneeScolaireActive'] = $anneeScolaireActive ;
        $this->data['decoupePar'] = $decoupePar ;
        $this->data['tabTypeDecoupConcerner'] = $tabTypeDecoupConcerner;
        $this->data['tabTypeDecoupEcolage'] = $tabTypeDecoupEcolage;
        
        $this->data['listeNiveau'] = $listeNiveau;
        $this->data['listeEns'] = $listeEns;
        $this->data['listeFiliere'] = $listeFiliere;
        $this->data['listeAnneeScolaire'] = $listeAnneeScolaire;
        $this->data['listeTypeMatiere'] = $listeTypeMatiere;
        $this->data['listeTypeDecoupage'] = $listeTypeDecoupage;
        $this->data['listeMatiere'] = $listeMatiere;
        $this->data['locale'] = $locale;

        return $this->render($this->scolariteBundle . 'Configuration:ajouterConfiguration.html.twig', $this->data, $this->response);
    }

    /*
     * Modifie un classe
     * @param type $idConfiguration
     * @return type
     */

    public function modifierConfigurationAction(Request $request, $idConfiguration) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Modification d'un classe";
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
//            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit de modifier un classe");
//            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
//        }

        $em = $this->getDoctrine()->getManager();
        $classe = $em->getRepository($this->scolariteBundle . 'Configuration')->find($idConfiguration);
        if ($classe == NULL) {
            return $this->redirect($this->generateUrl('app_admin_classes'));
        }
        $form = $this->createForm('\admin\ScolariteBundle\Form\ConfigurationType', $classe);

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);
            if ($form->isValid()) {
                $criteria = array('libelleConfiguration' => $classe->getLibelleConfiguration());
                $oldConfiguration = $em->getRepository($this->scolariteBundle . 'Configuration')->findOneBy($criteria);
                if ($oldConfiguration == NULL) {
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('classe.modifier.success', 'Modifications effectuées avec succès');
                    return $this->redirect($this->generateUrl('app_admin_classes'));
                } else {

                    /*
                     * S'il s'agit du mm classe ou k le classe est supprimé on effecute la modification
                     */
                    if (($oldConfiguration->getId() == $classe->getId()) || ($oldConfiguration->getEtat() == \admin\UserBundle\Types\TypeEtat::SUPPRIME)) {
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('classe.modifier.success', 'Modifications effectuées avec succès');
                        return $this->redirect($this->generateUrl('app_admin_classes'));
                    }
                    $this->get('session')->getFlashBag()->add('classe.modifier.already.exist', 'Le classe ' . $classe->getNom() . ' existe déjà');
                }
            } else {
                $this->get('session')->getFlashBag()->add('classe.modifier.error', 'Formulaire invalide');
            }
        }

        $this->data['formuView'] = $form->createView();
        $this->data['classe'] = $classe;
        $this->data['locale'] = $locale;
        $this->data['idConfiguration'] = $idConfiguration;

        return $this->render($this->scolariteBundle . 'Configuration:modifierConfiguration.html.twig', $this->data, $this->response);
    }

    /*
     * Activation, suppression, désactivation de classe
     * @return Response
     */

    public function changerEtatConfigurationAction(Request $request) {
        $rep = array('etat' => FALSE, 'msg' => 'Erreur survenue lors du traitement');
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Activation, suppression, désactivation de classe";
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
            $rep['msg'] = "Vous n'avez pas le droit d'activer, de supprimer ou de désactiver un classe";
            return new Response(json_encode($rep));
        }

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
                $classe = $em->getRepository($this->scolariteBundle . 'Configuration')->find((int) $idS);
                if ($classe != NULL) {
                    $classe->setEtat($etat);
                    $em->flush();
                    $oneOk = TRUE;
                }
            }
            if ($oneOk) {
                $this->get('session')->getFlashBag()->add('classe.modifier.success', 'Opération éffectuée avec succès');
                $rep['msg'] = '';
                $rep['etat'] = TRUE;
            }

            return new Response(json_encode($rep));
        }
        return new Response(json_encode($rep));
    }

    public function traiteConfigurationAction(Request $request) {

        $rep = array('etat' => FALSE, 'msg' => 'Erreur survenue lors du traitement');
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "traitement des configurations liéé au filiere ";
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
            $rep['msg'] = "Vous n'avez pas le droit de traiter des configurations liéé au filiere ";
            return new Response(json_encode($rep));
        }
       $em = $this->getDoctrine()->getManager();
       //recuperation de l'objet etablissement
       $objetEtablissement =  $em->getRepository($this->scolariteBundle . 'Etablissement')->find($sessionData['etablissement']);  
        
        
        
       $idfiliere = $request->get('idfiliere');
        $filiere = $em->getRepository($this->scolariteBundle . 'Filiere')->find($idfiliere);
        
        $donnee = $request->get('donnee');

        $lesFilieres = explode('|', $donnee);
        // var_dump($lesFilieres);exit;
        foreach ($lesFilieres as $unFiliere) {
            if ($unFiliere != "") {
                $info = explode('-', $unFiliere);
                $niveau = $em->getRepository($this->scolariteBundle . 'Niveau')->find($info[0]);
                $typeMatiere = $em->getRepository($this->scolariteBundle . 'TypeMatiere')->find($info[1]);
                $matiere = $em->getRepository($this->scolariteBundle . 'Matiere')->find($info[3]);
                
                $coefMatiere = $info[2];
                // $matiere = $em->getRepository($this->scolariteBundle . 'Matiere')->find($info[3]);
                $estEnseigne = new EstEnseigne();
                $estEnseigne->setMatiere($matiere);
                $estEnseigne->setNiveau($niveau);
                $estEnseigne->setEtablissement($objetEtablissement);
                $estEnseigne->setFiliere($filiere);
                $estEnseigne->setCoefficient($coefMatiere);
                $estEnseigne->setTypematiere($typeMatiere);
                $em->persist($estEnseigne);
                $em->flush();
            }
            $rep = array('etat' => TRUE, 'msg' => 'Opération effectuée avec succès ');
        }
        return new Response(json_encode(array('donnee' => $rep)));
    }

    public function traiteConfigEcolageAction(Request $request) {

        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Ajout d'un nouveau classe";
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
        $em = $this->getDoctrine()->getManager();

        /*
         * Gestion des droits
         */
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'ajouter un classe");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }
       //recuperation de l'objet etablissement
       $objetEtablissement =  $em->getRepository($this->scolariteBundle . 'Etablissement')->find($sessionData['etablissement']);
       
        $listeNiveau = $em->getRepository($this->scolariteBundle . 'Niveau')->getAllActifNiveau();
        
        $anneeScolaire = $em->getRepository($this->scolariteBundle . 'AnneeScolaire')->find(1);
        if ($request->isMethod('POST')) {
            // $form->handleRequest($request);
            // recuperation des donnnees concernant les traitements réalisés au niveau des config
            
            foreach ( $listeNiveau as $unNiveau ) {
                $mont = $request->get('tabMontantNiveau'.$unNiveau->getId());
                $tranche= $request->get('tabTrancheNiveau'.$unNiveau->getId());
                $montant= preg_replace('/\D/', '', $mont);
                $ecolage = new Ecolage();
                $unObjetNiveau = $em->getRepository($this->scolariteBundle . 'Niveau')->find((int)$unNiveau->getId());
                //Quand cela existe Fait la modification
                $testObjetEcolage = $em->getRepository($this->scolariteBundle . 'Ecolage')->findOneBy(array('niveau' => $unObjetNiveau, 'anneescolaire' => $anneeScolaire));
                if ($testObjetEcolage != NULL) {                    
                    $testObjetEcolage->setMontantEcolage($montant);
                    $testObjetEcolage->setTrancheEcolage($tranche);
                    $testObjetEcolage->setLibelleEcolage($montant);
                    $em->persist($testObjetEcolage);                    
                } else {           
                    $ecolage->setMontantEcolage($montant);
                    $ecolage->setTrancheEcolage($tranche);
                    $ecolage->setLibelleEcolage($montant);
                    $ecolage->setAnneeScolaire($anneeScolaire);
                    $ecolage->setEtablissement($objetEtablissement);
                    $ecolage->setNiveau($unNiveau);
                    $em->persist($ecolage);
                }
            }
            $em->flush();
        }
        return $this->redirect($this->generateUrl('app_admin_configuration_ajout'));
    }
    
    
    public function traiteConfigEnseignementAction(Request $request) {

        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Ajout d'un nouveau classe";
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
        $em = $this->getDoctrine()->getManager();

        /*
         * Gestion des droits
         */
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'ajouter un classe");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }
        
       //recuperation de l'objet etablissement
       $objetEtablissement =  $em->getRepository($this->scolariteBundle . 'Etablissement')->find($sessionData['etablissement']);
         // Liste des niveaux
        $listeEns = $em->getRepository($this->scolariteBundle . 'Enseignement')->getAllActifEnseignement();
        $anneeScolaire = $em->getRepository($this->scolariteBundle . 'AnneeScolaire')->find(1);
        if ($request->isMethod('POST')) {
            // $form->handleRequest($request);
            // recuperation des donnnees concernant les traitements réalisés au niveau des config
            foreach ($listeEns as $unEns) {
                $tabDecoupEnvoye = $request->get('tabNiveauDecoup');
                $concerner = new Concerner();
                $unObjetDecoupage = $em->getRepository($this->scolariteBundle . 'TypeDecoupage')->find((int) $tabDecoupEnvoye[$unEns->getId()]);
                //var_dump($unObjetDecoupage);exit;
                //Quand cela existe Fait la modification
                $testObjetConcerner = $em->getRepository($this->scolariteBundle . 'Concerner')->findOneBy(array('enseignement' => $unEns, 'anneescolaire' => $anneeScolaire));
                if ($testObjetConcerner != NULL) {
                    $testObjetConcerner->setTypeDecoupage($unObjetDecoupage);
                    $em->persist($testObjetConcerner);
                } else {
                    $concerner->setTypeDecoupage($unObjetDecoupage);
                    $concerner->setAnneeScolaire($anneeScolaire);
                    $concerner->setEtablissement($objetEtablissement);
                    $concerner->setEnseignement($unEns);
                    $em->persist($concerner);
                }
            }
            $em->flush();
        }
        return $this->redirect($this->generateUrl('app_admin_configuration_ajout'));
    }

    
    
    public function traiteConfigDecoupageAction(Request $request) {

        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Ajout d'un nouveau classe";
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
        $em = $this->getDoctrine()->getManager();

        /*
         * Gestion des droits
         */
//        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
//            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
//            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'ajouter un classe");
//            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
//        }
        
        $listeTypeDecoupage = $em->getRepository($this->scolariteBundle . 'TypeDecoupage')->getAllActifTypeDecoupage();
        
        if ($request->isMethod('POST')) {
            // $form->handleRequest($request);
            // recuperation des donnnees concernant les traitements réalisés au niveau des config
            
            // mise a jour de tout 
            
            
            foreach ( $listeTypeDecoupage as $unTypeDecoupage ) {
                $idDecoupage = $request->get('tabTypeDecoup'.$unTypeDecoupage->getId());
                 $this->updateAllDecoupage($em,$unTypeDecoupage->getId());
                $unObjetDecoupage = $em->getRepository($this->scolariteBundle . 'Decoupage')->find((int)$idDecoupage);
                //Quand cela existe Fait la modification
               
                if ($unObjetDecoupage != NULL) {                    
                    $unObjetDecoupage->setEtatDecoupage(\admin\UserBundle\Types\TypeEtat::ACTIF);
                    $em->persist($unObjetDecoupage);                    
                }
            $em->flush();
           }
        }
        return $this->redirect($this->generateUrl('app_admin_configuration_ajout'));
    }
    public function traiteConfigAnneeAction(Request $request) {

        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Ajout d'un nouveau classe";
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
        $em = $this->getDoctrine()->getManager();

        /*
         * Gestion des droits
         */
//        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
//            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
//            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'ajouter un classe");
//            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
//        }
        
       
        
        if ($request->isMethod('POST')) {
            // $form->handleRequest($request);
            // recuperation des donnnees concernant les traitements réalisés au niveau des config
            
            // mise a jour de tout 
            
            
            
                $idAnnee = $request->get('idAnnee');
                 $this->updateAllAnneee($em,$idAnnee);
                $unObjetAnnee = $em->getRepository($this->scolariteBundle . 'AnneeScolaire')->find((int)$idAnnee);
                //Quand cela existe Fait la modification
               
                if ($unObjetAnnee != NULL) {                    
                    $unObjetAnnee->setEtatDecoupage(\admin\UserBundle\Types\TypeEtat::ACTIF);
                    $em->persist($unObjetAnnee);                    
                }
            $em->flush();
           
        }
        return $this->redirect($this->generateUrl('app_admin_configuration_ajout'));
    }

}
