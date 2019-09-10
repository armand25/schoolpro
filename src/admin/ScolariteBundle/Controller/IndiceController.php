<?php

namespace admin\ScolariteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Response;
use \admin\UserBundle\Entity\Module;
use \admin\ScolariteBundle\Entity\Indice;
use \admin\UserBundle\Services\LoginManager;
use \Symfony\Component\HttpFoundation\Request;

class IndiceController extends Controller {

    
use \admin\UserBundle\ControllerModel\paramUtilTrait;
    
    
    public function __construct() {
        $this->response = new Response();
        $this->response->headers->addCacheControlDirective('no-cache', true);
        $this->response->headers->addCacheControlDirective('max-age', 0);
        $this->response->headers->addCacheControlDirective('must-revalidate', true);
        $this->response->headers->addCacheControlDirective('no-store', true);
        $this->moduleTitre = Module::MOD_CLASSE;
        $this->moduleDesc = Module::MOD_CLASSE_DESC;
        $this->logMessage = " [ IndiceController ] ";
        $this->description = "Controlleur qui gère les indices";
    }

    /**
     *  Afficle la liste des indices
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
    public function listeIndiceAction(Request $request) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage de la liste des indices";
            $locale ="fr";
        $valRetour = $this->gestionDroitUtil($request, $nomAction, $descAction,$this->moduleTitre,$this->moduleDesc);
        if($valRetour==1){
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }elseif($valRetour==2){
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }
        
        $em = $this->getDoctrine()->getManager();
        $indices = $em->getRepository($this->scolariteBundle . 'Indice')->getAllIndice();

        $this->data['indices'] = $indices;
        $this->data['locale'] = $locale;
        return $this->render($this->scolariteBundle . 'Indice:listeIndice.html.twig', $this->data, $this->response);
    }


    /**
     *  Formulaire d'ajout de indice
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
    public function ajoutIndiceAction(Request $request) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Ajout d'un nouveau indice";
         $locale ="fr";
        $valRetour = $this->gestionDroitUtil($request, $nomAction, $descAction,$this->moduleTitre,$this->moduleDesc);
        if($valRetour==1){
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }elseif($valRetour==2){
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }

        $indice = new Indice();
        $form = $this->createForm('\admin\ScolariteBundle\Form\IndiceType', $indice);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();

            if ($form->isValid()) {
                $criteria = array('libelleIndice' => $indice->getLibelleIndice());
                $oldIndice = $em->getRepository($this->scolariteBundle . 'Indice')->findOneBy($criteria);
                if ($oldIndice == NULL) {
                    //$indice->setDateInitialIndice(new \DateTime());
                    $em->persist($indice);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('indice.ajout.success', 'Ajout effectue avec succès');
                    return $this->redirect($this->generateUrl('app_admin_indices'));
                } else {
                    if ($oldIndice->getEtat() == \admin\UserBundle\Types\TypeEtat::SUPPRIME) {
                        $oldIndice->setEtat(\admin\UserBundle\Types\TypeEtat::ACTIF);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('indice.ajout.success', 'Ajout effectue avec succès');
                        return $this->redirect($this->generateUrl('app_admin_indices'));
                    }
                    $this->get('session')->getFlashBag()->add('indice.ajout.already.exist', 'Le indice ' . $indice->getNom() . ' existe déjà');
                }
            } else {
                $this->get('session')->getFlashBag()->add('indice.ajout.error', 'Formulaire invalide');
            }
        }

        $this->data['formuView'] = $form->createView();
        $this->data['indice'] = $indice;
        $this->data['locale'] = $locale;

        return $this->render($this->scolariteBundle . 'Indice:ajouterIndice.html.twig', $this->data, $this->response);
    }

    /*
     * Modifie un indice
     * @param type $idIndice
     * @return type
     */

    public function modifierIndiceAction(Request $request, $idIndice) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Modification d'un indice";
        //Gestion des droits
        $locale ="fr";
        $valRetour = $this->gestionDroitUtil($request, $nomAction, $descAction,$this->moduleTitre,$this->moduleDesc);
        if($valRetour==1){
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }elseif($valRetour==2){
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }
        
        $em = $this->getDoctrine()->getManager();
        $indice = $em->getRepository($this->scolariteBundle . 'Indice')->find($idIndice);
        if ($indice == NULL) {
            return $this->redirect($this->generateUrl('app_admin_indices'));
        }
        $form = $this->createForm('\admin\ScolariteBundle\Form\IndiceType', $indice);

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);
            if ($form->isValid()) {
                $criteria = array('libelleIndice' => $indice->getLibelleIndice());
                $oldIndice = $em->getRepository($this->scolariteBundle . 'Indice')->findOneBy($criteria);
                if ($oldIndice == NULL) {
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('indice.modifier.success', 'Modifications effectuées avec succès');
                    return $this->redirect($this->generateUrl('app_admin_indices'));
                } else {

                    /*
                     * S'il s'agit du mm indice ou k le indice est supprimé on effecute la modification
                     */
                    if (($oldIndice->getId() == $indice->getId()) || ($oldIndice->getEtat() == \admin\UserBundle\Types\TypeEtat::SUPPRIME)) {
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('indice.modifier.success', 'Modifications effectuées avec succès');
                        return $this->redirect($this->generateUrl('app_admin_indices'));
                    }
                    $this->get('session')->getFlashBag()->add('indice.modifier.already.exist', 'Le indice ' . $indice->getNom() . ' existe déjà');
                }
            } else {
                $this->get('session')->getFlashBag()->add('indice.modifier.error', 'Formulaire invalide');
            }
        }

        $this->data['formuView'] = $form->createView();
        $this->data['indice'] = $indice;
        $this->data['locale'] = $locale;
        $this->data['idIndice'] = $idIndice;

        return $this->render($this->scolariteBundle . 'Indice:modifierIndice.html.twig', $this->data, $this->response);
    }

    /*
     * Activation, suppression, désactivation de indice
     * @return Response
     */

    public function changerEtatIndiceAction(Request $request) {
        $rep = array('etat' => FALSE, 'msg' => 'Erreur survenue lors du traitement');
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Activation, suppression, désactivation de indice";
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idIndice'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $rep['msg'] = "Vous n'avez pas le droit d'activer, de supprimer ou de désactiver un indice";
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
                $indice = $em->getRepository($this->scolariteBundle . 'Indice')->find((int) $idS);
                if ($indice != NULL) {
                    $indice->setEtat($etat);
                    $em->flush();
                    $oneOk = TRUE;
                }
            }
            if ($oneOk) {
                $this->get('session')->getFlashBag()->add('indice.modifier.success', 'Opération éffectuée avec succès');
                $rep['msg'] = '';
                $rep['etat'] = TRUE;
            }

            return new Response(json_encode($rep));
        }
        return new Response(json_encode($rep));
    }

}
