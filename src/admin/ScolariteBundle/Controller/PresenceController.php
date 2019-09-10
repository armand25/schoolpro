<?php

namespace admin\ScolariteBundle\Controller;

use admin\ParamBundle\Types\TypeParametre;
use admin\ScolariteBundle\Entity\Eleve;
use admin\ScolariteBundle\Entity\EstParent;
use admin\ScolariteBundle\Entity\Media;
use admin\ScolariteBundle\Entity\SeTrouver;
use admin\UserBundle\Entity\CodeBase;
use admin\UserBundle\Entity\Utilisateur;
use admin\UserBundle\Entity\Profil;
use admin\UserBundle\Entity\ProfilRepository;
use admin\UserBundle\Entity\Module;
use admin\ScolariteBundle\Form\EleveType;
use admin\ScolariteBundle\Form\ModifierEleveType;
use admin\UserBundle\Types\TypeEtat;
use admin\UserBundle\Types\TypeParent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use \admin\UserBundle\Services\LoginManager;
use \admin\UserBundle\Types\TypeProfil;
use \Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;

class PresenceController extends Controller {

    use \admin\UserBundle\ControllerModel\paramUtilTrait;

    //use \admin\StockBundle\ControllerModel\stockTrait;

    public function __construct() {
        $this->response = new Response();
        $this->response->headers->addCacheControlDirective('no-cache', true);
        $this->response->headers->addCacheControlDirective('max-age', 0);
        $this->response->headers->addCacheControlDirective('must-revalidate', true);
        $this->response->headers->addCacheControlDirective('no-store', true);
        $this->logMessage = " [ EleveController ] ";
        $this->description = "Controlleur qui gère les élèves";
    }

    /*
     * Affiche la liste des eleves par parfil. si $idProfil = 0 alors on affiche les eleves de ts les profils
     * @param int $idProfil
     * @return type
     */

