<?php

namespace admin\ScolariteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Response;
use \admin\UserBundle\Entity\Module;
use \admin\ScolariteBundle\Entity\AnneeScolaire;
use \admin\UserBundle\Services\LoginManager;
use \Symfony\Component\HttpFoundation\Request;

class AnneeScolaireController extends Controller {

    
use \admin\UserBundle\ControllerModel\paramUtilTrait;
    
    
    public function __construct() {
        $this->response = new Response();
        $this->response->headers->addCacheControlDirective('no-cache', true);
        $this->response->headers->addCacheControlDirective('max-age', 0);
        $this->response->headers->addCacheControlDirective('must-revalidate', true);
        $this->response->headers->addCacheControlDirective('no-store', true);
        $this->logMessage = " [ AnneeScolaireController ] ";
        $this->moduleTitre = Module::MOD_ANNEE;
        $this->moduleDesc = Module::MOD_ANNEE_DESC;
        $this->description = "Controlleur qui gère les années scolaires";
    }

    /**
     *  Afficle la liste des anneescolaires
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
    public function listeAnneeScolaireAction(Request $request) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage de la liste des anneescolaires";
        $locale ="fr";
        $valRetour = $this->gestionDroitUtil($request, $nomAction, $descAction,$this->moduleTitre,$this->moduleDesc);
        if($valRetour==1){
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }elseif($valRetour==2){
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }

        $em = $this->getDoctrine()->getManager();
        $anneescolaires = $em->getRepository($this->scolariteBundle . 'AnneeScolaire')->getAllAnneeScolaire();

        $this->data['anneescolaires'] = $anneescolaires;
        $this->data['locale'] = $locale;
        return $this->render($this->scolariteBundle . 'AnneeScolaire:listeAnneeScolaire.html.twig', $this->data, $this->response);
    }


    /**
     *  Formulaire d'ajout de anneescolaire
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
    public function ajoutAnneeScolaireAction(Request $request) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Ajout d'un nouveau année scolaire";
         $locale ="fr";
        $valRetour = $this->gestionDroitUtil($request, $nomAction, $descAction,$this->moduleTitre,$this->moduleDesc);
        if($valRetour==1){
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }elseif($valRetour==2){
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }

        $anneescolaire = new AnneeScolaire();
        $form = $this->createForm('\admin\ScolariteBundle\Form\AnneeScolaireType', $anneescolaire);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();

            if ($form->isValid()) {
                $criteria = array('libelleAnneeScolaire' => $anneescolaire->getLibelleAnneeScolaire());
                $oldAnneeScolaire = $em->getRepository($this->scolariteBundle . 'AnneeScolaire')->findOneBy($criteria);
                if ($oldAnneeScolaire == NULL) {
                    $anneescolaire->setDateInitialAnneeScolaire(new \DateTime());
                    $em->persist($anneescolaire);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('anneescolaire.ajout.success', 'Ajout effectue avec succès');
                    return $this->redirect($this->generateUrl('app_admin_anneescolaires'));
                } else {
                    if ($oldAnneeScolaire->getEtat() == \admin\UserBundle\Types\TypeEtat::SUPPRIME) {
                        $oldAnneeScolaire->setEtat(\admin\UserBundle\Types\TypeEtat::ACTIF);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('anneescolaire.ajout.success', 'Ajout effectue avec succès');
                        return $this->redirect($this->generateUrl('app_admin_anneescolaires'));
                    }
                    $this->get('session')->getFlashBag()->add('anneescolaire.ajout.already.exist', 'Le anneescolaire ' . $anneescolaire->getNom() . ' existe déjà');
                }
            } else {
                $this->get('session')->getFlashBag()->add('anneescolaire.ajout.error', 'Formulaire invalide');
            }
        }

        $this->data['formuView'] = $form->createView();
        $this->data['anneescolaire'] = $anneescolaire;
        $this->data['locale'] = $locale;

        return $this->render($this->scolariteBundle . 'AnneeScolaire:ajouterAnneeScolaire.html.twig', $this->data, $this->response);
    }

    /*
     * Modifie un anneescolaire
     * @param type $idAnneeScolaire
     * @return type
     */

