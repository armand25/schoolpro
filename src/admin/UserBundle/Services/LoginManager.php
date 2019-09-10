<?php

/*
 * Description of LoginManager
 * Service de gestion des connexions et des droits des utilisateurs.
 *
 * @author Edmond
 */

namespace admin\UserBundle\Services;

use Symfony\Component\HttpFoundation\Session\Session;
use \Doctrine\ORM\EntityManager;
use \admin\ParamBundle\Types\TypeParametre;
use \admin\ParamBundle\Services\ParametreManager;
use \admin\ParamBundle\Services\DateManager;
use \admin\ParamBundle\Types\TypeDonnees;
use Doctrine\ORM\Tools\Pagination\Paginator;
use \admin\UserBundle\Types\TypeEtat;
use \admin\UserBundle\Types\TypeProfil;
use \admin\UserBundle\Types\TypeSexe;
use \admin\UserBundle\Entity\Utilisateur;
use \admin\UserBundle\Entity\Profil;
use PDO;
/**
 * LoginManager
 * 
 * Service de gestion des connexions et des droits des utilisateurs.
 * Permet de connecter un utilisateur, de créer une session et aussi de la supprimer
 * L'attribution de droit, la déconnexion automatique suite à un long moment d'innactivité et historisation de toute les
 * actions effectuées par le conntecté dans un fichier txt et/ou dans la table Connexion
 * 
 */
class LoginManager {

    /*
     *
     * @var \Doctrine\ORM\EntityManager  $em : gestionnaire d'entité
     * 
     */
    private $em;

    /*
     *
     * @var Symfony\Component\HttpFoundation\Session\Session  $session : Une instance de la la session de Symfony
     */
    private $session;

    /*
     *
     * @var admin\ParamBundle\Services\ParametreManager  $parametreManager
     * Une instance du service ParametreManager permetant la gestion des paramètres de l'application
     */
    private $parametreManager;

    /*
     * 
     * @var facture\AdmindBundle\Services\DateManager  $dateManager
     * Une instance du service DateManager permetant diverses traitements sur les dates,
     * date de PHP et la classe \DateTime de Symfony
     */
    private $dateManager;

    /*
     * 
     * @var string  $paramBundle
     * Nom du Bundle
     */
    private $paramBundle = 'adminParamBundle:';

    /*
     * 
     * @var string  $userBundle
     * Nom du Bundle
     */
    private $userBundle = 'adminUserBundle:';

    /*
     * Nom de la session
     */
    CONST SESSION_DATA_NAME = 'adsds6HhDbdhhd899b6f2ea76';

   
    /*
     * le constructeur qui initialise les attributs
     * 
     * @param \Doctrine\ORM\EntityManager $em
     * @param \Symfony\Component\HttpFoundation\Session\Session $s
     * @param \admin\ParamBundle\Services\ParametreManager $p
     * @param \admin\ParamBundle\Services\DateManager $d
     */
    public function __construct(EntityManager $em, Session $s, ParametreManager $p, DateManager $d) {
        $this->em = $em;
        $this->session = $s;
        $this->parametreManager = $p;
        $this->dateManager = $d;
         
        //si la session n'a pas demarré alors on la demarre
        if ((!isset($_SESSION)) && (!$this->session->isStarted() )) {
            
            $this->session->start();
        }
    }

    /*
     * 
     * 
     * @param type $idConnexion
     * @return array
     */
    
    /*
     * 
     *Retourne les détails d'une connexion, L'emsemble des actions effectuées par le connecté
     * 
     * @author armand.tevi@gmail.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @param int $idConnexion
     * @param int $isUser (0 ou 1)
     * @return string
     */
    public function getDetailsConnexion($idConnexion = 0, $isUser = 1) {
        $connexion = $this->em->getRepository($this->userBundle . 'Connexion')->find($idConnexion);
        if ($connexion == NULL) {
            return NULL;
        }

        $queryTabIdActions = $connexion->getTabIdActions();
        $queryTabDates = $connexion->getTabDateActions();
        $taille = count($queryTabDates);

        /*
         * On récupère la date et les id des différentes actions exécutées par le connecté
         */
        $tabIdActions = array();
        $tabDates = array();
        for ($i = ($taille - 1); $i >= 0; $i--) {
            $tabDates[] = $queryTabDates[$i];
            $tabIdActions[] = $queryTabIdActions[$i];
        }


        /*
         * On récupère les objets actions
         */
        $rep = array();
        foreach ($tabIdActions as $key => $idAction) {
            $action = $this->em->getRepository($this->userBundle . 'Action')->find($idAction);
            if ($action != NULL) {
                $dateAction = NULL;
                if (array_key_exists($key, $tabDates)) {
                    $dateAction = $tabDates[$key];
                }
                $rep[] = array('action' => $action, 'date' => $dateAction);
            }
        }

        /*
         * On trouve celui qui s'était connecté
         */
        $user = ($isUser == 1) ? $connexion->getUtilisateur() : $connexion->getAbonne();

        return array('user' => $user, 'details' => $rep, 'connexion' => $connexion);
    }

