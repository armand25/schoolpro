<?php

namespace admin\ScolariteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Response;
use \admin\UserBundle\Entity\Module;
use \admin\ScolariteBundle\Entity\Classe;
use admin\ScolariteBundle\Entity\Note;
use admin\ScolariteBundle\Entity\Presence;
use admin\ScolariteBundle\Entity\DetailPresence;
use admin\ScolariteBundle\Entity\DetailsNote;
use admin\ScolariteBundle\Entity\RecapNote;
use admin\ScolariteBundle\Entity\RecapMoyenneGenerale;
use admin\ScolariteBundle\Entity\FaireCours;
use \admin\UserBundle\Services\LoginManager;
use \Symfony\Component\HttpFoundation\Request;

class ClasseController extends Controller {

    use \admin\UserBundle\ControllerModel\paramUtilTrait;

    public function __construct() {
        $this->response = new Response();
        $this->response->headers->addCacheControlDirective('no-cache', true);
        $this->response->headers->addCacheControlDirective('max-age', 0);
        $this->response->headers->addCacheControlDirective('must-revalidate', true);
        $this->response->headers->addCacheControlDirective('no-store', true);
        $this->logMessage = " [ ClasseController ] ";
        $this->description = "Controlleur qui gère les utilisateurs";
    }

    /**
     *  Afficle la liste des classes
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
    public function listeClasseAction(Request $request) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage de la liste des classes";
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
                $routeName = $request->get('_route');
                $routeParams = $request->get('_route_params');
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des classes");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }

       $idannee = $sessionData['idannee'];

        $em = $this->getDoctrine()->getManager();
        //$classes = $em->getRepository($this->scolariteBundle . 'Classe')->getAllClasse();
        
        if($sessionData['idProfil']==3){
            $classes = $em->getRepository($this->scolariteBundle . 'Classe')->getEnseignantHisClasse($sessionData['id'], $idannee);
        }else{
           $classes = $em->getRepository($this->scolariteBundle . 'Classe')->getAllClasse(); 
        }
        $this->data['classes'] = $classes;
        $this->data['locale'] = $locale;
        return $this->render($this->scolariteBundle . 'Classe:listeClasse.html.twig', $this->data, $this->response);
    }

    /**
     *  Afficle la liste des classes
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
    public function listeInfoNoteEleveClasseAction(Request $request, $idClasse, $idMatiere, $idDecoupage, $idEleve) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage des notes liés aux élèves de la classse";
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
                $routeName = $request->get('_route');
                $routeParams = $request->get('_route_params');
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'afficher les notes liés aux élèves de la classse");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }
        $idannee = $sessionData['idannee'];

        $em = $this->getDoctrine()->getManager();
        $classes = $em->getRepository($this->scolariteBundle . 'Classe')->getAllClasse();
        
        $objetAnneeScolaire = $em->getRepository($this->scolariteBundle . 'AnneeScolaire')->find($idannee);
        $tableauDesDonneesNote = array();
        $allMatiere= array();
        $allTypeExamen= array();

        //Cas ou on a pas choisi de decoupage on choisit le decoupage actif
        
        
        //recuperation des eleves de la classe encours
        $listeEleveClasse = $em->getRepository($this->scolariteBundle . 'Eleve')->getListeSeTrouverEleveClasse($idClasse, $idannee);
        $unUtil = $em->getRepository($this->userBundle . 'Utilisateur')->find($sessionData['id']);
        $listeEleveClasse = array();
        if($sessionData['idProfil']==4){
            
            $unEleve = $em->getRepository($this->scolariteBundle . 'Eleve')->find($idEleve);
            $siTestObjetUtl = $em->getRepository($this->scolariteBundle . 'EstParent')->findOneBy(array('utilisateur'=>$unUtil,'eleve'=>$unEleve));
            if(count($siTestObjetUtl)==0){
                return $this->redirect($this->generateUrl('app_admin_user_index'));
            }
            
            $listeEleveClasse[0] = $siTestObjetUtl->getEleve();
             $classe = $em->getRepository($this->scolariteBundle . 'Eleve')->getClasseEnCours($idEleve, $idannee);
             $idClasse = $classe[0]->getId();
        }elseif($sessionData['idProfil']==5){
            
            $listeEleveClasse[0] = $unUtil->getEleve();
             //var_dump(count($listeEleveClasse));exit;
            $classe = $em->getRepository($this->scolariteBundle . 'Eleve')->getClasseEnCours($unUtil->getEleve()->getId(), $idannee);
            $idClasse = $classe[0]->getId();
            
           
        }
        if($sessionData['idProfil']==5 || $sessionData['idProfil']==4){
             $objetSeTrouver = $em->getRepository($this->scolariteBundle . 'seTrouver')->findOneBy(array('eleve'=> $listeEleveClasse[0],'classe'=> $idClasse, 'anneescolaire'=>$objetAnneeScolaire));
        }
        //var_dump($idClasse);exit;
        $decoupActif = $em->getRepository($this->scolariteBundle . 'Decoupage')->getDecoupageActifClasse($idClasse);
        if($idDecoupage ==0){
            $idDecoupage = $decoupActif[0]['id'];
        }
       $uneDecoupage = $em->getRepository($this->scolariteBundle . 'Decoupage')->find($idDecoupage);
	   $listeDecoupage = $em->getRepository($this->scolariteBundle . 'Decoupage')->getDecoupageClasse($idClasse);
//        if ($idMatiere != 0) {
            $allMatiere = $em->getRepository($this->scolariteBundle . 'Matiere')->getAllActifMatiereClasse($idClasse) ;  //findBy(array('id' => $idMatiere));
//        } else {
//        }
        // recuperation des notes par rapport au élève de la classe
       

        foreach ($allMatiere as $uneMatiere) {
            //Objet matiere
            $objetMatiere = $em->getRepository($this->scolariteBundle . 'Matiere')->find($uneMatiere['id']);
            
            foreach ($listeEleveClasse as $infoEleve) {
                $allTypeExamen = $em->getRepository($this->scolariteBundle . 'TypeExamen')->findAll();
                $i = 0;
                foreach ($allTypeExamen as $unTypeExamen) { 
                    //var_dump(count($infoEleve),count($objetMatiere),count($unTypeExamen),count($uneDecoupage));exit;
                    if($sessionData['idProfil']==5 || $sessionData['idProfil']==4){
                         $tableauDesDonneesNote[$uneMatiere['id']][$infoEleve->getId()][$unTypeExamen->getId()] = $em->getRepository($this->scolariteBundle . 'Note')->findBy(array('setrouver' => $objetSeTrouver, 'matiere' => $objetMatiere, 'typeexamen' => $unTypeExamen, 'decoupage' => $uneDecoupage));
                    }else{
                         $tableauDesDonneesNote[$uneMatiere['id']][$infoEleve->getId()][$unTypeExamen->getId()] = $em->getRepository($this->scolariteBundle . 'Note')->findBy(array('setrouver' => $infoEleve, 'matiere' => $objetMatiere, 'typeexamen' => $unTypeExamen, 'decoupage' => $uneDecoupage));                     
                    } 
                    $i++;
                }
            }
        }
        //var_dump($tableauDesDonneesNote);exit;
        if($sessionData['idProfil']==5 || $sessionData['idProfil']==4 ){
        //Recuperation des informations concernant les moyennes generaux des eleve
            $listeRecapMoyenneGenerale = $em->getRepository($this->scolariteBundle . 'RecapMoyenneGenerale')->getRecapNotesEleve($uneDecoupage->getId(),$sessionData['etablissement'],$idannee,$objetSeTrouver->getId());
        }else{
            $listeRecapMoyenneGenerale = $em->getRepository($this->scolariteBundle . 'RecapMoyenneGenerale')->getRecapNotesEleve($uneDecoupage->getId(),$sessionData['etablissement'],$idannee);
        }

        $this->data['listeEleveClasse'] = $listeEleveClasse;
        $this->data['allMatiere'] = $allMatiere;
        $this->data['allTypeExamen'] = $allTypeExamen;
		$this->data['listeDecoupage'] = $listeDecoupage;
		$this->data['idDecoupage'] = $idDecoupage;
        $this->data['classes'] = $classes;
        $this->data['idProfil'] = $sessionData['idProfil'];
        $this->data['tableauDesDonneesNote'] = $tableauDesDonneesNote;
        $this->data['listeRecapMoyenneGenerale'] = $listeRecapMoyenneGenerale;
        $this->data['locale'] = $locale;
        return $this->render($this->scolariteBundle . 'Classe:listeInfoNoteEleveClasse.html.twig', $this->data, $this->response);
    }

    /**
     *  Ajouter les notes des eleves d'une classe
     * 
     * @author armand.tevi@gmail.com
     *
     * @copyright Master Internationnal  - UTBM/IAI/CIC/
     *
     * @version 1
     * 
     *    
     * @access  public
     *
     * @return Response
     */
    public function ajoutNoteEleveClasseAction(Request $request, $idClasse) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage de la liste des classes";
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
                $routeName = $request->get('_route');
                $routeParams = $request->get('_route_params');
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des classes");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }
        $idannee = $sessionData['idannee'];
        $em = $this->getDoctrine()->getManager();
        
        
        
