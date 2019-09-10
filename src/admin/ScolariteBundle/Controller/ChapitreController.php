<?php

namespace admin\ScolariteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Response;
use \admin\UserBundle\Entity\Module;
use \admin\ScolariteBundle\Entity\Chapitre;
use \admin\UserBundle\Services\LoginManager;
use \Symfony\Component\HttpFoundation\Request;

class ChapitreController extends Controller {

    
use \admin\UserBundle\ControllerModel\paramUtilTrait;
    
    
    public function __construct() {
        $this->response = new Response();
        $this->response->headers->addCacheControlDirective('no-cache', true);
        $this->response->headers->addCacheControlDirective('max-age', 0);
        $this->response->headers->addCacheControlDirective('must-revalidate', true);
        $this->response->headers->addCacheControlDirective('no-store', true);
        $this->logMessage = " [ ChapitreController ] ";
        $this->moduleTitre = Module::MOD_MATIERE;
        $this->moduleDesc = Module::MOD_MATIERE_DESC;
        $this->description = "Controlleur qui gère les chaptitres des matières";
    }

    /**
     *  Afficle la liste des chapitres
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
    public function listeChapitreAction(Request $request,$estEns) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage de la liste des chapitres";
        $locale ="fr";
        $valRetour = $this->gestionDroitUtil($request, $nomAction, $descAction,$this->moduleTitre,$this->moduleDesc);
        if($valRetour==1){
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }elseif($valRetour==2){
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }        
        $em = $this->getDoctrine()->getManager();
        $chapitres = $em->getRepository($this->scolariteBundle . 'Chapitre')->getAllChapitre($estEns);

        $this->data['chapitres'] = $chapitres;
        $this->data['estEns'] = $estEns;
        $this->data['locale'] = $locale;
        return $this->render($this->scolariteBundle . 'Chapitre:listeChapitre.html.twig', $this->data, $this->response);
    }


    /**
     *  Formulaire d'ajout de chapitre
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
    public function ajoutChapitreAction(Request $request,$estEns) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Ajout d'un nouveau chapitre";
        $locale ="fr";
        $valRetour = $this->gestionDroitUtil($request, $nomAction, $descAction,$this->moduleTitre,$this->moduleDesc);
        if($valRetour==1){
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }elseif($valRetour==2){
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }

        $chapitre = new Chapitre();
        $form = $this->createForm('\admin\ScolariteBundle\Form\ChapitreType', $chapitre);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $objetEstEnseigne = $em->getRepository($this->scolariteBundle . 'EstEnseigne')->find($estEns);
            if ($form->isValid()) {
                $criteria = array('libelleChapitre' => $chapitre->getLibelleChapitre(),'estenseigne'=>$objetEstEnseigne);
                $oldChapitre = $em->getRepository($this->scolariteBundle . 'Chapitre')->findOneBy($criteria);
                if ($oldChapitre == NULL) {
                    //$chapitre->setDateInitialChapitre(new \DateTime());
                    
                    $chapitre->setEstenseigne($objetEstEnseigne);
                    $em->persist($chapitre);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('chapitre.ajout.success', 'Ajout effectue avec succès');
                    return $this->redirect($this->generateUrl('app_admin_chapitres',array('estEns'=>$estEns)));
                } else {
                    if ($oldChapitre->getEtat() == \admin\UserBundle\Types\TypeEtat::SUPPRIME) {
                        $oldChapitre->setEtat(\admin\UserBundle\Types\TypeEtat::ACTIF);
                        $chapitre->setEstenseigne($objetEstEnseigne);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('chapitre.ajout.success', 'Ajout effectue avec succès');
                        return $this->redirect($this->generateUrl('app_admin_chapitres',array('estEns'=>$estEns)));
                    }
                    $this->get('session')->getFlashBag()->add('chapitre.ajout.already.exist', 'Le chapitre ' . $chapitre->getNom() . ' existe déjà');
                }
            } else {
                $this->get('session')->getFlashBag()->add('chapitre.ajout.error', 'Formulaire invalide');
            }
        }
        $this->data['formuView'] = $form->createView();
        $this->data['chapitre'] = $chapitre;
        $this->data['estEns'] = $estEns;
        $this->data['locale'] = $locale;

        return $this->render($this->scolariteBundle . 'Chapitre:ajouterChapitre.html.twig', $this->data, $this->response);
    }

    /*
     * Modifie un chapitre
     * @param type $idChapitre
     * @return type
     */