    /*
     * Retourne l'historique de connexion d'un user
     * 
     * @param type $idUser
     * @param type $isUser
     * @param type $nbParPage
     * @param type $pageActuelle
     * @return array|Paginator
     */
    public function getHistoriqueConnexion($idUser, $isUser = TRUE, $nbParPage = 20, $pageActuelle = 1) {
        $nomTable = ($isUser) ? 'Utilisateur' : 'Abonne';
        $user = $this->em->getRepository($this->userBundle . $nomTable)->find($idUser);
        $nbTotal = $this->getNbTotalHistoriqueConnexion($idUser, $isUser);
        $rep = array('data' => array(), 'nbParPage' => $nbParPage, 'pageActuelle' => $pageActuelle, 'nbTotal' => $nbTotal, 'nbTotalPage' => 1, 'user' => NULL);
        if ($user != NULL) {
            $queryBulder1 = $this->em->getRepository($this->userBundle . 'Connexion')->createQueryBuilder('c');

            if ($isUser) {
                $queryBulder1 = $queryBulder1->leftJoin('c.utilisateur', 'a')
                        ->addSelect('a')
                        ->where('a.id = :user');
            } else {
                $queryBulder1 = $queryBulder1->leftJoin('c.abonne', 'a')
                        ->addSelect('a')
                        ->where('a.id = :user');
            }
            $queryBulder1->setParameter('user', $idUser)
                    ->orderBy('c.dateLastAction', 'DESC');

            $pageActuelleInt = (int) $pageActuelle;
            $nbParPageInt = (int) $nbParPage;
            if ($pageActuelleInt < 1) {
                $pageActuelleInt = 1;
            }
            if ($nbParPageInt < 1) {
                $nbParPageInt = 20;
            }
            $nbTotalPage = intval($nbTotal / $nbParPage);
            if ($nbTotalPage < 1) {
                $nbTotalPage = 1;
            }
            if ($pageActuelleInt > $nbTotalPage) {
                $pageActuelleInt = $nbTotalPage;
            }

            $query = $queryBulder1->getQuery();
            $query->setFirstResult(($pageActuelleInt - 1) * $nbParPageInt);
            $query->setMaxResults($nbParPageInt);
            $rep['nbParPage'] = $nbParPage;
            $rep['pageActuelle'] = $pageActuelle;
            $rep['nbTotal'] = $nbTotal;
            $rep['nbTotalPage'] = $nbTotalPage;
            $rep['user'] = $user;
            $rep['data'] = new Paginator($query);
        }

        return $rep;
    }

    private function getNbTotalHistoriqueConnexion($idUser, $isUser = FALSE) {
        $nbTotal = 0;
        $nomTable = ($isUser) ? 'Utilisateur' : 'Abonne';
        $user = $this->em->getRepository($this->userBundle . $nomTable)->find($idUser);
        if ($user != NULL) {
            $queryBulder1 = $this->em->getRepository($this->userBundle . 'Connexion')->createQueryBuilder('c');

            if ($isUser) {
                $queryBulder1 = $queryBulder1->leftJoin('c.utilisateur', 'a')
                        ->addSelect('a')
                        ->where('a.id = :user');
            } else {
                $queryBulder1 = $queryBulder1->leftJoin('c.abonne', 'a')
                        ->addSelect('a')
                        ->where('a.id = :user');
            }
            $queryBulder1->setParameter('user', $idUser);
            $rep = $queryBulder1->getQuery()->getResult();
            $nbTotal = count($rep);
        }
        return $nbTotal;
    }

    /*
     * retourne le contenu d'une cellule d'un tableau avec sa clée
     * 
     * @param string $cle
     * @param array $array
     * 
     * @return type
     */
    private function getValueInArray($cle, $array) {
        $rep = null;
        if (is_array($array)) {
            if (array_key_exists($cle, $array)) {
                $rep = $array[$cle];
            }
        }

        return $rep;
    }

    /*
     * retourne une info contenue dans la session avec sa clée
     * 
     * @param string $cle
     * 
     * @return type
     */
    public function getSessionData($cle) {
        
        $rep = null;
        if (!empty($cle)) {
            $rep = $this->getValueInArray($cle, $_SESSION);
        }

        return $rep;
    }

    /*
     *  ajoute des infos à la session
     */
    public function setSessionData($cle = LoginManager::SESSION_DATA_NAME, $data = array()) {
        $_SESSION[$cle] = $data;
        $this->session->set($cle, $data );
    }

    /*
     * Déconnexion du connecté
     * 
     * @param string $cle 
     */
    public function logout($cle = LoginManager::SESSION_DATA_NAME) {
        if ($this->isConnecte('')) { // une connexion existe
            // récupération des infos du connecté
            $data = $this->getSessionData($cle);
            if ($data != NULL) {
                // récupération de l'objet Connexion
                $con = $this->em->getRepository($this->userBundle . 'Connexion')->find($data['idConnexion']);
                if ($con != NULL) {
                    // mis à jour de la date de la derniere action
                    $dateAcutelle = new \DateTime();
                    $dateConnexion = $con->getDateConnexion();
                    $dureeConnexion = $this->dateManager->convertDureeConnexionToString($dateConnexion, $dateAcutelle);
                    $con->setDateLastAction($dateAcutelle);
                    $con->setDateDeconnexion($dateAcutelle);
                    $con->setDureeConnexion($dureeConnexion);
                }

                // mis à jour de l'état connecté
                $u = $this->em->getRepository($this->userBundle . $this->getTableConnecte())->find($data['id']);
                if ($u != NULL) {
                    $u->setEtatConnecte(FALSE);
                }
                // enregistrement des informations
                $this->em->flush();
            }
        }

        // suppression des variables de session
        unset($_SESSION[$cle]);
        $_SESSION[$cle] = NULL;
    }