        $enseignants = $em->getRepository($this->userBundle . 'Utilisateur')->find($sessionData['id']);
        //$enseignants = $em->getRepository($this->scolariteBundle . 'Classe')->getClasseEnseignant($idClasse, $idannee);
        $typeexamens = $em->getRepository($this->scolariteBundle . 'TypeExamen')->getAllActifTypeExamen();

        $idenseignant =$sessionData['id'] ;  //$request->get('id-enseignant');
        $idmatiere = $request->get('id-matiere');
        $idtypeexamen = $request->get('id-typeexamen');
        // var_dump($idenseignant);exit;

        $listeEleveClasse = $em->getRepository($this->scolariteBundle . 'Eleve')->getListeEleveClasse($idClasse, $idannee,$sessionData['etablissement']);

             $listeMatiere = $em->getRepository($this->userBundle . 'Utilisateur')->getAllMatiereDoUser($idenseignant);

        if ($request->isMethod('POST')) {
            
            $objetEnseign = $em->getRepository($this->userBundle . 'Utilisateur')->find($idenseignant);

            $objetAnnee = $em->getRepository($this->scolariteBundle . 'AnneeScolaire')->find($idannee);
            $objetMatiere = $em->getRepository($this->scolariteBundle . 'Matiere')->find($idmatiere);
            $objetTypeexamen = $em->getRepository($this->scolariteBundle . 'TypeExamen')->find($idtypeexamen);
            $objetClasse = $em->getRepository($this->scolariteBundle . 'Classe')->find($idClasse);
            $allTypeExamen = $em->getRepository($this->scolariteBundle . 'TypeExamen')->findAll();

            //Recuperation du decoupage
            $objetConcerner = $em->getRepository($this->scolariteBundle . 'Concerner')->findOneBy(array('anneescolaire' => $objetAnnee, 'enseignement' => $objetClasse->getFiliere()->getEnseignement()));
            $objetActifDecoupage = $em->getRepository($this->scolariteBundle . 'Decoupage')->findOneBy(array('typedecoupage' => $objetConcerner->getTypedecoupage(), 'etatDecoupage' => 1));

            foreach ($listeEleveClasse as $unEleve) {


                $objetSeTrouver = $em->getRepository($this->scolariteBundle . 'SeTrouver')->findOneBy(array('eleve' => $unEleve, 'anneescolaire' => $objetAnnee, 'classe' => $objetClasse));
                $testAncienNote = $em->getRepository($this->scolariteBundle . 'Note')->findOneBy(array('eleve' => $unEleve, 'setrouver' => $objetSeTrouver, 'matiere' => $objetMatiere, 'decoupage' => $objetActifDecoupage, 'typeexamen' => $objetTypeexamen));

//                    if($testAncienNote ==NULL){
                $noteEleve = $request->get('note' . $unEleve->getId());
                $uneNote = new Note();
                $uneNote->setNote($noteEleve);
                $uneNote->setEleve($unEleve);
                $uneNote->setDecoupage($objetActifDecoupage);
                $uneNote->setUtilisateur($objetEnseign);

                $uneNote->setMatiere($objetMatiere);
                $uneNote->setSetrouver($objetSeTrouver);
                $uneNote->setTypeexamen($objetTypeexamen);
                $em->persist($uneNote);
                $em->flush();

                $this->traiteMoyenneMatiere($allTypeExamen, $objetSeTrouver, $objetActifDecoupage, $objetMatiere);
                $this->traiteMoyenneGenerale($allTypeExamen, $objetSeTrouver, $objetActifDecoupage);
            }

            //Rang Moyenne De Classe
            $this->getRangByClasse($em, $idClasse, $objetAnnee->getId(), $objetActifDecoupage->getId(), 1);
            //Rang moyenne de classe et compo combiné
            $this->getRangByClasse($em, $idClasse, $objetAnnee->getId(), $objetActifDecoupage->getId(), 2);
            //Rang Moyenne generale
            $this->getRangByClasse($em, $idClasse, $objetAnnee->getId(), $objetActifDecoupage->getId(), 3);
        }
//Calculer les rangs

        $this->data['enseignants'] = $enseignants;
        $this->data['listeMatiere'] = $listeMatiere;
        $this->data['listeEleveClasse'] = $listeEleveClasse;
        