    public function modifierChapitreAction(Request $request, $idChapitre,$estEns) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Modification d'un chapitre";
       $locale ="fr";
        $valRetour = $this->gestionDroitUtil($request, $nomAction, $descAction,$this->moduleTitre,$this->moduleDesc);
        if($valRetour==1){
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }elseif($valRetour==2){
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }
        $em = $this->getDoctrine()->getManager();
        $chapitre = $em->getRepository($this->scolariteBundle . 'Chapitre')->find($idChapitre);
        if ($chapitre == NULL) {
            return $this->redirect($this->generateUrl('app_admin_chapitres',array('estEns'=>$estEns)));
        }
        $form = $this->createForm('\admin\ScolariteBundle\Form\ChapitreType', $chapitre);

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);
            if ($form->isValid()) {
                $criteria = array('libelleChapitre' => $chapitre->getLibelleChapitre());
                $oldChapitre = $em->getRepository($this->scolariteBundle . 'Chapitre')->findOneBy($criteria);
                if ($oldChapitre == NULL) {
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('chapitre.modifier.success', 'Modifications effectuées avec succès');
                    return $this->redirect($this->generateUrl('app_admin_chapitres',array('estEns'=>$estEns)));
                } else {

                    /*
                     * S'il s'agit du mm chapitre ou k le chapitre est supprimé on effecute la modification
                     */
                    if (($oldChapitre->getId() == $chapitre->getId()) || ($oldChapitre->getEtat() == \admin\UserBundle\Types\TypeEtat::SUPPRIME)) {
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('chapitre.modifier.success', 'Modifications effectuées avec succès');
                        return $this->redirect($this->generateUrl('app_admin_chapitres',array('estEns'=>$estEns)));
                    }
                    $this->get('session')->getFlashBag()->add('chapitre.modifier.already.exist', 'Le chapitre ' . $chapitre->getNom() . ' existe déjà');
                }
            } else {
                $this->get('session')->getFlashBag()->add('chapitre.modifier.error', 'Formulaire invalide');
            }
        }

        $this->data['formuView'] = $form->createView();
        $this->data['chapitre'] = $chapitre;
        $this->data['estEns'] = $estEns;
        $this->data['locale'] = $locale;
        $this->data['idChapitre'] = $idChapitre;

        return $this->render($this->scolariteBundle . 'Chapitre:modifierChapitre.html.twig', $this->data, $this->response);
    }

    /*
     * Activation, suppression, désactivation de chapitre
     * @return Response
     */

    public function changerEtatChapitreAction(Request $request) {
        $rep = array('etat' => FALSE, 'msg' => 'Erreur survenue lors du traitement');
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Activation, suppression, désactivation de chapitre";
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idChapitre'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $rep['msg'] = "Vous n'avez pas le droit d'activer, de supprimer ou de désactiver un chapitre";
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
                $chapitre = $em->getRepository($this->scolariteBundle . 'Chapitre')->find((int) $idS);
                if ($chapitre != NULL) {
                    $chapitre->setEtat($etat);
                    $em->flush();
                    $oneOk = TRUE;
                }
            }
            if ($oneOk) {
                $this->get('session')->getFlashBag()->add('chapitre.modifier.success', 'Opération éffectuée avec succès');
                $rep['msg'] = '';
                $rep['etat'] = TRUE;
            }

            return new Response(json_encode($rep));
        }
        return new Response(json_encode($rep));
    }

}