    /*
     * suppression des infos du connecté
     */
    public function deleteSessionData($cle = LoginManager::SESSION_DATA_NAME) {
        unset($_SESSION[$cle]);
        $_SESSION[$cle] = array();
    }

    /*
     * Vérifie si l'utilisateur est connecté.
     * Retourne un tableu contenant 4 entrées decrites ci-dessous
     * 
     * 1- isConnecte  : boolean - Indique si une session existe, donc si l'utilisateur est connecté ou pas
     * 2- isInnactif : boolean - Indique si l'utilisateur courant a accusé un long moment d'innactivité. Si TRUE il sera déconnecté automatiquement
     * 3- isUser : boolean - Indique si le connecté est un User (Utilisateur)
     * 4- isAbonne : boolean - Indique si le connecté est un abonne 
     * 
     * @param string $nomDAction : Le nom de l'action où est invoqué le méthode isConnecte. Il permet, au cas où une session existe de créer une historique
     * des divers actions éffectuées par le connecté. Ces informations (historiques) sont enregistrées dans la table Connexion
     * 
     * @return array
     */
    public function isConnecte($nomDAction) {
        // initialisation de réponse
        $rep = array('isConnecte' => FALSE, 'isInnactif' => FALSE, 'isUser' => FALSE, 'isAbonne' => FALSE);
        $nomAction = trim($nomDAction);
        $sessionData = $this->getSessionData(LoginManager::SESSION_DATA_NAME);
        
        if (($sessionData != NULL) && ($sessionData['id'] > 0 )) {
            $rep['isConnecte'] = TRUE;
            $rep['isUser'] = $sessionData['isUser'];
            $rep['isAbonne'] = $sessionData['isAbonne'];
            // on vérifie si le connecté n'est pas innactif
            $rep['isInnactif'] = $this->isInactivite($nomAction);
        }

        return $rep;
    }

    /*
     * Vérifie si le conntecte est un  abonne et non un utilisateur 
     * @return type
     */
    private function isAbonne() {
        return ($this->getTableConnecte() == \admin\UserBundle\Types\TypeTable::ABONNE);
    }

    /*
     * Vérifie si le conntecte est un utilisateur et non un abonne
     * @return type
     */
    private function isUtilisateur() {
        return ($this->getTableConnecte() == \admin\UserBundle\Types\TypeTable::UTILISATEUR);
    }

    /*
     * Retourne le nom de la table connecté
     * @return type
     */
    public function getTableConnecte() {
        $table = null;
        $sessionDate = $this->getSessionData(LoginManager::SESSION_DATA_NAME);
        if ($sessionDate != NULL) {
            $table = $sessionDate['nomTableConnecte'];
        }
        return $table;
    }

    /*
     * Vérifie si le connecté a dépassé la durée max d'innactivité
     * 
     * @param string $nomAction
     * 
     * @return boolean
     */
    public function isInactivite($nomAction = '') {
        $rep = FALSE;
        $sessionData = $this->getSessionData(LoginManager::SESSION_DATA_NAME);

        $configurationAutoLogOut = $this->parametreManager->getValeurParametre(TypeParametre::TIME_OUT_BOOL);

        $connexion = $this->em->getRepository($this->userBundle . 'Connexion')->find($sessionData['idConnexion']);

        $action = $this->em->getRepository($this->userBundle . 'Action')->findOneBy(array('nom' => $nomAction));

        if (($configurationAutoLogOut != NULL) && ($configurationAutoLogOut == TRUE) && ($connexion != NULL)) { // la déconnexion automatique est activée
            if (($sessionData != NULL) && (isset($sessionData['id'])) && ( $sessionData['id'] > 0)) { // effectivement connecte
                // récuperation de la Connexion en cours
                $dateActuelle = new \DateTime();
//                $now = $dateActuelle->createFromFormat('Y-m-d H:i:s', time());
                // calcul des minutes d'innactivité
                $minutes = $this->dateManager->getMinutesEcoulees($connexion->getDateLastAction(), $dateActuelle);
                // récupération de la duréé maximale d'innactivité

                $maxMinute = $this->parametreManager->getValeurParametre(TypeParametre::DUREE_TIME_OUT_INT);

                if (($maxMinute != NULL) && ( $minutes >= $maxMinute)) {
                    $rep = TRUE; // le connecté a accusé un long tps d'innactivité
                }
            }
        }
        
        if (($action != NULL) && ($connexion != NULL)) {
            // mis a jr des infos de navigation

            $tabIdActions = $connexion->getTabIdActions();
            $tabIdActions[] = $action->getId();
            
            $connexion->setTabIdActions($tabIdActions);

            $tabDateActions = $connexion->getTabDateActions();
            $tabDateActions[] = date('d-m-Y H:i:s');
            $connexion->setTabDateActions($tabDateActions);

            $connexion->setDateLastAction(new \DateTime());
            $this->em->flush();
        }

        /*
         * Admin page accueil
         */
        if (($action == NULL) && ($nomAction == 'homeAction') && ($connexion != NULL)) {
            $tabIdActions = $connexion->getTabIdActions();
            
            $tabIdActions[] = 0;
            $connexion->setTabIdActions($tabIdActions);

            $tabDateActions = $connexion->getTabDateActions();
            $tabDateActions[] = date('d-m-Y H:i:s');
            $connexion->setTabDateActions($tabDateActions);

            $connexion->setDateLastAction(new \DateTime());
            $this->em->flush();
        }

        /*
         * Abonne page accueil
         */
        if (($action == NULL) && ($nomAction == 'siteHomeAction') && ($connexion != NULL)) {
            $tabIdActions = $connexion->getTabIdActions();
            $tabIdActions[] = 0;
            $connexion->setTabIdActions($tabIdActions);

            $tabDateActions = $connexion->getTabDateActions();
            $tabDateActions[] = date('d-m-Y H:i:s');
            $connexion->setTabDateActions($tabDateActions);

            $connexion->setDateLastAction(new \DateTime());
            $this->em->flush();
        }


        return $rep;
    }