        $this->data['locale'] = $locale;
        $this->data['idClasse'] = $idClasse;
        $this->data['typeexamens'] = $typeexamens;
        return $this->render($this->scolariteBundle . 'Classe:ajoutNoteEleveClasse.html.twig', $this->data, $this->response);
    }
    

    /**
     *  Faire l'appel des eleves d'une classe
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
    public function presenceEleveClasseAction(Request $request, $idClasse) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Page de présence des élèves d'une classe ";
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
                $routeName = $request->get('_route');
                $routeParams = $request->get('_route_params');
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la page de présence");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }
        $idannee = $sessionData['idannee'];
        $em = $this->getDoctrine()->getManager();
        $enseignants = $em->getRepository($this->scolariteBundle . 'Classe')->getClasseEnseignant($idClasse, $idannee);
        $typeexamens = $em->getRepository($this->scolariteBundle . 'TypeExamen')->getAllActifTypeExamen();

       
        
        $idEns =$sessionData['id'];
        
        //Enseignant qui faire la matiere 
         
        //$idMat =1;
        //Recuperation de l'id de l'utilisateur en cours 
        $allHeureEnCours = $em->getRepository($this->scolariteBundle . 'HeureJournee')->findAll();            
        $allCodeJourEncours = $em->getRepository($this->scolariteBundle . 'Jour')->findAll();
        $allEnseignant = $em->getRepository($this->userBundle . 'Utilisateur')->findAll();
//        if($idMatiere==0){
//            $idMatiere = $idMat;
//        }
       $idFaireCours =$this->getUtilIdFaireCoursACours($em,$idEns,\admin\UserBundle\Types\TypeProfil::PROFIL_ENSEIGANT);
        
        $listeEleveClasse = $em->getRepository($this->scolariteBundle . 'Eleve')->getListeEleveClasse($idClasse, $idannee,$sessionData['etablissement']);
        if ($request->isMethod('POST')) {            
            if($sessionData['idProfil'] == \admin\UserBundle\Types\TypeProfil::PROFIL_UTILISATEUR){
                    $idEns = $request->get('id-enseignant');
                    $idHeure = $request->get('id-heure');
                    $idJour = $request->get('id-jour');
                    //$idMatiere = $request->get('id-matiere');
                    $dateJour = $request->get('date-appel');
                    $idMatiere = $this->getMatIdACours($em,$idEns,3,$idHeure,$idJour);
                    $objetHeureEnCours = $em->getRepository($this->scolariteBundle . 'HeureJournee')->find($idHeure);            
                    $objetCodeJourEncours = $em->getRepository($this->scolariteBundle . 'Jour')->find($idJour);               
             }
            $objetEnseignant = $em->getRepository($this->userBundle . 'Utilisateur')->find($idEns);
            $objetAnnee = $em->getRepository($this->scolariteBundle . 'AnneeScolaire')->find($idannee);            
            $objetClasse = $em->getRepository($this->scolariteBundle . 'Classe')->find($idClasse);            
           // $objetMatiere = $em->getRepository($this->scolariteBundle . 'Matiere')->find($idMatiere);
                
            //Recuperation de l'objet est enseigné           
            //$objetEstEnseigne = $em->getRepository($this->scolariteBundle . 'EstEnseigne')->findOneBy(array('filiere' => $objetClasse->getFiliere(),'niveau' => $objetClasse->getNiveau(), 'matiere' => $objetMatiere ));          
            if($sessionData['idProfil'] == \admin\UserBundle\Types\TypeProfil::PROFIL_UTILISATEUR){
              $objetFaireCours = $em->getRepository($this->scolariteBundle . 'FaireCours')->findOneBy(array('utilisateur'=>$objetEnseignant, 'heurejournee'=>$objetHeureEnCours,'jour'=>$objetCodeJourEncours, 'classe' => $objetClasse,));
              $dateJour = new \DateTime();
              
              
              
            }else{
                //Reste a faire recuperer l'Id du jours ou l'enseignant fait le cours
                $objetFaireCours = $em->getRepository($this->scolariteBundle . 'FaireCours')->find($idFaireCours);
                $dateJour = new \DateTime();      
            }
            if($objetFaireCours==NULL){
                   $this->get('session')->getFlashBag()->add("classe.appel.error", "Opération impossible, vous n'avez pas le droit de faire l'appel ");
                  return $this->redirect($this->generateUrl('app_admin_user_home'));
              }
            $unePresence = new Presence();
            $unePresence->setDatePresence($dateJour); 
            $unePresence->setFairecours($objetFaireCours);
            $em->persist($unePresence);
            $em->flush();
            //Recuperation du decoupage        
                foreach ($listeEleveClasse as $unEleve) {
                    $seTrouver = $em->getRepository($this->scolariteBundle . 'SeTrouver')->findOneBy(array('anneescolaire' => $objetAnnee, 'eleve' => $unEleve, 'classe'=>$objetClasse));                
                    $presenceEleve = $request->get('presence' . $unEleve->getId());
                    $uneDetailPresence = new DetailPresence();
                    $uneDetailPresence->setDateDetailPresence(new \DateTime());
                    $uneDetailPresence->setSetrouve($seTrouver);
                    $uneDetailPresence->setSiPresent($presenceEleve);
                    $uneDetailPresence->setPresence($unePresence);
                    $em->persist($uneDetailPresence);
                    $em->flush();
               }
               
               $this->get('session')->getFlashBag()->add('classe.appel.succes', 'Appel enrégistré avec succès ');
               return $this->redirect($this->generateUrl('app_admin_classe_infos',array('idClasse'=>$idClasse)));                
           }
        //Calculer les rangs
        $this->data['enseignants'] = $enseignants;
        $this->data['listeEleveClasse'] = $listeEleveClasse;
        $this->data['allCodeJourEncours'] = $allCodeJourEncours;
        $this->data['allEnseignant'] = $allEnseignant;
        $this->data['allHeureEnCours'] = $allHeureEnCours;
        $this->data['locale'] = $locale;
        $this->data['idClasse'] = $idClasse;
        $this->data['typeexamens'] = $typeexamens;
        return $this->render($this->scolariteBundle . 'Classe:presenceEleveClasse.html.twig', $this->data, $this->response);
    }

    /**
     *  Afficle la liste des classes
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
    public function ajoutNoteEleveClasseByEpreuveAction(Request $request, $idEpreuve, $idClasse) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage de la liste des classes";
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
                $routeName = $request->get('_route');
                $routeParams = $request->get('_route_params');
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des classes");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }
        $idannee = $sessionData['idannee'];
        $em = $this->getDoctrine()->getManager();
        $objetAnnee = $em->getRepository($this->scolariteBundle . 'AnneeScolaire')->find($idannee);
        $objetEpreuve = $em->getRepository($this->scolariteBundle . 'Epreuve')->find($idEpreuve);
        $objetClasse = $em->getRepository($this->scolariteBundle . 'Classe')->find($idClasse);
        $listeEleveClasse = $em->getRepository($this->scolariteBundle . 'Eleve')->getListeEleveClasse($idClasse, $idannee,$sessionData['etablissement']);
        $listeExercice = $em->getRepository($this->scolariteBundle . 'Exercice')->findBy(array('epreuve' => $objetEpreuve));
        $objetEstEnseigne = $em->getRepository($this->scolariteBundle . 'EstEnseigne')->findOneBy(array('matiere' => $objetEpreuve->getMatiere(), 'filiere' => $objetClasse->getFiliere(), 'niveau' => $objetClasse->getNiveau()));
        $objetFaireCours = $em->getRepository($this->scolariteBundle . 'FaireCours')->findOneBy(array('estenseigne' => $objetEstEnseigne, 'anneescolaire' => $objetAnnee, 'classe' => $objetClasse));
        // var_dump($objetEstEnseigne->getId());exit;
        
        $infoNote = $this->infoNoteEpreuve($idEpreuve, $listeEleveClasse);
        $idenseignant = $objetFaireCours->getUtilisateur()->getId();
        if ($request->isMethod('POST')) {
            $objetEnseign = $em->getRepository($this->userBundle . 'Utilisateur')->find($idenseignant);
            $objetMatiere = $em->getRepository($this->scolariteBundle . 'Matiere')->find($objetEpreuve->getMatiere()->getId());
            $objetTypeexamen = $em->getRepository($this->scolariteBundle . 'TypeExamen')->find($objetEpreuve->getTypeExamen()->getId());
            
            $allTypeExamen = $em->getRepository($this->scolariteBundle . 'TypeExamen')->findAll();
            //Recuperation du decoupage
            $objetConcerner = $em->getRepository($this->scolariteBundle . 'Concerner')->findOneBy(array('anneescolaire' => $objetAnnee, 'enseignement' => $objetClasse->getFiliere()->getEnseignement()));
            $objetActifDecoupage = $em->getRepository($this->scolariteBundle . 'Decoupage')->findOneBy(array('typedecoupage' => $objetConcerner->getTypedecoupage(), 'etatDecoupage' => 1));

            foreach ($listeEleveClasse as $unEleve) {


                $objetSeTrouver = $em->getRepository($this->scolariteBundle . 'SeTrouver')->findOneBy(array('eleve' => $unEleve, 'anneescolaire' => $objetAnnee, 'classe' => $objetClasse));
                $testAncienNote = $em->getRepository($this->scolariteBundle . 'Note')->findOneBy(array('eleve' => $unEleve, 'setrouver' => $objetSeTrouver, 'matiere' => $objetMatiere, 'decoupage' => $objetActifDecoupage, 'typeexamen' => $objetTypeexamen, 'epreuve' => $objetEpreuve));
                $noteEleve = $request->get('note' . $unEleve->getId());
                if ($testAncienNote == NULL) {
                    $uneNote = new Note();
                    $uneNote->setNote($noteEleve);
                    $uneNote->setEleve($unEleve);
                    $uneNote->setDecoupage($objetActifDecoupage);
                    $uneNote->setUtilisateur($objetEnseign);
                    $uneNote->setEpreuve($objetEpreuve);
                    $uneNote->setMatiere($objetMatiere);
                    $uneNote->setSetrouver($objetSeTrouver);
                    $uneNote->setTypeexamen($objetTypeexamen);
                    $em->persist($uneNote);
                    $em->flush();
                    //Enregistrement des informations concernant les appreciations des enseignants
                    //recevoir la liste des exercices de l'epreuve en question
                    foreach ($listeExercice as $unExercice) {
                        $appreciation = $request->get('appreciation'.$unEleve->getId().''.$unExercice->getId());                        
                        $unDetailsNote = new DetailsNote();
                        $unDetailsNote->setAppreciation($appreciation);
                        $unDetailsNote->setNote($uneNote);
                        $unDetailsNote->setExercice($unExercice);
                        $em->persist($unDetailsNote);
                    }
                    $em->flush();
                    
                } else {

                    $testAncienNote->setNote($noteEleve);
                    $testAncienNote->setEleve($unEleve);
                    $testAncienNote->setDecoupage($objetActifDecoupage);
                    $testAncienNote->setUtilisateur($objetEnseign);
                    $testAncienNote->setEpreuve($objetEpreuve);
                    $testAncienNote->setMatiere($objetMatiere);
                    $testAncienNote->setSetrouver($objetSeTrouver);
                    $testAncienNote->setTypeexamen($objetTypeexamen);
                    $em->persist($testAncienNote);
                    $em->flush();
                    
                     foreach ($listeExercice as $unExercice) {
                        $ancienDetailNote = $em->getRepository($this->scolariteBundle . 'DetailsNote')->findOneBy(array('exercice' => $unExercice, 'note'=>$testAncienNote));               
                        $appreciation = $request->get('appreciation'.$unEleve->getId().''.$unExercice->getId());                             
                        $ancienDetailNote->setAppreciation($appreciation);
                        $ancienDetailNote->setNote($testAncienNote);
                        $ancienDetailNote->setExercice($unExercice);
                        $em->persist($ancienDetailNote);
                    }
                    $em->flush();
                }
                $this->traiteMoyenneMatiere($allTypeExamen, $objetSeTrouver, $objetActifDecoupage, $objetMatiere);
                $this->traiteMoyenneGenerale($allTypeExamen, $objetSeTrouver, $objetActifDecoupage);
            }

            //Rang Moyenne De Classe
            $this->getRangByClasse($em, $objetClasse->getId(), $objetAnnee->getId(), $objetActifDecoupage->getId(), 1);
            //Rang moyenne de classe et compo combiné
            $this->getRangByClasse($em, $objetClasse->getId(), $objetAnnee->getId(), $objetActifDecoupage->getId(), 2);
            //Rang Moyenne generale
            $this->getRangByClasse($em,$objetClasse->getId(), $objetAnnee->getId(), $objetActifDecoupage->getId(), 3);
            
            return $this->redirect($this->generateUrl('app_admin_classe_ajout_note_eleve_by_epreuve',array('idEpreuve'=>$idEpreuve, 'idClasse'=>$idClasse)));
        }
//Calculer les rangs

        $this->data['listeEleveClasse'] = $listeEleveClasse;
        $this->data['listeExercice'] = $listeExercice;
        $this->data['idEpreuve'] = $idEpreuve;
        $this->data['idC'] = $idEpreuve;
        $this->data['infoNote'] = $infoNote;
        $this->data['locale'] = $locale;
        return $this->render($this->scolariteBundle . 'Classe:ajoutNoteEleveClasseByEpreuve.html.twig', $this->data, $this->response);
    }

    /**
     *  Formulaire d'ajout de classe
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
    public function ajoutClasseAction(Request $request) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Ajout d'un nouveau classe";
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
                $routeName = $request->get('_route');
                $routeParams = $request->get('_route_params');
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
//        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
//            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
//            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'ajouter un classe");
//            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
//        }

        $classe = new Classe();
        $form = $this->createForm('\admin\ScolariteBundle\Form\ClasseType', $classe);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();

            if ($form->isValid()) {
                $criteria = array('libelleClasse' => $classe->getLibelleClasse());
                $oldClasse = $em->getRepository($this->scolariteBundle . 'Classe')->findOneBy($criteria);
                if ($oldClasse == NULL) {
                    //$classe->setDateInitialClasse(new \DateTime());
                    $em->persist($classe);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('classe.ajout.success', 'Ajout effectue avec succès');
                    return $this->redirect($this->generateUrl('app_admin_classes'));
                } else {
                    if ($oldClasse->getEtat() == \admin\UserBundle\Types\TypeEtat::SUPPRIME) {
                        $oldClasse->setEtat(\admin\UserBundle\Types\TypeEtat::ACTIF);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('classe.ajout.success', 'Ajout effectue avec succès');
                        return $this->redirect($this->generateUrl('app_admin_classes'));
                    }
                    $this->get('session')->getFlashBag()->add('classe.ajout.already.exist', 'Le classe ' . $classe->getNom() . ' existe déjà');
                }
            } else {
                $this->get('session')->getFlashBag()->add('classe.ajout.error', 'Formulaire invalide');
               
            }
        }
        $locale = "";

        $this->data['formuView'] = $form->createView();
        $this->data['classe'] = $classe;
        $this->data['locale'] = $locale;

        return $this->render($this->scolariteBundle . 'Classe:ajouterClasse.html.twig', $this->data, $this->response);
    }

    /*
     * Modifie un classe
     * @param type $idClasse
     * @return type
     */

    public function modifierClasseAction(Request $request, $idClasse) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Modification d'un classe";
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
                $routeName = $request->get('_route');
                $routeParams = $request->get('_route_params');
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
//        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
//            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
//            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit de modifier un classe");
//            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
//        }

        $em = $this->getDoctrine()->getManager();
        $classe = $em->getRepository($this->scolariteBundle . 'Classe')->find($idClasse);
        if ($classe == NULL) {
            return $this->redirect($this->generateUrl('app_admin_classes'));
        }
        $form = $this->createForm('\admin\ScolariteBundle\Form\ClasseType', $classe);

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);
            if ($form->isValid()) {
                $criteria = array('libelleClasse' => $classe->getLibelleClasse());
                $oldClasse = $em->getRepository($this->scolariteBundle . 'Classe')->findOneBy($criteria);
                if ($oldClasse == NULL) {
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('classe.modifier.success', 'Modifications effectuées avec succès');
                    return $this->redirect($this->generateUrl('app_admin_classes'));
                } else {

                    /*
                     * S'il s'agit du mm classe ou k le classe est supprimé on effecute la modification
                     */
                    if (($oldClasse->getId() == $classe->getId()) || ($oldClasse->getEtat() == \admin\UserBundle\Types\TypeEtat::SUPPRIME)) {
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('classe.modifier.success', 'Modifications effectuées avec succès');
                        return $this->redirect($this->generateUrl('app_admin_classes'));
                    }
                    $this->get('session')->getFlashBag()->add('classe.modifier.already.exist', 'Le classe ' . $classe->getNom() . ' existe déjà');
                }
            } else {
                $this->get('session')->getFlashBag()->add('classe.modifier.error', 'Formulaire invalide');
            }
        }

        $this->data['formuView'] = $form->createView();
        $this->data['classe'] = $classe;
        $this->data['locale'] = $locale;
        $this->data['idClasse'] = $idClasse;

        return $this->render($this->scolariteBundle . 'Classe:modifierClasse.html.twig', $this->data, $this->response);
    }

    /*
     * Activation, suppression, désactivation de classe
     * @return Response
     */

    public function changerEtatClasseAction(Request $request) {
        $rep = array('etat' => FALSE, 'msg' => 'Erreur survenue lors du traitement');
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Activation, suppression, désactivation de classe";
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
            $rep['msg'] = "Vous n'avez pas le droit d'activer, de supprimer ou de désactiver un classe";
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
                $classe = $em->getRepository($this->scolariteBundle . 'Classe')->find((int) $idS);
                if ($classe != NULL) {
                    $classe->setEtat($etat);
                    $em->flush();
                    $oneOk = TRUE;
                }
            }
            if ($oneOk) {
                $this->get('session')->getFlashBag()->add('classe.modifier.success', 'Opération éffectuée avec succès');
                $rep['msg'] = '';
                $rep['etat'] = TRUE;
            }

            return new Response(json_encode($rep));
        }
        return new Response(json_encode($rep));
    }

    /*
     * Afficher les informations  d'un abonné
     * 
     * @param type $idClasse
     * @return type
     */

    public function infosClasseAction(Request $request, $idClasse) {
        /**
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /**
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage des informations d'un utilisateur";
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
                $routeName = $request->get('_route');
                $routeParams = $request->get('_route_params');
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_ABONNE, Module::MOD_GEST_ABONNE_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder aux informations des abonnés");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }


        $em = $this->getDoctrine()->getManager();
        /*
         * On recupère l'classe
         */
        $idannee = $sessionData['idannee'];
        $classe = $em->getRepository($this->scolariteBundle . 'Classe')->find($idClasse);
        $objetAnneeScolaire = $em->getRepository($this->scolariteBundle . 'AnneeScolaire')->find($idannee);
        //  $classe = $em->getRepository($this->scolariteBundle . 'Classe')->getClasseEnCours($idClasse, $idannee);
        $eleves = $em->getRepository($this->scolariteBundle . 'Eleve')->getListeRechercheEleve(0,0, 0,0, $idClasse, 0, 0,0, 0,$annee=1, 1000000 , 100000);
        
        $siAfficheMenuPresence = false;
        //recuperation de la de l'heure en cours 
        $date = new \Datetime();
        $heureEncours = $date->format('0000:00:00 h:i:s');
        $codeJourEncours = $date->format('D');
        $objetCodeJourEncours = $em->getRepository($this->scolariteBundle . 'Jour')->findOneBy(array('codeJour'=>$codeJourEncours));
        $objetEnseignant = $em->getRepository($this->userBundle . 'Utilisateur')->find($sessionData['id']);
        
        $siHeureEnCours= $this->siHeureEnCours($em, $heureEncours);
        
        if($siHeureEnCours!=false && count($objetCodeJourEncours)==1){
            $objetHeureEnCours = $em->getRepository($this->scolariteBundle . 'HeureJournee')->find($siHeureEnCours['id']);
            $siAfficheMenuPresence= true;
        }else{
            $siAfficheMenuPresence= false;
        }
        if(count($objetCodeJourEncours)==1 && $siAfficheMenuPresence ){
            $siAfficheMenuPresence = true;
             $objetFaireCours = $em->getRepository($this->scolariteBundle . 'FaireCours')->findBy(array('utilisateur'=>$objetEnseignant, 'heurejournee'=>$objetHeureEnCours,'jour'=>$objetCodeJourEncours, 'classe' => $classe, 'anneescolaire'=>$objetAnneeScolaire));
            if($sessionData['idProfil']==\admin\UserBundle\Types\TypeProfil::PROFIL_ENSEIGANT ){
                if(count($objetFaireCours)!=0){
                   $siAfficheMenuPresence = true; 
                }else{
                    $siAfficheMenuPresence = false;
                }
            }
           // var_dump(count($objetCodeJourEncours),$siAfficheMenuPresence);exit;
       
        }else{
            $siAfficheMenuPresence = false;
        }        
        // Encours);exit;      
        if ($classe == NULL) {
            return $this->redirect($this->generateUrl('app_admin_classes'));
        }
        //Voir si la personne  connectée est un enseignant et acces à la fiche de présence        
        //Creation de l'objet pour recoperer l'objet du jour encours
        // $form = $this->createForm('admin\ScolariteBundle\Form\ModifierClasseType', $classe);
        //Recuperation de la classe actuel 

        /*
         * Le mot de passe ne doit pas être vide
         */
//        $parametreManager = $this->get('parametre_manager');
//        $minLengthPassword = $parametreManager->getValeurParametre(TypeParametre::LONGUEUR_MIN_PASSWORD_INT);
//        $longueurCompte = $parametreManager->getValeurParametre(TypeParametre::LONGUEUR_COMPTE_INT);
//        $longueurNumeroTel = $parametreManager->getValeurParametre(TypeParametre::LONGUEUR_NUM_TEL_INT);
        //$this->data['formuView'] = $form->createView();
        $this->data['classe'] = $classe;
        $this->data['eleves'] = $eleves ;
//        $this->data['longueurCompte'] = $longueurCompte;
        $this->data['idClasse'] = $idClasse;
        $this->data['idAnnee'] = $idannee;
        $this->data['idEtablissement'] = $sessionData['etablissement'];
        $this->data['siAfficheMenuPresence'] = $siAfficheMenuPresence;
        $this->data['locale'] = $locale;
        $this->data['isAdmin'] = $sessionData['isUser'];
        return $this->render($this->scolariteBundle . 'Classe:afficherinfosClasse.html.twig', $this->data, $this->response);
    }

    /*
     * Afficher les informations  l'emploi du temps d'un élève
     * 
     * @param type $idClasse
     * @return type
     */

    public function emploiTempsClasseAction(Request $request, $idClasse) {
        /**
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /**
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage des informations d'un utilisateur";
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
                $routeName = $request->get('_route');
                $routeParams = $request->get('_route_params');
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_ABONNE, Module::MOD_GEST_ABONNE_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder aux informations des abonnés");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }
        $em = $this->getDoctrine()->getManager();
        /*
         * On recupère l'classe
         */
        $classe=array();
        $estEnseigne=array();
        $idannee = 1;
        $heureJour = $em->getRepository($this->scolariteBundle . 'HeureJournee')->findAll();
        $anneeScolaire = $em->getRepository($this->scolariteBundle . 'AnneeScolaire')->find($idannee);
        $jour = $em->getRepository($this->scolariteBundle . 'Jour')->findAll();
        
        if($sessionData['idProfil']==5){
            $unUtil = $em->getRepository($this->userBundle . 'Utilisateur')->find($sessionData['id']);
            $idEleve = $unUtil->getEleve()->getId();
            $Objetclasse = $em->getRepository($this->scolariteBundle . 'Eleve')->getClasseEnCours($idEleve, $idannee);
            $idClasse= $Objetclasse[0]->getId();
        }
        //Recuperation des matières enseignées dans la classe
        $classe = $em->getRepository($this->scolariteBundle . 'Classe')->find($idClasse);
        if ($request->isMethod('POST')) {

            foreach ($heureJour as $lheure) {
                foreach ($jour as $unJour) {

                    //recuperation des donnée
                    $infoMat = $request->get("val-matiere" . $lheure->getId() . "" . $unJour->getId());
                    $infoEns = $request->get("val-enseignant" . $lheure->getId() . "" . $unJour->getId());
                    // var_dump($infoMat,$infoEns,"val-enseignant".$lheure->getId()."".$unJour->getId());exit;
                    if ($infoMat != "" && $infoEns != "") {
                        //Recuperation donnée Mat
                        $matiere = $em->getRepository($this->scolariteBundle . 'Matiere')->find($infoMat);
                        //Recuperation donnée Ens
                        $enseignant = $em->getRepository($this->userBundle . 'Utilisateur')->find($infoEns);

                        $estEnseigne = $em->getRepository($this->scolariteBundle . 'EstEnseigne')->findOneBy(array('matiere' => $matiere, 'niveau' => $classe->getNiveau(), 'filiere' => $classe->getFiliere()));

                        $ControleFaireCours = $em->getRepository($this->scolariteBundle . 'FaireCours')->findOneBy(array('classe' => $classe, 'heurejournee' => $lheure, 'jour' => $unJour));
                        if ($ControleFaireCours != NULL) {
                            $ControleFaireCours->setUtilisateur($enseignant);
                            $ControleFaireCours->setEstenseigne($estEnseigne);
                            $em->persist($ControleFaireCours);
                            $em->flush();
                        } else {
                            $faireCours = new FaireCours();
                            $faireCours->setAnneescolaire($anneeScolaire);
                            $faireCours->setClasse($classe);
                            $faireCours->setJour($unJour);
                            $faireCours->setHeurejournee($lheure);
                            $faireCours->setUtilisateur($enseignant);
                            $faireCours->setEstenseigne($estEnseigne);
                            $em->persist($faireCours);
                            $em->flush();
                        }
                    }
                }
            }
            return $this->redirect($this->generateUrl('app_admin_classe_infos', array('idClasse' => $idClasse)));
        }
        $tablDonneEmploiTps = array();
        //Recuperation de l'objet fait Cours  
        foreach ($heureJour as $lheure) {
            foreach ($jour as $unJour) {
                $objetFaireCours = $em->getRepository($this->scolariteBundle . 'FaireCours')->findOneBy(array('classe' => $classe, 'heurejournee' => $lheure, 'jour' => $unJour));
                if ($objetFaireCours != NULL) {
                    $tablDonneEmploiTps[$lheure->getId()][$unJour->getId()]['infoMatiere'] = $objetFaireCours->getEstEnseigne()->getMatiere()->getLibelleMatiere();
                    $tablDonneEmploiTps[$lheure->getId()][$unJour->getId()]['infoEnseignant'] = $objetFaireCours->getUtilisateur()->getNom() . " " . $objetFaireCours->getUtilisateur()->getPrenoms();
                    $tablDonneEmploiTps[$lheure->getId()][$unJour->getId()]['infoMatiereId'] = $objetFaireCours->getEstEnseigne()->getMatiere()->getId();
                    $tablDonneEmploiTps[$lheure->getId()][$unJour->getId()]['infoEnseignantId'] = $objetFaireCours->getUtilisateur()->getId();
                }
            }
        }
        if($classe!=NULL){
            $estEnseigne = $em->getRepository($this->scolariteBundle . 'EstEnseigne')->findBy(array('niveau' => $classe->getNiveau(), 'filiere' => $classe->getFiliere()));
        }
        $this->data['heureJour'] = $heureJour;
        $this->data['jour'] = $jour;
        $this->data['estEnseigne'] = $estEnseigne;
        $this->data['tablDonneEmploiTps'] = $tablDonneEmploiTps;
        $this->data['idClasse'] = $idClasse;
        $this->data['idEns'] = $sessionData['id'];
        $this->data['locale'] = $locale;
        $this->data['isAdmin'] = $sessionData['isUser'];
        return $this->render($this->scolariteBundle . 'Classe:emploiTemps.html.twig', $this->data, $this->response);
    }

    /*
     * Afficher les informations  d'un abonné
     * 
     * @param type $idClasse
     * @return type
     */

    public function listeEleveClasseAction(Request $request, $idClasse, $typeoper) {
        /**
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /**
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage des informations d'un utilisateur";
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
                $routeName = $request->get('_route');
                $routeParams = $request->get('_route_params');
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
        if (!$loginManager->getOrSetActions(Module::MOD_ELEVE, Module::MOD_ELEVE_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des élèves par classe");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }


        $em = $this->getDoctrine()->getManager();
        /*
         * On recupère l'classe
         */
        $idannee = $sessionData['idannee'];
        $type = 2;
        $operationManager = $this->get('operation_manager');
        $InfoSoldeEleve = array();
        $classe = $em->getRepository($this->scolariteBundle . 'Classe')->find($idClasse);
        $listeEleveClasse = $em->getRepository($this->scolariteBundle . 'Eleve')->getListeEleveClasse($idClasse, $idannee,$sessionData['etablissement']);

        //Recuperation de la scolairite
        foreach ($listeEleveClasse as $unEleve) {
            $InfoSoldeEleve[$unEleve->getId()] = $operationManager->getInfoSoldeEleve($unEleve->getId(), $idannee, $type, 'CPTE00001');
        }

        if ($classe == NULL) {
            return $this->redirect($this->generateUrl('app_admin_classes'));
        }

        $this->data['classe'] = $classe;
        $this->data['listeEleveClasse'] = $listeEleveClasse;
        $this->data['soldeEleve'] = $InfoSoldeEleve;
        $this->data['idClasse'] = $idClasse;
        $this->data['type'] = $typeoper;
        $this->data['locale'] = $locale;
        $this->data['isAdmin'] = $sessionData['isUser'];
        return $this->render($this->scolariteBundle . 'Classe:listeEleveClasse.html.twig', $this->data, $this->response);
    }

    /**
     * Afficher les informations  d'un abonné
     * 
     * @param type $idClasse
     * @return type
     */
    public function listeSoldeClasseAction(Request $request, $idClasse) {
        /**
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /**
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage des informations d'un utilisateur";
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
                $routeName = $request->get('_route');
                $routeParams = $request->get('_route_params');
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_ABONNE, Module::MOD_GEST_ABONNE_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des élèves par classe");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }


        $em = $this->getDoctrine()->getManager();
        /*
         * On recupère l'classe
         */
        $idannee = 1;
        $type = 2;
        $operationManager = $this->get('operation_manager');
        $InfoSoldeClasse = array();
        $listeClasse = $em->getRepository($this->scolariteBundle . 'Classe')->getAllClasse($idClasse);
        // $listeEleveClasse = $em->getRepository($this->scolariteBundle . 'Eleve')->getListeEleveClasse($idClasse, $idannee,$sessionData['etablissement']);
        //Recuperation de la scolairite
        foreach ($listeClasse as $unClasse) {
            $InfoSoldeClasse[$unClasse->getId()] = $operationManager->getInfoSoldeClasse($unClasse->getId(), $idannee, $type, 'CPTE00001',$sessionData['etablissement']);
        }

        $this->data['listeClasse'] = $listeClasse;

        $this->data['soldeClasse'] = $InfoSoldeClasse;
        $this->data['idClasse'] = $idClasse;
        $this->data['locale'] = $locale;
        $this->data['isAdmin'] = $sessionData['isUser'];
        return $this->render($this->scolariteBundle . 'Classe:listeSoldeClasse.html.twig', $this->data, $this->response);
    }

    /*
     * Afficher les informations  d'un abonné
     * 
     * @param type $idProfil
     * @param type $idEleve
     * @return type
     */

    public function carteClasseAction(Request $request, $idClasse) {
        /**
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /**
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage des informations d'un utilisateur";
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
                $routeName = $request->get('_route');
                $routeParams = $request->get('_route_params');
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_ABONNE, Module::MOD_GEST_ABONNE_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder aux informations des abonnés");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }


        $em = $this->getDoctrine()->getManager();
        /*
         * On recupère l'eleve
         */
        $idannee = 1;
        $classe = $em->getRepository($this->scolariteBundle . 'Classe')->find($idClasse);
        $listeEleve = $em->getRepository($this->scolariteBundle . 'Eleve')->getListeEleveClasse($idClasse, $idannee,$sessionData['etablissement']);


        //   $this->data['formuView'] = $form->createView();
        $this->data['listeEleveClasse'] = $listeEleve;
        $this->data['classe'] = $classe;
        $this->data['locale'] = $locale;
        $this->data['isAdmin'] = $sessionData['isUser'];

        return $this->render($this->scolariteBundle . 'Classe:afficherCarteClasse.html.twig', $this->data, $this->response);
    }

    public function traiteMoyenneMatiere($listeTypeExamen, $seTrouver, $decoupage, $matiere) {
        $em = $this->getDoctrine()->getManager();

        $recapTestNote = $em->getRepository($this->scolariteBundle . 'RecapNote')->findOneBy(array('setrouver' => $seTrouver, 'decoupage' => $decoupage, 'matiere' => $matiere));
        $noteClasse = 0;
        $moyenneGenerale = 0;
        $noteManager = $this->get('note_manager');

        if ($recapTestNote == NULL) {
            $recapNote = new RecapNote();
        } else {
            $recapNote = $recapTestNote;
        }
        $siNoteClasse = true;

        foreach ($listeTypeExamen as $typeExamen) {
            $objetMoyenne = $noteManager->getMoyenneNote($typeExamen->getId(), $seTrouver->getId(), $decoupage->getId(), $matiere->getId());
			//var_dump($typeExamen->getId());
            if (isset($objetMoyenne[0]['moyenne'])) {
                $Moyenne = round($objetMoyenne[0]['moyenne'], 2);
            }else{
			    $Moyenne = 0; 
			}
            if ($typeExamen->getId() == 1) {
                if ($Moyenne != 0) {
                    $moyenInterro = $Moyenne;
                    $siInterro = True;
                } else {
                    $moyenInterro = "--";
                    $siInterro = False;
                }
                $recapNote->setMoyenneInterro($moyenInterro);
            } elseif ($typeExamen->getId() == 2) {
			//var_dump($Moyenne);exit;
                if ($Moyenne != 0) {
                    $moyenDevoir = $Moyenne;
                    $siDevoir = True;
                    if ($siInterro) {
                        $noteClasse = round((($moyenInterro + $moyenDevoir) / 2), 2);
                    } else {
                        $noteClasse = $moyenDevoir;
                    }
                } else {
                    $moyenDevoir = "--";
                    $siDevoir = False;
                    if ($siInterro) {
                        $noteClasse = $moyenInterro;
                    } else {
                        $noteClasse = "--";
                        $siNoteClasse = false;
                    }
                }
                $recapNote->setMoyenneDevoir($moyenDevoir);
            } else {
                if ($Moyenne != 0) {
                    $moyenCompo = $Moyenne;
                    if ($siNoteClasse) {
                        $moyenneGenerale = round((($moyenCompo + $noteClasse) / 2), 2);
                    } else {
                        $moyenneGenerale = $moyenCompo;
                    }
                } else {
                    $moyenCompo = "--";
                    if ($siNoteClasse) {
                        $moyenneGenerale = $noteClasse;
                    }
                }
                $recapNote->setMoyenneCompo($moyenCompo);
            }
        }

        $recapNote->setNoteClasse($noteClasse);
        $recapNote->setMoyenneGenerale($moyenneGenerale);
        $recapNote->setMatiere($matiere);
        $recapNote->setDecoupage($decoupage);
        $recapNote->setSetrouver($seTrouver);
        $em->persist($recapNote);
        $em->flush();
    }

    public function traiteMoyenneGenerale($listeTypeExamen, $seTrouver, $decoupage) {
        $em = $this->getDoctrine()->getManager();

        $recapTestNote = $em->getRepository($this->scolariteBundle . 'RecapNote')->findBy(array('setrouver' => $seTrouver, 'decoupage' => $decoupage));
        $ObjetMoyenneGenerale = $em->getRepository($this->scolariteBundle . 'RecapMoyenneGenerale')->findOneBy(array('setrouver' => $seTrouver, 'decoupage' => $decoupage));
        $noteClasse = 0;
        $noteManager = $this->get('note_manager');

        if ($ObjetMoyenneGenerale == NULL) {
            $traiteMoyenneGenerale = new RecapMoyenneGenerale();
        } else {
            $traiteMoyenneGenerale = $ObjetMoyenneGenerale;
        }
        $totalCoefClasse = 0;
        $totalCoefCompo = 0;
        $totalCoefGenerale = 0;
        $compteClasse = 0;
        $compteCompo = 0;
        $compteGenerale = 0;
        $classGener = 0;
        $compoGener = 0;
        $moyenneGener = 0;
        $siNoteClasse = true;
        $siMoyenneCompo = true;
        $siMoyenneGenerale = true;

        //Recuperation de la classe
        $objetClasse = $seTrouver->getClasse();

        $objetAnneeScolaire = $seTrouver->getAnneescolaire();

        foreach ($recapTestNote as $unObjetRecap) {
            //            $Moyenne = $noteManager->getListeLigneCommande($typeExamen->getId(),$seTrouver->getId(), $decoupage->getId(), $matiere->getId());  
            $estEnseigne = $em->getRepository($this->scolariteBundle . 'EstEnseigne')->findOneBy(array('niveau' => $objetClasse->getNiveau(), 'filiere' => $objetClasse->getFiliere(), 'matiere' => $unObjetRecap->getMatiere()));
            $coefMat = $estEnseigne->getCoefficient();
            //Moyenne de classe
           // var_dump($unObjetRecap->getNoteClasse());
            if ($unObjetRecap->getNoteClasse() != '--') {
                //var_dump($estEnseigne->getCoefficient());
                $totalCoefClasse +=$estEnseigne->getCoefficient();
                $totalNoteClasse = $unObjetRecap->getNoteClasse() * $coefMat;
                $classGener += $totalNoteClasse;
                $compteClasse++;
            } else {
                $siNoteClasse = false;
            }
            //var_dump($unObjetRecap->getMoyenneCompo());
            if ($unObjetRecap->getMoyenneCompo() != '--') {
               // var_dump($estEnseigne->getCoefficient());
                $totalCoefCompo +=$estEnseigne->getCoefficient();
                $totalNoteCompo = $unObjetRecap->getMoyenneCompo() * $coefMat;
                $compoGener += $totalNoteCompo;
                $compteCompo++;
            } else {
                $siMoyenneCompo = false;
            }

            //var_dump($unObjetRecap->getMoyenneGenerale());
            if ($unObjetRecap->getMoyenneGenerale() != '--') {
                //var_dump($estEnseigne->getCoefficient());
                $totalCoefGenerale +=$estEnseigne->getCoefficient();
                $totalMoyenneGenerale = $unObjetRecap->getMoyenneGenerale() * $coefMat;
                $moyenneGener += $totalMoyenneGenerale;
                $compteGenerale++;
            } else {
                $siMoyenneGenerale = false;
            }
        }
        if (!$siNoteClasse && $compteClasse == 0) {
            $moyenneClasse = '--';
        } else {
            $moyenneClasse = round($classGener / $totalCoefClasse, 2);
        }
        if (!$siMoyenneCompo && $compteCompo == 0) {
            $moyenneCompo = '--';
        } else {
            $moyenneCompo = round($compoGener / $totalCoefCompo, 2);
        }
        if (!$siMoyenneGenerale && $compteGenerale == 0) {
            $moyenneGenerale = '--';
        } else {
            $moyenneGenerale = round($moyenneGener / $totalCoefGenerale, 2);
        }
        

        $moyenneClasseCast = (string) $moyenneClasse;
        $moyenneCompoCast = (string) $moyenneCompo;
        $moyenneGeneraleCast = (string) $moyenneGenerale;
        $traiteMoyenneGenerale->setSetrouver($seTrouver);
        $traiteMoyenneGenerale->setDecoupage($decoupage);
        $traiteMoyenneGenerale->setMoyenneClasse($moyenneClasseCast);
        $traiteMoyenneGenerale->setMoyenneCompo($moyenneCompoCast);
        $traiteMoyenneGenerale->setMoyenneGenerale($moyenneGeneraleCast);

        $em->persist($traiteMoyenneGenerale);
        $em->flush();
        //exit;
    }

    public function getRangByClasse($em, $id, $idannee, $iddecoup, $type) {

        $recapMoyenne = $em->getRepository($this->scolariteBundle . 'Classe')->getInfoRecapNote($id, $idannee, $iddecoup, $type);
        $rang = 0;
        $oldMoyenne = 0;
        $siDoublons = false;
        $nbreFois = 0;
        foreach ($recapMoyenne as $uneRecapMoyenne) {

            if ($type == 1) {
                $newMoyenne = $uneRecapMoyenne['moyenneClasse'];
            } elseif ($type == 2) {
                $newMoyenne = $uneRecapMoyenne['moyenneCompo'];
            } else {
                $newMoyenne = $uneRecapMoyenne['moyenneGenerale'];
            }
            $objetRecapMoyenneGenerale = $em->getRepository($this->scolariteBundle . 'RecapMoyenneGenerale')->find($uneRecapMoyenne['id']);
            if ($oldMoyenne != $newMoyenne) {
                $rang++;
                if (!$siDoublons) {
                    $rang = $rang + $nbreFois;
                }
                $siDoublons = true;
            } else {
                $nbreFois++;
                $siDoublons = false;
            }
            if ($type == 1) {
                $objetRecapMoyenneGenerale->setRangMoyenneClasse($rang);
            } elseif ($type == 2) {
                $objetRecapMoyenneGenerale->setRangMoyenneCompo($rang);
            } else {
                $objetRecapMoyenneGenerale->setRangMoyenneGenerale($rang);
            }



            if ($type == 1) {
                $oldMoyenne = $uneRecapMoyenne['moyenneClasse'];
            } elseif ($type == 2) {
                $oldMoyenne = $uneRecapMoyenne['moyenneCompo'];
            } else {
                $oldMoyenne = $uneRecapMoyenne['moyenneGenerale'];
            }
            $em->persist($objetRecapMoyenneGenerale);
        }

        $em->flush();
        return 1;
    }
    
    public function infoNoteEpreuve($idEpreuve, $listeEleveClasse){
        
        $em = $this->getDoctrine()->getManager();
        //tableau des information
        $tabNoteGeneral = array();
        $tabNoteEleve = array();
        $tabNoteExercice = array();
        $objetEpreuve = $em->getRepository($this->scolariteBundle . 'Epreuve')->find($idEpreuve);
        foreach ($listeEleveClasse as $unEleve) {
            $listeExercice = $em->getRepository($this->scolariteBundle . 'Exercice')->findBy(array('epreuve' => $objetEpreuve));
            $uneNote = $em->getRepository($this->scolariteBundle . 'Note')->findOneBy(array('epreuve' => $objetEpreuve, 'eleve'=>$unEleve));
             if(count($uneNote)){
                //
                $tabNoteEleve[$unEleve->getId()] =  $uneNote->getNote();
                foreach ($listeExercice as $unExercice){
                    //var_dump($unExercice->getId(),$uneNote->getId());
                     $InfoNoteExercice = $em->getRepository($this->scolariteBundle . 'DetailsNote')->findOneBy(array('note' => $uneNote,'exercice' => $unExercice));
                     $tabNoteExercice[$unEleve->getId()][$unExercice->getId()] =$InfoNoteExercice->getAppreciation();
                }
             }
            
        }
        $tabNoteGeneral[0]= $tabNoteEleve ;
        $tabNoteGeneral[1]= $tabNoteExercice;
        return $tabNoteGeneral;
        
        
    }

 /**
     *  Mise en place des bulletins des élèves de la classe
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
    public function bulletinClasseAction(Request $request, $idClasse, $idDecoupage) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage  des bulletins des élèves d'une classe";
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
                $routeName = $request->get('_route');
                $routeParams = $request->get('_route_params');
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des bulletins de la classe ");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }

        $idannee = $sessionData['idannee'];

        $em = $this->getDoctrine()->getManager();
        $tabInfo = array();
        $tabMatiere = array();
        $tabMoyenneGenerale = array();
        $i=0;
        if($idDecoupage==0){
          $idDecoupage = 1;  
        }
        $listeEleveClasse = $em->getRepository($this->scolariteBundle . 'Eleve')->getListeEleveClasse($idClasse, $idannee,$sessionData['etablissement']);
        $objetClasse = $em->getRepository($this->scolariteBundle . 'Classe')->find($idClasse);
        $objetAnneeScolaire = $em->getRepository($this->scolariteBundle . 'AnneeScolaire')->find($idannee);
        $objetDecoupage = $em->getRepository($this->scolariteBundle . 'Decoupage')->find($idDecoupage);
		$listeDecoupage = $em->getRepository($this->scolariteBundle . 'Decoupage')->getDecoupageClasse($idClasse);
        
        $listeTypeMatiere = $em->getRepository($this->scolariteBundle . 'TypeMatiere')->getClasseTypeMatiere($objetClasse->getNiveau()->getId(), $objetClasse->getFiliere()->getId());
        
        foreach($listeEleveClasse as $unEleve){
            
            foreach($listeTypeMatiere as $unTypeMatiere){
                //Recuperation des recuperations des matieres 
                $objetTypeMatiere = $em->getRepository($this->scolariteBundle . 'TypeMatiere')->find($unTypeMatiere['id']);
        
                
                $listeEstEnseigne[$unTypeMatiere['id']] = $em->getRepository($this->scolariteBundle . 'EstEnseigne')->findBy(array('niveau'=> $objetClasse->getNiveau(),'filiere'=> $objetClasse->getFiliere(), 'typematiere'=>$objetTypeMatiere));
                
                foreach($listeEstEnseigne[$unTypeMatiere['id']]  as $unEstEnseigne){
                    //Formation de l'objet se trouver
                    
                    $uneMatiere = $unEstEnseigne->getMatiere();
                    //$tabMatiere[$objetTypeMatiere][$unEstEnseigne->getId()]= $uneMatiere;
                    
                    $i++;
                    $objetSeTrouver = $em->getRepository($this->scolariteBundle . 'seTrouver')->findOneBy(array('eleve'=> $unEleve,'classe'=> $objetClasse, 'anneescolaire'=>$objetAnneeScolaire));
                    
                    //Recuparation de la note de l'élève
                    $objetRecapNote = $em->getRepository($this->scolariteBundle . 'RecapNote')->findOneBy(array('setrouver'=> $objetSeTrouver,'matiere'=> $uneMatiere, 'decoupage'=>$objetDecoupage));
                    
                   // var_dump($objetSeTrouver->getId(), $uneMatiere->getId(),$objetDecoupage->getId());exit;
                    if($objetRecapNote != NULL ){
                        //Formation du tableau à afficher au niveau du Twig
                        $tabInfo[$unEleve->getId()][$unTypeMatiere['id']][$uneMatiere->getId()]['moyInterro'] = $objetRecapNote->getMoyenneInterro();
                        $tabInfo[$unEleve->getId()][$unTypeMatiere['id']][$uneMatiere->getId()]['libelleMatiere'] = $uneMatiere->getLibelleMatiere() ;
                        $tabInfo[$unEleve->getId()][$unTypeMatiere['id']][$uneMatiere->getId()]['Coefficient'] = $unEstEnseigne->getCoefficient();
                        $tabInfo[$unEleve->getId()][$unTypeMatiere['id']][$uneMatiere->getId()]['nomEnseignant'] = '' ; 
                        $tabInfo[$unEleve->getId()][$unTypeMatiere['id']][$uneMatiere->getId()]['moyDevoir'] = $objetRecapNote->getMoyenneDevoir() ;
                        $tabInfo[$unEleve->getId()][$unTypeMatiere['id']][$uneMatiere->getId()]['moyClasse'] = $objetRecapNote->getNoteClasse();
                        $tabInfo[$unEleve->getId()][$unTypeMatiere['id']][$uneMatiere->getId()]['moyCompo'] = $objetRecapNote->getMoyenneCompo();
                        $tabInfo[$unEleve->getId()][$unTypeMatiere['id']][$uneMatiere->getId()]['moyGenerale'] = $objetRecapNote->getMoyenneGenerale();

                    }else{
                       //Formation du tableau à afficher au niveau du Twig
                        $tabInfo[$unEleve->getId()][$unTypeMatiere['id']][$uneMatiere->getId()]['moyInterro'] = '-';
                        $tabInfo[$unEleve->getId()][$unTypeMatiere['id']][$uneMatiere->getId()]['libelleMatiere'] = '-' ;
                        $tabInfo[$unEleve->getId()][$unTypeMatiere['id']][$uneMatiere->getId()]['Coefficient'] = '-';
                        $tabInfo[$unEleve->getId()][$unTypeMatiere['id']][$uneMatiere->getId()]['nomEnseignant'] = '-' ; 
                        $tabInfo[$unEleve->getId()][$unTypeMatiere['id']][$uneMatiere->getId()]['moyDevoir'] = '-' ;
                        $tabInfo[$unEleve->getId()][$unTypeMatiere['id']][$uneMatiere->getId()]['moyClasse'] = '-';
                        $tabInfo[$unEleve->getId()][$unTypeMatiere['id']][$uneMatiere->getId()]['moyCompo'] = '-';
                        $tabInfo[$unEleve->getId()][$unTypeMatiere['id']][$uneMatiere->getId()]['moyGenerale'] = '-';

                    }
                    
                    //Recupération des moyennes generaux
                    $objetRecapMoyenneGenerale = $em->getRepository($this->scolariteBundle . 'RecapMoyenneGenerale')->findOneBy(array('setrouver'=> $objetSeTrouver, 'decoupage'=>$objetDecoupage));
                    if($objetRecapMoyenneGenerale != NULL ){
                        //Formation du tableau à afficher au niveau du Twig
                        $tabMoyenneGenerale[$unEleve->getId()]['moyGeneClasse'] = $objetRecapMoyenneGenerale->getMoyenneClasse();
                        $tabMoyenneGenerale[$unEleve->getId()]['moyGeneClasseRang'] = $objetRecapMoyenneGenerale->getRangMoyenneClasse();
                        $tabMoyenneGenerale[$unEleve->getId()]['moyGeneCompo'] = $objetRecapMoyenneGenerale->getMoyenneCompo();
                        $tabMoyenneGenerale[$unEleve->getId()]['moyGeneCompoRang'] = $objetRecapMoyenneGenerale->getRangMoyenneCompo();
                        $tabMoyenneGenerale[$unEleve->getId()]['moyGeneGenerale'] = $objetRecapMoyenneGenerale->getMoyenneGenerale();
                        $tabMoyenneGenerale[$unEleve->getId()]['moyGeneGeneraleRang'] = $objetRecapMoyenneGenerale->getRangMoyenneGenerale();
                        

                    }else{
                        $tabMoyenneGenerale[$unEleve->getId()]['moyGeneClasse'] = '-';
                        $tabMoyenneGenerale[$unEleve->getId()]['moyGeneClasseRang'] = '-';
                        $tabMoyenneGenerale[$unEleve->getId()]['moyGeneCompo'] = '-';
                        $tabMoyenneGenerale[$unEleve->getId()]['moyGeneCompoRang'] = '-';
                        $tabMoyenneGenerale[$unEleve->getId()]['moyGeneGenerale'] ='-';
                        $tabMoyenneGenerale[$unEleve->getId()]['moyGeneGeneraleRang'] = '-';
                        
                    }
                    
                    }
            }
        }

        $this->data['objetClasse'] = $objetClasse;
        $this->data['listeEleveClasse'] = $listeEleveClasse;
        $this->data['listeTypeMatiere'] = $listeTypeMatiere;
		$this->data['listeDecoupage'] = $listeDecoupage;
        $this->data['listeEstEnseigne'] = $listeEstEnseigne;
        $this->data['objetDecoupage'] = $objetDecoupage;
        $this->data['tabMoyenneGenerale'] = $tabMoyenneGenerale;
        $this->data['objetAnneeScolaire'] = $objetAnneeScolaire;
        $this->data['tabMatiere'] = $tabMatiere;
        $this->data['tabInfo'] = $tabInfo;
        $this->data['idClasse'] = $idClasse;
        
        $this->data['locale'] = $locale;
        return $this->render($this->scolariteBundle . 'Classe:bulletinClasse.html.twig', $this->data, $this->response);
    }

    
    
    public function getUtilIdFaireCoursACours($em,$id,$idProfil){
        $date = new \Datetime();
        $heureEncours = $date->format('0000:00:00 h:i:s');
        $matierId = 0;
        $codeJourEncours = $date->format('D');
        $objetCodeJourEncours = $em->getRepository($this->scolariteBundle . 'Jour')->findOneBy(array('codeJour'=>$codeJourEncours));
        $objetEnseignant = $em->getRepository($this->userBundle . 'Utilisateur')->find($id);
        $objetFaireCours = array();
        $siHeureEnCours= $this->siHeureEnCours($em, $heureEncours);
       if($siHeureEnCours!=false && count($objetCodeJourEncours)==1){
            $objetHeureEnCours = $em->getRepository($this->scolariteBundle . 'HeureJournee')->find($siHeureEnCours['id']);
            $siAfficheMenuPresence= true;
        }else{
            $siAfficheMenuPresence= false;
        }
        if(count($objetCodeJourEncours)==1 && $siAfficheMenuPresence ){
            $siAfficheMenuPresence = true;
             $objetFaireCours = $em->getRepository($this->scolariteBundle . 'FaireCours')->findOneBy(array('utilisateur'=>$objetEnseignant, 'heurejournee'=>$objetHeureEnCours,'jour'=>$objetCodeJourEncours));
            if($idProfil==\admin\UserBundle\Types\TypeProfil::PROFIL_ENSEIGANT ){
                if(count($objetFaireCours)!=0){
                   $siAfficheMenuPresence = true; 
                   $matierId = $objetFaireCours->getEstEnseigne()->getMatiere()->getId();
                }else{
                    $siAfficheMenuPresence = false;
                }
            }                  
        }else{
            $siAfficheMenuPresence = false;
        }
        if(count($objetFaireCours)!=0){
          return $objetFaireCours->getId();
        }else{
           return 0;  
        }
    }    
    
    public function getMatIdACours($em,$id,$idHeure,$idJour,$idProfil){
        $date = new \Datetime();
        
        $matierId = 0;
        
        $objetCodeJourEncours = $em->getRepository($this->scolariteBundle . 'Jour')->find($idJour);
        $objetEnseignant = $em->getRepository($this->userBundle . 'Utilisateur')->find($id);
        
      //  $siHeureEnCours= $this->siHeureEnCours($em, $heureEncours);
       if(count($objetCodeJourEncours)==1){
            $objetHeureEnCours = $em->getRepository($this->scolariteBundle . 'HeureJournee')->find($idHeure);
            $siAfficheMenuPresence= true;
        }else{
            $siAfficheMenuPresence= false;
        }
        if(count($objetCodeJourEncours)==1 && $siAfficheMenuPresence ){
            $siAfficheMenuPresence = true;
             $objetFaireCours = $em->getRepository($this->scolariteBundle . 'FaireCours')->findOneBy(array('utilisateur'=>$objetEnseignant, 'heurejournee'=>$objetHeureEnCours,'jour'=>$objetCodeJourEncours));
            if($idProfil==\admin\UserBundle\Types\TypeProfil::PROFIL_ENSEIGANT ){
                if(count($objetFaireCours)!=0){
                   $siAfficheMenuPresence = true; 
                   $matierId = $objetFaireCours->getEstEnseigne()->getMatiere()->getId();
                }else{
                    $siAfficheMenuPresence = false;
                }
            }                  
        }else{
            $siAfficheMenuPresence = false;
        }
        return $matierId;
    }     
    
    
}
