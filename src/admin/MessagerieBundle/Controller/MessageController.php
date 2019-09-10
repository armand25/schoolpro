<?php

namespace admin\MessagerieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use admin\UserBundle\Entity\Module;
use admin\UserBundle\Services\LoginManager;
use admin\MessagerieBundle\Types\TypeStatutEnvoi;
use admin\UserBundle\Types\TypeEtat;
use admin\MessagerieBundle\Entity\Message;
use Symfony\Component\HttpFoundation\Request;

/**
 * MessageController.
 */
class MessageController extends Controller
{
    
use \admin\UserBundle\ControllerModel\paramUtilTrait;    
    
    /**
     * Nom du controleur.
     */
    private $nom = 'ChampController';

    /**
     * Description  du controleur.
     */
    private $description = 'Contra´leur qui gere les type Champs';

    /*
     * Declaration de la methode  de recuperation de la response
     */
    private $response;

    /**
     * Declaration de la methode de recuperation de la resquest.
     */
    private $request;

    /**
     * Declaration de la methode de recuperation de la session.
     */
    private $session;

    /**
     * Declaration des messages flash.
     */
    private $flashMessage;

    /**
     * Information permettant d'identifier la methode dans les fichiers de log.
     */
    private $logMessage = ' [ ChampController ] ';

    /**
     * Declaration de l'entity manager.
     *
     * @var
     */
    protected $em;

 

    /**
     * @var string
     *             Le controleur 
     */
    public function __construct()
    {
        $this->response = new Response();
        $this->response->headers->addCacheControlDirective('no-cache', true);
        $this->response->headers->addCacheControlDirective('max-age', 0);
        $this->response->headers->addCacheControlDirective('must-revalidate', true);
        $this->response->headers->addCacheControlDirective('no-store', true);
        $this->logMessage = ' [ MessageController ] ';
        $this->description = 'Controlleur qui gère la messagerie';
    }