    /*
     * retourne les infos de session du connecte
     * 
     * @return array
     */
    public function getInfosCurrentConnecte() {
        return $this->getSessionData(LoginManager::SESSION_DATA_NAME);
    }

    /*
     * retourne l'id du connecté courant
     * 
     * @return int : Retourne 0 aucune session n'existe
     */
    public function getCurrentId() {
        $sessionData = $this->getSessionData(LoginManager::SESSION_DATA_NAME);

        return ($sessionData == NULL) ? 0 : $sessionData['id'];
    }

    /*
     * Retourne le locale actuel
     * @return type
     */
    public function getLocale() {
        $locale = 'fr';
        $sessionLocale = $this->getSessionData('locale');
        if ($sessionLocale == NULL) {
            $sessionLocale = array('locale' => $locale);
            $this->setSessionData('locale', $sessionLocale);
        } else {
            $locale = $sessionLocale['locale'];
        }
        return $locale;
    }

    /*
     * Met à jour le locale de la session 
     * @param type $locale
     */
    public function setLocale($locale = 'fr') {
        $sessionData = $this->getSessionData(LoginManager::SESSION_DATA_NAME);
        if ($sessionData != NULL) {
            $sessionData['locale'] = $locale;
            $this->setSessionData(LoginManager::SESSION_DATA_NAME, $sessionData);
        }
        $sessionLocale = $this->getSessionData('locale');
        if ($sessionLocale != NULL) {
            $sessionLocale['locale'] = $locale;
            $this->setSessionData('locale', $sessionData);
        } else {
            $sessionLocale = array('locale' => $locale);
            $this->setSessionData('locale', $sessionLocale);
        }
    }

    /*
     * retourne le connecté courant (Il sagit d'un objet Utilisateur ou Personne). 
     * 
     * @return type
     */
    public function getCurrentConnecte() {
        $rep = NULL;
        $id = $this->getCurrentId();
        if ($id > 0) {
            $rep = $this->em->getRepository($this->userBundle . 'Utilisateur')->find($id);
        }

        return $rep;
    }

    /*
     * Lors de la connexion, déconnecte tout ceux qui ont fait plus de 5h d'innactivité
     * Il sagit des utilisateurs qui ont kité l'application sans se deconnecté. De ce fait leur étatConnecte vaut 1
     * alors k leurs infos de session sont perdues. 
     */
    public function autoLogOutAfterTimeOutAll() {
        // déconnexion dans la table User
        $users = $this->em->getRepository($this->userBundle . 'Utilisateur')->findByEtatConnecte(TRUE);

        foreach ($users as $p) {
            $this->setEtatConnecteToFalse($p);
        }
    }

    /*
     * change l'étatConnecte des entité en FALSE
     * pour utilisateurs ayant kité l'application sans se deconnecté. De ce fait leur étatConnecte vaut 1
     * alors k leurs infos de session sont serdues.
     * 
     * @param type $user : une instance d'un d'utilisateur (User / Ressource)
     * @param boolean $isUser : 
     */
    private function setEtatConnecteToFalse($user) {
        if ($user != NULL) {
            // récupération de la connexion expirée
            $connexion = $this->em->getRepository($this->userBundle . 'Connexion')->getNotLogoutConnexion($user->getId());

            if ($connexion != NULL) { // connexion trouvée
                // calcul du nombre d'heure ecoulee juska present depuis la date de la derniere action
                $heuresEcoulees = $this->dateManager->getNombreHeuresEcollees($connexion->getDateLastAction(), new \DateTime());

                if ($heuresEcoulees >= 2) {  // nb d'heure supérieur à 5
                    $connexion->setDateDeconnexion($connexion->getDateLastAction());
                    // changement de l'étatConnecte de l'utilisateur
                    $user->setEtatConnecte(FALSE);
                    $this->em->flush();
                }
            }
        }
    }

