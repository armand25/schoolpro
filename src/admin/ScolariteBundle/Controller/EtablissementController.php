<?php

namespace admin\ScolariteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Response;
use \admin\UserBundle\Entity\Module;
use \admin\ScolariteBundle\Entity\Etablissement;
use \admin\UserBundle\Services\LoginManager;
use \Symfony\Component\HttpFoundation\Request;

class EtablissementController extends Controller {

    
use \admin\UserBundle\ControllerModel\paramUtilTrait;
    
    
    public function __construct() {
        $this->response = new Response();
        $this->response->headers->addCacheControlDirective('no-cache', true);
        $this->response->headers->addCacheControlDirective('max-age', 0);
        $this->response->headers->addCacheControlDirective('must-revalidate', true);
        $this->response->headers->addCacheControlDirective('no-store', true);
        $this->moduleTitre = Module::MOD_EPREUVE;
        $this->moduleDesc = Module::MOD_EPREUVE_DESC;
        $this->logMessage = " [ EtablissementController ] ";
        $this->description = "Controlleur qui gère les établissements";
    }

    /**
     *  Afficle la liste des etablissements
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
    public function listeEtablissementAction(Request $request) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage de la liste des etablissements";
        $locale ="fr";
        $valRetour = $this->gestionDroitUtil($request, $nomAction, $descAction,$this->moduleTitre,$this->moduleDesc);
        if($valRetour==1){
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }elseif($valRetour==2){
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        } 
        
        $em = $this->getDoctrine()->getManager();
        $etablissements = $em->getRepository($this->scolariteBundle . 'Etablissement')->getAllEtablissement();

        $this->data['etablissements'] = $etablissements;
        $this->data['locale'] = $locale;
        return $this->render($this->scolariteBundle . 'Etablissement:listeEtablissement.html.twig', $this->data, $this->response);
    }


    /**
     *  Formulaire d'ajout de etablissement
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
    public function ajoutEtablissementAction(Request $request) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Ajout d'un nouveau établissement";
       $locale ="fr";
        $valRetour = $this->gestionDroitUtil($request, $nomAction, $descAction,$this->moduleTitre,$this->moduleDesc);
        if($valRetour==1){
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }elseif($valRetour==2){
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        } 
        
        $etablissement = new Etablissement();
        $form = $this->createForm('\admin\ScolariteBundle\Form\EtablissementType', $etablissement);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();

            if ($form->isValid()) {
                $criteria = array('libelleEtablissement' => $etablissement->getLibelleEtablissement());
                $oldEtablissement = $em->getRepository($this->scolariteBundle . 'Etablissement')->findOneBy($criteria);
                if ($oldEtablissement == NULL) {
                    $objetTemplate = $em->getRepository($this->cmsBundle . 'Template')->find(\admin\UserBundle\Types\TypeEtat::ACTIF);
                    $etablissement->setDateInitialEtablissement(new \DateTime());
                    $etablissement->setTemplate($objetTemplate);
                    $etablissement->setCodeEtablissement($objetTemplate);
                    $em->persist($etablissement);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('etablissement.ajout.success', 'Ajout effectue avec succès');
                    
                    //Redireger vers l'ajout d'un etablissemnt 
                    return $this->redirect($this->generateUrl('app_admin_user_ajout',array("idEtablissement"=>$etablissement->getId())));
                    //return $this->redirect($this->generateUrl('app_admin_etablissements'));                    
                } else {
                    if ($oldEtablissement->getEtatEtablissement() == \admin\UserBundle\Types\TypeEtat::SUPPRIME) {
                        $oldEtablissement->setEtatEtablissement(\admin\UserBundle\Types\TypeEtat::ACTIF);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('etablissement.ajout.success', 'Ajout effectue avec succès');
                       
                        return $this->redirect($this->generateUrl('app_admin_etablissements'));
                    }
                    $this->get('session')->getFlashBag()->add('etablissement.ajout.already.exist', 'Le etablissement ' . $etablissement->getLibelleEtablissement() . ' existe déjà');
                }
            } else {
                $this->get('session')->getFlashBag()->add('etablissement.ajout.error', 'Formulaire invalide');
            }
        }
        $locale="";

        $this->data['formuView'] = $form->createView();
        $this->data['etablissement'] = $etablissement;
        $this->data['locale'] = $locale;

        return $this->render($this->scolariteBundle . 'Etablissement:ajouterEtablissement.html.twig', $this->data, $this->response);
    }

    /*
     * Modifie un etablissement
     * @param type $idEtablissement
     * @return type
     */

