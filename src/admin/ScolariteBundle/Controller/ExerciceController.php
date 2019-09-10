<?php

namespace admin\ScolariteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Response;
use \admin\UserBundle\Entity\Module;
use \admin\ScolariteBundle\Entity\Exercice;
use \admin\UserBundle\Services\LoginManager;
use \Symfony\Component\HttpFoundation\Request;

class ExerciceController extends Controller {

    
use \admin\UserBundle\ControllerModel\paramUtilTrait;
    
    
    public function __construct() {
        $this->response = new Response();
        $this->response->headers->addCacheControlDirective('no-cache', true);
        $this->response->headers->addCacheControlDirective('max-age', 0);
        $this->response->headers->addCacheControlDirective('must-revalidate', true);
        $this->response->headers->addCacheControlDirective('no-store', true);
        $this->moduleTitre = Module::MOD_EPREUVE;
        $this->moduleDesc = Module::MOD_EPREUVE_DESC;
        $this->logMessage = " [ ExerciceController ] ";
        $this->description = "Controlleur qui gère les exercices";
    }

    /**
     *  Afficle la liste des exercices
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
    public function listeExerciceAction(Request $request,$idEpreuve) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage de la liste des exercices";
             $locale ="fr";
        $valRetour = $this->gestionDroitUtil($request, $nomAction, $descAction,$this->moduleTitre,$this->moduleDesc);
        if($valRetour==1){
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }elseif($valRetour==2){
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        } 

        $em = $this->getDoctrine()->getManager();
        $exercices = $em->getRepository($this->scolariteBundle . 'Exercice')->getAllExercice($idEpreuve);

        $this->data['exercices'] = $exercices;
        $this->data['idEpreuve'] = $idEpreuve;
        $this->data['locale'] = $locale;
        return $this->render($this->scolariteBundle . 'Exercice:listeExercice.html.twig', $this->data, $this->response);
    }


    /**
     *  Formulaire d'ajout de exercice
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
    public function ajoutExerciceAction(Request $request,$idEpreuve) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Ajout d'un nouveau exercice";
        $locale ="fr";
        $valRetour = $this->gestionDroitUtil($request, $nomAction, $descAction,$this->moduleTitre,$this->moduleDesc);
        if($valRetour==1){
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }elseif($valRetour==2){
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        } 
        
        $exercice = new Exercice();
        $form = $this->createForm('\admin\ScolariteBundle\Form\ExerciceType', $exercice);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $objetEpreuve = $em->getRepository($this->scolariteBundle . 'Epreuve')->find($idEpreuve);
            if ($form->isValid()) {
              
                    //$exercice->setDateInitialExercice(new \DateTime());
                    
                    $exercice->setEpreuve($objetEpreuve);
                    $em->persist($exercice);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('exercice.ajout.success', 'Ajout effectue avec succès');
                    return $this->redirect($this->generateUrl('app_admin_exercices',array('idEpreuve'=>$idEpreuve)));
             
            } else {
                $this->get('session')->getFlashBag()->add('exercice.ajout.error', 'Formulaire invalide');
            }
        }

        $this->data['formuView'] = $form->createView();
        $this->data['exercice'] = $exercice;
        $this->data['idEpreuve'] = $idEpreuve;
        $this->data['locale'] = $locale;

        return $this->render($this->scolariteBundle . 'Exercice:ajouterExercice.html.twig', $this->data, $this->response);
    }

    /*
     * Modifie un exercice
     * @param type $idExercice
     * @return type
     */

    public function modifierExerciceAction(Request $request, $idExercice,$idEpreuve) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Modification d'un exercice";
             $locale ="fr";
        $valRetour = $this->gestionDroitUtil($request, $nomAction, $descAction,$this->moduleTitre,$this->moduleDesc);
        if($valRetour==1){
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }elseif($valRetour==2){
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        } 