    /*
     * gestion des droits d'acces à une action
     * 
     * @param string $nomDuModule : code du module contenant l'action
     * @param string $descModule : description du module contenant l'action
     * @param string $descControl : description u controlleur contenant l'action
     * 
     * 
     * @param string $nomDAction : nom de l'action
     * @param string $descAction : description de l'action
     * @param array $tabCodeProfil : tableau contenant les codes des Profils User à qui sont destinés l'action
     * 
     * @return boolean
     */
    public function getOrSetActions($nomDuModule, $descModule, $nomDuControleur, $descControl, $nomDAction, $descAction, $idProfil = 0) {
        // initialisation de la réponse
        $rep = FALSE;
        /* Contrôles liés aux paramètres */
        $nomModule = trim($nomDuModule);
        $nomControleur = trim($nomDuControleur);
        $nomAction = trim($nomDAction);
        /* fin Contrôles liés aux paramètres */

       

        // recuperation du module
        $module = $this->em->getRepository($this->userBundle . 'Module')->findOneByNom($nomModule);
        if ($module == NULL) { //enregistrment du module au cas ou il est innexistant
            $module = new \admin\UserBundle\Entity\Module($nomModule, $descModule);
            $this->em->persist($module);
        }

        // récupération du contrôleur
        $controleur = $this->em->getRepository($this->userBundle . 'Controleur')->findOneByNom($nomControleur);
        if ($controleur == NULL) { //enregistrment du controleur au cas ou il est innexistant
            $controleur = new \admin\UserBundle\Entity\Controleur($nomControleur, $descControl);
            $this->em->persist($controleur);
        }
        // enregistrement des informations dans la base de données
        $this->em->flush();
        // récupération de l'action par rapport au module et au controleur
//        $action = $this->em->getRepository($this->adminUserBundle . 'Action')->findOneByCode($codeAction);
        $action = $this->em->getRepository($this->userBundle . 'Action')->findOneBy(array('nom' => $nomAction, 'module' => $module, 'controleur' => $controleur));

        if ($action == NULL) { //enregistrment de l'action au cas ou elle est innexistante
            $action = new \admin\UserBundle\Entity\Action($nomAction, $descAction);
            $action->setControleur($controleur);
            $action->setModule($module);
            $action->setNom($nomAction);
            $action->setDescription($descAction);
            $this->em->persist($action);
            $this->em->flush();
            $module->addAction($action);
            $controleur->addAction($action);

            $this->em->flush();
        }

        if ($idProfil == NULL) {
            $idProfil = 0;
        }

        // on vérifie si l'utilisateur a le droit 
        $profil = $this->em->getRepository($this->userBundle . 'Profil')->find($idProfil);
        
        if ($profil != NULL) {
            $rep = FALSE;
            $tabActionOfProfil = $profil->getTabIdActions();
            $idActionEncours = $action->getId();
           
            if (in_array($idActionEncours, $tabActionOfProfil)) {
                $rep = TRUE;
            }
        }
        return $rep;
    }

    /*
     * Mise à jour des droits d'un profil d'utilisateur. Retourne TRUE si l'action a réussie et FALSE si non
     * 
     * @param int $idProfil : identifiant du profil concerné.
     * @param array $tabIdActionToAllow : tableau contenant les id (de type STRING car provenant du client ) des actions à autoriser au profil concerné
     * des actions à autoriser au profil concerné
     * @param string $nomTable : nom de l'entité représentant  le profil
     * 
     * @return boolean
     */
    public function updateDroitOfProfil($idProfil, $tabIdActionToAllow, $nomTable = 'Profil') {
        // iniatlisation de la réponse
        $rep = FALSE;
        // inialisation du tableau devant contenir les id (de type int) des actions à autoriser au profil concerné
        $tabIdDroit = array();

        // récupération du profil
        $profil = $this->em->getRepository($this->userBundle . $nomTable)->find($idProfil);

        // la variable $tabIdActionToAllow est un tableau et possède au moins une entrée
        if (is_array($tabIdActionToAllow) && count($tabIdActionToAllow) > 0) {
            //Parcours du tableau $tabIdActionToAllow et conversion de chacune de ses entrée en une valeur de type int 
            foreach ($tabIdActionToAllow as $id) {
                $id = (int) $id;
                if ($id > 0) {
                    $tabIdDroit[] = (int) $id;
                }
            }

            // mis à jour des droits
            if ($profil != NULL) {
                $profil->setTabIdActions($tabIdDroit);
                $this->em->flush();

                // la réponse passe à TRUE
                $rep = TRUE;
                /*
                 * On met à jour les droits  du connecté au cas les modifications on touchées son profil
                 */
                $sessionData = $this->getSessionData(LoginManager::SESSION_DATA_NAME);
                if (($sessionData != NULL) && ( $profil->getId() == $sessionData['idProfil'])) {
                    $sessionData['tabIdActions'] = $tabIdDroit;
                    $this->setSessionData(LoginManager::SESSION_DATA_NAME, $sessionData);
                }
            }
        }



        return $rep;
    }

    /*
     * Retourne la longueur des numéros de téléphones
     * 
     * @return int
     */
    public function getLengthTel() {
        $taille = $this->parametreManager->getValeurParametre(TypeParametre::LONGUEUR_NUM_TEL_INT);
        if ($taille == NULL) {
            $taille = 8;
        }

        return $taille;
    }
    
    /*
     * Retourne le nombre maximal de tentatives de connexion
     * 
     * @return int
     */
    public function getMaxAttempt() {
        $maxAttempt = $this->parametreManager->getValeurParametre(TypeParametre::NB_ATTEMPT_INT);
        if ($maxAttempt == NULL) {
            $maxAttempt = 3;
        }

        return $maxAttempt;
    }

