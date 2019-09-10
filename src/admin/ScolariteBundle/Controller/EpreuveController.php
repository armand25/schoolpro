<?php

namespace admin\ScolariteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Response;
use \admin\UserBundle\Entity\Module;
use \admin\ScolariteBundle\Entity\Epreuve;
use \admin\UserBundle\Services\LoginManager;
use \Symfony\Component\HttpFoundation\Request;

class EpreuveController extends Controller {

    
use \admin\UserBundle\ControllerModel\paramUtilTrait;
    
    
    public function __construct() {
        $this->response = new Response();
        $this->response->headers->addCacheControlDirective('no-cache', true);
        $this->response->headers->addCacheControlDirective('max-age', 0);
        $this->response->headers->addCacheControlDirective('must-revalidate', true);
        $this->response->headers->addCacheControlDirective('no-store', true);
        $this->moduleTitre = Module::MOD_EPREUVE;
        $this->moduleDesc = Module::MOD_EPREUVE_DESC;
        $this->logMessage = " [ EpreuveController ] ";
        $this->description = "Controlleur qui gère les épreuves";
    }

    /**
     *  Afficle la liste des epreuves
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
    public function listeEpreuveAction(Request $request,$idClasse) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage de la liste des epreuves";
       $locale ="fr";
        $valRetour = $this->gestionDroitUtil($request, $nomAction, $descAction,$this->moduleTitre,$this->moduleDesc);
        if($valRetour==1){
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }elseif($valRetour==2){
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        } 
        $sessionData = $this->infosConnecte($request);
        $em = $this->getDoctrine()->getManager();
        //Recuperation des informations de la personne connectée
        
        $idannee=$sessionData['idannee'];
        if($sessionData['idProfil']==5){
            $idEleve = $sessionData['idEleve'];
            $classe = $em->getRepository($this->scolariteBundle . 'Eleve')->getClasseEnCours($idEleve, $idannee);
            $idClasse = $classe[0]->getId();
        }
        //var_dump($idClasse);exit;
        $epreuves = $em->getRepository($this->scolariteBundle . 'Epreuve')->getAllEpreuve($idClasse);
        
        $this->data['epreuves'] = $epreuves;
        $this->data['idClasse'] = $idClasse;
        $this->data['locale'] = $locale;
        return $this->render($this->scolariteBundle . 'Epreuve:listeEpreuve.html.twig', $this->data, $this->response);
    }


    /**
     *  Formulaire d'ajout de epreuve
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
    public function ajoutEpreuveAction(Request $request,$idClasse) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Ajout d'un nouveau epreuve";
       $locale ="fr";
        $valRetour = $this->gestionDroitUtil($request, $nomAction, $descAction,$this->moduleTitre,$this->moduleDesc);
        if($valRetour==1){
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }elseif($valRetour==2){
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        } 
        $sessionData = $this->infosConnecte($request);
        $em = $this->getDoctrine()->getManager();
        $idannee =  $sessionData['idannee'];
        $idmatiere = $request->get('id-matiere');
        $epreuve = new Epreuve();
        $epreuve->getExercices()->add(new \admin\ScolariteBundle\Entity\Exercice());
        $form = $this->createForm('\admin\ScolariteBundle\Form\EpreuveType', $epreuve);
        $enseignants = $em->getRepository($this->userBundle . 'Utilisateur')->find($sessionData['id']);  
        $listeMatiere = $em->getRepository($this->userBundle . 'Utilisateur')->getAllMatiereDoUser($sessionData['id']);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            
            $objetClasse = $em->getRepository($this->scolariteBundle . 'Classe')->find($idClasse);
            $objetAnneeScolaire = $em->getRepository($this->scolariteBundle . 'AnneeScolaire')->find($idannee);
            $objetMatiere = $em->getRepository($this->scolariteBundle . 'Matiere')->find($idmatiere);
            if ($form->isValid()) {
                    $siToutNiveau = $request->get('siToutNiveau');
                    if($siToutNiveau==1){
                        $listeClasse = $em->getRepository($this->scolariteBundle . 'Classe')->findBy(array('niveau'=>$objetClasse->getNiveau(), 'filiere'=>$objetClasse->getFiliere()));
                        foreach ($listeClasse as $uneClasse){
                             $epreuve->addClasse($uneClasse);
                             $uneClasse->addEpreuve($epreuve);
                        }
                    }else{
                        $epreuve->addClasse($objetClasse);
                    }
                    $epreuve->setMatiere($objetMatiere);
                    $epreuve->setUtilisateur($enseignants);
                    
                    $epreuve->setAnneescolaire($objetAnneeScolaire);
                    $exerces = $epreuve->getExercices();
                   
                    $em->persist($epreuve);
                    $em->flush();
                    foreach ($exerces as $unexercice){
                       $unexercice->setEpreuve($epreuve);
                       $em->persist($unexercice);
                   }
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('epreuve.ajout.success', 'Ajout effectue avec succès');
                    return $this->redirect($this->generateUrl('app_admin_epreuves',array('idClasse'=>$idClasse)));
            } else {
                $this->get('session')->getFlashBag()->add('epreuve.ajout.error', 'Formulaire invalide');
            }
        }
        $this->data['formuView'] = $form->createView();
        $this->data['enseignants'] = $enseignants;
        $this->data['listeMatiere'] = $listeMatiere;
        $this->data['epreuve'] = $epreuve;
        $this->data['idClasse'] = $idClasse;
        $this->data['locale'] = $locale;

        return $this->render($this->scolariteBundle . 'Epreuve:ajouterEpreuve.html.twig', $this->data, $this->response);
    }

    /*
     * Modifie un epreuve
     * @param type $idEpreuve
     * @return type
     */

