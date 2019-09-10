<?php

namespace admin\MessagerieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use admin\UserBundle\Entity\Module;
use admin\UserBundle\Services\LoginManager;
use admin\MessagerieBundle\Types\TypeStatutEnvoi;
use admin\MessagerieBundle\Entity\Message;
use admin\UserBundle\Types\TypeCodeProfil;
use admin\ParamBundle\Types\TypeParametre;
use Symfony\Component\HttpFoundation\Request;

/**
 * MessageAbonneController.
 
 * Contrôleur ki gere la messagerie des abonnés (Site public)
 */
class MessageAbonneController extends Controller
{
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
     *             Nom du Bundle
     */
    private $messagerieBundle = 'adminMessagerieBundle:';
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
        $this->logMessage = ' [ MessageAbonneController ] ';
        $this->description = 'Controlleur qui gère la messagerie du site public';
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
    public function siteBoiteReceptionAction(Request $request, $mot, $dateDebut, $dateFin, $nbParPage, $pageActuelle)
    {
        /*
         * Nom de l'action en cours
         */
        $nomAction = 'siteBoiteReceptionAction';
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Boite de reception d'un abonné";
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

                return $this->redirect($this->generateUrl('app_pub_site_user_logout'));
            }
            /*
             * Seuls les utilisateurs admins ont le droit d'acceder à cette action
             */
            if (!$status['isAbonne']) {
                return $this->redirect($this->generateUrl('app_pub_site_user_logout'));
            }
        } else {
            return $this->redirect($this->generateUrl('app_pub_site_user_logout'));
        }

        /*
         * Gestion des droits
         */
        if (!$loginManager->getOrSetActions(Module::MOD_SITE_MESSAGE, Module::MOD_SITE_MESSAGE_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la boîte de réception");

            return $this->redirect($this->generateUrl('app_pub_site_user_access_refuse'));
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
         * Service de gestion de la méssagerie
         */
        $messagerieManager = $this->get('messagerie_manager');

        /*
         * $typeMessage : 0 = Message recu d'abonné + message recu d'utilisateur; 1 = Message Abonne uniquement, 2 = Message utilisateur uniquement
         * Ici c'est seulement les message recu des amin kon affiche
         */
        $typeMessage = 2;
        $queryResult = $messagerieManager->getListeMessageRecu($sessionData['id'], $objetDateDebut, $objetDateFin, $mot, $nbParPage, $pageActuelle, false, $typeMessage);

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
        $this->updateMessageNonLu();

        return $this->render($this->messagerieBundle.'Abonne:siteBoiteReception.html.twig', $this->data, $this->response);
    }

    /*
     * Formulaire d'envoi de message par les abonnés 
     * $idMessageRepondre = identifiant du message auquel on souhaite repondre, 
     * $idMessageTransfere : identifiant du message on souhaute transferer
     * $idAbonne : abonné à qui on envoi le message
     * 
     * @param type $idMessageTransfere
     * @param type $idMessageRepondre
     * @return type
     */
    public function siteNouveauMessageToAdminAction(Request $request, $idMessageTransfere, $idMessageRepondre)
    {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Formulaire d'envoi de message par les abonnés";
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

                return $this->redirect($this->generateUrl('app_pub_site_user_logout'));
            }
            /*
             * Seuls les abonnés ont le droit d'acceder à cette action
             */
            if (!$status['isAbonne']) {
                return $this->redirect($this->generateUrl('app_pub_site_user_logout'));
            }
        } else {
            return $this->redirect($this->generateUrl('app_pub_site_user_logout'));
        }

        /*
         * Gestion des droits
         */
        if (!$loginManager->getOrSetActions(Module::MOD_SITE_MESSAGE, Module::MOD_SITE_MESSAGE_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'envoyer un message");

            return $this->redirect($this->generateUrl('app_pub_site_user_access_refuse'));
        }
        $parametreManager = $this->get('parametre_manager');
        $messagerieManager = $this->get('messagerie_manager');
        $em = $this->getDoctrine()->getManager();

        $abonne = $em->getRepository($this->userBundle.'Abonne')->find($sessionData['id']);
        if ($abonne == null) {
            return $this->redirect($this->generateUrl('app_pub_site_user_logout'));
        }

        /*
         * Il on cherche à recupérer le message qu'on veut transfere/répondre
         */
        $messageRepondre = $em->getRepository($this->messagerieBundle.'Message')->find($idMessageRepondre);
        $messageTransfere = $em->getRepository($this->messagerieBundle.'Message')->find($idMessageTransfere);

        /*
         * Les potentiels destinataires admin
         * 
         * VOUS POUVEZ PERSONNALISER CETTE REQUETTE
         */
        $allAdmin = $messagerieManager->getAllUserMessage(0, TypeCodeProfil::MESSAGE);

        /*
         * Au cas ou c'est une réponse à un mail
         * Le tableau $tabIdAdmin contient l'identifiant des admin  ayant reçu le msg auquel on répond. Cela permet de les présellectionné sur la vue
         */
        $tabIdAdmin = array();
        /*
         * S'il s'agit d'une réponse à un autre message, on cherche les distinataires par défauts :
         * - L'émetteur du message 
         * - Les destinateurs du message $messageRepondre
         */
        if ($messageRepondre != null) {
            /*
             * Recupération des destinaires du message
             */
            $allDestinataire = $messagerieManager->getDestinairesOfMessage($messageRepondre);
            $tabIdAdmin = $allDestinataire['tabIdAdmin'];

            $utilisateur = $messageRepondre->getUtilisateur();
            if (($utilisateur != null)) {
                $tmpId = $utilisateur->getId();
                if (!in_array($tmpId, $tabIdAdmin)) {
                    $tabIdAdmin[] = $tmpId;
                }
            } else {
                /*
                 * Si on écrit à admin, aucun abonné ne voi le message, mm s'il était recepteur
                 */
//                $abonne = $messageRepondre->getAbonne();
//                if ($abonne != NULL) {
//                    $tmpId = $abonne->getId();
//                    if (!in_array($tmpId, $tabIdAbonne)) {
//                        $tabIdAbonne[] = $tmpId;
//                        $tabAbonne[] = $abonne;
//                    }
//                }
            }
        }

        /*
         * Autorise les abonnés à choisir eux mm les destinaires du message
         */
        $allowChoseUser = $parametreManager->getValeurParametre(TypeParametre::CHOIX_DESTINAIRE_MSG_PAR_ABONNE);
        if ($allowChoseUser == null) {
            $allowChoseUser = false;
        }

        $this->data['allAdmin'] = $allAdmin;
        $this->data['tabIdAdmin'] = $tabIdAdmin;
        $this->data['idMessageRepondre'] = $idMessageRepondre;
        $this->data['idMessageTransfere'] = $idMessageTransfere;
        $this->data['messageRepondre'] = $messageRepondre;
        $this->data['messageTransfere'] = $messageTransfere;
        $this->data['allowChoseUser'] = $allowChoseUser;
        $this->data['locale'] = $locale;

        return $this->render($this->messagerieBundle.'Abonne:nouveauToAdmin.html.twig', $this->data, $this->response);
    }

    /*
     * Enregistrement du message envoyé par un abonne par AJAX (app_pub_site_messagerie_send_msg_to_utilisateur)
     * 
     * @return Response
     */
    public function siteNouveauMessageToAdminSaveAction(Request $request)
    {
        $rep = array('etat' => false, 'msg' => 'Erreur survenue lors du traitement');
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = 'Enregistre un message envoyé par un abonné';
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
            if (!$status['isAbonne']) {
                return new Response(json_encode($rep));
            }
        } else {
            $rep['msg'] = 'Vous êtes déconnecté. Veuillez-vous connecter de nouveau';

            return new Response(json_encode($rep));
        }

        /*
         * Gestion des droits
         */