    /*
     * Retourne tous les profils abonné
     * 
     * @return array
     */
    public function getAllProfilAbonne() {
        $criteria = array('etat' => TypeEtat::ACTIF, 'typeProfil' => TypeProfil::PROFIL_ABONNE);
        $profilsAbonne = $this->em->getRepository($this->userBundle . 'Profil')->findBy($criteria);

        return $profilsAbonne;
    }

    /*
     * Ajoute les parametres par defaut
     */
    public function addDefaultParametre() {
        $this->addDefaultUser();
        $this->addDefaultConfig();
    }

    public function addDefaultConfig() {
        $this->parametreManager->setParametre(TypeParametre::DUREE_TIME_OUT_INT, 15, TypeDonnees::INT, "Durée max d'inactivité", "Durée d'inactivité après laquelle l'utilisateur est déconnecté automatiquement");

        $this->parametreManager->setParametre(TypeParametre::KEY_SMS_STR, '@ETR_ACE@', TypeDonnees::VARCHAR, "Clé SMS", "La clé de validation des SMS reçus. Il s’agit de clé de KANNEL ");

        $this->parametreManager->setParametre(TypeParametre::LONGUEUR_CODE_BASE_INT, 5, TypeDonnees::INT, "Longueur code base", "Longueur des codes de base attribués automatiquement aux abonnés à leur création");

        $this->parametreManager->setParametre(TypeParametre::LONGUEUR_CODE_SMS_CONFIRMATION_ORDRE_VIREMENT_INT, 10, TypeDonnees::INT, "Longueur code confirmation", "Longueur des codes de confirmation des ordres de virement effectués et acquittés par la banque");

        $this->parametreManager->setParametre(TypeParametre::LONGUEUR_CODE_SMS_END_ORDRE_VIREMENT_INT, 10, TypeDonnees::INT, "Longueur code sms", "Longueur du code SMS envoyé à un abonné pour finaliser un ordre de virement ");

        $this->parametreManager->setParametre(TypeParametre::LONGUEUR_COMPTE_INT, 14, TypeDonnees::INT, "Longueur compte", "Longueur des comptes bancaires ");

        $this->parametreManager->setParametre(TypeParametre::LONGUEUR_MIN_PASSWORD_INT, 5, TypeDonnees::INT, "Longueur min password", "Longueur minimale des mots de passe  ");

        $this->parametreManager->setParametre(TypeParametre::LONGUEUR_NUM_TEL_INT, 8, TypeDonnees::INT, "Longueur tel", "Longueur des numéros de téléphone sans l'indicatif téléphonique");

        $this->parametreManager->setParametre(TypeParametre::NB_ATTEMPT_INT, 3, TypeDonnees::INT, "Max attempt", "Nombre maximal de tentatives de connexions échouées ");

        $this->parametreManager->setParametre(TypeParametre::NB_MAX_MESSAGERIE_BOARD_INT, 10, TypeDonnees::INT, "Nombre de messages tableau de bord", "Nombre maximal de messages récents à afficher sur le tableau de bord");

        $this->parametreManager->setParametre(TypeParametre::TIME_OUT_BOOL, 1, TypeDonnees::BOOLEAN, 'Déconnexion automatique', 'Active/désactive la déconnexion automatique');

        $this->parametreManager->setParametre(TypeParametre::TIME_URL_INIT_PASSWORD_EXPIRE_INT, 2, TypeDonnees::INT, "Durée de vie des urls de réinitialisation de mot de passe", "Nombre maximal d'heures après lesquelles une route de réinitialisation de mot de passe expire ");
        
        $this->parametreManager->setParametre(TypeParametre::CHOIX_DESTINAIRE_MSG_PAR_ABONNE_BOOL, 1, TypeDonnees::INT, "Choix des destinaires d'un msg par les abonnes", "Indique si les abonné peuvent choisir le destinaire d'un message ou pas ");
    }

    private function addDefaultUser() {
        $em = $this->em;
        
        $em->transactional(
            function($em){

                $res =  $em->getRepository($this->userBundle . 'Profil')->findOneBy(array('nom'=>'MAINTENANCE'));
                
                if (!($res instanceof Profil)){
                    $maintenance = new Profil();
                    $maintenance->setCode('MAINTE');
                    $maintenance->setDescription('Profil mainteance');
                    $maintenance->setNom('MAINTENANCE');
                    $maintenance->setTypeProfil(TypeProfil::PROFIL_UTILISATEUR);
                    $em->persist($maintenance);
                }     
                //$this->em->flush();
                $res =  $em->getRepository($this->userBundle . 'Utilisateur')->findOneBy(array('email'=>'user@stock.com'));
                
                if (!($res instanceof Utilisateur)){
                    $user = new Utilisateur();
                    $user->setAdresse('Adresse user');
                    $user->setAttempt(0);
                    $user->setEmail('user@stock.com');
                    $user->setNom('stockprouser');
                    $passWord = md5('stockpro');
                    $user->setPassword($passWord);
                    $user->setCPassword($passWord);
                    $user->setPrenoms('Gautier');
                    $user->setProfil($maintenance);
                    $user->setSexe(TypeSexe::MASCULIN);
                    $user->setTel1('99220453');
                    $user->setTel2('');
                    $user->setUsername('stockpro');
                    $em->persist($user);
                }

                $res =  $em->getRepository($this->userBundle . 'Profil')->findOneBy(array('nom'=>'ABONNE'));
                //var_dump($res);
                if (!($res instanceof Abonne)){
                    $abonne = new Profil();
                    $abonne->setCode('ABONNE');
                    $abonne->setDescription('Profil Abonné');
                    $abonne->setNom('ABONNE');
                    $abonne->setTypeProfil(TypeProfil::PROFIL_ABONNE);
                    $this->em->persist($abonne);
                }

                $this->em->flush();
            });
    }
    