    public function modifierEtablissementAction(Request $request, $idEtablissement) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Modification d'un etablissement";
        $locale ="fr";
        $valRetour = $this->gestionDroitUtil($request, $nomAction, $descAction,$this->moduleTitre,$this->moduleDesc);
        if($valRetour==1){
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }elseif($valRetour==2){
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        } 

        $em = $this->getDoctrine()->getManager();
        $etablissement = $em->getRepository($this->scolariteBundle . 'Etablissement')->find($idEtablissement);
        if ($etablissement == NULL) {
            return $this->redirect($this->generateUrl('app_admin_etablissements'));
        }
        $form = $this->createForm('\admin\ScolariteBundle\Form\EtablissementType', $etablissement);

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);
            if ($form->isValid()) {
                $criteria = array('libelleEtablissement' => $etablissement->getLibelleEtablissement());
                $oldEtablissement = $em->getRepository($this->scolariteBundle . 'Etablissement')->findOneBy($criteria);
                
                
                if ($oldEtablissement == NULL) {
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('etablissement.modifier.success', 'Modifications effectuées avec succès');
                    return $this->redirect($this->generateUrl('app_admin_etablissements'));
                } else {
                    /*
                     * S'il s'agit du mm etablissement ou k le etablissement est supprimé on effecute la modification
                     */
                    if (($oldEtablissement->getId() == $etablissement->getId()) || ($oldEtablissement->getEtatEtablissement() == \admin\UserBundle\Types\TypeEtat::SUPPRIME)) {
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('etablissement.modifier.success', 'Modifications effectuées avec succès');
                        return $this->redirect($this->generateUrl('app_admin_etablissements'));
                    }
                    $this->get('session')->getFlashBag()->add('etablissement.modifier.already.exist', 'Le etablissement ' . $etablissement->getLibelleEtablissement() . ' existe déjà');
                }
            } else {
                $this->get('session')->getFlashBag()->add('etablissement.modifier.error', 'Formulaire invalide');
            }
        }

        $this->data['formuView'] = $form->createView();
        $this->data['etablissement'] = $etablissement;
        $this->data['locale'] = $locale;
        $this->data['idEtablissement'] = $idEtablissement;

        return $this->render($this->scolariteBundle . 'Etablissement:modifierEtablissement.html.twig', $this->data, $this->response);
    }

    /*
     * Activation, suppression, désactivation de etablissement
     * @return Response
     */

    public function changerEtatEtablissementAction(Request $request) {
        $rep = array('etat' => FALSE, 'msg' => 'Erreur survenue lors du traitement');
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Activation, suppression, désactivation de etablissement";
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
            $rep['msg'] = "Vous n'avez pas le droit d'activer, de supprimer ou de désactiver un etablissement";
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
                $etablissement = $em->getRepository($this->scolariteBundle . 'Etablissement')->find((int) $idS);
                if ($etablissement != NULL) {
                    $etablissement->setEtat($etat);
                    $em->flush();
                    $oneOk = TRUE;
                }
            }
            if ($oneOk) {
                $this->get('session')->getFlashBag()->add('etablissement.modifier.success', 'Opération éffectuée avec succès');
                $rep['msg'] = '';
                $rep['etat'] = TRUE;
            }

            return new Response(json_encode($rep));
        }
        return new Response(json_encode($rep));
    }

}