        $em = $this->getDoctrine()->getManager();
        $exercice = $em->getRepository($this->scolariteBundle . 'Exercice')->find($idExercice);
        if ($exercice == NULL) {
            return $this->redirect($this->generateUrl('app_admin_exercices',array('idEpreuve'=>$idEpreuve)));
        }
        $form = $this->createForm('\admin\ScolariteBundle\Form\ExerciceType', $exercice);

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);
            if ($form->isValid()) {
                $criteria = array('libelleExercice' => $exercice->getLibelleExercice());
                $oldExercice = $em->getRepository($this->scolariteBundle . 'Exercice')->findOneBy($criteria);
                if ($oldExercice == NULL) {
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('exercice.modifier.success', 'Modifications effectuées avec succès');
                    return $this->redirect($this->generateUrl('app_admin_exercices',array('idEpreuve'=>$idEpreuve)));
                } else {

                    /*
                     * S'il s'agit du mm exercice ou k le exercice est supprimé on effecute la modification
                     */
                    if (($oldExercice->getId() == $exercice->getId()) || ($oldExercice->getEtat() == \admin\UserBundle\Types\TypeEtat::SUPPRIME)) {
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('exercice.modifier.success', 'Modifications effectuées avec succès');
                        return $this->redirect($this->generateUrl('app_admin_exercices',array('idEpreuve'=>$idEpreuve)));
                    }
                    $this->get('session')->getFlashBag()->add('exercice.modifier.already.exist', 'Le exercice ' . $exercice->getNom() . ' existe déjà');
                }
            } else {
                $this->get('session')->getFlashBag()->add('exercice.modifier.error', 'Formulaire invalide');
            }
        }

        $this->data['formuView'] = $form->createView();
        $this->data['exercice'] = $exercice;
        $this->data['idEpreuve'] = $idEpreuve;
        $this->data['locale'] = $locale;
        $this->data['idExercice'] = $idExercice;

        return $this->render($this->scolariteBundle . 'Exercice:modifierExercice.html.twig', $this->data, $this->response);
    }

    /*
     * Activation, suppression, désactivation de exercice
     * @return Response
     */

    public function changerEtatExerciceAction(Request $request) {
        $rep = array('etat' => FALSE, 'msg' => 'Erreur survenue lors du traitement');
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Activation, suppression, désactivation de exercice";
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idExercice'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $rep['msg'] = "Vous n'avez pas le droit d'activer, de supprimer ou de désactiver un exercice";
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
                $exercice = $em->getRepository($this->scolariteBundle . 'Exercice')->find((int) $idS);
                if ($exercice != NULL) {
                    $exercice->setEtat($etat);
                    $em->flush();
                    $oneOk = TRUE;
                }
            }
            if ($oneOk) {
                $this->get('session')->getFlashBag()->add('exercice.modifier.success', 'Opération éffectuée avec succès');
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

    public function infosExerciceAction(Request $request, $idExercice) {
        /**
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /**
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage des informations d'un exercice";
              $locale ="fr";
        $valRetour = $this->gestionDroitUtil($request, $nomAction, $descAction,$this->moduleTitre,$this->moduleDesc);
        if($valRetour==1){
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }elseif($valRetour==2){
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        } 
        $sessionData = $this->infosConnecte($request);

        $em = $this->getDoctrine()->getManager();

        $exercice = $em->getRepository($this->scolariteBundle . 'Exercice')->find($idExercice);
      //  $exercice = $em->getRepository($this->scolariteBundle . 'Exercice')->getExerciceEnCours($idExercice, $idannee);
        
        
        if ($exercice == NULL) {
            return $this->redirect($this->generateUrl('app_admin_exercices'));
        }

       // $form = $this->createForm('admin\ScolariteBundle\Form\ModifierExerciceType', $exercice);

        //Recuperation de la exercice actuel 
        
        /*
         * Le mot de passe ne doit pas être vide
         */
//        $parametreManager = $this->get('parametre_manager');
//        $minLengthPassword = $parametreManager->getValeurParametre(TypeParametre::LONGUEUR_MIN_PASSWORD_INT);
//        $longueurCompte = $parametreManager->getValeurParametre(TypeParametre::LONGUEUR_COMPTE_INT);
//        $longueurNumeroTel = $parametreManager->getValeurParametre(TypeParametre::LONGUEUR_NUM_TEL_INT);
      

        //$this->data['formuView'] = $form->createView();
        $this->data['exercice'] = $exercice;
//        $this->data['longueurCompte'] = $longueurCompte;
        $this->data['idExercice'] = $idExercice;
        $this->data['locale'] = $locale;
        $this->data['isAdmin'] = $sessionData['isUser'];
        return $this->render($this->scolariteBundle . 'Exercice:afficherinfosExercice.html.twig', $this->data, $this->response);
    }   
            

}
