<?php

namespace admin\EconomatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use admin\UserBundle\Entity\Module;
use admin\EconomatBundle\Entity\Categorie;
use admin\EconomatBundle\Entity\Facture;
use admin\EconomatBundle\Form\CategorieType;
use admin\EconomatBundle\Services\LoginManager;
use admin\EconomatBundle\Types\TypeMenu;
use Symfony\Component\HttpFoundation\Request;

class OperationController extends Controller
{
    use \admin\UserBundle\ControllerModel\paramUtilTrait;
    protected $nom = 'CategorieController';
    protected $description = 'Controlleur qui gère les opérations';
    protected $response;
    protected $logMessage = ' [ CategorieController ] ';

    /**
     * @var string
     *             Nom du Bundle
     */
   
    /**
     * Déclaration de l'entity manager.
     *
     * @var
     */
    protected $em;
  
    public function __construct()
    {
        //parent::__construct();
        $this->response = new Response();
        $this->response->headers->addCacheControlDirective('no-cache', true);
        $this->response->headers->addCacheControlDirective('max-age', 0);
        $this->response->headers->addCacheControlDirective('must-revalidate', true);
        $this->response->headers->addCacheControlDirective('no-store', true);
    }
    
    
    /**
     * Methode s'occupant de l'ajout du produit.
     *
     * @author galaws1@gmail.com
     * @copyright ADMIN 2015
     *
     * @version 1
     * @return twig d'ajout d'un abonne ajouterproduit.html.twig
     */
    
    public function getPageOperationAction(Request $request,$idEleve, $type) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Avoir accès à la page de paiement des commandes livrées";
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
        $sessionData =  $this->infosConnecte($request);
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_PROD, Module::MOD_GEST_PROD_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des abonnés");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }
       try {
            /*
             * Récupération des la liste des critères
             */
            $this->em = $this->getDoctrine()->getManager();
            //$listeProduit = $this->em->getRepository($this->stockBundle . 'Produit')->getAllProduit();
        } catch (\Exception $e) {
            var_dump($e);
            exit;
        }
        
        //Recuperation du montant A payé
        $idannee= $sessionData['idannee'];
        $classe = $this->em->getRepository($this->scolariteBundle . 'Eleve')->getClasseEnCours($idEleve, $idannee);
        $objetAnnee = $this->em->getRepository($this->scolariteBundle . 'Eleve')->find($idannee);
        if($classe !=NULL){
             $testObjetEcolage = $this->em->getRepository($this->scolariteBundle . 'Ecolage')->findOneBy(array('niveau' => $classe[0]->getNiveau(), 'anneescolaire' => $objetAnnee));
             $montant= $testObjetEcolage->getMontantEcolage();
             $nbreTotalTranche= $testObjetEcolage->getTrancheEcolage();
        }
        /*
         * Préparation des informations que nous allons traiter  sur le twig
         */
        //$this->data['listeProduit'] = $listeProduit;
        $this->data['locale'] = $locale;
        $this->data['idEleve'] = $idEleve;
        $this->data['montant'] = $montant;
        $this->data['nbreTotalTranche'] = $nbreTotalTranche;
        $this->data['type'] = $type;
        
      //  var_dump($montant);exit;