    public function getDataTableauDeBord($idUser, $isAbonne, $typeTb) {
        
        /*if ($typeTb == 3) {
            try {        
                $sqlrech = " SELECT cle,valeur::integer "
				//." json_build_object('name',replace(replace(replace(replace(cle,' ',''),chr(10),''),'>',''),'<',''),'y',valeur ) as ligne "
                          ." FROM 	statistik "
			." WHERE 	type_stat =0  ";
			
			($isAbonne != 0)? $sqlrech .= " and id_user = :id_quest " : $sqlrech = $sqlrech;
			
			$sqlrech .=" and idfile = (select max(idfile) from fichier where 1 = 1  and type_file = 0 ";
			
			($isAbonne != 0)? $sqlrech .= " and id_user = :id_quest " : $sqlrech = $sqlrech;
			$sqlrech .=" )";
                $stmt = $this->em->getConnection()->prepare($sqlrech);
                ($isAbonne != 0)? $stmt->bindValue(':id_quest', $idUser, PDO::PARAM_INT) : $p = 1;
                $stmt->execute();
                $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $stmt =  null;
            } catch (\Symfony\Component\Form\Exception\Exception $e) {
                $stmt = null;
                var_dump($e->getMessage()) ;                
            } 
        }elseif ($typeTb == 4) {
            try {        
                $sqlrech =  'with don as( '
			   .'	SELECT EXTRACT(YEAR FROM CURRENT_DATE) as an, EXTRACT(MONTH FROM CURRENT_DATE) as mois '
			   .'	union '
			   .'	SELECT EXTRACT(YEAR FROM CURRENT_DATE - INTERVAL \'1 month\') as an, EXTRACT(MONTH FROM CURRENT_DATE - INTERVAL \'1 month\') as mois '
			   .' ) '
			.' SELECT 	 don.an , don.mois, count(*) as nbre '
			.' FROM 	 connexion m INNER JOIN don on EXTRACT(MONTH FROM date_connexion) = don.mois AND EXTRACT(MONTH FROM date_connexion) = don.mois '
			.' WHERE    (tab_id_actions <> \'\') and (regexp_replace(tab_id_actions,\'[,0 ]{1,200}\',\'\', \'g\')<>\'[]\') and abonne_id = :id_quest '
			.' GROUP BY  don.an, don.mois;';
                $stmt = $this->em->getConnection()->prepare($sqlrech);
                $stmt->bindValue(':id_quest', $idUser, PDO::PARAM_INT);
                $stmt->execute();
                $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $stmt =  null;
            } catch (\Symfony\Component\Form\Exception\Exception $e) {
                $stmt = null;
                var_dump($e->getMessage()) ;                
            } 
        }elseif ( in_array($typeTb,array(5,7,6))) {
            
            if ($typeTb == 5) $etatfile = 1;
                
            if ($typeTb == 6) $etatfile = 2;
                    
           try {        
                $sqlrech =  'with don as( '
			.'	SELECT EXTRACT(YEAR FROM CURRENT_DATE) as an, EXTRACT(MONTH FROM CURRENT_DATE) as mois '
			.'	union '
			.'	SELECT EXTRACT(YEAR FROM CURRENT_DATE - INTERVAL \'1 month\') as an, EXTRACT(MONTH FROM CURRENT_DATE - INTERVAL \'1 month\') as mois '
			.') '
			.' SELECT   don.an,don.mois,count(*) as nbre'
			.' FROM     fichier m INNER JOIN don on EXTRACT(MONTH FROM add_date ) = don.mois AND EXTRACT(MONTH FROM add_date ) = don.mois '
			.' WHERE    1 = 1 ';
                        ($typeTb != 7)? $sqlrech .=' AND m.etat_file = :etat_file ' :$sqlrech .=' ';
                        $sqlrech .=' AND userid = :id_quest and type_file = 0 '
			.' GROUP BY  don.an , don.mois  '
			.' ORDER BY don.an DESC, don.mois DESC ';	;
                $stmt = $this->em->getConnection()->prepare($sqlrech);
                $stmt->bindValue(':id_quest', $idUser, PDO::PARAM_INT);
                ($typeTb != 7)?
                $stmt->bindValue(':etat_file', $etatfile, PDO::PARAM_INT) : 1;
                $stmt->execute();
                $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $stmt =  null;
            } catch (\Symfony\Component\Form\Exception\Exception $e) {
                $stmt = null;
                var_dump($e->getMessage()) ;                
            } 
        }else {
            try {        
                $sqlrech = ' SELECT tableau_de_bord( :id_quest , :is_abonne , :type_tb);';
                $stmt = $this->em->getConnection()->prepare($sqlrech);
                $stmt->bindValue(':id_quest', $idUser, PDO::PARAM_INT);
                $stmt->bindValue(':is_abonne', $isAbonne, PDO::PARAM_INT);
                $stmt->bindValue(':type_tb', $typeTb, PDO::PARAM_INT);
                $stmt->execute();
                $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (\Symfony\Component\Form\Exception\Exception $e) {
                $stmt = null;
                var_dump($e->getMessage()) ;
            } 
        }*/
        
        return $res=null;
    }  
    
