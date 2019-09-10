<?php

namespace admin\ScolariteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Response;
use \admin\UserBundle\Entity\Module;
use \admin\ScolariteBundle\Entity\Filiere;
use \admin\UserBundle\Services\LoginManager;
use \Symfony\Component\HttpFoundation\Request;

class FiliereController extends Controller {

    
use \admin\UserBundle\ControllerModel\paramUtilTrait;
    
    
    public function __construct() {
        $this->response = new Response();
        $this->response->headers->addCacheControlDirective('no-cache', true);
        $this->response->headers->addCacheControlDirective('max-age', 0);
        $this->response->headers->addCacheControlDirective('must-revalidate', true);
        $this->response->headers->addCacheControlDirective('no-store', true);
        $this->moduleTitre = Module::MOD_CLASSE;
        $this->moduleDesc = Module::MOD_CLASSE_DESC;
        $this->logMessage = " [ FiliereController ] ";
        $this->description = "Controlleur qui gère les utilisateurs";
    }

    /**
     *  Afficle la liste des filieres
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
    public function listeFiliereAction(Request $request) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage de la liste des filieres";
            $locale ="fr";
        $valRetour = $this->gestionDroitUtil($request, $nomAction, $descAction,$this->moduleTitre,$this->moduleDesc);
        if($valRetour==1){
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }elseif($valRetour==2){
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }
        
        $em = $this->getDoctrine()->getManager();
        $filieres = $em->getRepository($this->scolariteBundle . 'Filiere')->getAllFiliere();

        $this->data['filieres'] = $filieres;
        $this->data['locale'] = $locale;
        return $this->render($this->scolariteBundle . 'Filiere:listeFiliere.html.twig', $this->data, $this->response);
    }


    /**
     *  Formulaire d'ajout de filiere
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
    public function ajoutFiliereAction(Request $request) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Ajout d'un nouveau filiere";
            $locale ="fr";
        $valRetour = $this->gestionDroitUtil($request, $nomAction, $descAction,$this->moduleTitre,$this->moduleDesc);
        if($valRetour==1){
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }elseif($valRetour==2){
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }
        
        $filiere = new Filiere();
        $form = $this->createForm('\admin\ScolariteBundle\Form\FiliereType', $filiere);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();

            if ($form->isValid()) {
                $criteria = array('libelleFiliere' => $filiere->getLibelleFiliere());
                $oldFiliere = $em->getRepository($this->scolariteBundle . 'Filiere')->findOneBy($criteria);
                if ($oldFiliere == NULL) {
                    //$filiere->setDateInitialFiliere(new \DateTime());
                    $em->persist($filiere);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('filiere.ajout.success', 'Ajout effectue avec succès');
                    return $this->redirect($this->generateUrl('app_admin_filieres'));
                } else {
                    if ($oldFiliere->getEtat() == \admin\UserBundle\Types\TypeEtat::SUPPRIME) {
                        $oldFiliere->setEtat(\admin\UserBundle\Types\TypeEtat::ACTIF);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('filiere.ajout.success', 'Ajout effectue avec succès');
                        return $this->redirect($this->generateUrl('app_admin_filieres'));
                    }
                    $this->get('session')->getFlashBag()->add('filiere.ajout.already.exist', 'Le filiere ' . $filiere->getNom() . ' existe déjà');
                }
            } else {
                $this->get('session')->getFlashBag()->add('filiere.ajout.error', 'Formulaire invalide');
            }
        }
        $locale="";

        $this->data['formuView'] = $form->createView();
        $this->data['filiere'] = $filiere;
        $this->data['locale'] = $locale;

        return $this->render($this->scolariteBundle . 'Filiere:ajouterFiliere.html.twig', $this->data, $this->response);
    }

    /*
     * Modifie un filiere
     * @param type $idFiliere
     * @return type
     */

    public function modifierFiliereAction(Request $request, $idFiliere) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Modification d'un filière";
            $locale ="fr";
        $valRetour = $this->gestionDroitUtil($request, $nomAction, $descAction,$this->moduleTitre,$this->moduleDesc);
        if($valRetour==1){
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }elseif($valRetour==2){
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }
        
        $em = $this->getDoctrine()->getManager();
        $filiere = $em->getRepository($this->scolariteBundle . 'Filiere')->find($idFiliere);
        if ($filiere == NULL) {
            return $this->redirect($this->generateUrl('app_admin_filieres'));
        }
        $form = $this->createForm('\admin\ScolariteBundle\Form\FiliereType', $filiere);

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);
            if ($form->isValid()) {
                $criteria = array('libelleFiliere' => $filiere->getLibelleFiliere());
                $oldFiliere = $em->getRepository($this->scolariteBundle . 'Filiere')->findOneBy($criteria);
                if ($oldFiliere == NULL) {
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('filiere.modifier.success', 'Modifications effectuées avec succès');
                    return $this->redirect($this->generateUrl('app_admin_filieres'));
                } else {

                    /*
                     * S'il s'agit du mm filiere ou k le filiere est supprimé on effecute la modification
                     */
                    if (($oldFiliere->getId() == $filiere->getId()) || ($oldFiliere->getEtat() == \admin\UserBundle\Types\TypeEtat::SUPPRIME)) {
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('filiere.modifier.success', 'Modifications effectuées avec succès');
                        return $this->redirect($this->generateUrl('app_admin_filieres'));
                    }
                    $this->get('session')->getFlashBag()->add('filiere.modifier.already.exist', 'Le filiere ' . $filiere->getNom() . ' existe déjà');
                }
            } else {
                $this->get('session')->getFlashBag()->add('filiere.modifier.error', 'Formulaire invalide');
            }
        }

        $this->data['formuView'] = $form->createView();
        $this->data['filiere'] = $filiere;
        $this->data['locale'] = $locale;
        $this->data['idFiliere'] = $idFiliere;

        return $this->render($this->scolariteBundle . 'Filiere:modifierFiliere.html.twig', $this->data, $this->response);
    }

    /*
     * Activation, suppression, désactivation de filiere
     * @return Response
     */

    public function changerEtatFiliereAction(Request $request) {
        $rep = array('etat' => FALSE, 'msg' => 'Erreur survenue lors du traitement');
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Activation, suppression, désactivation de filiere";
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idFiliere'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $rep['msg'] = "Vous n'avez pas le droit d'activer, de supprimer ou de désactiver un filiere";
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
                $filiere = $em->getRepository($this->scolariteBundle . 'Filiere')->find((int) $idS);
                if ($filiere != NULL) {
                    $filiere->setEtat($etat);
                    $em->flush();
                    $oneOk = TRUE;
                }
            }
            if ($oneOk) {
                $this->get('session')->getFlashBag()->add('filiere.modifier.success', 'Opération éffectuée avec succès');
                $rep['msg'] = '';
                $rep['etat'] = TRUE;
            }

            return new Response(json_encode($rep));
        }
        return new Response(json_encode($rep));
    }

}
