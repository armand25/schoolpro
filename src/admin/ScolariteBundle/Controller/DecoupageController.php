<?php

namespace admin\ScolariteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Response;
use \admin\UserBundle\Entity\Module;
use \admin\ScolariteBundle\Entity\Decoupage;
use \admin\UserBundle\Services\LoginManager;
use \Symfony\Component\HttpFoundation\Request;

class DecoupageController extends Controller {

    
use \admin\UserBundle\ControllerModel\paramUtilTrait;
    
    
    public function __construct() {
        $this->response = new Response();
        $this->response->headers->addCacheControlDirective('no-cache', true);
        $this->response->headers->addCacheControlDirective('max-age', 0);
        $this->response->headers->addCacheControlDirective('must-revalidate', true);
        $this->response->headers->addCacheControlDirective('no-store', true);
        $this->logMessage = " [ DecoupageController ] ";
        $this->moduleTitre = Module::MOD_DECOUP;
        $this->moduleDesc = Module::MOD_DECOUP_DESC;
        $this->description = "Controlleur qui gère les découpages";
    }

    /**
     *  Afficle la liste des decoupages
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
    public function listeDecoupageAction(Request $request) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage de la liste des decoupages";
        $locale ="fr";
        $valRetour = $this->gestionDroitUtil($request, $nomAction, $descAction,$this->moduleTitre,$this->moduleDesc);
        if($valRetour==1){
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }elseif($valRetour==2){
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }
        $em = $this->getDoctrine()->getManager();
        $decoupages = $em->getRepository($this->scolariteBundle . 'Decoupage')->getAllDecoupage();

        $this->data['decoupages'] = $decoupages;
        $this->data['locale'] = $locale;
        return $this->render($this->scolariteBundle . 'Decoupage:listeDecoupage.html.twig', $this->data, $this->response);
    }


    /**
     *  Formulaire d'ajout de decoupage
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
    public function ajoutDecoupageAction(Request $request) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Ajout d'un nouveau decoupage";
        $locale ="fr";
        $valRetour = $this->gestionDroitUtil($request, $nomAction, $descAction,$this->moduleTitre,$this->moduleDesc);
        if($valRetour==1){
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }elseif($valRetour==2){
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }

        $decoupage = new Decoupage();
        $form = $this->createForm('\admin\ScolariteBundle\Form\DecoupageType', $decoupage);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();

            if ($form->isValid()) {
                $criteria = array('libelleDecoupage' => $decoupage->getLibelleDecoupage());
                $oldDecoupage = $em->getRepository($this->scolariteBundle . 'Decoupage')->findOneBy($criteria);
                if ($oldDecoupage == NULL) {
                    //$decoupage->setDateInitialDecoupage(new \DateTime());
                    $em->persist($decoupage);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('decoupage.ajout.success', 'Ajout effectue avec succès');
                    return $this->redirect($this->generateUrl('app_admin_decoupages'));
                } else {
                    if ($oldDecoupage->getEtat() == \admin\UserBundle\Types\TypeEtat::SUPPRIME) {
                        $oldDecoupage->setEtat(\admin\UserBundle\Types\TypeEtat::ACTIF);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('decoupage.ajout.success', 'Ajout effectue avec succès');
                        return $this->redirect($this->generateUrl('app_admin_decoupages'));
                    }
                    $this->get('session')->getFlashBag()->add('decoupage.ajout.already.exist', 'Le decoupage ' . $decoupage->getNom() . ' existe déjà');
                }
            } else {
                $this->get('session')->getFlashBag()->add('decoupage.ajout.error', 'Formulaire invalide');
            }
        }

        $this->data['formuView'] = $form->createView();
        $this->data['decoupage'] = $decoupage;
        $this->data['locale'] = $locale;

        return $this->render($this->scolariteBundle . 'Decoupage:ajouterDecoupage.html.twig', $this->data, $this->response);
    }

    /*
     * Modifie un decoupage
     * @param type $idDecoupage
     * @return type
     */

    public function modifierDecoupageAction(Request $request, $idDecoupage) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Modification d'un decoupage";
               $locale ="fr";
        $valRetour = $this->gestionDroitUtil($request, $nomAction, $descAction,$this->moduleTitre,$this->moduleDesc);
        if($valRetour==1){
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }elseif($valRetour==2){
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }
        $em = $this->getDoctrine()->getManager();
        $decoupage = $em->getRepository($this->scolariteBundle . 'Decoupage')->find($idDecoupage);
        if ($decoupage == NULL) {
            return $this->redirect($this->generateUrl('app_admin_decoupages'));
        }
        $form = $this->createForm('\admin\ScolariteBundle\Form\DecoupageType', $decoupage);

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);
            if ($form->isValid()) {
                $criteria = array('libelleDecoupage' => $decoupage->getLibelleDecoupage());
                $oldDecoupage = $em->getRepository($this->scolariteBundle . 'Decoupage')->findOneBy($criteria);
                if ($oldDecoupage == NULL) {
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('decoupage.modifier.success', 'Modifications effectuées avec succès');
                    return $this->redirect($this->generateUrl('app_admin_decoupages'));
                } else {

                    /*
                     * S'il s'agit du mm decoupage ou k le decoupage est supprimé on effecute la modification
                     */
                    if (($oldDecoupage->getId() == $decoupage->getId()) || ($oldDecoupage->getEtat() == \admin\UserBundle\Types\TypeEtat::SUPPRIME)) {
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('decoupage.modifier.success', 'Modifications effectuées avec succès');
                        return $this->redirect($this->generateUrl('app_admin_decoupages'));
                    }
                    $this->get('session')->getFlashBag()->add('decoupage.modifier.already.exist', 'Le decoupage ' . $decoupage->getNom() . ' existe déjà');
                }
            } else {
                $this->get('session')->getFlashBag()->add('decoupage.modifier.error', 'Formulaire invalide');
            }
        }

        $this->data['formuView'] = $form->createView();
        $this->data['decoupage'] = $decoupage;
        $this->data['locale'] = $locale;
        $this->data['idDecoupage'] = $idDecoupage;

        return $this->render($this->scolariteBundle . 'Decoupage:modifierDecoupage.html.twig', $this->data, $this->response);
    }

    /*
     * Activation, suppression, désactivation de decoupage
     * @return Response
     */

    public function changerEtatDecoupageAction(Request $request) {
        $rep = array('etat' => FALSE, 'msg' => 'Erreur survenue lors du traitement');
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Activation, suppression, désactivation de decoupage";
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
            $rep['msg'] = "Vous n'avez pas le droit d'activer, de supprimer ou de désactiver un decoupage";
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
                $decoupage = $em->getRepository($this->scolariteBundle . 'Decoupage')->find((int) $idS);
                if ($decoupage != NULL) {
                    $decoupage->setEtat($etat);
                    $em->flush();
                    $oneOk = TRUE;
                }
            }
            if ($oneOk) {
                $this->get('session')->getFlashBag()->add('decoupage.modifier.success', 'Opération éffectuée avec succès');
                $rep['msg'] = '';
                $rep['etat'] = TRUE;
            }

            return new Response(json_encode($rep));
        }
        return new Response(json_encode($rep));
    }

}
