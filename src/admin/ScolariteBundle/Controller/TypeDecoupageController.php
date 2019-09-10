<?php

namespace admin\ScolariteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Response;
use \admin\UserBundle\Entity\Module;
use \admin\ScolariteBundle\Entity\TypeDecoupage;
use \admin\UserBundle\Services\LoginManager;
use \Symfony\Component\HttpFoundation\Request;

class TypeDecoupageController extends Controller {

    
use \admin\UserBundle\ControllerModel\paramUtilTrait;
    
    
    public function __construct() {
        $this->response = new Response();
        $this->response->headers->addCacheControlDirective('no-cache', true);
        $this->response->headers->addCacheControlDirective('max-age', 0);
        $this->response->headers->addCacheControlDirective('must-revalidate', true);
        $this->response->headers->addCacheControlDirective('no-store', true);
        $this->moduleTitre = Module::MOD_DECOUP;
        $this->moduleDesc = Module::MOD_DECOUP_DESC;
        $this->logMessage = " [ TypeDecoupageController ] ";
        $this->description = "Controlleur qui gère les utilisateurs";
    }

    /**
     *  Afficle la liste des typedecoupages
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
    public function listeTypeDecoupageAction(Request $request) {
        
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage de la liste des typedecoupages";
        $locale ="fr";
        $valRetour = $this->gestionDroitUtil($request, $nomAction, $descAction,$this->moduleTitre,$this->moduleDesc);
        if($valRetour==1){
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }elseif($valRetour==2){
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }

        $em = $this->getDoctrine()->getManager();
        $typedecoupages = $em->getRepository($this->scolariteBundle . 'TypeDecoupage')->getAllTypeDecoupage();

        $this->data['typedecoupages'] = $typedecoupages;
        $this->data['locale'] = $locale;
        return $this->render($this->scolariteBundle . 'TypeDecoupage:listeTypeDecoupage.html.twig', $this->data, $this->response);
    }


    /**
     *  Formulaire d'ajout de typedecoupage
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
    public function ajoutTypeDecoupageAction(Request $request) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Ajout d'un nouveau typedecoupage";
        $locale ="fr";
        $valRetour = $this->gestionDroitUtil($request, $nomAction, $descAction,$this->moduleTitre,$this->moduleDesc);
        if($valRetour==1){
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }elseif($valRetour==2){
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }

        $typedecoupage = new TypeDecoupage();
        $form = $this->createForm('\admin\ScolariteBundle\Form\TypeDecoupageType', $typedecoupage);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();

            if ($form->isValid()) {
                $criteria = array('libelleTypeDecoupage' => $typedecoupage->getLibelleTypeDecoupage());
                $oldTypeDecoupage = $em->getRepository($this->scolariteBundle . 'TypeDecoupage')->findOneBy($criteria);
                if ($oldTypeDecoupage == NULL) {
                    //$typedecoupage->setDateInitialTypeDecoupage(new \DateTime());
                    $em->persist($typedecoupage);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('typedecoupage.ajout.success', 'Ajout effectue avec succès');
                    return $this->redirect($this->generateUrl('app_admin_typedecoupages'));
                } else {
                    if ($oldTypeDecoupage->getEtat() == \admin\UserBundle\Types\TypeEtat::SUPPRIME) {
                        $oldTypeDecoupage->setEtat(\admin\UserBundle\Types\TypeEtat::ACTIF);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('typedecoupage.ajout.success', 'Ajout effectue avec succès');
                        return $this->redirect($this->generateUrl('app_admin_typedecoupages'));
                    }
                    $this->get('session')->getFlashBag()->add('typedecoupage.ajout.already.exist', 'Le typedecoupage ' . $typedecoupage->getNom() . ' existe déjà');
                }
            } else {
                $this->get('session')->getFlashBag()->add('typedecoupage.ajout.error', 'Formulaire invalide');
            }
        }

        $this->data['formuView'] = $form->createView();
        $this->data['typedecoupage'] = $typedecoupage;
        $this->data['locale'] = $locale;

        return $this->render($this->scolariteBundle . 'TypeDecoupage:ajouterTypeDecoupage.html.twig', $this->data, $this->response);
    }

    /*
     * Modifie un typedecoupage
     * @param type $idTypeDecoupage
     * @return type
     */