        return $this->render($this->scolariteBundle . 'Eleve:operationPaiement.html.twig', $this->data, $this->response);
    }    
    
    
    
   /**
     * Methode s'occupant 'afficher la ligne d'une commande.
     *
     * @author armand.tevi@gmail.com
     * @copyright ADMIN 2015
     *
     * @version 1
     *
     * @return twig d'ajout d'un abonne detailOperation.html.twig
     */
    public function getInfoCommandeAction(Request $request) {
        $rep = array('etat' => 0, 'msg' => 'Operation bien reussi', 'logout' => FALSE);
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "recuperation de la liste des operations d'une commande";
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
        $sessionData =  $this->infosConnecte($request);
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_FOUR, Module::MOD_GEST_FOUR_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $rep['msg'] = "Vous n'avez pas le droit de recuperer la liste des operations d'une commande";
            return new Response(json_encode($rep));
        }
        
        /*
         * Traitement de la requete qui vient de la vue
         * on vérifie si la requete est Ajax et si la methode est post
         */
        
        if (($request->isXmlHttpRequest()) && ($request->getMethod() == 'POST')) {
            //Recuperation du code de la commande 
            $idEleve =  $request->get('code');
            $type=2;  //$request->get('type');
            
            //Recuperation de l'année scolaire encours
            $anneeScolaire = 1;
            
            $this->em = $this->getDoctrine()->getManager();
            /*
            * Service de gestion des caisse
            */
            
            $operationManager = $this->get('operation_manager');
          //  var_dump($type);exit;   
            $InfoSoldeEleve =  $operationManager->getInfoSoldeEleve($idEleve,$anneeScolaire,$type,'CPTE00001') ;
            $listeOperationScolarite = $operationManager->getListeOperationScolariteEleve($idEleve,$anneeScolaire,$type,'CPTE00001');
//             foreach ($unTypeop->getSchemas() as $unGenreTypeOp) {  
//                 
//             }
        // var_dump($listeOperationScolarite);exit;
            if(count($InfoSoldeEleve)!=0){
                //$this->em->getRepository($this->stockBundle . 'Commande')->getInformationCommande($codeCommande);
                if(count($listeOperationScolarite)==0){
                   $rep['reponse'][0]['solde']=0;  
                }else{
                     $rep['reponse']=$InfoSoldeEleve;
                }
              
               $rep['listeOperationScolarite']=$listeOperationScolarite;
              // var_dump($listeLigneCommande);exit;
               $rep['etat'] = 1;                
            }             
            
        }

        return new Response(json_encode($rep));
    }	
	
    
  /* @copyright ADMIN 2015
     *
     * @version 1
     *
     * @return twig d'ajout d'un abonne detailOperation.html.twig
     */
    public function getListeOperationCommandeAjaxAction(Request $request) {
        $rep = array('etat' => 0, 'msg' => 'Operation bien reussi', 'logout' => FALSE);
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "recuperation de la liste des operations d'une commande";
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
        $sessionData =  $this->infosConnecte($request);
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_FOUR, Module::MOD_GEST_FOUR_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $rep['msg'] = "Vous n'avez pas le droit de recuperer la liste des operations d'une commande";
            return new Response(json_encode($rep));
        }
        
        /*
         * Traitement de la requete qui vient de la vue
         * on vérifie si la requete est Ajax et si la methode est post
         */        
        if (($request->isXmlHttpRequest()) && ($request->getMethod() == 'POST')) {
            //Recuperation du code de la commande 
            $idCommande =  $request->get('idcommande');
            $idAbonne =  $request->get('idabonne');
            $this->em = $this->getDoctrine()->getManager();
            /*
            * Service de gestion des caisse
            */
            
            $operationManager = $this->get('operation_manager');
            
                    
            $listeLigneCommande =  $this->em->getRepository($this->stockBundle . 'Operation')->getOperationsCaisseBrouillard(0, 1,0, 0, 0,$idCommande, $idAbonne,'CPTE001', '0')  ;
            
         // var_dump($listeLigneCommande);exit;
          //  if(count($listeLigneCommande)!=0){
                //$this->em->getRepository($this->stockBundle . 'Commande')->getInformationCommande($codeCommande);
               $rep['reponse']=$listeLigneCommande;
              // var_dump($listeLigneCommande);exit;
               $rep['etat'] = 1;                
           // }             
            
        }

        return new Response(json_encode($rep));
    }    
    
    
   /**
     * Methode s'occupant 'de faire les traitements du paiement fait avec le mode  paiement espece
     *
     * @author armand.tevi@gmail.com
     * @copyright ADMIN 2015
     *
     * @version 1
     *
     * @return twig d'ajout d'un abonne detailOperation.html.twig
     */
    public function traiteCommandeAction(Request $request) {
            $rep = array('etat' => FALSE, 'msg' => 'Erreur survenue lors du traitement', 'logout' => FALSE);
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "traitement du paiement avec l'espece";
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
        $sessionData =  $this->infosConnecte($request);
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_FOUR, Module::MOD_GEST_FOUR_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $rep['msg'] = "Vous n'avez pas le droit de faire le paiement ";
            return new Response(json_encode($rep));
        }
        /*
         * Traitement de la requete qui vient de la vue
         * on vérifie si la requete est Ajax et si la methode est post
         */
        if (($request->isXmlHttpRequest()) && ($request->getMethod() == 'POST')) {
            
                $this->em = $this->getDoctrine()->getManager();
                // Recuperation du type operation 
                $typePaie = $request->get('type');
                $idEleve = $request->get('idEleve');
               // var_dump($idEleve);exit;
                $caisse_id =1;            
                //Recuperation du de linfo util 
                $infoDepo =  $request->get('infoDepoEsp');
                
                $montantEsp =  $request->get('montantEsp');
                $idTypeOp =  2;//$request->get('idTypeOp');
                $anneeScolaire= $sessionData['idannee'];
                $etablissement= $sessionData['etablissement'];
                $infoDepoCheq =  $request->get('infoDepoCheq');
                $montantCheq =  $request->get('montantCheq');
                if( $typePaie == 1){                    
                    $montantEsp =  $request->get('montantEsp');                   
                }else{                   
                    $montantEsp =  $request->get('montantCheq');                                    
                }
                $numCheque =  $request->get('numCheque');
                $telDepoCheq='';
                 $d = new \DateTime();//$sessionData['dateChoisie'];
                 $dateChoisie = $d->format('Y/m/d');
                $nomCompte =  $request->get('nomCompte');              
                $operationManager = $this->get('operation_manager');                
                $objetEleve = $this->em->getRepository($this->scolariteBundle . 'Eleve')->find($idEleve);
                //Recuperation de la classe actuelle de l'élève
                $classe = $this->em->getRepository($this->scolariteBundle . 'Eleve')->getClasseEnCours($idEleve, $anneeScolaire);
               
                if($classe!=NULL ){
                    $idClas = $classe[0]->getId();
                }
                $compteAuxi='';
//                if($idAb != 0 ){                    
//                    $unAbonne = $this->em->getRepository($this->userBundle . 'Abonne')->find($idAb);
//                    $compteAuxi ='CPTE001' ;//$unAbonne->getNumCompte(); 
//                }else{
//                    $compteAuxi='';
//                }
                                                
                $d = new \DateTime();
                $an = $d->format('Y');
                $m = $d->format('m');
                $refFacture = $this->getRefLivrer($this->em, 1, $an, $m, $entite = 'LIVRER', $taille = 5);
                
                //creation de l'entite fature
                $uneFacture = new Facture();
                $uneFacture->setCodeFacture($refFacture);
                $uneFacture->setDatePublication($d);
                // var_dump($typeAction);exit;
                $uneFacture->setTypeFacture(0);
                $this->em->persist($uneFacture);
                $this->em->flush();                
                $montantEsp= preg_replace('/\D/', '', $montantEsp);
                $unTypeop = $this->em->getRepository($this->stockBundle . 'TypeOperation')->find($idTypeOp);
                $i=0;
                $lesComptes=array();
                $lesMontants=array();                  
                 // var_dump($unTypeop->getSchemas()[0]);exit;
                foreach ($unTypeop->getSchemas() as $unGenreTypeOp) {                                                    
                      $lesComptes[$i]= $unGenreTypeOp->getPlancomptable()->getCompte(); 
                      $lesMontants[$i]=$montantEsp ;                                                            
                  $i++;
                }                
                
                if($typePaie == 1){//Traitement des especes
                   $montantReste =20000;//(int)$laCommande->getMontantResteCommande()-(int)$montantEsp;                    
                    // $operationManager->geneLigneOperation($idCommande,$caisse_id,$montantEsp, $infoDepo,$refFacture);  
                   $operationManager->geneLigneOperationComptable($idEleve, $caisse_id , $lesMontants, $infoDepo,$telDepo='', $refFacture, '',$lesComptes, $idTypeOp,  $piece =0, $nomCompte='',$typePaie,$idClas,$compteAuxi,$dateChoisie, $anneeScolaire, $etablissement) ;                 
                  
                   }else{
                   $montantReste =20000;////(int)$laCommande->getMontantResteCommande()-(int)$montantCheq;   
                   $operationManager->geneLigneOperationComptable($idEleve, $caisse_id , $lesMontants, $infoDepo,$telDepoCheq, $refFacture,$numCheque, $lesComptes, $idTypeOp, $piece =0, $nomCompte,$typePaie,$idClas,$compteAuxi,$dateChoisie, $anneeScolaire, $etablissement) ;
                   // $operationManager->geneLigneOperation($idCommande,$caisse_id,$montantCheq, $infoDepoCheq,$refFacture);
                }
                $etatCommande=1;
                if($montantReste ==0 ){
                    $etatCommande = 2;
                }
//                $laCommande->setMontantResteCommande($montantReste);
//                $laCommande->setEtatCommande($etatCommande);
              //  $this->em->persist($laCommande);
             //   $this->em->flush();                
                $rep['etat'] =true; 
                $rep['reponse']['reponse'] = $infoDepo;
                $rep['reponse']['montantEsp'] = $montantEsp;
                $rep['reponse']['infoDepoCheq'] = $infoDepoCheq;
                $rep['reponse']['montantCheq'] = $montantCheq;
                $rep['reponse']['numCheque'] = $numCheque;
                $rep['reponse']['nomCompte'] = $nomCompte;
                
        }
        return new Response(json_encode($rep));
    }	
	
    /**
     * Methode s'occupant 'de faire les traitements du paiement fait avec le mode  paiement espece
     *
     * @author armand.tevi@gmail.com
     * @copyright ADMIN 2015
     *
     * @version 1
     *
     * @return twig d'ajout d'un abonne detailOperation.html.twig
     */
    public function traiteCommandeChequeAction(Request $request) {
            $rep = array('etat' => FALSE, 'msg' => 'Erreur survenue lors du traitement', 'logout' => FALSE);
        /**
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "traitement du paiement avec cheque";
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
        $sessionData =  $this->infosConnecte($request);
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_FOUR, Module::MOD_GEST_FOUR_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $rep['msg'] = "Vous n'avez pas le droit de faire le paiement ";
            return new Response(json_encode($rep));
        }
        /*
         * Traitement de la requete qui vient de la vue
         * on vérifie si la requete est Ajax et si la methode est post
         */
        if (($this->request->isXmlHttpRequest()) && ($this->request->getMethod() == 'POST')) {

        }

        return new Response(json_encode($rep));
    }   
        /**
     * Methode s'occupant de l'ajout du operation.
     *
     * @author armand.tevi@gmail.com
     * @copyright ADMIN 2015
     *
     * @version 1
     *
     * @return twig d'ajout d'un abonne ajouterOperation.html.twig
     */
    public function afficherFactureAction(Request $request) {
               
        return $this->render($this->stockBundle . 'Operation:templateFacture.html.twig', $this->data, $this->response);
    }
    
        /**
     * Methode s'occupant d'imprimer .
     *
     * @author armand.tevi@gmail.com
     * @copyright ADMIN 2015
     *
     * @version 1
     *
     * @return twig d'ajout d'un abonne ajouterOperation.html.twig
     */
    public function traiteRecuAction(Request $request,$id) {
       $this->em = $this->getDoctrine()->getManager(); 
        //Recuperation des informations concernant le client
        $infoClient = $this->em->getRepository($this->stockBundle . 'Commande')->getClientCommande($id);
        //Recuperation des informations concernant les lignes commande
        $ligneCommande = $this->em->getRepository($this->stockBundle . 'Commande')->getLigneCommande($id);
        //Recuperation des informations concernant les operation de la commande 
        $ligneOperation = $this->em->getRepository($this->stockBundle . 'Operation')->getListeOperationCommande($id);
        
                /*
         * Preparation des informations à traiter sur les twig 
         */
        $this->data['infoClient'] = $infoClient;
        $this->data['ligneCommande'] = $ligneCommande;
        $this->data['ligneOperation'] = $ligneOperation;
        return $this->render($this->stockBundle . 'Operation:recuPaiement.html.twig', $this->data, $this->response);
    }   
    
    /**
     * Methode s'occupant de lister les Operations.
     *
     * @author armand.tevi@gmail.com
     * @copyright ADMIN 2015
     *
     * @version 1
     *
     * @return twig d'ajout d'un abonne listeOperation.html.twig
     */
    public function listerOperationAction(Request $request) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage de la liste des operations";
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
        $sessionData =  $this->infosConnecte($request);
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_FOUR, Module::MOD_GEST_FOUR_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des abonnés");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }



        try {
            /*
             * Récupération des la liste des critères
             */
            $this->em = $this->getDoctrine()->getManager();
            $listeOperation = $this->em->getRepository($this->stockBundle . 'Operation')->getAllOperation();
        } catch (\Exception $e) {
            var_dump($e);
            exit;
        }
       // var_dump($listeOperation);exit;
        /*
         * Préparation des informations que nous allons traiter  sur le twig
         */
        $this->data['listeOperation'] = $listeOperation;
        $this->data['locale'] = $locale;

        return $this->render($this->stockBundle . 'Operation:listeOperation.html.twig', $this->data, $this->response);
    }    
}