    /*
     * Formulaire d'envoi de message aux abonnés 
     * $idMessageRepondre = identifiant du message auquel on souhaite repondre, 
     * $idMessageTransfere : identifiant du message on souhaute transferer
     * $idAbonne : abonné à qui on envoi le message
     * 
     * @param type $idMessageTransfere
     * @param type $idMessageRepondre
     * @return type
     */
    public function nouveauMessageToAUserAction(Request $request, $idMessageTransfere, $idMessageRepondre, $idProfil)
    {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = 'Envoi de messages aux admins';
        /*
         * Préparation du message de log 
         */
        $this->logMessage .= ' [ '.$nomAction.' ] ';
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
        if (!$loginManager->getOrSetActions(Module::MOD_MESSAGE_ADMIN, Module::MOD_MESSAGE_ADMIN_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'envoyer un message aux admins");

            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }
        $messagerieManager = $this->get('messagerie_manager');
        $em = $this->getDoctrine()->getManager();
        
//        if($sessionData['idProfil'] == \admin\UserBundle\Types\TypeProfil::PROFIL_PARENT_ELEVE){
//            $idProfil =  \admin\UserBundle\Types\TypeProfil::PROFIL_UTILISATEUR;
//        }
        $idProfil =$sessionData['idProfil'];
        $etablissement = $sessionData['etablissement'];
        $utilisateur = $em->getRepository($this->userBundle.'Utilisateur')->find($sessionData['id']);
        if ($utilisateur == null) {
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }

        /*
         * Il on cherche à recupérer le message qu'on veut transfere/répondre
         */
        $messageRepondre = $em->getRepository($this->messagerieBundle.'Message')->find($idMessageRepondre);
        $messageTransfere = $em->getRepository($this->messagerieBundle.'Message')->find($idMessageTransfere);
        $listeProfil = $em->getRepository($this->userBundle.'Profil')->getAllProfil();

        /*
         * Les potentiels destinataires admin
         * 
         * VOUS POUVEZ PERSONNALISER CETTE REQUETTE
         */
        $allAdmin = $messagerieManager->getAllUserMessage($sessionData['id'], $idProfil,$etablissement);

        /*
         * Au cas ou c'est une réponse à un mail
         * Le tableau $tabAdmin contient l'identifiant des admin étant en copie du msg auquel on répond
         * Le tableau $tabIdAbonne contient l'identifiant des abonné étant en copie du msg auquel on répond
         */
        $tabIdAdmin = array();
        /*
         * S'il s'agit d'une réponse à un autre message, les distinaires par défauts sont :
         * - L'émetteur du message 
         * - Les destinateurs du message $messageRepondre
         */
        if ($messageRepondre != null) {
            /*
             * Recupération des destinaires du message $messageRepondre
             */
            $allAdminDestinataire = $messagerieManager->getDestinairesOfMessage($messageRepondre);
            $tabIdAdmin = $allAdminDestinataire['tabIdAdmin'];
            /*
             * On ajoute ensuite l'emetteur du message
             */
            $emetteur = $messageRepondre->getUtilisateur();
            if ($emetteur != null) {
                $idEmetteur = $emetteur->getId();
                if (!in_array($idEmetteur, $tabIdAdmin)) {
                    $tabIdAdmin[] = $idEmetteur;
                }
            }
        }

        $fils = null;
        $message = null;
        /*
         * Au cas ou c'est une réponse à un autre message, on recupere le fil de discussion et on l'affiche au dessous du formulaire
         */
        if ($messageRepondre != null) {
            $message = $messagerieManager->getMessageDebutTiket($messageRepondre->getCodeFil());
            if ($message != null) {
                $fils = $messagerieManager->getFilMessage($message, $sessionData);
            }
        }

        $this->data['message'] = $message;
        $this->data['fils'] = $fils;
        $this->data['messageRepondre'] = $messageRepondre;
        $this->data['listeProfil'] = $listeProfil;
        $this->data['messageTransfere'] = $messageTransfere;
        $this->data['allAdmin'] = $allAdmin;
        $this->data['tabIdAdmin'] = $tabIdAdmin;
        $this->data['idMessageRepondre'] = $idMessageRepondre;
        $this->data['idMessageTransfere'] = $idMessageTransfere;
        $this->data['messageRepondre'] = $messageRepondre;
        $this->data['messageTransfere'] = $messageTransfere;
        $this->data['locale'] = $locale;

        return $this->render($this->messagerieBundle.'Admin:nouveauToUser.html.twig', $this->data, $this->response);
    }

    /*
     * Enregistre un message envoyé à un admin par un admin par AJAX. ( le formulaire d'envoi est accessbile avec la route app_messagerie_send_msg_to_utilisateur) 
     * 
     * @return Response
     */
    public function nouveauMessageToAUserSaveAction(Request $request)
    {
        $rep = array('etat' => false, 'msg' => 'Erreur survenue lors du traitement');
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = 'Enregistre un message envoyé à un admin par un admin par AJAX';
        /*
         * Préparation du message de log 
         */
        $this->logMessage .= ' [ '.$nomAction.' ] ';
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
            $rep['msg'] = 'Vous êtes déconnecté. Veuillez-vous connecter de nouveau';

            return new Response(json_encode($rep));
        }

        /*
         * Gestion des droits
         */
//        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction , $descAction, $sessionData['idProfil'])) {
////            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
////            return new Response(json_encode($rep));
//        }

        $em = $this->getDoctrine()->getManager();
        $utilisateur = $em->getRepository($this->userBundle.'Utilisateur')->find($sessionData['id']);
        if ($utilisateur == null) {
            return new Response(json_encode($rep));
        }

        if (($request->isXmlHttpRequest()) && ($request->getMethod() == 'POST')) {
            /*
             * Services necessaires aux traitements
             */
            $messagerieManager = $this->get('messagerie_manager');
            $randCodeManager = $this->get('randcode_manager');
            /*
             * Récupération des identifiants du message auquel on souhaite répondre/transferer
             */
            $idMessageRepondre = (int) $request->request->get('idMessageRepondre');
            $idMessageTransfere = (int) $request->request->get('idMessageTransfere');
            /*
             * Indique si le massage doit etre anvoyé à tous les admins ou pas
             */
            $allAdmin = (int) $request->request->get('allAdmin');
            /*
             * Récupération des identifiants des admin recepeteurs du message
             */
            $idsAdmin = $request->request->get('idsAdmin');
            /*
             * Le contenu du message est débarassé des entités html (&eacute et autre, ...)
             */
            $contenuMessage = html_entity_decode($request->request->get('message'));
            /*
             * L'objet du message est débarassé des entités html (&eacute et autre, ...)
             */
            $objet = html_entity_decode($request->request->get('objet'));

            /*
             * L'obet et le contenu du message ne doivent pas etre vide
             */
            if (strlen($objet) < 1) {
                $rep['msg'] = "Vous devez indiquer l'objet du message ";

                return new Response(json_encode($rep));
            }
            if (strlen($contenuMessage) < 1) {
                $rep['msg'] = 'Vous devez indiquer le contenu du message ';

                return new Response(json_encode($rep));
            }

            /*
             * On débarasse le contenu claire du message des :
             * - balise html
             * - espaces blancs multiples
             * - des tabulations
             * - des retoure à la ligne
             */
            $contenuMessageClaire = preg_replace('( +)', ' ', str_replace(array("\t", "\r", "\n"), ' ', trim(strip_tags($contenuMessage))));

            /*
             * Il on cherche à recupérer le message qu'on veut transfere/répondre
             */
            $messageRepondre = $em->getRepository($this->messagerieBundle.'Message')->find($idMessageRepondre);
            $messageTransfere = $em->getRepository($this->messagerieBundle.'Message')->find($idMessageTransfere);

            $message = new Message();
            $message->setCodeFil(date('Ymds').$randCodeManager->randAlphaNumerique(10));
            if ($messageRepondre != null) {
                $message->setMessageRepondu($messageRepondre);
                $message->setCodeFil($messageRepondre->getCodeFil());
            }
            if ($messageTransfere != null) {
                $message->setMessageTransfere($messageTransfere);
            }

            /*
             * On récupere les destinaires admin
             */
            $tabAdmin = array();
            $tabIdsAdmin = array_unique(explode('|', $idsAdmin));
            foreach ($tabIdsAdmin as $key => $idS) {
                $idS = (int) $idS;
                $unAdmin = $em->getRepository($this->userBundle.'Utilisateur')->find($idS);
                if ($unAdmin != null) {
                    $tabAdmin[] = $unAdmin;
                }
                $tabIdsAdmin[$key] = $idS;
            }

            if ($allAdmin == 1) {
                /*
                 * Au cas c'est une une néponse à un autre mail, l'utilisateur courant fait parti des destinaires
                 * 
                 * VOUS POUVEZ PERSONNALISER CETTE REQUETTE
                 */
                $tabAdmin = $messagerieManager->getAllUserMessage($sessionData['id']);
            }
            /*
             * L'admin emetteur du message recoit aussi un envoi, afin de former le fil de discussion
             */
            if (!in_array($sessionData['id'], $tabIdsAdmin)) {
                $tabAdmin[] = $utilisateur;
            }

            if (count($tabAdmin) < 1) {
                $rep['msg'] = 'Vous devez indiquer au moins un destinataire ';

                return new Response(json_encode($rep));
            }

            $message->setObjet($objet);
            $message->setContenuClaire($contenuMessageClaire);
            $message->setContenu($contenuMessage);
            $message->setUtilisateur($utilisateur);

            /*
             * Envoi du message
             */
            $messagerieManager->sendMessageToUser($message, $tabAdmin, $sessionData);

            $rep['msg'] = '';
            $rep['etat'] = true;
            $this->get('session')->getFlashBag()->add('send.msg.succes', 'Message envoyé avec succès');

            return new Response(json_encode($rep));
        }

        return new Response(json_encode($rep));
    }