    public function getUpdateFile($file) {
	
		/*try {        
			$sqlrech = ' SELECT update_file(:file);';
			$stmt = $this->em->getConnection()->prepare($sqlrech);
			$stmt->bindValue(':file', $file, PDO::PARAM_STR);
			$stmt->execute();
			$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (\Symfony\Component\Form\Exception\Exception $e) {
			$stmt = null;
			var_dump($e->getMessage()) ;
		} */
        
        return $res=null;
    }  	

    public function getListFiles($idUser,$idFile = 0) {
	
		/*try {        
			$sqlrech = ' SELECT * from list_files_view where userid =:user ';
                        ($idFile!=0)? $sqlrech .= ' and idfile =:file' : $res = null;
			$stmt = $this->em->getConnection()->prepare($sqlrech);
			$stmt->bindValue(':user', $idUser, PDO::PARAM_INT);
                        ($idFile!=0)? $stmt->bindValue(':file', $idFile, PDO::PARAM_INT) : $res = null;
			$stmt->execute();
			$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (\Symfony\Component\Form\Exception\Exception $e) {
			$stmt = null;
			var_dump($e->getMessage()) ;
		} */
        //var_dump($res);
        return $res=null;
    }  	
	
    public function getData($i_table,$idFile) {
		/*$sqlrech = ''; $res = null;
		try {        
			
			if ($i_table == 6){
				$sqlrech = 	 ' SELECT *  '
							.' FROM mds6 m6  '
							.' WHERE m6.id_file = :file '
							.' LIMIT 25 OFFSET 0; ';
			}elseif ($i_table == 0){
				$sqlrech = 	 " SELECT upper('mds1') as lib, count(*) as nbre  "
							." FROM mds1 m1  "
							." WHERE m1.id_file = :file "
							." UNION ALL "							
							." SELECT upper('mds3') as lib, count(*) as nbre  "
							." FROM mds3 m3  "
							." WHERE m3.id_file = :file "
							." UNION ALL "							
							." SELECT upper('mds4') as lib, count(*) as nbre  "
							." FROM mds4 m4 "
							." WHERE m4.id_file = :file "							
							." UNION ALL "							
							." SELECT upper('mds5') as lib, count(*) as nbre  "
							." FROM mds5 m5  "
							." WHERE m5.id_file = :file "
							." UNION ALL "							
							." SELECT upper('mds6') as lib, count(*) as nbre  "
							." FROM mds6 m6  "
							." WHERE m6.id_file = :file "
							." UNION ALL "
							." SELECT upper('mds7') as lib, count(*) as nbre  "
							." FROM mds7 m7  "
							." WHERE m7.id_file = :file "
							." UNION ALL "							
							." SELECT upper('z') as lib, count(*) as nbre  "
							." FROM z  "
							." WHERE id_file = :file ";
			}elseif ($i_table == 7){
				$sqlrech = 	 " SELECT *  "
							." FROM mds7 m7  "
							." WHERE m7.id_file = :file ";
			}elseif($i_table>0 && $i_table<6){
				$sqlrech = 	 ' SELECT *  '
							.' FROM mds1 m1  '
							.' INNER join mds3 m3 on m1.id_file = m3.id_file '
							.' INNER join mds4 m4 on m1.id_file = m4.id_file '
							.' INNER join mds5 m5 on m1.id_file = m5.id_file '
							.' WHERE m5.id_file = :file '							
							.' LIMIT 60 OFFSET 0; ';
			}
			
			if ( trim($sqlrech)  != ''){
				$stmt = $this->em->getConnection()->prepare($sqlrech);
				$stmt->bindValue(':file', $idFile, PDO::PARAM_INT);
				$stmt->execute();
				$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
			}
			
		} catch (\Symfony\Component\Form\Exception\Exception $e) {
			$stmt = null;
			var_dump($e->getMessage()) ;
		} */
        
        return $res=null;
    } 

    public function getStatusFiles($path_inbox,$path_guce,$process_id) {
	
        /*try {        
                $sqlrech = ' SELECT check_file(:path_inbox,:path_guce,:process_id)';
                $stmt = $this->em->getConnection()->prepare($sqlrech);
                $stmt->bindValue(':path_inbox', $path_inbox, PDO::PARAM_STR);
                $stmt->bindValue(':path_guce', $path_guce, PDO::PARAM_STR); 
                $stmt->bindValue(':process_id', $process_id, PDO::PARAM_INT); 
                $stmt->execute();
                $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Symfony\Component\Form\Exception\Exception $e) {
                $stmt = null;
                var_dump($e->getMessage()) ;
        } */
        //var_dump($res);
        return $res=null;
    }      
    public function getProcessFiles($process_id) {
	
        /*try {        
                $sqlrech = ' SELECT *  from  fichier where type_charge = :type_charge';
                $stmt = $this->em->getConnection()->prepare($sqlrech);
                $stmt->bindValue(':type_charge', $process_id, PDO::PARAM_INT);
                $stmt->execute();
                $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Symfony\Component\Form\Exception\Exception $e) {
                $stmt = null;
                var_dump($e->getMessage()) ;
        } */
        return $res=null;
    }
    
}
