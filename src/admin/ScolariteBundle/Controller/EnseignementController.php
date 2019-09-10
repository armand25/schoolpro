<?php

namespace admin\ScolariteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Response;
use \admin\UserBundle\Entity\Module;
use \admin\ScolariteBundle\Entity\Enseignement;
use \admin\UserBundle\Services\LoginManager;
use \Symfony\Component\HttpFoundation\Request;

class EnseignementController extends Controller {

    
use \admin\UserBundle\ControllerModel\paramUtilTrait;
    
    
    public function __construct() {
        $this->response = new Response();
        $this->response->headers->addCacheControlDirective('no-cache', true);
        $this->response->headers->addCacheControlDirective('max-age', 0);
        $this->response->headers->addCacheControlDirective('must-revalidate', true);
        $this->response->headers->addCacheControlDirective('no-store', true);
        $this->moduleTitre = Module::MOD_ENSEIGN;
        $this->moduleDesc = Module::MOD_ENSEIGN_DESC;
        $this->logMessage = " [ EnseignementController ] ";
        $this->description = "Controlleur qui gère les enseignements";
    }

    /**
     *  Afficle la liste des enseignements
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
    public function listeEnseignementAction(Request $request) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage de la liste des enseignements";
       $locale ="fr";
        $valRetour = $this->gestionDroitUtil($request, $nomAction, $descAction,$this->moduleTitre,$this->moduleDesc);
        if($valRetour==1){
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }elseif($valRetour==2){
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }    
        $em = $this->getDoctrine()->getManager();
        $enseignements = $em->getRepository($this->scolariteBundle . 'Enseignement')->getAllEnseignement();

        $this->data['enseignements'] = $enseignements;
        $this->data['locale'] = $locale;
        return $this->render($this->scolariteBundle . 'Enseignement:listeEnseignement.html.twig', $this->data, $this->response);
    }


    /**
     *  Formulaire d'ajout de enseignement
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
    public function ajoutEnseignementAction(Request $request) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Ajout d'un nouveau enseignement";
     $locale ="fr";
        $valRetour = $this->gestionDroitUtil($request, $nomAction, $descAction,$this->moduleTitre,$this->moduleDesc);
        if($valRetour==1){
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }elseif($valRetour==2){
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }    
        $enseignement = new Enseignement();
        $form = $this->createForm('\admin\ScolariteBundle\Form\EnseignementType', $enseignement);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();

            if ($form->isValid()) {
                $criteria = array('libelleEnseignement' => $enseignement->getLibelleEnseignement());
                $oldEnseignement = $em->getRepository($this->scolariteBundle . 'Enseignement')->findOneBy($criteria);
                if ($oldEnseignement == NULL) {
                    //$enseignement->setDateInitialEnseignement(new \DateTime());
                    $em->persist($enseignement);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('enseignement.ajout.success', 'Ajout effectue avec succès');
                    return $this->redirect($this->generateUrl('app_admin_enseignements'));
                } else {
                    if ($oldEnseignement->getEtat() == \admin\UserBundle\Types\TypeEtat::SUPPRIME) {
                        $oldEnseignement->setEtat(\admin\UserBundle\Types\TypeEtat::ACTIF);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('enseignement.ajout.success', 'Ajout effectue avec succès');
                        return $this->redirect($this->generateUrl('app_admin_enseignements'));
                    }
                    $this->get('session')->getFlashBag()->add('enseignement.ajout.already.exist', 'Le enseignement ' . $enseignement->getNom() . ' existe déjà');
                }
            } else {
                $this->get('session')->getFlashBag()->add('enseignement.ajout.error', 'Formulaire invalide');
            }
        }
        $this->data['formuView'] = $form->createView();
        $this->data['enseignement'] = $enseignement;
        $this->data['locale'] = $locale;

        return $this->render($this->scolariteBundle . 'Enseignement:ajouterEnseignement.html.twig', $this->data, $this->response);
    }

    /*
     * Modifie un enseignement
     * @param type $idEnseignement
     * @return type
     */

    public function modifierEnseignementAction(Request $request, $idEnseignement) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Modification d'un enseignement";
       $locale ="fr";
        $valRetour = $this->gestionDroitUtil($request, $nomAction, $descAction,$this->moduleTitre,$this->moduleDesc);
        if($valRetour==1){
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }elseif($valRetour==2){
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }    
        $em = $this->getDoctrine()->getManager();
        $enseignement = $em->getRepository($this->scolariteBundle . 'Enseignement')->find($idEnseignement);
        if ($enseignement == NULL) {
            return $this->redirect($this->generateUrl('app_admin_enseignements'));
        }
        $form = $this->createForm('\admin\ScolariteBundle\Form\EnseignementType', $enseignement);

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);
            if ($form->isValid()) {
                $criteria = array('libelleEnseignement' => $enseignement->getLibelleEnseignement());
                $oldEnseignement = $em->getRepository($this->scolariteBundle . 'Enseignement')->findOneBy($criteria);
                if ($oldEnseignement == NULL) {
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('enseignement.modifier.success', 'Modifications effectuées avec succès');
                    return $this->redirect($this->generateUrl('app_admin_enseignements'));
                } else {

                    /*
                     * S'il s'agit du mm enseignement ou k le enseignement est supprimé on effecute la modification
                     */
                    if (($oldEnseignement->getId() == $enseignement->getId()) || ($oldEnseignement->getEtat() == \admin\UserBundle\Types\TypeEtat::SUPPRIME)) {
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('enseignement.modifier.success', 'Modifications effectuées avec succès');
                        return $this->redirect($this->generateUrl('app_admin_enseignements'));
                    }
                    $this->get('session')->getFlashBag()->add('enseignement.modifier.already.exist', 'Le enseignement ' . $enseignement->getNom() . ' existe déjà');
                }
            } else {
                $this->get('session')->getFlashBag()->add('enseignement.modifier.error', 'Formulaire invalide');
            }
        }

        $this->data['formuView'] = $form->createView();
        $this->data['enseignement'] = $enseignement;
        $this->data['locale'] = $locale;
        $this->data['idEnseignement'] = $idEnseignement;

        return $this->render($this->scolariteBundle . 'Enseignement:modifierEnseignement.html.twig', $this->data, $this->response);
    }

    /*
     * Activation, suppression, désactivation de enseignement
     * @return Response
     */

    public function changerEtatEnseignementAction(Request $request) {
        $rep = array('etat' => FALSE, 'msg' => 'Erreur survenue lors du traitement');
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Activation, suppression, désactivation de enseignement";
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idEnseignement'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $rep['msg'] = "Vous n'avez pas le droit d'activer, de supprimer ou de désactiver un enseignement";
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
                $enseignement = $em->getRepository($this->scolariteBundle . 'Enseignement')->find((int) $idS);
                if ($enseignement != NULL) {
                    $enseignement->setEtat($etat);
                    $em->flush();
                    $oneOk = TRUE;
                }
            }
            if ($oneOk) {
                $this->get('session')->getFlashBag()->add('enseignement.modifier.success', 'Opération éffectuée avec succès');
                $rep['msg'] = '';
                $rep['etat'] = TRUE;
            }

            return new Response(json_encode($rep));
        }
        return new Response(json_encode($rep));
    }

}
