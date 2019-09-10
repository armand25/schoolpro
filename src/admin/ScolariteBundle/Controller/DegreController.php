<?php

namespace admin\ScolariteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Response;
use \admin\UserBundle\Entity\Module;
use \admin\ScolariteBundle\Entity\Degre;
use \admin\UserBundle\Services\LoginManager;
use \Symfony\Component\HttpFoundation\Request;

class DegreController extends Controller {

    
use \admin\UserBundle\ControllerModel\paramUtilTrait;
    
    
    public function __construct() {
        $this->response = new Response();
        $this->response->headers->addCacheControlDirective('no-cache', true);
        $this->response->headers->addCacheControlDirective('max-age', 0);
        $this->response->headers->addCacheControlDirective('must-revalidate', true);
        $this->response->headers->addCacheControlDirective('no-store', true);
        $this->moduleTitre = Module::MOD_DEGRE;
        $this->moduleDesc = Module::MOD_DEGRE_DESC;
        $this->logMessage = " [ DegreController ] ";
        $this->description = "Controlleur qui gère les dégrés";
        
    }

    /**
     *  Afficle la liste des degres
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
    public function listeDegreAction(Request $request) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage de la liste des degres";
        $locale ="fr";
        $valRetour = $this->gestionDroitUtil($request, $nomAction, $descAction,$this->moduleTitre,$this->moduleDesc);
        if($valRetour==1){
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }elseif($valRetour==2){
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }        
        $em = $this->getDoctrine()->getManager();
        $degres = $em->getRepository($this->scolariteBundle . 'Degre')->getAllDegre();

        $this->data['degres'] = $degres;
        $this->data['locale'] = $locale;
        return $this->render($this->scolariteBundle . 'Degre:listeDegre.html.twig', $this->data, $this->response);
    }


    /**
     *  Formulaire d'ajout de degre
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
    public function ajoutDegreAction(Request $request) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Ajout d'un nouveau degre";
        $locale ="fr";
        $valRetour = $this->gestionDroitUtil($request, $nomAction, $descAction,$this->moduleTitre,$this->moduleDesc);
        if($valRetour==1){
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }elseif($valRetour==2){
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }        
        $degre = new Degre();
        $form = $this->createForm('\admin\ScolariteBundle\Form\DegreType', $degre);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();

            if ($form->isValid()) {
                $criteria = array('libelleDegre' => $degre->getLibelleDegre());
                $oldDegre = $em->getRepository($this->scolariteBundle . 'Degre')->findOneBy($criteria);
                if ($oldDegre == NULL) {
                    //$degre->setDateInitialDegre(new \DateTime());
                    $em->persist($degre);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('degre.ajout.success', 'Ajout effectue avec succès');
                    return $this->redirect($this->generateUrl('app_admin_degres'));
                } else {
                    if ($oldDegre->getEtat() == \admin\UserBundle\Types\TypeEtat::SUPPRIME) {
                        $oldDegre->setEtat(\admin\UserBundle\Types\TypeEtat::ACTIF);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('degre.ajout.success', 'Ajout effectue avec succès');
                        return $this->redirect($this->generateUrl('app_admin_degres'));
                    }
                    $this->get('session')->getFlashBag()->add('degre.ajout.already.exist', 'Le degre ' . $degre->getNom() . ' existe déjà');
                }
            } else {
                $this->get('session')->getFlashBag()->add('degre.ajout.error', 'Formulaire invalide');
            }
        }
        $this->data['formuView'] = $form->createView();
        $this->data['degre'] = $degre;
        $this->data['locale'] = $locale;

        return $this->render($this->scolariteBundle . 'Degre:ajouterDegre.html.twig', $this->data, $this->response);
    }

    /*
     * Modifie un degre
     * @param type $idDegre
     * @return type
     */

    public function modifierDegreAction(Request $request, $idDegre) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Modification d'un degre";
       $locale ="fr";
        $valRetour = $this->gestionDroitUtil($request, $nomAction, $descAction,$this->moduleTitre,$this->moduleDesc);
        if($valRetour==1){
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }elseif($valRetour==2){
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }        
        $em = $this->getDoctrine()->getManager();
        $degre = $em->getRepository($this->scolariteBundle . 'Degre')->find($idDegre);
        if ($degre == NULL) {
            return $this->redirect($this->generateUrl('app_admin_degres'));
        }
        $form = $this->createForm('\admin\ScolariteBundle\Form\DegreType', $degre);

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);
            if ($form->isValid()) {
                $criteria = array('libelleDegre' => $degre->getLibelleDegre());
                $oldDegre = $em->getRepository($this->scolariteBundle . 'Degre')->findOneBy($criteria);
                if ($oldDegre == NULL) {
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('degre.modifier.success', 'Modifications effectuées avec succès');
                    return $this->redirect($this->generateUrl('app_admin_degres'));
                } else {

                    /*
                     * S'il s'agit du mm degre ou k le degre est supprimé on effecute la modification
                     */
                    if (($oldDegre->getId() == $degre->getId()) || ($oldDegre->getEtat() == \admin\UserBundle\Types\TypeEtat::SUPPRIME)) {
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('degre.modifier.success', 'Modifications effectuées avec succès');
                        return $this->redirect($this->generateUrl('app_admin_degres'));
                    }
                    $this->get('session')->getFlashBag()->add('degre.modifier.already.exist', 'Le degre ' . $degre->getNom() . ' existe déjà');
                }
            } else {
                $this->get('session')->getFlashBag()->add('degre.modifier.error', 'Formulaire invalide');
            }
        }

        $this->data['formuView'] = $form->createView();
        $this->data['degre'] = $degre;
        $this->data['locale'] = $locale;
        $this->data['idDegre'] = $idDegre;

        return $this->render($this->scolariteBundle . 'Degre:modifierDegre.html.twig', $this->data, $this->response);
    }

    /*
     * Activation, suppression, désactivation de degre
     * @return Response
     */

    public function changerEtatDegreAction(Request $request) {
        $rep = array('etat' => FALSE, 'msg' => 'Erreur survenue lors du traitement');
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Activation, suppression, désactivation de degre";
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idDegre'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $rep['msg'] = "Vous n'avez pas le droit d'activer, de supprimer ou de désactiver un degre";
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
                $degre = $em->getRepository($this->scolariteBundle . 'Degre')->find((int) $idS);
                if ($degre != NULL) {
                    $degre->setEtat($etat);
                    $em->flush();
                    $oneOk = TRUE;
                }
            }
            if ($oneOk) {
                $this->get('session')->getFlashBag()->add('degre.modifier.success', 'Opération éffectuée avec succès');
                $rep['msg'] = '';
                $rep['etat'] = TRUE;
            }

            return new Response(json_encode($rep));
        }
        return new Response(json_encode($rep));
    }

}