    /*
     * Boîte de réception
     * 
     * @param type typeMessage : 0 = Message recu d'abonné + message recu d'utilisateur; 1 = Message Abonne uniquement, 2 = Message utilisateur uniquement
     * @param type $mot
     * @param type $dateDebut
     * @param type $dateFin
     * @param type $nbParPage
     * @param type $pageActuelle
     * @return type
     */
    public function boiteReceptionAction(Request $request, $typeMessage, $mot, $dateDebut, $dateFin, $nbParPage, $pageActuelle)
    {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Boite de reception d'un utilisateur";
        /*
         * Préparation du message de log 
         */
        $this->logMessage .= ' [ '.$nomAction.' ] ';
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
        if (!$loginManager->getOrSetActions(Module::MOD_MESSAGE_ADMIN, Module::MOD_MESSAGE_ADMIN_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la boîte de réception");

            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }

        /*
         * Service de gestion des dates
         */
        $dateManager = $this->get('date_manager');
        /*
         * Controles sur les dates de début et de fin
         */
        $yearActuelle = (int) date('Y');
        if (!$dateManager->isDateUI($dateDebut, '-')) {
            $dateDebut = '01-01-'.$yearActuelle;
        }

        $yearFinDefault = $yearActuelle + 1;
        if (!$dateManager->isDateUI($dateFin, '-')) {
            $dateFin = '01-01-'.$yearFinDefault;
        }

        if (strtotime($dateFin) < strtotime($dateDebut)) {
            $dateDebut = '01-01-'.$yearActuelle;
            $dateFin = '01-01-'.$yearFinDefault;
        }
        /*
         * Indique s'il y a une recherche. Si oui, on affiche si sur la vue les= nombre le résultat correspond à la recherche
         */
        $search = false;

//        $em = $this->getDoctrine()->getManager();

        if ($request->isMethod('POST')) {
            $search = true;
            $dateDebut = $request->request->get('dateDebut');
            $dateFin = $request->request->get('dateFin');
            $mot = $request->request->get('mot');

            if (!$dateManager->isDateUI($dateDebut, '-')) {
                $dateDebut = '01-01-'.$yearActuelle;
            }

            if (!$dateManager->isDateUI($dateFin, '-')) {
                $dateFin = '01-01-'.$yearFinDefault;
            }

            if (strtotime($dateFin) < strtotime($dateDebut)) {
                $dateDebut = '01-01-'.$yearActuelle;
                $dateFin = '01-01-'.$yearFinDefault;
            }
            if (($mot == null) || (strlen($mot) == 0)) {
                $mot = '';
            }
        }
        /*
         * Conversion des dates au format string en Objet \DateTime()
         */
        $objetDateDebut = $dateManager->getDateTimeByDateUI($dateDebut, $separateurDate = '-', true);
        $objetDateFin = $dateManager->getDateTimeByDateUI($dateFin, $separateurDate = '-', false);
        /*
         * $typeMessage : 0 = Message recu d'abonné + message recu d'utilisateur; 1 = Message Abonne uniquement, 2 = Message utilisateur uniquement
         */
        if (($typeMessage < 0) || ($typeMessage > 2)) {
            $typeMessage = 0;
        }

        $messagerieManager = $this->get('messagerie_manager');

        $queryResult = $messagerieManager->getListeMessageRecu($sessionData['id'], $objetDateDebut, $objetDateFin, $mot, $nbParPage, $pageActuelle, true, $typeMessage);

        $nbParPage = $queryResult['nbParPage'];
        $pageActuelle = $queryResult['pageActuelle'];
        $nbTotal = $queryResult['nbTotal'];
        $nbTotalPage = $queryResult['nbTotalPage'];

        $envois = $queryResult['data'];

        /*
         * COnfiguration de la pagination
         */
        $lastPage = $nbTotalPage;

        $nextPage = $pageActuelle + 1;
        if ($nextPage > $lastPage) {
            $nextPage = $lastPage;
        }
        $previousPage = $pageActuelle - 1;
        if ($previousPage < 1) {
            $previousPage = 1;
        }

        $debutPagination = $pageActuelle - 2;
        if ($debutPagination < 1) {
            $debutPagination = 1;
        }

        $finPagination = $pageActuelle + 2;
        if ($finPagination > $lastPage) {
            $finPagination = $lastPage;
        }

        $this->data['finPagination'] = $finPagination;
        $this->data['debutPagination'] = $debutPagination;
        $this->data['previousPage'] = $previousPage;
        $this->data['nextPage'] = $nextPage;
        $this->data['lastPage'] = $lastPage;
        $this->data['nbParPage'] = $nbParPage;
        $this->data['pageActuelle'] = $pageActuelle;
        $this->data['nbTotal'] = $nbTotal;
        $this->data['nbTotalPage'] = $nbTotalPage;
        $this->data['envois'] = $envois;

        $this->data['search'] = $search;
        $this->data['mot'] = $mot;
        $this->data['dateDebut'] = $dateDebut;
        $this->data['dateFin'] = $dateFin;
        $this->data['typeMessage'] = $typeMessage;
        $this->data['locale'] = $locale;
       // $this->updateMessageNonLu();

        return $this->render($this->messagerieBundle.'Admin:boiteReception.html.twig', $this->data, $this->response);
    }

    /*
     * Affiche la liste des messages envoyés par un utilisateur
     * @param type $mot
     * @param type $dateDebut
     * @param type $dateFin
     * @param type $nbParPage
     * @param type $pageActuelle
     * @return type
     */
    public function messageEnvoyesAction(Request $request, $mot, $dateDebut, $dateFin, $nbParPage, $pageActuelle)
    {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = 'Affiche la liste des messages envoyés par un utilisateur';
        /*
         * Préparation du message de log 
         */
        $this->logMessage .= ' [ '.$nomAction.' ] ';
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
        if (!$loginManager->getOrSetActions(Module::MOD_MESSAGE_ADMIN, Module::MOD_MESSAGE_ADMIN_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder aux messages envoyés");

            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }
        /*
         * Service de gestion des dates et de la messagerie
         */
        $messagerieManager = $this->get('messagerie_manager');
        $dateManager = $this->get('date_manager');
        /*
         * Controles sur les dates de début et de fin
         */
        $yearActuelle = (int) date('Y');
        if (!$dateManager->isDateUI($dateDebut, '-')) {
            $dateDebut = '01-01-'.$yearActuelle;
        }

        $yearFinDefault = $yearActuelle + 1;
        if (!$dateManager->isDateUI($dateFin, '-')) {
            $dateFin = '01-01-'.$yearFinDefault;
        }

        if (strtotime($dateFin) < strtotime($dateDebut)) {
            $dateDebut = '01-01-'.$yearActuelle;
            $dateFin = '01-01-'.$yearFinDefault;
        }
        /*
         * Indique s'il y a une recherche. Si oui, on affiche si sur la vue les= nombre le résultat correspond à la recherche
         */
        $search = false;

//        $em = $this->getDoctrine()->getManager();

        if ($request->isMethod('POST')) {
            $search = true;
            $dateDebut = $request->request->get('dateDebut');
            $dateFin = $request->request->get('dateFin');
            $mot = $request->request->get('mot');

            if (!$dateManager->isDateUI($dateDebut, '-')) {
                $dateDebut = '01-01-'.$yearActuelle;
            }

            if (!$dateManager->isDateUI($dateFin, '-')) {
                $dateFin = '01-01-'.$yearFinDefault;
            }

            if (strtotime($dateFin) < strtotime($dateDebut)) {
                $dateDebut = '01-01-'.$yearActuelle;
                $dateFin = '01-01-'.$yearFinDefault;
            }
            if (($mot == null) || (strlen($mot) == 0)) {
                $mot = '';
            }
        }
        /*
         * Conversion des dates au format string en Objet \DateTime()
         */
        $objetDateDebut = $dateManager->getDateTimeByDateUI($dateDebut, $separateurDate = '-', true);
        $objetDateFin = $dateManager->getDateTimeByDateUI($dateFin, $separateurDate = '-', false);

        $queryResult = $messagerieManager->getListeMessageEnvoyes($sessionData['id'], $objetDateDebut, $objetDateFin, $mot, $nbParPage, $pageActuelle, true);

        $nbParPage = $queryResult['nbParPage'];
        $pageActuelle = $queryResult['pageActuelle'];
        $nbTotal = $queryResult['nbTotal'];
        $nbTotalPage = $queryResult['nbTotalPage'];

        $envois = $queryResult['data'];
        /*
         * COnfiguration de la pagination
         */
        $lastPage = $nbTotalPage;

        $nextPage = $pageActuelle + 1;
        if ($nextPage > $lastPage) {
            $nextPage = $lastPage;
        }
        $previousPage = $pageActuelle - 1;
        if ($previousPage < 1) {
            $previousPage = 1;
        }

        $debutPagination = $pageActuelle - 2;
        if ($debutPagination < 1) {
            $debutPagination = 1;
        }

        $finPagination = $pageActuelle + 2;
        if ($finPagination > $lastPage) {
            $finPagination = $lastPage;
        }

        $this->data['finPagination'] = $finPagination;
        $this->data['debutPagination'] = $debutPagination;

        $this->data['previousPage'] = $previousPage;
        $this->data['nextPage'] = $nextPage;
        $this->data['lastPage'] = $lastPage;
        $this->data['nbParPage'] = $nbParPage;
        $this->data['pageActuelle'] = $pageActuelle;
        $this->data['nbTotal'] = $nbTotal;
        $this->data['nbTotalPage'] = $nbTotalPage;
        $this->data['envois'] = $envois;

        $this->data['search'] = $search;
        $this->data['mot'] = $mot;
        $this->data['dateDebut'] = $dateDebut;
        $this->data['dateFin'] = $dateFin;
        $this->data['locale'] = $locale;

        return $this->render($this->messagerieBundle.'Admin:messagesEnvoyes.html.twig', $this->data, $this->response);
    }

    /*
     * Formulaire d'envoi de message aux abonnés 
     * $idMessageRepondre = identifiant du message auquel on souhaite repondre, 
     * $idMessageTransfere : identifiant du message on souhaute transferer
     * $idAbonne : abonné à qui on envoi le message
     * 
     * @param type $idMessageTransfere
     * @param type $idMessageRepondre
     * @return type
     */
    public function nouveauMessageToAbonneAction(Request $request, $idMessageTransfere, $idMessageRepondre)
    {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = 'Envoi de messages aux abonnés';
        /*
         * Préparation du message de log 
         */
        $this->logMessage .= ' [ '.$nomAction.' ] ';
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
        if (!$loginManager->getOrSetActions(Module::MOD_MESSAGE_ADMIN, Module::MOD_MESSAGE_ADMIN_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'envoyer un message aux abonnés");

            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }
        $messagerieManager = $this->get('messagerie_manager');
        $em = $this->getDoctrine()->getManager();

        $utilisateur = $em->getRepository($this->userBundle.'Utilisateur')->find($sessionData['id']);
        if ($utilisateur == null) {
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }

        /*
         * Il on cherche à recupérer le message qu'on veut transfere/répondre
         */
        $messageRepondre = $em->getRepository($this->messagerieBundle.'Message')->find($idMessageRepondre);
        $messageTransfere = $em->getRepository($this->messagerieBundle.'Message')->find($idMessageTransfere);

        /*
         * Au cas ou il s'agit dune réponse à un autre message, on cherche l'abonné émetteur du message
         */
        $idAbonne = 0;
        $abonne = null;
        if ($messageRepondre != null) {
            $abonne = $messageRepondre->getAbonne();
            if ($abonne != null) {
                $idAbonne = $abonne->getId();
            }
        }

        /*
         * Les potentiels destinataires abonné
         * 
         * VOUS POUVEZ PERSONNALISER CETTE REQUETTE
         */
        $allAbonne = $messagerieManager->getAllAbonneMessage();
        /*
         * Les potentiels destinataires admin
         * 
         * VOUS POUVEZ PERSONNALISER CETTE REQUETTE
         */
        $allAdmin = $messagerieManager->getAllUserMessage($sessionData['id']);

        /*
         * Au cas ou c'est une réponse à un mail
         * Le tableau $tabAdmin contient l'identifiant des admin étant en copie du msg auquel on répond
         * Le tableau $tabIdAbonne contient l'identifiant des abonné étant en copie du msg auquel on répond
         */
        $tabIdAdmin = array();
        $tabIdAbonne = array();
        /*
         * S'il s'agit d'une réponse à un autre message, les distinaires par défauts sont :
         * - L'émetteur du message 
         * - Les destinateurs du message $messageRepondre
         */
        if ($messageRepondre != null) {
            /*
             * Ici on récupère l'emetteur du message
             */
            $abonne = $messageRepondre->getAbonne();
            /*
             * Recupération des destinaires du message $messageRepondre
             */
            $allAdminDestinataire = $messagerieManager->getDestinairesOfMessage($messageRepondre);
            $tabIdAdmin = $allAdminDestinataire['tabIdAdmin'];

            /*
             * On ajoute l'émetteur abonné au cas ou le message auquel on repond est écri par un abonné
             */
            $tabIdAbonne = $allAdminDestinataire['tabIdAbonne'];
            if (($idAbonne > 0) && (!in_array($idAbonne, $tabIdAbonne))) {
                $tabIdAbonne[] = $idAbonne;
            }
        }

        $fils = null;
        $message = null;
        /*
         * On recupère le fil de discussion correspond au mail pour l'afficher au dessus du formulaire
         */
        if ($messageRepondre != null) {
            $message = $messagerieManager->getMessageDebutTiket($messageRepondre->getCodeFil());
            if ($message != null) {
                $fils = $messagerieManager->getFilMessage($message, $sessionData);
//                $premierEnvoiNonLu = $em->getRepository($this->messagerieBundle . 'Envoi')->find($fils['idFirstEnvoiNonLu']);
//                if ($premierEnvoiNonLu != NULL) {
//                    $premierEnvoiNonLu->setStatut(TypeStatutEnvoi::MSG_LU);
//                    $em->flush();
//                }
            }
        }

        $this->data['message'] = $message;
        $this->data['fils'] = $fils;

        $this->data['messageRepondre'] = $messageRepondre;
        $this->data['messageTransfere'] = $messageTransfere;
        $this->data['allAbonne'] = $allAbonne;
        $this->data['allAdmin'] = $allAdmin;
        $this->data['tabIdAdmin'] = $tabIdAdmin;
        $this->data['tabIdAbonne'] = $tabIdAbonne;
        $this->data['idMessageRepondre'] = $idMessageRepondre;
        $this->data['idMessageTransfere'] = $idMessageTransfere;
        $this->data['messageRepondre'] = $messageRepondre;
        $this->data['messageTransfere'] = $messageTransfere;
        $this->data['idAbonne'] = $idAbonne;
        $this->data['abonne'] = $abonne;
        $this->data['locale'] = $locale;

        return $this->render($this->messagerieBundle.'Admin:nouveauToAbonne.html.twig', $this->data, $this->response);
    }

    /*
     * Enregistre un message envoyé à un abonné par AJAX. ( le formulaire d'envoi est accessbile avec la route app_messagerie_send_msg_to_abonne) 
     * 
     * @return Response
     */
    public function nouveauMessageToAbonneSaveAction(Request $request)
    {
        $rep = array('etat' => false, 'msg' => 'Erreur survenue lors du traitement');
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = 'Enregistre un message envoyé à un abonné par AJAX';
        /*
         * Préparation du message de log 
         */
        $this->logMessage .= ' [ '.$nomAction.' ] ';
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
            $rep['msg'] = 'Vous êtes déconnecté. Veuillez-vous connecter de nouveau';

            return new Response(json_encode($rep));
        }

        /*
         * Gestion des droits
         */
//        if (!$loginManager->getOrSetActions(Module::MOD_GEST_USER, Module::MOD_GEST_USER_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction , $descAction, $sessionData['idProfil'])) {
////            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
////            return new Response(json_encode($rep));
//        }

        $em = $this->getDoctrine()->getManager();
        $utilisateur = $em->getRepository($this->userBundle.'Utilisateur')->find($sessionData['id']);
        if ($utilisateur == null) {
            return new Response(json_encode($rep));
        }

        if (($request->isXmlHttpRequest()) && ($request->getMethod() == 'POST')) {
            /*
             * Services necessaires aux traitements
             */
            $messagerieManager = $this->get('messagerie_manager');
            $randCodeManager = $this->get('randcode_manager');

            /*
             * Récupération des identifiants du message auquel on souhaite répondre/transferer
             */
            $idMessageRepondre = (int) $request->request->get('idMessageRepondre');
            $idMessageTransfere = (int) $request->request->get('idMessageTransfere');

            /*
             * Choix de tous les admins/abonnés
             */
            $allAbonne = (int) $request->request->get('allAbonne');
            $allAdmin = (int) $request->request->get('allAdmin');

            /*
             * Récupération des identifiants des abonnés recepeteurs du message
             */
            $idsAbonne = $request->request->get('idsAbonne');
            /*
             * Récupération des identifiants des admin recepeteurs du message
             */
            $idsAdmin = $request->request->get('idsAdmin');
            /*
             * Le contenu du message est débarassé des entités html (&eacute et autre, ...)
             */
            $contenuMessage = html_entity_decode($request->request->get('message'));
            /*
             * L'objet du message est débarassé des entités html (&eacute et autre, ...)
             */
            $objet = html_entity_decode($request->request->get('objet'));
            /*
             * L'obet et le contenu du message ne doivent pas etre vide
             */
            if (strlen($objet) < 1) {
                $rep['msg'] = "Vous devez indiquer l'objet du message ";

                return new Response(json_encode($rep));
            }
            if (strlen($contenuMessage) < 1) {
                $rep['msg'] = 'Vous devez indiquer le contenu du message ';

                return new Response(json_encode($rep));
            }

            /*
             * On débarasse le contenu claire du message des :
             * - balise html
             * - espaces blancs multiples
             * - des tabulations
             * - des retoure à la ligne
             */
            $contenuMessageClaire = preg_replace('( +)', ' ', str_replace(array("\t", "\r", "\n"), ' ', trim(strip_tags($contenuMessage))));

            /*
             * Il on cherche à recupérer le message qu'on veut transfere/répondre
             */
            $messageRepondre = $em->getRepository($this->messagerieBundle.'Message')->find($idMessageRepondre);
            $messageTransfere = $em->getRepository($this->messagerieBundle.'Message')->find($idMessageTransfere);

            $message = new Message();
            $message->setCodeFil(date('Ymds').$randCodeManager->randAlphaNumerique(10));
            if ($messageRepondre != null) {
                /*
                 * Au cas ou c'est une réponse à un autre message, le nouveau message et l'ancient doivent avoir le mm code
                 */
                $message->setMessageRepondu($messageRepondre);
                $message->setCodeFil($messageRepondre->getCodeFil());
            }
            if ($messageTransfere != null) {
                $message->setMessageTransfere($messageTransfere);
            }

            /*
             * On récupere les destinaires abonnés
             */
            $tabAbonne = array();
            $tabIdsAbonne = array_unique(explode('|', $idsAbonne));
            foreach ($tabIdsAbonne as $idS) {
                $unAbonne = $em->getRepository($this->userBundle.'Abonne')->find((int) $idS);
                if ($unAbonne != null) {
                    $tabAbonne[] = $unAbonne;
                }
            }
//            var_dump($tabAbonne);
//            var_dump($allAbonne); exit;
            if ($allAbonne == 1) {
                /*
                 * Récupération de tous les abonnés
                 * 
                 * VOUS POUVEZ PERSONNALISER CETTE REQUETTE
                 */
                $tabAbonne = $messagerieManager->getAllAbonneMessage();
            }

            if (count($tabAbonne) < 1) {
                $rep['msg'] = 'Vous devez indiquer au moins un abonné ';

                return new Response(json_encode($rep));
            }

             /*
             * On récupere les destinaires admin
             */
            $tabAdmin = array();
            $tabIdsAdmin = array_unique(explode('|', $idsAdmin));
            foreach ($tabIdsAdmin as $key => $idS) {
                $idS = (int) $idS;
                $unAdmin = $em->getRepository($this->userBundle.'Utilisateur')->find($idS);
                if ($unAdmin != null) {
                    $tabAdmin[] = $unAdmin;
                }
                $tabIdsAdmin[$key] = $idS;
            }

            if ($allAdmin == 1) {
                /*
                 * Au cas c'est une une néponse à un autre mail, l'utilisateur courant fait parti des destinaires
                 * 
                 * VOUS POUVEZ PERSONNALISER CETTE REQUETTE
                 */
                $tabAdmin = $messagerieManager->getAllUserMessage($sessionData['id']);
            }
            if (!in_array($sessionData['id'], $tabIdsAdmin)) {
                $tabAdmin[] = $utilisateur;
            }

            $message->setObjet($objet);
            $message->setContenuClaire($contenuMessageClaire);
            $message->setContenu($contenuMessage);
            $message->setUtilisateur($utilisateur);

            $messagerieManager->sendMessageToAbonne($message, $tabAbonne, $tabAdmin, $sessionData);

            $rep['msg'] = '';
            $rep['etat'] = true;
            $this->get('session')->getFlashBag()->add('send.msg.succes', 'Message envoyé avec succès');

            return new Response(json_encode($rep));
        }

        return new Response(json_encode($rep));
    }

    /*
     * Suppression de message recu
     * 
     * @return Response
     */
    public function supprimerMessageRecuAction(Request $request)
    {
        $rep = array('etat' => false, 'msg' => 'Erreur survenue lors du traitement');
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = 'Supprime un message reçu';
        /*
         * Préparation du message de log 
         */
        $this->logMessage .= ' [ '.$nomAction.' ] ';
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
            $rep['msg'] = 'Vous êtes déconnecté. Veuillez-vous connecter de nouveau';

            return new Response(json_encode($rep));
        }

        /*
         * Gestion des droits
         */
        if (!$loginManager->getOrSetActions(Module::MOD_MESSAGE_ADMIN, Module::MOD_MESSAGE_ADMIN_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $rep['msg'] = "Vous n'avez pas le droit  de supprimer un message reçu";

            return new Response(json_encode($rep));
        }

        $em = $this->getDoctrine()->getManager();

        if (($request->isXmlHttpRequest()) && ($request->getMethod() == 'POST')) {
            $tempEtat = (int) $request->request->get('etat');
            $tempIds = $request->request->get('sId');

            $etat = TypeStatutEnvoi::MSG_LU;
            if ($tempEtat == 1) {
                $etat = TypeStatutEnvoi::MSG_LU;
            } elseif ($tempEtat == 2) {
                $etat = TypeStatutEnvoi::MSG_SUPPRIME;
            } else {
                return new Response(json_encode($rep));
            }

            $tabIds = explode('|', $tempIds);
            $oneOk = false;
            foreach ($tabIds as $idS) {
                $envoi = $em->getRepository($this->messagerieBundle.'Envoi')->find((int) $idS);
                if ($envoi != null) {
                    $envoi->setStatut($etat);
                    $em->flush();
                    $oneOk = true;
                }
            }
            if ($oneOk) {
                $this->get('session')->getFlashBag()->add('envoi.modifier.success', 'Opération éffectuée avec succès');
                $rep['msg'] = '';
                $rep['etat'] = true;
                $this->updateMessageNonLu();
            }

            return new Response(json_encode($rep));
        }

        return new Response(json_encode($rep));
    }

    /*
     * Change l'état d'un envoi à lu
     * 
     * @return Response
     */
    public function setEtatEnvoiToLuAction(Request $request)
    {
        $rep = array('etat' => false, 'msg' => 'Erreur survenue lors du traitement');
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Change l'état d'un envoi à lu";
        /*
         * Préparation du message de log 
         */
        $this->logMessage .= ' [ '.$nomAction.' ] ';
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
//            if (!$status['isUser']) {
//                return new Response(json_encode($rep));
//            }
        } else {
            $rep['msg'] = 'Vous êtes déconnecté. Veuillez-vous connecter de nouveau';

            return new Response(json_encode($rep));
        }

        /*
         * Gestion des droits
         */
//        if (!$loginManager->getOrSetActions(Module::MOD_MESSAGE_ADMIN, Module::MOD_MESSAGE_ADMIN_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
//            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
//            $rep['msg'] = "Vous n'avez pas le droit  de supprimer un message reçu";
//            return new Response(json_encode($rep));
//        }

        $em = $this->getDoctrine()->getManager();

        if (($request->isXmlHttpRequest()) && ($request->getMethod() == 'POST')) {
            $tempIds = $request->request->get('sId');

            $tabIds = explode('|', $tempIds);
            $oneOk = false;
            foreach ($tabIds as $idS) {
                $envoi = $em->getRepository($this->messagerieBundle.'Envoi')->find((int) $idS);
                if ($envoi != null) {
                    $envoi->setStatut(TypeStatutEnvoi::MSG_LU);
                    $em->flush();
                    $oneOk = true;
                }
            }
            if ($oneOk) {
                $rep['msg'] = '';
                $rep['etat'] = true;
                $this->updateMessageNonLu();
            }

            return new Response(json_encode($rep));
        }

        return new Response(json_encode($rep));
    }

    /*
     * Affiche les détails d'un message reçu par un  utilisateur
     * 
     * @param type $idMessage
     * @return type
     */
    public function detailsMsgRecuAction(Request $request, $idMessage)
    {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affiche les détails d'un message reçu par un  utilisateur";
        /*
         * Préparation du message de log 
         */
        $this->logMessage .= ' [ '.$nomAction.' ] ';
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
        if (!$loginManager->getOrSetActions(Module::MOD_MESSAGE_ADMIN, Module::MOD_MESSAGE_ADMIN_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            //            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
//            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder au contenu d'un message");
//            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }

        $messageManager = $this->get('messagerie_manager');
        $em = $this->getDoctrine()->getManager();

        $message = $em->getRepository($this->messagerieBundle.'Message')->find($idMessage);
        if ($message == null) {
            return $this->redirect($this->generateUrl('app_messagerie_boite_reception'));
        }

        /*
         * Fils de discussion du message
         */
        $fils = $messageManager->getFilMessage($message, $sessionData);

        $this->data['fils'] = $fils;
        $this->data['message'] = $message;
        $this->data['messageCloture'] = ($message->getCloturer()) ? 1 : 0;
        $this->data['locale'] = $locale;
       // $this->updateMessageNonLu();

        return $this->render($this->messagerieBundle.'Admin:detailsMsgRecu.html.twig', $this->data, $this->response);
    }

    /*
     * Cloture un ticket
     * 
     * @return Response
     */
    public function endTicketAction(Request $request)
    {
        $rep = array('etat' => false, 'msg' => 'Erreur survenue lors du traitement');
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = 'Clôture un ticket';
        /*
         * Préparation du message de log 
         */
        $this->logMessage .= ' [ '.$nomAction.' ] ';
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
            $rep['msg'] = 'Vous êtes déconnecté. Veuillez-vous connecter de nouveau';

            return new Response(json_encode($rep));
        }

        /*
         * Gestion des droits
         */
        if (!$loginManager->getOrSetActions(Module::MOD_MESSAGE_ADMIN, Module::MOD_MESSAGE_ADMIN_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $rep['msg'] = "Vous n'avez pas le droit  de clôturer un ticket";

            return new Response(json_encode($rep));
        }

        $em = $this->getDoctrine()->getManager();

        if (($request->isXmlHttpRequest()) && ($request->getMethod() == 'POST')) {
            $tempIds = $request->request->get('sId');

            $tabIds = explode('|', $tempIds);
            $message = null;
            foreach ($tabIds as $idS) {
                $idMessage = (int) $idS;
                if ($idMessage > 0) {
                    $message = $em->getRepository($this->messagerieBundle.'Message')->find($idMessage);

                    if ($message != null) {
                        continue;
                    }
                }
            }
            $messageManager = $this->get('messagerieManager');
            if ($message != null) {
                $messageManager->cloturerTicket($message);
                $this->get('session')->getFlashBag()->add('ticket.cloturer.success', 'Ticket cloturé avec succès');
                $rep['msg'] = '';
                $rep['etat'] = true;
            }

            return new Response(json_encode($rep));
        }

        return new Response(json_encode($rep));
    }

    /*
     * Affiche les détails d'un message envoyé par un  utilisateur
     * 
     * @param type $idMessage
     * @return type
     */
    public function detailsMsgEnvoyeAction(Request $request, $idMessage)
    {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affiche les détails d'un message envoyé par un  utilisateur";
        /*
         * Préparation du message de log 
         */
        $this->logMessage .= ' [ '.$nomAction.' ] ';
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
        if (!$loginManager->getOrSetActions(Module::MOD_MESSAGE_ADMIN, Module::MOD_MESSAGE_ADMIN_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            //            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
//            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder au contenu d'un message");
//            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }

        $em = $this->getDoctrine()->getManager();

        $message = $em->getRepository($this->messagerieBundle.'Message')->find($idMessage);
        if ($message == null) {
            return $this->redirect($this->generateUrl('app_messagerie_boite_reception'));
        }

        $messageManager = $this->get('messagerie_manager');

        $fils = $messageManager->getFilMessage($message, $sessionData);

        $this->data['fils'] = $fils;
        $this->data['message'] = $message;
        $this->data['locale'] = $locale;

        return $this->render($this->messagerieBundle.'Admin:detailsMsgEnvoye.html.twig', $this->data, $this->response);
    }

    /*
     * Suppression de message envoyé
     * 
     * @return Response
     */
    public function supprimerMessageEnvoieAction(Request $request)
    {
        $rep = array('etat' => false, 'msg' => 'Erreur survenue lors du traitement');
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = 'Supprime un message envoyé';
        /*
         * Préparation du message de log 
         */
        $this->logMessage .= ' [ '.$nomAction.' ] ';
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
            $rep['msg'] = 'Vous êtes déconnecté. Veuillez-vous connecter de nouveau';

            return new Response(json_encode($rep));
        }

        /*
         * Gestion des droits
         */
        if (!$loginManager->getOrSetActions(Module::MOD_MESSAGE_ADMIN, Module::MOD_MESSAGE_ADMIN_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $rep['msg'] = "Vous n'avez pas le droit  de supprimer un message envoyé";

            return new Response(json_encode($rep));
        }

        $em = $this->getDoctrine()->getManager();

        if (($request->isXmlHttpRequest()) && ($request->getMethod() == 'POST')) {
            $tempEtat = (int) $request->request->get('etat');
            $tempIds = $request->request->get('sId');

            $etat = TypeStatutEnvoi::MSG_LU;
            if ($tempEtat == 1) {
                $etat = TypeStatutEnvoi::MSG_LU;
            } elseif ($tempEtat == 2) {
                $etat = TypeStatutEnvoi::MSG_SUPPRIME;
            } else {
                return new Response(json_encode($rep));
            }

            $tabIds = explode('|', $tempIds);
            $oneOk = false;
            foreach ($tabIds as $idS) {
                $message = $em->getRepository($this->messagerieBundle.'Message')->find((int) $idS);
                if ($message != null) {
                    $message->setEtat(TypeEtat::SUPPRIME);
                    $em->flush();
                    $oneOk = true;

                    $envois = $message->getEnvois();
                    foreach ($envois as $item) {
                        $item->setStatut(TypeStatutEnvoi::MSG_SUPPRIME);
                        $em->flush();
                    }
                }
            }
            if ($oneOk) {
                $this->get('session')->getFlashBag()->add('message.modifier.success', 'Opération éffectuée avec succès');
                $rep['msg'] = '';
                $rep['etat'] = true;
                $this->updateMessageNonLu();
            }

            return new Response(json_encode($rep));
        }

        return new Response(json_encode($rep));
    }
}