    public function modifierEpreuveAction(Request $request, $idEpreuve,$idClasse) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Modification d'un epreuve";
        $locale ="fr";
        $valRetour = $this->gestionDroitUtil($request, $nomAction, $descAction,$this->moduleTitre,$this->moduleDesc);
        if($valRetour==1){
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }elseif($valRetour==2){
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        } 
       
        $em = $this->getDoctrine()->getManager();
        $epreuve = $em->getRepository($this->scolariteBundle . 'Epreuve')->find($idEpreuve);
        if ($epreuve == NULL) {
            return $this->redirect($this->generateUrl('app_admin_epreuves',array('idClasse'=>$idClasse)));
        }
        $form = $this->createForm('\admin\ScolariteBundle\Form\EpreuveType', $epreuve);

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);
            if ($form->isValid()) {
                $criteria = array('libelleEpreuve' => $epreuve->getLibelleEpreuve());
                $oldEpreuve = $em->getRepository($this->scolariteBundle . 'Epreuve')->findOneBy($criteria);
                if ($oldEpreuve == NULL) {
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('epreuve.modifier.success', 'Modifications effectuées avec succès');
                    return $this->redirect($this->generateUrl('app_admin_epreuves',array('idClasse'=>$idClasse)));
                } else {

                    /*
                     * S'il s'agit du mm epreuve ou k le epreuve est supprimé on effecute la modification
                     */
                    if (($oldEpreuve->getId() == $epreuve->getId()) || ($oldEpreuve->getEtat() == \admin\UserBundle\Types\TypeEtat::SUPPRIME)) {
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('epreuve.modifier.success', 'Modifications effectuées avec succès');
                        return $this->redirect($this->generateUrl('app_admin_epreuves',array('idClasse'=>$idClasse)));
                    }
                    $this->get('session')->getFlashBag()->add('epreuve.modifier.already.exist', 'Le epreuve ' . $epreuve->getNom() . ' existe déjà');
                }
            } else {
                $this->get('session')->getFlashBag()->add('epreuve.modifier.error', 'Formulaire invalide');
            }
        }

        $this->data['formuView'] = $form->createView();
        $this->data['epreuve'] = $epreuve;
        $this->data['idClasse'] = $idClasse;
        $this->data['locale'] = $locale;
        $this->data['idEpreuve'] = $idEpreuve;

        return $this->render($this->scolariteBundle . 'Epreuve:modifierEpreuve.html.twig', $this->data, $this->response);
    }

    /*
     * Activation, suppression, désactivation de epreuve
     * @return Response
     */

    public function changerEtatEpreuveAction(Request $request) {
        $rep = array('etat' => FALSE, 'msg' => 'Erreur survenue lors du traitement');
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Activation, suppression, désactivation de epreuve";
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idEpreuve'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $rep['msg'] = "Vous n'avez pas le droit d'activer, de supprimer ou de désactiver un epreuve";
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
                $epreuve = $em->getRepository($this->scolariteBundle . 'Epreuve')->find((int) $idS);
                if ($epreuve != NULL) {
                    $epreuve->setEtat($etat);
                    $em->flush();
                    $oneOk = TRUE;
                }
            }
            if ($oneOk) {
                $this->get('session')->getFlashBag()->add('epreuve.modifier.success', 'Opération éffectuée avec succès');
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

    public function infosEpreuveAction(Request $request, $idEpreuve) {
        /**
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /**
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage des informations d'un utilisateur";
             $locale ="fr";
        $valRetour = $this->gestionDroitUtil($request, $nomAction, $descAction,$this->moduleTitre,$this->moduleDesc);
        if($valRetour==1){
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }elseif($valRetour==2){
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        } 
        $sessionData = $this->infosConnecte($request);
        $em = $this->getDoctrine()->getManager();
        

        /*
         * On recupère l'année en cours
         */
        $idannee =  $sessionData['idannee'];
        $epreuve = $em->getRepository($this->scolariteBundle . 'Epreuve')->find($idEpreuve);
      //  $exercice = $em->getRepository($this->scolariteBundle . 'Exercice')->getExerciceEnCours($idExercice, $idannee);
        
        
        if ($epreuve == NULL) {
            return $this->redirect($this->generateUrl('app_admin_epreuves'));
        }

       // $form = $this->createForm('admin\ScolariteBundle\Form\ModifierExerciceType', $exercice);

        //Recuperation de la exercice actuel 
        
        /*
         * Le mot de passe ne doit pas être vide
         */
        $this->data['epreuve'] = $epreuve;
//        $this->data['longueurCompte'] = $longueurCompte;
        $this->data['idEpreuve'] = $idEpreuve;
        $this->data['locale'] = $locale;
        $this->data['isAdmin'] = $sessionData['isUser'];
        return $this->render($this->scolariteBundle . 'Epreuve:afficherinfosEpreuve.html.twig', $this->data, $this->response);
    }   
                
    
    
}