    public function modifierTypeDecoupageAction(Request $request, $idTypeDecoupage) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Modification d'un typedecoupage";
        $locale ="fr";
        $valRetour = $this->gestionDroitUtil($request, $nomAction, $descAction,$this->moduleTitre,$this->moduleDesc);
        if($valRetour==1){
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }elseif($valRetour==2){
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }

        $em = $this->getDoctrine()->getManager();
        $typedecoupage = $em->getRepository($this->scolariteBundle . 'TypeDecoupage')->find($idTypeDecoupage);
        if ($typedecoupage == NULL) {
            return $this->redirect($this->generateUrl('app_admin_typedecoupages'));
        }
        $form = $this->createForm('\admin\ScolariteBundle\Form\TypeDecoupageType', $typedecoupage);

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);
            if ($form->isValid()) {
                $criteria = array('libelleTypeDecoupage' => $typedecoupage->getLibelleTypeDecoupage());
                $oldTypeDecoupage = $em->getRepository($this->scolariteBundle . 'TypeDecoupage')->findOneBy($criteria);
                if ($oldTypeDecoupage == NULL) {
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('typedecoupage.modifier.success', 'Modifications effectuées avec succès');
                    return $this->redirect($this->generateUrl('app_admin_typedecoupages'));
                } else {

                    /*
                     * S'il s'agit du mm typedecoupage ou k le typedecoupage est supprimé on effecute la modification
                     */
                    if (($oldTypeDecoupage->getId() == $typedecoupage->getId()) || ($oldTypeDecoupage->getEtat() == \admin\UserBundle\Types\TypeEtat::SUPPRIME)) {
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('typedecoupage.modifier.success', 'Modifications effectuées avec succès');
                        return $this->redirect($this->generateUrl('app_admin_typedecoupages'));
                    }
                    $this->get('session')->getFlashBag()->add('typedecoupage.modifier.already.exist', 'Le typedecoupage ' . $typedecoupage->getNom() . ' existe déjà');
                }
            } else {
                $this->get('session')->getFlashBag()->add('typedecoupage.modifier.error', 'Formulaire invalide');
            }
        }

        $this->data['formuView'] = $form->createView();
        $this->data['typedecoupage'] = $typedecoupage;
        $this->data['locale'] = $locale;
        $this->data['idTypeDecoupage'] = $idTypeDecoupage;

        return $this->render($this->scolariteBundle . 'TypeDecoupage:modifierTypeDecoupage.html.twig', $this->data, $this->response);
    }

    /*
     * Activation, suppression, désactivation de typedecoupage
     * @return Response
     */

    public function changerEtatTypeDecoupageAction(Request $request) {
        $rep = array('etat' => FALSE, 'msg' => 'Erreur survenue lors du traitement');
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Activation, suppression, désactivation de typedecoupage";
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idTypeDecoupage'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $rep['msg'] = "Vous n'avez pas le droit d'activer, de supprimer ou de désactiver un typedecoupage";
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
                $typedecoupage = $em->getRepository($this->scolariteBundle . 'TypeDecoupage')->find((int) $idS);
                if ($typedecoupage != NULL) {
                    $typedecoupage->setEtat($etat);
                    $em->flush();
                    $oneOk = TRUE;
                }
            }
            if ($oneOk) {
                $this->get('session')->getFlashBag()->add('typedecoupage.modifier.success', 'Opération éffectuée avec succès');
                $rep['msg'] = '';
                $rep['etat'] = TRUE;
            }

            return new Response(json_encode($rep));
        }
        return new Response(json_encode($rep));
    }

}
