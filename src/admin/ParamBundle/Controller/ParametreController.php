<?php

namespace admin\ParamBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Response;
use \admin\ParamBundle\Types\TypeDonnees;
use \admin\UserBundle\Entity\Module;
use \admin\UserBundle\Services\LoginManager;

class ParametreController extends Controller {

    /*
     * Nom du logo par defaut
     */

    CONST DEFAULT_LOGO_NAME = 'defaultLogo.png';
    private $logMessage;

    public function __construct() {
        $this->response = new Response;
        $this->response->headers->addCacheControlDirective('no-cache', true);
        $this->response->headers->addCacheControlDirective('max-age', 0);
        $this->response->headers->addCacheControlDirective('must-revalidate', true);
        $this->response->headers->addCacheControlDirective('no-store', true);
        $this->description = "Controlleur qui gère les paramètres";
    }

    /*
     * 
     * Affiche la liste des paramètres
     * 
     * @author @@@
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * 
     * @return Response
     */

    public function parametresAction() {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage de la liste des paramètres";
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
        $sessionData = $this->infosConnecte();
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
                $routeName = $this->getRequest()->get('_route');
                $routeParams = $this->getRequest()->get('_route_params');
                $this->get('session')->getFlashBag()->add('restoreUrl', $this->generateUrl($routeName, $routeParams));
                $this->get('session')->getFlashBag()->add('ina', "Vous avez accusé un long moment d'inactivité");
                return $this->redirect($this->generateUrl('app_admin_user_logout'));
            }
            /*
             * Seuls les utilisateurs admins ont le droit d'acceder à cette action
             */
            if (!$status['isUser']) {
                return $this->redirect($this->generateUrl('app_admin_user_logout'));
            }
        } else {
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }

        /*
         * Gestion des droits
         */
        if (!$loginManager->getOrSetActions(Module::MOD_PARAM, Module::MOD_PARAM_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder aux paramètres");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }

        $parametreManager = $this->get('parametre_manager');
        $params = $parametreManager->getAllParametres();

        $this->data['params'] = $params;
        $this->data['locale'] = $locale;
        return $this->render($this->paramBundle . 'Param:listeParam.html.twig', $this->data);
    }

    /*
     * 
     *  Ajoute un parametre
     * 
     * @author @@@
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * 
     * @return Response
     */

    public function ajoutParametreAction() {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Ajout d'un parametre";
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
        $sessionData = $this->infosConnecte();
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
                $msg = "Vous avez accusé un long moment d'inactivité. Veuillez-vous connecter de nouveau";
                $loginManager->logout(LoginManager::SESSION_DATA_NAME);
                return new Response($msg);
            }
            /*
             * Seuls les utilisateurs admins ont le droit d'acceder à cette action
             */
            if (!$status['isUser']) {
                return new Response("Vous n'avez pas le droit d'ajouter un paramètre");
            }
        } else {
            return new Response("");
        }

        /*
         * Gestion des droits
         */
        if (!$loginManager->getOrSetActions(Module::MOD_PARAM, Module::MOD_PARAM_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            return new Response("Vous n'avez pas le droit d'ajouter un paramètre");
        }

        $typesDonnees = array();
        $typesDonnees[] = array('libelle' => 'Type Entier', 'val' => TypeDonnees::INT);
        $typesDonnees[] = array('libelle' => 'Type Date', 'val' => TypeDonnees::DATE);
        $typesDonnees[] = array('libelle' => 'Type Date time', 'val' => TypeDonnees::DATETIME);
        $typesDonnees[] = array('libelle' => 'Type Chaine de caractère', 'val' => TypeDonnees::VARCHAR);
        $typesDonnees[] = array('libelle' => 'Type Chaine de caractère long', 'val' => TypeDonnees::TEXT);
        $typesDonnees[] = array('libelle' => 'Type booléen', 'val' => TypeDonnees::BOOLEAN);

        $parametreManager = $this->get('parametre_manager');

        $nom = $parametreManager->getDefaultNewName();

        $valeur = '';
        $typeDonnee = TypeDonnees::INT;
        $libelle = '';
        $description = '';

        $request = $this->getRequest();
        if ($request->isMethod('POST')) {
            $nom = (int) $request->request->get('nomPram');
            $typeParam = (int) $request->request->get('typeInfoPram');
            $valeur = $request->request->get('valeurPram');
            $typeDonnee = (int) $request->request->get('typeDonneePram');
            $libelle = $request->request->get('libellePram');
            $description = $request->request->get('descriptionPram');
            $formValid = TRUE;
            if ($nom == 0) {
                $this->get('session')->getFlashBag()->add('param.ajout.error.nom', 'Nom invalide');
                $formValid = FALSE;
            }
            if ($typeDonnee == 0) {
                $this->get('session')->getFlashBag()->add('param.ajout.error.type_onne', 'Type de donnée invalide');
                $formValid = FALSE;
            }

            if (strlen(trim($libelle)) == 0 || strlen(trim($libelle)) == 0) {
                $this->get('session')->getFlashBag()->add('param.ajout.error.libelle_desc', 'Libelle ou description invalide');
                $formValid = FALSE;
            }

            if ($formValid) {
                $oldParam = $parametreManager->getParametre($nom);
                if ($oldParam != NULL) {
                    $this->get('session')->getFlashBag()->add('param.ajout.error.existe_deja', 'Parametre du même nom existe déja');
                    $formValid = FALSE;
                } else {
                    $etat = $parametreManager->setParametre($nom, $valeur, $typeDonnee, $libelle, $description, $typeParam);
                    if ($etat) {
                        $this->get('session')->getFlashBag()->add('param.ajout.sucess', 'Paramètre ajouté avec succès');
                        return $this->redirect($this->generateUrl('app_param_liste', array('locale' => $locale)));
                    }
                    $formValid = FALSE;
                    $this->get('session')->getFlashBag()->add('param.ajout.error', 'Formualire invalide');
                }
            }
        }

        $this->data['nom'] = $nom;
        $this->data['valeur'] = $valeur;
        $this->data['typeDonnee'] = $typeDonnee;
        $this->data['libelle'] = $libelle;
        $this->data['description'] = $description;
        $this->data['locale'] = $locale;
        $this->data['typesDonnees'] = $typesDonnees;

        return $this->render($this->paramBundle . 'Param:ajoutParam.html.twig', $this->data);
    }

 
    /*
     * 
     *  Modifie un parametre
     * 
     * @author @@@
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @param int $nomParam : Nom du parametre à modifier
     * @return Response
     */
    public function modifierParametreAction($nomParam) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Modification de la valeur d'un parametre";
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
        $sessionData = $this->infosConnecte();
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
                $msg = "Vous avez accusé un long moment d'inactivité. Veuillez-vous connecter de nouveau";
                $loginManager->logout(LoginManager::SESSION_DATA_NAME);
            }
            /*
             * Seuls les utilisateurs admins ont le droit d'acceder à cette action
             */
            if (!$status['isUser']) {
                return new Response("Vous n'avez pas le droit de modifier un paramètre");
            }
        } else {
            return new Response("");
        }

        /*
         * Gestion des droits
         */
        if (!$loginManager->getOrSetActions(Module::MOD_PARAM, Module::MOD_PARAM_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            return new Response("Vous n'avez pas le droit de modifier un paramètre");
        }
        $parametreManager = $this->get('parametre_manager');

        $parametre = $parametreManager->getParametre($nomParam);
        if ($parametre == NULL) {
            return new Response("Informations introuvables");
        }


        $request = $this->getRequest();
        if ($request->isMethod('POST')) {
            $libelle = $request->request->get('libelle_' . $nomParam);
            $description = $request->request->get('description_' . $nomParam);
            $valeur = $request->request->get('valeur_' . $nomParam);
            $typeParam = (int) $request->request->get('typeInfoPram_'. $nomParam);


            $formValid = TRUE;
            if (strlen(trim($libelle)) == 0 || strlen(trim($libelle)) == 0) {
                $this->get('session')->getFlashBag()->add('param.ajout.error.libelle_desc', 'Libelle ou description invalide');
                $formValid = FALSE;
            }

            if ($formValid) {
                $oldParam = $parametreManager->getParametre($nomParam);
                if ($oldParam == NULL) {
                    $this->get('session')->getFlashBag()->add('param.modifier.null', 'Paramètre introuvable');
                    return $this->redirect($this->generateUrl('app_param_liste', array('locale' => $locale)));
                } else {
                    $etat = $parametreManager->setParametre($nomParam, $valeur, $parametre->getTypeDonnee(), $libelle, $description, $typeParam);
                    if ($etat) {
                        $this->get('session')->getFlashBag()->add('param.modif.sucess', 'Paramètre modifié avec succès');
                        return $this->redirect($this->generateUrl('app_param_liste', array('locale' => $locale)));
                    }
                    $formValid = FALSE;
                    $this->get('session')->getFlashBag()->add('param.ajout.error', 'Formualire invalide');
                }
            }
        }

        $this->data['parametre'] = $parametre;
        $this->data['nom'] = $nomParam;
        $this->data['locale'] = $locale;

        return $this->render($this->paramBundle . 'Param:modifierParam.html.twig', $this->data);
    }

    /*
     * 
     *  Restauration des paramètres par défaut
     * 
     * @author @@@
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @return Response
     */
    public function resetAllParamAction() {
        $rep = array('etat' => FALSE, 'msg' => 'Erreur survenue lors du traitement');
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Restauration des paramètres par défaut";
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
        $sessionData = $this->infosConnecte();
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
        if (!$loginManager->getOrSetActions(Module::MOD_PARAM, Module::MOD_PARAM_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $rep['msg'] = "Vous n'avez pas le droit de restaurer les paramètres par défaut";
            return new Response(json_encode($rep));
        }

//        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if (($request->isXmlHttpRequest()) && ($request->getMethod() == 'POST')) {
            $loginManager->addDefaultConfig();
            $this->get('session')->getFlashBag()->add('reset.param.success', 'Restauration des paramètres par défaut éffectuée avec succès');
            $rep['msg'] = '';
            $rep['etat'] = TRUE;
            return new Response(json_encode($rep));
        }
        return new Response(json_encode($rep));
    }

}