    public function listePresenceAction(Request $request, $idens, $classe,$ideleve, $iddecoupage,$datedeb, $datefin,$nbParPage, $pageActuelle,$typepresence) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage de la liste des présences faites pendant par les enseignants";
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
         * On vérifie si l'élève est connecté
         */
        $status = $loginManager->isConnecte($nomAction);
        if ($status['isConnecte']) {
            /*
             * Au cas ou l'élève est connecté on vérifie s'il nest pas innactif. S'il est innactif
             * on garde en mémoire flash la route actuelle pour effectuer une redirection lors de la prochaine connexion
             */
            if ($status['isInnactif']) {
                $routeName = $request->get('_route');
                $routeParams = $request->get('_route_params');
                $this->get('session')->getFlashBag()->add('restoreUrl', $this->generateUrl($routeName, $routeParams));
                $this->get('session')->getFlashBag()->add('ina', "Vous avez accusé un long moment d'inactivité");
                return $this->redirect($this->generateUrl('app_admin_user_logout'));
            }
            /*
             * Seuls les élèves admins ont le droit d'acceder à cette action
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_ABONNE, Module::MOD_GEST_ABONNE_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des présences");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }
        




        $em = $this->getDoctrine()->getManager();
        $annee = $sessionData['idannee'];
        $twigManager = $this->get('type_manager');
        
        if ($request->getMethod() == 'POST') {
            $idens = strtolower($request->get('idens_'));
            $request->attributes->set('idens_', $idens);

            
           
            $datefin = strtolower($request->get('datefin_'));
            $request->attributes->set('datefin_', $datefin);

            $datedeb = strtolower($request->get('datedeb_'));
            $request->attributes->set('datedeb_', $datedeb);

            $ideleve = strtolower($request->get('ideleve_'));
            $request->attributes->set('ideleve_', $ideleve);

            $classe = strtolower($request->get('classe_'));
            $request->attributes->set('classe_', $classe);
            
            $iddecoupage = strtolower($request->get('iddecoupage_'));
            $request->attributes->set('iddecoupage_', $iddecoupage);
            
            $typepresence = strtolower($request->get('typepresence_'));
            $request->attributes->set('typepresence_', $typepresence);
        
        }
        
        //$queryResult = $em->getRepository($this->scolariteBundle . 'Eleve')->getAllEleve($idProfil = 0, $nbParPage, $pageActuelle, $sessionData['id']);
       
       // $presences = $em->getRepository($this->scolariteBundle . 'Eleve')->getListeRechercheEleve($nom, $prenoms,$sexe, $classe, $degre, $niveau,$datedeb, $datefin,$annee=1, $nbParPage , $pageActuelle);
        $presences = $em->getRepository($this->scolariteBundle . 'Presence')->getListeRecherchePresence($idens, $classe,$ideleve, $iddecoupage,$datedeb, $datefin,$annee,$typepresence, $nbParPage, $pageActuelle);

//        $nbParPage = //$queryResult['nbParPage'];
//        $pageActuelle = $queryResult['pageActuelle'];
//        $nbTotal = $queryResult['nbTotal'];
//        $nbTotalPage = $queryResult['nbTotalPage'];
//
//        $eleves = $queryResult['data'];
//
//
//        $lastPage = $nbTotalPage;
//
//        $nextPage = $pageActuelle + 1;
//        if ($nextPage > $lastPage) {
//            $nextPage = $lastPage;
//        }
//        $previousPage = $pageActuelle - 1;
//        if ($previousPage < 1) {
//            $previousPage = 1;
//        }
//
//        $debutPagination = $pageActuelle - 2;
//        if ($debutPagination < 1) {
//            $debutPagination = 1;
//        }
//
//        $finPagination = $pageActuelle + 2;
//        if ($finPagination > $lastPage) {
//            $finPagination = $lastPage;
//        }

//        $this->data['finPagination'] = $finPagination;
//        $this->data['debutPagination'] = $debutPagination;
//        $this->data['previousPage'] = $previousPage;
        
        //Recuperation de la liste des classes
        $classes = $em->getRepository($this->scolariteBundle . 'Classe')->getAllActifClasse();

        // Recuperation de la liste des niveaux
        $decoupages = $em->getRepository($this->scolariteBundle . 'Decoupage')->getAllDecoupage();
        
        // Recuperation de la liste de degre
        $enseignants = $em->getRepository($this->userBundle . 'Utilisateur')->getAllUser(3);
                
        
        $this->data['datedeb_'] = $datedeb;
        $this->data['classes'] = $classes;
        $this->data['idens_'] = $idens;
        $this->data['datefin_'] = $datefin;
        $this->data['ideleve_'] = $ideleve;
        $this->data['typepresence_'] = $typepresence;
        $this->data['iddecoupage_'] = $iddecoupage;
        $this->data['classe_'] = $classe;
        $this->data['decoupages'] = $decoupages;
        $this->data['enseignants'] = $enseignants;
//        $this->data['lastPage'] = $lastPage;
//        $this->data['nbParPage'] = $nbParPage;
//        $this->data['pageActuelle'] = $pageActuelle;
//        $this->data['nbTotal'] = $nbTotal;
//        $this->data['nbTotalPage'] = $nbTotalPage;

        $this->data['presences'] = $presences;
        $this->data['locale'] = $locale;
        
        return $this->render($this->scolariteBundle . 'Presence:listePresence.html.twig', $this->data, $this->response);
    }    
    

    /*
     * Affiche la liste des eleves par parfil. si $idProfil = 0 alors on affiche les eleves de ts les profils
     * @param int $idProfil
     * @return type
     */

    public function listePresenceDetailAction(Request $request, $idpre,  $idens, $classe,$ideleve, $iddecoupage,$datedeb, $datefin,$nbParPage, $pageActuelle,$typepresence) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage de la liste des présences faites pendant par les enseignants";
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
         * On vérifie si l'élève est connecté
         */
        $status = $loginManager->isConnecte($nomAction);
        if ($status['isConnecte']) {
            /*
             * Au cas ou l'élève est connecté on vérifie s'il nest pas innactif. S'il est innactif
             * on garde en mémoire flash la route actuelle pour effectuer une redirection lors de la prochaine connexion
             */
            if ($status['isInnactif']) {
                $routeName = $request->get('_route');
                $routeParams = $request->get('_route_params');
                $this->get('session')->getFlashBag()->add('restoreUrl', $this->generateUrl($routeName, $routeParams));
                $this->get('session')->getFlashBag()->add('ina', "Vous avez accusé un long moment d'inactivité");
                return $this->redirect($this->generateUrl('app_admin_user_logout'));
            }
            /*
             * Seuls les élèves admins ont le droit d'acceder à cette action
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_ABONNE, Module::MOD_GEST_ABONNE_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des présences");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }

        $em = $this->getDoctrine()->getManager();
        $annee = $sessionData['idannee'];
        $twigManager = $this->get('type_manager');
        
        if ($request->getMethod() == 'POST') {
            $idens = strtolower($request->get('idens_'));
            $request->attributes->set('idens_', $idens);           
            $datefin = strtolower($request->get('datefin_'));
            $request->attributes->set('datefin_', $datefin);

            $datedeb = strtolower($request->get('datedeb_'));
            $request->attributes->set('datedeb_', $datedeb);

            $ideleve = strtolower($request->get('ideleve_'));
            $request->attributes->set('ideleve_', $ideleve);

            $classe = strtolower($request->get('classe_'));
            $request->attributes->set('classe_', $classe);
            
            $iddecoupage = strtolower($request->get('iddecoupage_'));
            $request->attributes->set('iddecoupage_', $iddecoupage);
            
            $typepresence = strtolower($request->get('typepresence_'));
            $request->attributes->set('typepresence_', $typepresence);
        
        }
        
        //$queryResult = $em->getRepository($this->scolariteBundle . 'Eleve')->getAllEleve($idProfil = 0, $nbParPage, $pageActuelle, $sessionData['id']);
       
       // $presences = $em->getRepository($this->scolariteBundle . 'Eleve')->getListeRechercheEleve($nom, $prenoms,$sexe, $classe, $degre, $niveau,$datedeb, $datefin,$annee=1, $nbParPage , $pageActuelle);
        $presences = $em->getRepository($this->scolariteBundle . 'Presence')->getListeRecherchePresence($idens, $classe,$ideleve, $iddecoupage,$datedeb, $datefin,$annee,$typepresence, $nbParPage, $pageActuelle, 1,$idpre);

        //Recuperation de la liste des classes
        $classes = $em->getRepository($this->scolariteBundle . 'Classe')->getAllActifClasse();

        // Recuperation de la liste des niveaux
        $decoupages = $em->getRepository($this->scolariteBundle . 'Decoupage')->getAllDecoupage();
        
        // Recuperation de la liste de degre
        $enseignants = $em->getRepository($this->userBundle . 'Utilisateur')->getAllUser(3);
                        
        $this->data['datedeb_'] = $datedeb;
        $this->data['classes'] = $classes;
        $this->data['idens_'] = $idens;
        $this->data['datefin_'] = $datefin;
        $this->data['ideleve_'] = $ideleve;
        $this->data['typepresence_'] = $typepresence;
        $this->data['iddecoupage_'] = $iddecoupage;
        $this->data['classe_'] = $classe;
        $this->data['decoupages'] = $decoupages;
        $this->data['enseignants'] = $enseignants;
//        $this->data['lastPage'] = $lastPage;
//        $this->data['nbParPage'] = $nbParPage;
//        $this->data['pageActuelle'] = $pageActuelle;
//        $this->data['nbTotal'] = $nbTotal;
//        $this->data['nbTotalPage'] = $nbTotalPage;

        $this->data['presences'] = $presences;
        $this->data['locale'] = $locale;
        
        return $this->render($this->scolariteBundle . 'Presence:listeDetailPresence.html.twig', $this->data, $this->response);
    }    
    
}