    public function modifierAnneeScolaireAction(Request $request, $idAnneeScolaire) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Modification d'un année scolaire";
               $locale ="fr";
        $valRetour = $this->gestionDroitUtil($request, $nomAction, $descAction,$this->moduleTitre,$this->moduleDesc);
        if($valRetour==1){
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }elseif($valRetour==2){
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }
        $em = $this->getDoctrine()->getManager();
        $anneescolaire = $em->getRepository($this->scolariteBundle . 'AnneeScolaire')->find($idAnneeScolaire);
        if ($anneescolaire == NULL) {
            return $this->redirect($this->generateUrl('app_admin_anneescolaires'));
        }
        $form = $this->createForm('\admin\ScolariteBundle\Form\AnneeScolaireType', $anneescolaire);

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);
            if ($form->isValid()) {
                $criteria = array('libelleAnneeScolaire' => $anneescolaire->getLibelleAnneeScolaire());
                $oldAnneeScolaire = $em->getRepository($this->scolariteBundle . 'AnneeScolaire')->findOneBy($criteria);
                if ($oldAnneeScolaire == NULL) {
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('anneescolaire.modifier.success', 'Modifications effectuées avec succès');
                    return $this->redirect($this->generateUrl('app_admin_anneescolaires'));
                } else {

                    /*
                     * S'il s'agit du mm anneescolaire ou k le anneescolaire est supprimé on effecute la modification
                     */
                    if (($oldAnneeScolaire->getId() == $anneescolaire->getId()) || ($oldAnneeScolaire->getEtat() == \admin\UserBundle\Types\TypeEtat::SUPPRIME)) {
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('anneescolaire.modifier.success', 'Modifications effectuées avec succès');
                        return $this->redirect($this->generateUrl('app_admin_anneescolaires'));
                    }
                    $this->get('session')->getFlashBag()->add('anneescolaire.modifier.already.exist', 'Le anneescolaire ' . $anneescolaire->getNom() . ' existe déjà');
                }
            } else {
                $this->get('session')->getFlashBag()->add('anneescolaire.modifier.error', 'Formulaire invalide');
            }
        }

        $this->data['formuView'] = $form->createView();
        $this->data['anneescolaire'] = $anneescolaire;
        $this->data['locale'] = $locale;
        $this->data['idAnneeScolaire'] = $idAnneeScolaire;

        return $this->render($this->scolariteBundle . 'AnneeScolaire:modifierAnneeScolaire.html.twig', $this->data, $this->response);
    }

    /*
     * Activation, suppression, désactivation de anneescolaire
     * @return Response
     */

    public function changerEtatAnneeScolaireAction(Request $request) {
        $rep = array('etat' => FALSE, 'msg' => 'Erreur survenue lors du traitement');
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Activation, suppression, désactivation de anneescolaire";
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
        if (!$loginManager->getOrSetActions(Module::MOD_ANNEE, Module::MOD_ANNEE_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idAnneeScolaire'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $rep['msg'] = "Vous n'avez pas le droit d'activer, de supprimer ou de désactiver un anneescolaire";
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
                $anneescolaire = $em->getRepository($this->scolariteBundle . 'AnneeScolaire')->find((int) $idS);
                if ($anneescolaire != NULL) {
                    $anneescolaire->setEtat($etat);
                    $em->flush();
                    $oneOk = TRUE;
                }
            }
            if ($oneOk) {
                $this->get('session')->getFlashBag()->add('anneescolaire.modifier.success', 'Opération éffectuée avec succès');
                $rep['msg'] = '';
                $rep['etat'] = TRUE;
            }

            return new Response(json_encode($rep));
        }
        return new Response(json_encode($rep));
    }

}
