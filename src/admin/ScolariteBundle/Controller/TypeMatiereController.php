<?php

namespace admin\ScolariteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Response;
use \admin\UserBundle\Entity\Module;
use \admin\ScolariteBundle\Entity\TypeMatiere;
use \admin\UserBundle\Services\LoginManager;
use \Symfony\Component\HttpFoundation\Request;

class TypeMatiereController extends Controller {

    
use \admin\UserBundle\ControllerModel\paramUtilTrait;
    
    
    public function __construct() {
        $this->response = new Response();
        $this->response->headers->addCacheControlDirective('no-cache', true);
        $this->response->headers->addCacheControlDirective('max-age', 0);
        $this->response->headers->addCacheControlDirective('must-revalidate', true);
        $this->response->headers->addCacheControlDirective('no-store', true);
        $this->moduleTitre = Module::MOD_MATIERE;
        $this->moduleDesc = Module::MOD_MATIERE_DESC;
        $this->logMessage = " [ TypeMatiereController ] ";
        $this->description = "Controlleur qui gère les types matière";
    }

    /**
     *  Afficle la liste des typematieres
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
    public function listeTypeMatiereAction(Request $request) {
        
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage de la liste des typematieres";
        $locale ="fr";
        $valRetour = $this->gestionDroitUtil($request, $nomAction, $descAction,$this->moduleTitre,$this->moduleDesc);
        if($valRetour==1){
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }elseif($valRetour==2){
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }

        
        $em = $this->getDoctrine()->getManager();
        $typematieres = $em->getRepository($this->scolariteBundle . 'TypeMatiere')->getAllTypeMatiere();

        $this->data['typematieres'] = $typematieres;
        $this->data['locale'] = $locale;
        return $this->render($this->scolariteBundle . 'TypeMatiere:listeTypeMatiere.html.twig', $this->data, $this->response);
    }


    /**
     *  Formulaire d'ajout de typematiere
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
    public function ajoutTypeMatiereAction(Request $request) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Ajout d'un nouveau typematiere";
         $locale ="fr";
        $valRetour = $this->gestionDroitUtil($request, $nomAction, $descAction,$this->moduleTitre,$this->moduleDesc);
        if($valRetour==1){
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }elseif($valRetour==2){
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }

        $typematiere = new TypeMatiere();
        $form = $this->createForm('\admin\ScolariteBundle\Form\TypeMatiereType', $typematiere);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();

            if ($form->isValid()) {
                $criteria = array('libelleTypeMatiere' => $typematiere->getLibelleTypeMatiere());
                $oldTypeMatiere = $em->getRepository($this->scolariteBundle . 'TypeMatiere')->findOneBy($criteria);
                if ($oldTypeMatiere == NULL) {
                    //$typematiere->setDateInitialTypeMatiere(new \DateTime());
                    $em->persist($typematiere);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('typematiere.ajout.success', 'Ajout effectue avec succès');
                    return $this->redirect($this->generateUrl('app_admin_typematieres'));
                } else {
                    if ($oldTypeMatiere->getEtat() == \admin\UserBundle\Types\TypeEtat::SUPPRIME) {
                        $oldTypeMatiere->setEtat(\admin\UserBundle\Types\TypeEtat::ACTIF);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('typematiere.ajout.success', 'Ajout effectue avec succès');
                        return $this->redirect($this->generateUrl('app_admin_typematieres'));
                    }
                    $this->get('session')->getFlashBag()->add('typematiere.ajout.already.exist', 'Le typematiere ' . $typematiere->getNom() . ' existe déjà');
                }
            } else {
                $this->get('session')->getFlashBag()->add('typematiere.ajout.error', 'Formulaire invalide');
            }
        }

        $this->data['formuView'] = $form->createView();
        $this->data['typematiere'] = $typematiere;
        $this->data['locale'] = $locale;

        return $this->render($this->scolariteBundle . 'TypeMatiere:ajouterTypeMatiere.html.twig', $this->data, $this->response);
    }

    /*
     * Modifie un typematiere
     * @param type $idTypeMatiere
     * @return type
     */

    public function modifierTypeMatiereAction(Request $request, $idTypeMatiere) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Modification d'un typematiere";
        $locale ="fr";
        $valRetour = $this->gestionDroitUtil($request, $nomAction, $descAction,$this->moduleTitre,$this->moduleDesc);
        if($valRetour==1){
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }elseif($valRetour==2){
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }

        $em = $this->getDoctrine()->getManager();
        $typematiere = $em->getRepository($this->scolariteBundle . 'TypeMatiere')->find($idTypeMatiere);
        if ($typematiere == NULL) {
            return $this->redirect($this->generateUrl('app_admin_typematieres'));
        }
        $form = $this->createForm('\admin\ScolariteBundle\Form\TypeMatiereType', $typematiere);

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);
            if ($form->isValid()) {
                $criteria = array('libelleTypeMatiere' => $typematiere->getLibelleTypeMatiere());
                $oldTypeMatiere = $em->getRepository($this->scolariteBundle . 'TypeMatiere')->findOneBy($criteria);
                if ($oldTypeMatiere == NULL) {
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('typematiere.modifier.success', 'Modifications effectuées avec succès');
                    return $this->redirect($this->generateUrl('app_admin_typematieres'));
                } else {

                    /*
                     * S'il s'agit du mm typematiere ou k le typematiere est supprimé on effecute la modification
                     */
                    if (($oldTypeMatiere->getId() == $typematiere->getId()) || ($oldTypeMatiere->getEtat() == \admin\UserBundle\Types\TypeEtat::SUPPRIME)) {
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('typematiere.modifier.success', 'Modifications effectuées avec succès');
                        return $this->redirect($this->generateUrl('app_admin_typematieres'));
                    }
                    $this->get('session')->getFlashBag()->add('typematiere.modifier.already.exist', 'Le typematiere ' . $typematiere->getNom() . ' existe déjà');
                }
            } else {
                $this->get('session')->getFlashBag()->add('typematiere.modifier.error', 'Formulaire invalide');
            }
        }

        $this->data['formuView'] = $form->createView();
        $this->data['typematiere'] = $typematiere;
        $this->data['locale'] = $locale;
        $this->data['idTypeMatiere'] = $idTypeMatiere;

        return $this->render($this->scolariteBundle . 'TypeMatiere:modifierTypeMatiere.html.twig', $this->data, $this->response);
    }

    /*
     * Activation, suppression, désactivation de typematiere
     * @return Response
     */

    public function changerEtatTypeMatiereAction(Request $request) {
        $rep = array('etat' => FALSE, 'msg' => 'Erreur survenue lors du traitement');
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Activation, suppression, désactivation de typematiere";
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idTypeMatiere'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $rep['msg'] = "Vous n'avez pas le droit d'activer, de supprimer ou de désactiver un typematiere";
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
                $typematiere = $em->getRepository($this->scolariteBundle . 'TypeMatiere')->find((int) $idS);
                if ($typematiere != NULL) {
                    $typematiere->setEtat($etat);
                    $em->flush();
                    $oneOk = TRUE;
                }
            }
            if ($oneOk) {
                $this->get('session')->getFlashBag()->add('typematiere.modifier.success', 'Opération éffectuée avec succès');
                $rep['msg'] = '';
                $rep['etat'] = TRUE;
            }

            return new Response(json_encode($rep));
        }
        return new Response(json_encode($rep));
    }

}