//        if (!$loginManager->getOrSetActions(Module::MOD_SITE_MESSAGE, Module::MOD_SITE_MESSAGE_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction , $descAction, $sessionData['idProfil'])) {
////            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
////            return new Response(json_encode($rep));
//        }

        $em = $this->getDoctrine()->getManager();
        $abonne = $em->getRepository($this->userBundle.'Abonne')->find($sessionData['id']);
        if ($abonne == null) {
            return new Response(json_encode($rep));
        }

        if (($request->isXmlHttpRequest()) && ($request->getMethod() == 'POST')) {
            /*
             * Services necessaires aux traitements
             */
            $messagerieManager = $this->get('messagerie_manager');
            $parametreManager = $this->get('parametre_manager');
            $randCodeManager = $this->get('randcode_manager');

            /*
             * Récupération des identifiants du message auquel on souhaite répondre/transferer
             */
            $idMessageRepondre = (int) $request->request->get('idMessageRepondre');
            $idMessageTransfere = (int) $request->request->get('idMessageTransfere');

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
             * On récupere les destinaires admin
             */
            $tabAdmin = array();
            $tabIdsAdmin = array_unique(explode('|', $idsAdmin));
            foreach ($tabIdsAdmin as $idS) {
                $unAdmin = $em->getRepository($this->userBundle.'Utilisateur')->find((int) $idS);
                if ($unAdmin != null) {
                    $tabAdmin[] = $unAdmin;
                }
            }

            /*
             * Au cas ou les abonnés n'ont pas le droit de spécifier les récepteurs, on choisit tous les utilisateur du profil message
             */
            $allowChoseUser = $parametreManager->getValeurParametre(TypeParametre::CHOIX_DESTINAIRE_MSG_PAR_ABONNE);
            if ($allowChoseUser == null) {
                $allowChoseUser = false;
            }

            if (!$allowChoseUser) {
                /*
                 * Ici vous pouvez personnaliser les recepteurs admin
                 * 
                 * VOUS POUVEZ PERSONNALISER CETTE REQUETTE
                 */
                $tabAdmin = $messagerieManager->getAllUserMessage(0, TypeCodeProfil::MESSAGE);
            }

            /*
             * Au moins un recepteur admin doit être renseigné
             */
            if (count($tabAdmin) < 1) {
                $rep['msg'] = 'Vous devez indiquer au moins un destinataire ';

                return new Response(json_encode($rep));
            }
            /*
             * On récupere les destinaires abonnés (ceux qui étatent en copie du mail au cas c'est une réponse à un mail qui est en cours)
             */
            $tabAbonne = array();
            $tabIdAbonne = array();
            if ($messageRepondre != null) {
                /*
                 *  IMPORTANT - A NE PAS SUPPRIMER. Les trois lignes suivants sont importantes - 
                 * 
                 * Actuelement lorsqu'un abonné répond à mail qu'un admin avait envoyé à plusieurs abonnés, les autres abonnés destinataires ne  
                 * recoivent pas le message. Si on souhaite changé ce comprtement, il suffi de décommenter les 3 lignes suivantes
                 * 
                 * $allDestinataire = $messagerieManager->getDestinairesOfMessage($messageRepondre);
                 * $tabAbonne = $allDestinataire['tabAbonne'];
                 * $tabIdAbonne = $allDestinataire['tabIdAbonne'];
                 */
//                $allDestinataire = $messagerieManager->getDestinairesOfMessage($messageRepondre);
//                $tabAbonne = $allDestinataire['tabAbonne'];
//                $tabIdAbonne = $allDestinataire['tabIdAbonne'];
                /*
                 *  FIN IMPORTANT -  A NE PAS SUPPRIMER.
                 */
            }

            /*
             * L'abonné émetteur du message recoit aussi un envoi, afin de former le fil de discussion
             */
            if (!in_array($sessionData['id'], $tabIdAbonne)) {
                $tabAbonne[] = $em->getRepository($this->userBundle.'Abonne')->find($sessionData['id']);
                $tabIdAbonne = $sessionData['id'];
            }

            $message->setObjet($objet);
            $message->setContenuClaire($contenuMessageClaire);
            $message->setContenu($contenuMessage);
            $message->setAbonne($abonne);

            $messagerieManager->abonneSendMessage($message, $tabAbonne, $tabAdmin, $sessionData);

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
    public function siteSupprimerMessageRecuAction(Request $request)
    {
        $rep = array('etat' => false, 'msg' => 'Erreur survenue lors du traitement');
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = 'Supprime un message reçu par un abonné';
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
            if (!$status['isAbonne']) {
                return new Response(json_encode($rep));
            }
        } else {
            $rep['msg'] = 'Vous êtes déconnecté. Veuillez-vous connecter de nouveau';

            return new Response(json_encode($rep));
        }

        /*
         * Gestion des droits
         */
        if (!$loginManager->getOrSetActions(Module::MOD_SITE_MESSAGE, Module::MOD_SITE_MESSAGE_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
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
     * Affiche les détails d'un message reçu par un  abonné
     * 
     * @param type $idMessage
     * @return type
     */
    public function siteDetailsMsgRecuAction(Request $request, $idMessage)
    {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affiche les détails d'un message reçu par un  abonné";
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

                return $this->redirect($this->generateUrl('app_pub_site_user_logout'));
            }
            /*
             * Seuls les utilisateurs admins ont le droit d'acceder à cette action
             */
            if (!$status['isAbonne']) {
                return $this->redirect($this->generateUrl('app_pub_site_user_logout'));
            }
        } else {
            return $this->redirect($this->generateUrl('app_pub_site_user_logout'));
        }

        /*
         * Gestion des droits
         */
        if (!$loginManager->getOrSetActions(Module::MOD_SITE_MESSAGE, Module::MOD_SITE_MESSAGE_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            //            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
//            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder au contenu d'un message");
//            return $this->redirect($this->generateUrl('app_pub_site_user_access_refuse'));
        }

        $messageManager = $this->get('messagerie_manager');
        $em = $this->getDoctrine()->getManager();

        $message = $em->getRepository($this->messagerieBundle.'Message')->find($idMessage);
        if ($message == null) {
            return $this->redirect($this->generateUrl('app_pub_site_messagerie_boite_reception'));
        }

        /*
         * On recupere le fil de discussion correspondant au message
         */
        $fils = $messageManager->getFilMessage($message, $sessionData);
        /*
         * On met à jour le nbre de mesasge non lu
         */
        $this->updateMessageNonLu();

        $this->data['fils'] = $fils;
        $this->data['message'] = $message;
        $this->data['locale'] = $locale;

        return $this->render($this->messagerieBundle.'Abonne:siteDetailsMsgRecu.html.twig', $this->data, $this->response);
    }

    /*
     * Cloture un ticket
     * 
     * @return Response
     */
    public function siteEndTicketAction(Request $request)
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
            if (!$status['isAbonne']) {
                return new Response(json_encode($rep));
            }
        } else {
            $rep['msg'] = 'Vous êtes déconnecté. Veuillez-vous connecter de nouveau';

            return new Response(json_encode($rep));
        }

        /*
         * Gestion des droits
         */
        if (!$loginManager->getOrSetActions(Module::MOD_SITE_MESSAGE, Module::MOD_SITE_MESSAGE_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $rep['msg'] = "Vous n'avez pas le droit  de clôturer un ticket";

            return new Response(json_encode($rep));
        }

        $em = $this->getDoctrine()->getManager();

        if (($request->isXmlHttpRequest()) && ($request->getMethod() == 'POST')) {
            /*
             * Identifiant des messages du ticket (fil de discussion)
             */
            $tempIds = $request->request->get('sId');

            $tabIds = explode('|', $tempIds);
            $message = null;
            foreach ($tabIds as $idS) {
                $idMessage = (int) $idS;
                if ($idMessage > 0) {
                    $message = $em->getRepository($this->messagerieBundle.'Message')->find($idMessage);
                    /*
                     * Dès qu'on trouve un message du fil on sort de la boucle, puisque tous les messages d'un ticket on le mm code
                     */
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
     * Affiche la liste des messages envoyés par un abonné
     * @param type $mot
     * @param type $dateDebut
     * @param type $dateFin
     * @param type $nbParPage
     * @param type $pageActuelle
     * @return type
     */
    public function siteMessageEnvoyesAction(Request $request, $mot, $dateDebut, $dateFin, $nbParPage, $pageActuelle)
    {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = 'Affiche la liste des messages envoyés par un abonné';
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

                return $this->redirect($this->generateUrl('app_pub_site_user_logout'));
            }
            /*
             * Seuls les utilisateurs admins ont le droit d'acceder à cette action
             */
            if (!$status['isAbonne']) {
                return $this->redirect($this->generateUrl('app_pub_site_user_logout'));
            }
        } else {
            return $this->redirect($this->generateUrl('app_pub_site_user_logout'));
        }

        /*
         * Gestion des droits
         */
        if (!$loginManager->getOrSetActions(Module::MOD_SITE_MESSAGE, Module::MOD_SITE_MESSAGE_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder aux messages envoyés");

            return $this->redirect($this->generateUrl('app_pub_site_user_access_refuse'));
        }
        /*
         * Service de gestion de la messagerie
         */
        $messagerieManager = $this->get('messagerie_manager');
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

        $queryResult = $messagerieManager->getListeMessageEnvoyes($sessionData['id'], $objetDateDebut, $objetDateFin, $mot, $nbParPage, $pageActuelle, false);

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

        return $this->render($this->messagerieBundle.'Abonne:siteMessagesEnvoyes.html.twig', $this->data, $this->response);
    }

    /*
     * Affiche les détails d'un message envoyé par un  abonné
     * 
     * @param type $idMessage
     * @return type
     */
    public function siteDetailsMsgEnvoyeAction(Request $request, $idMessage)
    {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affiche les détails d'un message envoyé par un abonne ";
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

                return $this->redirect($this->generateUrl('app_pub_site_user_logout'));
            }
            /*
             * Seuls les utilisateurs admins ont le droit d'acceder à cette action
             */
            if (!$status['isAbonne']) {
                return $this->redirect($this->generateUrl('app_pub_site_user_logout'));
            }
        } else {
            return $this->redirect($this->generateUrl('app_pub_site_user_logout'));
        }

        /*
         * Gestion des droits
         */
        if (!$loginManager->getOrSetActions(Module::MOD_SITE_MESSAGE, Module::MOD_SITE_MESSAGE_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            //            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
//            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder au contenu d'un message");
//            return $this->redirect($this->generateUrl('app_pub_site_user_access_refuse'));
        }

        $em = $this->getDoctrine()->getManager();

        $message = $em->getRepository($this->messagerieBundle.'Message')->find($idMessage);
        if ($message == null) {
            return $this->redirect($this->generateUrl('app_pub_site_messagerie_boite_reception'));
        }

        $messageManager = $this->get('messagerie_manager');

        /*
         * On recuper le fil de discussion
         */
        $fils = $messageManager->getFilMessage($message, $sessionData);

        $this->data['fils'] = $fils;
        $this->data['message'] = $message;
        $this->data['locale'] = $locale;

        return $this->render($this->messagerieBundle.'Abonne:siteDetailsMsgEnvoye.html.twig', $this->data, $this->response);
    }

    /*
     * Mis à jour du nombre de message non lu
     */
//    private function updateMessageNonLu() {
//        $em = $this->getDoctrine()->getManager();
//        $loginManager = $this->get('login_manager');
//        $sessionData = $this->infosConnecte();
//        $nbMessageNonLu = $envoi = $em->getRepository($this->messagerieBundle . 'Envoi')->getNbMessageNonLu($sessionData['isAbonne'], $sessionData['id']);
//        $sessionData['nbMessageNonLu'] = $nbMessageNonLu;
//        $loginManager->setSessionData(LoginManager::SESSION_DATA_NAME, $sessionData);
//        $this->infosConnecte();
//    }
}
