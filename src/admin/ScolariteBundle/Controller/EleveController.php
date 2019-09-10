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

class EleveController extends Controller {

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

    public function listeEleveByProfilAction(Request $request, $nom, $etab, $prenoms,$sexe, $classe, $degre, $niveau,$datedeb, $datefin, $idProfil, $nbParPage, $pageActuelle) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage de la liste des élèves";
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
        if (!$loginManager->getOrSetActions(Module::MOD_ELEVE, Module::MOD_ELEVE_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des élèves");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }
        




        $em = $this->getDoctrine()->getManager();

        $idProfilQuery = (int) $idProfil;
        $profil = null;
        if ($idProfilQuery > 0) {
            $profil = $em->getRepository($this->userBundle . 'Profil')->find($idProfilQuery);
            if ($profil == NULL) {
                return $this->redirect($this->generateUrl('app_admin_user_home'));
            }
        }
        $twigManager = $this->get('type_manager');

        if ($request->getMethod() == 'POST') {
            $nom = strtolower($request->get('nom_'));
            $request->attributes->set('nom_', $nom);

            $prenoms = strtolower($request->get('prenom_'));
            //var_dump($prenom);exit;
            $request->attributes->set('prenom_', $prenoms);
            
            $datefin = strtolower($request->get('datefin_'));
            $request->attributes->set('datefin_', $datefin);

            $datedeb = strtolower($request->get('datedeb_'));
            $request->attributes->set('datedeb_', $datedeb);

            $sexe = strtolower($request->get('sexe_'));
            $request->attributes->set('sexe_', $sexe);

            $classe = strtolower($request->get('classe_'));
            $request->attributes->set('classe_', $classe);
            
            $degre = strtolower($request->get('degre_'));
            $request->attributes->set('degre_', $degre);
            
            $niveau = strtolower($request->get('niveau_'));
            $request->attributes->set('niveau_', $niveau);
            
            $etab= strtolower($request->get('etab_'));
            $request->attributes->set('etab_', $etab);
 
        }
        
        
        if($sessionData['siAdminGeneral']){
            $siEtabl = 1;
            //$queryResult = $em->getRepository($this->scolariteBundle . 'Eleve')->getAllEleve($idProfil = 0, $nbParPage, $pageActuelle, $sessionData['id']);
            $eleves = $em->getRepository($this->scolariteBundle . 'Eleve')->getListeRechercheEleve($etab,$nom, $prenoms,$sexe, $classe, $degre, $niveau,$datedeb, $datefin,$annee=1, $nbParPage , $pageActuelle);
            $stat=$this->recuperationStatistique($etab,$nom, $prenoms,$sexe, $classe, $degre, $niveau,$datedeb, $datefin,$annee, $nbParPage , $pageActuelle);

        }else{
             $siEtabl = 0;
             //$queryResult = $em->getRepository($this->scolariteBundle . 'Eleve')->getAllEleve($idProfil = 0, $nbParPage, $pageActuelle, $sessionData['id']);
             $eleves = $em->getRepository($this->scolariteBundle . 'Eleve')->getListeRechercheEleve($sessionData['etablissement'],$nom, $prenoms,$sexe, $classe, $degre, $niveau,$datedeb, $datefin,$annee=1, $nbParPage , $pageActuelle);
             $stat=$this->recuperationStatistique($sessionData['etablissement'],$nom, $prenoms,$sexe, $classe, $degre, $niveau,$datedeb, $datefin,$annee, $nbParPage , $pageActuelle);
        }      

        
        //Recuperation de la liste des classes
        $classes = $em->getRepository($this->scolariteBundle . 'Classe')->getAllActifClasse();

        // Recuperation de la liste des niveaux
         $niveaux = $em->getRepository($this->scolariteBundle . 'Niveau')->getAllActifNiveau();
        
        // Recuperation de la liste de degre
         $degres = $em->getRepository($this->scolariteBundle . 'Degre')->getAllActifDegre();
         $etablissements = $em->getRepository($this->scolariteBundle . 'Etablissement')->getAllActifEtablissement();
        
        
        
         $this->data['datedeb_'] = $datedeb;
         $this->data['classes'] = $classes;
         $this->data['degres'] = $degres;
         $this->data['stat'] = $stat;
         $this->data['siEtabl'] = $siEtabl;
         $this->data['niveaux'] = $niveaux;
        $this->data['datefin_'] = $datefin;
        $this->data['nom_'] = $nom;
        $this->data['prenom_'] = $prenoms;
        $this->data['sexe_'] = $sexe;
        $this->data['classe_'] = $classe;
        $this->data['niveau_'] = $niveau;
        $this->data['degre_'] = $degre;
        $this->data['etab_'] = $etab;

        $this->data['eleves'] = $eleves;
        $this->data['etablissements'] = $etablissements;
        $this->data['locale'] = $locale;
        $this->data['idProfil'] = $idProfil;
        $this->data['profil'] = $profil;

        return $this->render($this->scolariteBundle . 'Eleve:listeEleve.html.twig', $this->data, $this->response);
    }

    /*
     * Formulaire d'ajout d'eleve
     * 
     * @param type $idProfil
     * @return type
     */

    public function ajoutEleveAction(Request $request, $idProfil) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Ajout d'un élève ";
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

       // $originalImages = new ArrayCollection();
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
        if (!$loginManager->getOrSetActions(Module::MOD_ELEVE, Module::MOD_ELEVE_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'ajouter un élève");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }

        $locale = 'fr';

        //Generation du mot de passe 
        $motDePasse = 'passdelv';
			
        $em = $this->getDoctrine()->getManager();
        $codeBaseManager = $this->get('codebase_manager');
        /*
         * On recupère le profil si un est défini
         */
		 
        $eleve = new Eleve();
        $profil = $em->getRepository($this->userBundle . 'Profil')->findOneBy(array('id' => $idProfil, 'typeProfil' => TypeProfil::PROFIL_ABONNE));



        if ($profil != NULL) {
            $eleve->setProfil($profil);
        }
        $infoPere = array();
        $infoMere = array();
        $infoPrevenir = array();

        $form = $this->createForm('admin\ScolariteBundle\Form\EleveType', $eleve);

        $codeBase = $codeBaseManager->getNewCodeBase();
        $image = new Media();
        /*
         * Le mot de passe ne doit pas être vide
         */
        $parametreManager = $this->get('parametre_manager');
        $minLengthPassword = $parametreManager->getValeurParametre(TypeParametre::LONGUEUR_MIN_PASSWORD_INT);
//        $longueurCompte = $parametreManager->getValeurParametre(TypeParametre::LONGUEUR_COMPTE_INT);
        $longueurNumeroTel = $parametreManager->getValeurParametre(TypeParametre::LONGUEUR_NUM_TEL_INT);


        //Recupération des informations concernants l'établissment
        $objetEtablissement =  $em->getRepository($this->scolariteBundle . 'Etablissement')->find($sessionData['etablissement']);
        $originalImages = new ArrayCollection();
        //recuperation de la classe
        $listeClasse = $em->getRepository($this->scolariteBundle . 'Classe')->findAll();
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);            
 //           if ($form->isValid()) {
 //var_dump('iamge',$eleve->getMedias());
                if ($eleve->getMedias() != null) {
                   foreach ($eleve->getMedias() as $image) {
                       $originalImages->add($image);
                   } 
                } 
                $passwordEleve = $request->get('password');
                $cPasswordEleve = $request->get('password');
                $checkpere = $request->get('checkpere');
                $checkmere = $request->get('checkmere');
                //Recuparation des informations concernant les parents
                $infoPere['nom'] = $request->get('nom-pere');
                $infoPere['prenom'] = $request->get('prenom-pere');
                $infoPere['email'] = $request->get('email-pere');
                $infoPere['sexe'] = 1;
                $infoPere['tel'] = $request->get('tel-pere');
                $infoPere['adresse'] = $request->get('adresse-pere');

                $infoMere['nom'] = $request->get('nom-mere');
                $infoMere['prenom'] = $request->get('prenom-mere');
                $infoMere['email'] = $request->get('email-mere');
                $infoMere['sexe'] = 2;
                $infoMere['tel'] = $request->get('tel-mere');
                $infoMere['adresse'] = $request->get('adresse-mere');

                $infoPrevenir['nom'] = $request->get('nom-pers-prevenue');
                $infoPrevenir['prenom'] = $request->get('prenom-pers-prevenue');
                $infoPrevenir['email'] = $request->get('email-pers-prevenue');
                $infoPrevenir['tel'] = $request->get('tel-pers-prevenue');
                $infoPrevenir['adresse'] = $request->get('adresse-pers-prevenue');

                if ($minLengthPassword == NULL) {
                    $minLengthPassword = 5;
                }
                if ($longueurNumeroTel == NULL) {
                    $longueurNumeroTel = 8;
                }

                if (strlen($passwordEleve) < $minLengthPassword) {
                    $this->get('session')->getFlashBag()->add('eleve.ajout.error', "Vous devez indiquer un mot de passe d'au moins " . $minLengthPassword . " caractères");
//                } else if (strlen($eleve->getTel1()) != $longueurNumeroTel) {
//                    $this->get('session')->getFlashBag()->add('eleve.ajout.error', "Le numéro de téléphone doit contenir exactement " . $longueurNumeroTel . " caractères");
//               
               } else {
                 
                    /*
                     * Les mots de passe doivent être identiques 
                     */
                    //  if ($passwordEleve != $cPasswordEleve) {
                    if ($passwordEleve != $cPasswordEleve) {
                        $this->get('session')->getFlashBag()->add('eleve.ajout.error', 'Les mots de passe doivent être identiques');
                    } else {
                        $eleve->setPassword(md5($passwordEleve));
                        $eleve->setCPassword($passwordEleve);
                        $criteria = array('nom' => $eleve->getNom(), 'prenoms' => $eleve->getPrenoms());
                        $criteriMatricule = array('matricule' => $eleve->getMatricule());
                        $oldEleveMatricule = $em->getRepository($this->scolariteBundle . 'Eleve')->findOneBy($criteriMatricule);
                        /*
                         * Aucun user ne possede le mm non et ls mm prénoms
                         */
                        if($oldEleveMatricule==null){
                            //if ($oldEleve == NULL) {
                            $oldUserByEmail = $em->getRepository($this->scolariteBundle . 'Eleve')->findOneByEmail($eleve->getEmail());
                            /*
                             * Aucun user ne possede l'adresse email renseigne
                             */

                            if ($oldUserByEmail == NULL) {
                                /*
                                 * On vérifie enfin si le numéro de tel1 n'existe pas deja
                                 */
                                $em->getConnection()->beginTransaction();

                                try {
                                    $eleveWithTel1 = $em->getRepository($this->scolariteBundle . 'Eleve')->findOneByTel1($eleve->getTel1());
    //                                if ($eleveWithTel1 == NULL) {
                                     //   var_dump($eleve->getMedias());exit;
                                        foreach ($originalImages as $image) {
                                            $image->setEleve($eleve);
                                        }
                                      //  var_dump($eleve->getMedias());exit;
//                                        if ($eleve->getMedias() != null) {
//                                           $eleve->addMedia($eleve->getMedias()[0]);
//                                        }
                                        //recuperation de l'information concernant la personne à prevenir en cas de problème
                                        $pers_prevenir = $request->get('info-parent');
                                        $objetCodeBase = new CodeBase($codeBase);
                                        $em->persist($objetCodeBase);
                                        $em->flush();
                                        $eleve->setCodeBase($codeBase);
                                        $eleve->setDateInscription(new \DateTime());
                                        $eleve->setDateNaissance(new \DateTime($eleve->getDateNaissance()));
                                        $eleve->setEtablissement($objetEtablissement);
                                        $em->persist($eleve);
                                        if($checkpere == 1){
                                            $this->saveParentInformation($infoPere, $eleve, TypeParent::PERE, 0, TypeProfil::PROFIL_PARENT_ELEVE,$sessionData['etablissement'],$motDePasse);
                                        }
                                        if($checkmere == 1){
                                            $this->saveParentInformation($infoMere, $eleve, TypeParent::MERE, 0,TypeProfil::PROFIL_PARENT_ELEVE,$sessionData['etablissement'],$motDePasse);
                                        }
                                        $this->saveParentInformation($infoPrevenir, $eleve, TypeParent::PREVENU, $pers_prevenir,TypeProfil::PROFIL_PARENT_ELEVE,$sessionData['etablissement'],$motDePasse);
                                        //enregistrement de la classe de l'eleve
                                        $idClasse = $request->get('la-classe');
                                        $objetClasse = $em->getRepository($this->scolariteBundle . 'Classe')->find($idClasse);
                                        $objetAnneeScolaire = $em->getRepository($this->scolariteBundle . 'AnneeScolaire')->find(1);
                                        $objetSeTrouve = new SeTrouver();
                                        $objetSeTrouve->setClasse($objetClasse);
                                        $objetSeTrouve->setAnneescolaire($objetAnneeScolaire);
                                        $objetSeTrouve->setEleve($eleve);
                                        $em->persist($objetSeTrouve);
                                        $em->flush();
                                        $infoEleve['nom']=$eleve->getNom();$infoEleve['prenom']=$eleve->getPrenoms();$infoEleve['email']=$eleve->getEmail();$infoEleve['sexe']=$eleve->getSexe();$infoEleve['tel']=$eleve->getTel1();$infoEleve['adresse']=$eleve->getAdresse();
                                        $this->saveParentInformation($infoEleve, $eleve, TypeEtat::APRODUIRE, TypeEtat::APRODUIRE,TypeProfil::PROFIL_ELEVE,$sessionData['etablissement'],$motDePasse);
                                        $em->getConnection()->commit();
                                        $this->get('session')->getFlashBag()->add('eleve.ajout.success', 'Ajout effectue avec succès');
                                        return $this->redirect($this->generateUrl('app_admin_eleves', array('idProfil' => $idProfil)));
    //                                } else {
    //                                    $this->get('session')->getFlashBag()->add('eleve.ajout.error', "Un élève est déja ajouté avec le numéro de téléphone  " . $eleve->getTel1() . " ( " . $eleveWithTel1->getNom() . " " . $eleveWithTel1->getPrenoms() . ")");
    //                                }
                                } catch (\Exception $e) {
                                    $em->getConnection()->rollback();
                                    $em->close();
                                    $this->get('session')->getFlashBag()->add('eleve.ajout.error', "Erreur survenue pendant l'ajout de l'élève");
                                    throw $e;
                                }
                            } else {
                                $this->get('session')->getFlashBag()->add('eleve.ajout.error', "L'adresse email (" . $eleve->getEmail() . ") existe déjà");
                            }
                        } else {
                            $this->get('session')->getFlashBag()->add('eleve.ajout.matri.error', "Le matricule ajouté existe déjà");
                        }
                    }
                }
//            } else {
//                $this->get('session')->getFlashBag()->add('eleve.ajout.error', 'Formulaire invalide');
//            }
        }

        $this->data['formuView'] = $form->createView();
        $this->data['eleve'] = $eleve;
        $this->data['infoPere'] = $infoPere;
        $this->data['infoMere'] = $infoMere;
        $this->data['infoPrevenir'] = $infoPrevenir;

        $this->data['motDePasse'] = $motDePasse;
        $this->data['profil'] = $profil;
        $this->data['listeClasse'] = $listeClasse;
        $this->data['idProfil'] = $idProfil;
//        $this->data['longueurCompte'] = $longueurCompte;
        $this->data['locale'] = $locale;
//        $this->data['numeroCompte'] = $numeroCompte;
//        $this->data['typeCompteSelected'] = $typeCompteSelected;
//        $this->data['typesCompte'] = $typesCompte;
        $this->data['codeBase'] = $codeBase;

        return $this->render($this->scolariteBundle . 'Eleve:ajouterEleve.html.twig', $this->data, $this->response);
    }

    /*
     * Modifie un élève
     * 
     * @param type $idProfil
     * @param type $idEleve
     * @return type
     */

    public function modifierEleveAction(Request $request, $idProfil, $idEleve) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Modification d'un élève";
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
        if (!$loginManager->getOrSetActions(Module::MOD_ELEVE, Module::MOD_ELEVE_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit de modifier un élève");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }
        $em = $this->getDoctrine()->getManager();
        $listeClasse = $em->getRepository($this->scolariteBundle . 'Classe')->findAll();

        /*
         * On recupère l'eleve
         */
        $eleve = $em->getRepository($this->scolariteBundle . 'Eleve')->find($idEleve);
        if ($eleve == NULL) {
            return $this->redirect($this->generateUrl('app_admin_eleves', array('idProfil' => $idProfil)));
        }
        $form = $this->createForm('admin\ScolariteBundle\Form\ModifierEleveType', $eleve);
        /*
         * Le mot de passe ne doit pas être vide
         */
        $parametreManager = $this->get('parametre_manager');
        $minLengthPassword = $parametreManager->getValeurParametre(TypeParametre::LONGUEUR_MIN_PASSWORD_INT);
        $longueurCompte = $parametreManager->getValeurParametre(TypeParametre::LONGUEUR_COMPTE_INT);
        $longueurNumeroTel = $parametreManager->getValeurParametre(TypeParametre::LONGUEUR_NUM_TEL_INT);
        //Reperation des informations sur le père
        $mere = $em->getRepository($this->scolariteBundle . 'EstParent')->findOneBy(array('eleve' => $eleve, 'etatEstParent' => 1));

        //var_dump($mere);exit;
        //Recuperation des informations sur la mère 
        $pere = $em->getRepository($this->scolariteBundle . 'EstParent')->findOneBy(array('eleve' => $eleve, 'etatEstParent' => 2));

        //Recuperation des information sur la personne à prevenir en cas d'accident

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {

                if ($minLengthPassword == NULL) {
                    $minLengthPassword = 5;
                }
                if ($longueurCompte == NULL) {
                    $longueurCompte = 15;
                }
                if ($longueurNumeroTel == NULL) {
                    $longueurNumeroTel = 8;
                }

//                if (strlen($passwordEleve) < $minLengthPassword) {
//                    $this->get('session')->getFlashBag()->add('eleve.modifier.error', "Vous devez indiquer un mot de passe d'au moins 5 caractères");
//                } else {
                $formOk = TRUE;

                if (strlen($eleve->getTel1()) != $longueurNumeroTel) {
                    $formOk = FALSE;
                    $this->get('session')->getFlashBag()->add('eleve.modifier.error', "Le numéro de téléphone doit contenir exactement " . $longueurNumeroTel . " caractères");
                }

                if ($formOk) {
                    /*
                     * On vérifie si le numéro de tel n'est pas encor utilisé
                     */
                    $eleveWithTel1 = $em->getRepository($this->scolariteBundle . 'Eleve')->findOneByTel1($eleve->getTel1());
                    if ($eleveWithTel1 != NULL) {
                        if ($eleve->getId() != $eleveWithTel1->getId()) {
                            $formOk = FALSE;
                            $this->get('session')->getFlashBag()->add('eleve.modifier.error', "Un élève existe déjà avec le numéro de téléphone " . $eleve->getTel1() . " ( " . $eleveWithTel1->getNom() . " " . $eleveWithTel1->getPrenoms() . "  )");
                        }
                    }
                }

                /*
                 * Les mots de passe doivent être identiques 
                 */
//                    if ($passwordEleve != $cPasswordEleve) {
//                        $this->get('session')->getFlashBag()->add('eleve.modifier.error', 'Les mots de passe doivent être identiques');
//                    } else if ($formOk) {
                if ($formOk) {
                    /*
                     * A ce stade le code élève et le tel1 sont uniques
                     */
                    $criteria = array('nom' => $eleve->getNom(), 'prenoms' => $eleve->getPrenoms());
                    $oldEleve = $em->getRepository($this->scolariteBundle . 'Eleve')->findOneBy($criteria);
                    /*
                     * Aucun user ne possede le mm non et ls mm prénoms
                     */
                    if ($oldEleve == NULL) {
                        $oldUserByEmail = $em->getRepository($this->scolariteBundle . 'Eleve')->findOneByEmail($eleve->getEmail());
                        /*
                         * Aucun user ne possede l'adresse email renseigne
                         */
                        if ($oldUserByEmail == NULL) {
//                                $em->persist($eleve);
                            $em->flush();

                            $this->get('session')->getFlashBag()->add('eleve.modifier.success', 'Modifications effectuées avec succès');
                            return $this->redirect($this->generateUrl('app_admin_eleves', array('idProfil' => $idProfil)));
                        } else {
                            if ($oldUserByEmail->getId() == $eleve->getId()) {
                                /*
                                 * Il sagit du mm élève
                                 */
                                $em->flush();

                                $this->get('session')->getFlashBag()->add('eleve.modifier.success', 'Modifications effectuées avec succès');
                                return $this->redirect($this->generateUrl('app_admin_eleves', array('idProfil' => $idProfil)));
                            } else {
                                $this->get('session')->getFlashBag()->add('eleve.modifier.error', "L'adresse email (" . $eleve->getEmail() . ") existe déjà");
                            }
                        }
                    } else {
                        /*
                         * Utilisateur avec mm nom et prenoms existe deja et est supprime
                         */
                        if ($oldEleve->getEtat() == TypeEtat::SUPPRIME) {
                            $oldUserByEmail = $em->getRepository($this->scolariteBundle . 'Eleve')->findOneByEmail($eleve->getEmail());
                            /*
                             * Aucun user ne possede l'adresse email renseigne
                             */
                            if ($oldUserByEmail == NULL) {
//                                    $em->persist($eleve);
                                $em->flush();

                                $this->get('session')->getFlashBag()->add('eleve.modifier.success', 'Modifications effectuées avec succès');
                                return $this->redirect($this->generateUrl('app_admin_eleves', array('idProfil' => $idProfil)));
                            } else {
                                /*
                                 * Un eleve avec la mm adresse email exist
                                 */
                                if ($oldUserByEmail->getId() == $eleve->getId()) {
                                    /*
                                     * Il sagit du mm eleve
                                     */
//                                        $em->persist($eleve);
                                    $em->flush();

                                    $this->get('session')->getFlashBag()->add('eleve.modifier.success', 'Modifications effectuées avec succès');
                                    return $this->redirect($this->generateUrl('app_admin_eleves', array('idProfil' => $idProfil)));
                                } else {
                                    $this->get('session')->getFlashBag()->add('eleve.modifier.already.exist', "L'élève " . $eleve->getNom() . " " . $eleve->getPrenoms() . " existe déjà");
                                }
                            }
                        } else {
                            /*
                             * Utilisateur avec mm nom et prenoms existe deja et est non supprime
                             */
                            if ($oldEleve->getId() == $eleve->getId()) {
                                /*
                                 * Il sagit du mm eleve
                                 */
                                $em->flush();

                                $this->get('session')->getFlashBag()->add('eleve.modifier.success', 'Modifications effectuées avec succès');
                                return $this->redirect($this->generateUrl('app_admin_eleves', array('idProfil' => $idProfil)));
                            } else {
                                $this->get('session')->getFlashBag()->add('eleve.modifier.already.exist', "L'élève " . $eleve->getNom() . " " . $eleve->getPrenoms() . " existe déjà");
                            }
                        }
                    }
                }
                //}
            } else {
                $this->get('session')->getFlashBag()->add('eleve.modifier.error', 'Formulaire invalide');
            }
        }

        $this->data['formuView'] = $form->createView();
        $this->data['eleve'] = $eleve;
        $this->data['listeClasse'] = $listeClasse;
        // $this->data['profil'] = $eleve->getProfil();
        $this->data['idProfil'] = $idProfil;
        $this->data['pere'] = $pere;
        $this->data['mere'] = $mere;
        $this->data['idEleve'] = $idEleve;
        $this->data['locale'] = $locale;

        return $this->render($this->scolariteBundle . 'Eleve:modifierEleve.html.twig', $this->data, $this->response);
    }

    /*
     * Déconnecte un eleve
     * @return Response
     */

    public function adminLogoutEleveAction(Request $request) {
        $rep = array('etat' => FALSE, 'msg' => 'Erreur survenue lors du traitement', 'logout' => FALSE);
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Déconnection d'un Utilisateur";
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
                $rep['msg'] = "Vous avez accusé un long moment d'inactivité. Veuillez-vous connecter de nouveau";
                $loginManager->logout(LoginManager::SESSION_DATA_NAME);
                return new Response(json_encode($rep));
            }
            /*
             * Seuls les élèves admins ont le droit d'acceder à cette action
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
        if (!$loginManager->getOrSetActions(Module::MOD_ELEVE, Module::MOD_ELEVE_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $rep['msg'] = "Vous n'avez pas le droit de déconnecter un élève";
            return new Response(json_encode($rep));
        }

        $em = $this->getDoctrine()->getManager();
        if (($request->isXmlHttpRequest()) && ($request->getMethod() == 'POST')) {
            $idUser = (int) $request->get('idUser');
            $user = $em->getRepository($this->scolariteBundle . 'Eleve')->find($idUser);
            if ($user == NULL) {
                $rep['msg'] = "Informations introuvables";
                return new Response(json_encode($rep));
            }

            $user->setEtatConnecte(FALSE);
            $em->flush();
            $rep['etat'] = TRUE;
            if (($user->getId() == $sessionData['id']) && ($user->getProfil()->getId() == $sessionData['idProfil'])) {
                $rep['logout'] = TRUE;
            } else {
                $this->get('session')->getFlashBag()->add('eleve.modifier.success', 'Utilisateur ' . $user->getNom() . ' ' . $user->getPrenoms() . ' déconnecté avec succès');
            }
            return new Response(json_encode($rep));
        }
        return new Response(json_encode($rep));
    }

    /*
     * Activation, suppression, désactivation d'un élève
     * @return Response
     */

    public function changerEtatEleveAction(Request $request) {
        $rep = array('etat' => FALSE, 'msg' => 'Erreur survenue lors du traitement');
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Activation, suppression, désactivation d'un élève";
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
                $rep['msg'] = "Vous avez accusé un long moment d'inactivité. Veuillez-vous connecter de nouveau";
                $loginManager->logout(LoginManager::SESSION_DATA_NAME);
                return new Response(json_encode($rep));
            }
            /*
             * Seuls les élèves admins ont le droit d'acceder à cette action
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
        if (!$loginManager->getOrSetActions(Module::MOD_ELEVE, Module::MOD_ELEVE_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $rep['msg'] = "Vous n'avez pas le droit d'activer, de supprimer ou de désactiver un élève";
            return new Response(json_encode($rep));
        }

        $em = $this->getDoctrine()->getManager();
        if (($request->isXmlHttpRequest()) && ($request->getMethod() == 'POST')) {
            $tempEtat = (int) $request->get('etat');
            $tempIds = $request->get('sId');

            $etat = TypeEtat::INACTIF;
            if ($tempEtat == 0) {
                $etat = TypeEtat::INACTIF;
            } else if ($tempEtat == 1) {
                $etat = TypeEtat::ACTIF;
            } else if ($tempEtat == 2) {
                $etat = TypeEtat::SUPPRIME;
            } else {
                return new Response(json_encode($rep));
            }
            $tabIds = explode('|', $tempIds);
            $oneOk = FALSE;
            foreach ($tabIds as $idS) {
                $user = $em->getRepository($this->scolariteBundle . 'Eleve')->find((int) $idS);
                if ($user != NULL) {
                    $user->setEtat($etat);
                    $em->flush();
                    $oneOk = TRUE;
                }
            }
            if ($oneOk) {
                $this->get('session')->getFlashBag()->add('eleve.modifier.success', 'Opération éffectuée avec succès');
                $rep['msg'] = '';
                $rep['etat'] = TRUE;
            }

            return new Response(json_encode($rep));
        }
        return new Response(json_encode($rep));
    }

    /*
     * Afficher les informations  d'un élève
     * 
     * @param type $idProfil
     * @param type $idEleve
     * @return type
     */

    public function infosEleveAction(Request $request, $idProfil, $idEleve) {
        /**
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /**
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage des informations d'un élève";
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
        if (!$loginManager->getOrSetActions(Module::MOD_ELEVE, Module::MOD_ELEVE_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder aux informations des élèves");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }

        if($sessionData['idProfil']==5){
            $idEleve = $sessionData['idEleve'];
        }

        $em = $this->getDoctrine()->getManager();

        $operationManager = $this->get('operation_manager');
        $idannee=$sessionData['idannee'];
        $eleve = $em->getRepository($this->scolariteBundle . 'Eleve')->find($idEleve);
        $classe = $em->getRepository($this->scolariteBundle . 'Eleve')->getClasseEnCours($idEleve, $idannee);
        $scolarite = $operationManager->getInfoSoldeEleve($idEleve, $idannee,2,'CPTE00001');
        
       // var_dump($idEleve);exit;
        if ($eleve == NULL) {
            return $this->redirect($this->generateUrl('app_admin_eleves', array('idProfil' => $idProfil)));
        }
        $form = $this->createForm('admin\ScolariteBundle\Form\ModifierElevePhotoType', $eleve);
        /*
         * On recupère l'eleve
         */
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            //if ($form->isValid()) {
                //$em->persist($eleve);
                $eleve->setDateModification(new \DateTime());
                $em->flush();
           // }          
          return $this->redirect($this->generateUrl('app_admin_eleve_infos', array('idEleve' => $eleve->getId())));                             
        }
        //Recuperation de la classe actuel 
        
        /*
         * Le mot de passe ne doit pas être vide
         */
//        $parametreManager = $this->get('parametre_manager');
//        $minLengthPassword = $parametreManager->getValeurParametre(TypeParametre::LONGUEUR_MIN_PASSWORD_INT);
//        $longueurCompte = $parametreManager->getValeurParametre(TypeParametre::LONGUEUR_COMPTE_INT);
//        $longueurNumeroTel = $parametreManager->getValeurParametre(TypeParametre::LONGUEUR_NUM_TEL_INT);
      

        $this->data['formuView'] = $form->createView();
        $this->data['eleve'] = $eleve;
        $this->data['scolarite'] = $scolarite;
        $this->data['classe'] = $classe;
        //$this->data['profil'] = $eleve->getProfil();
        $this->data['idProfil'] = $idProfil;
//        $this->data['longueurCompte'] = $longueurCompte;
        $this->data['idEleve'] = $idEleve;
        $this->data['locale'] = $locale;
        $this->data['isAdmin'] = $sessionData['isUser'];

        return $this->render($this->scolariteBundle . 'Eleve:afficherinfosEleve.html.twig', $this->data, $this->response);
    }
    
    
    /*
     * Afficher les informations  d'un élève
     * 
     * @param type $idProfil
     * @param type $idEleve
     * @return type
     */

    public function carteEleveAction(Request $request, $idClasse, $idEleve) {
        /**
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /**
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage des informations d'un élève";
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
        if (!$loginManager->getOrSetActions(Module::MOD_ELEVE, Module::MOD_ELEVE_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder aux informations des élèves");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }


        $em = $this->getDoctrine()->getManager();
        /*
         * On recupère l'eleve
         */
        $idannee= $sessionData['etablissement'];
        $eleve = $em->getRepository($this->scolariteBundle . 'Eleve')->find($idEleve);
        $classe = $em->getRepository($this->scolariteBundle . 'Eleve')->getClasseEnCours($idEleve, $idannee);
        
        
        if ($eleve == NULL) {
            return $this->redirect($this->generateUrl('app_admin_eleves'));
        }

        $form = $this->createForm('admin\ScolariteBundle\Form\ModifierEleveType', $eleve);

        //Recuperation de la classe actuel 

      

        $this->data['formuView'] = $form->createView();
        $this->data['eleve'] = $eleve;
        $this->data['classe'] = $classe;
        //$this->data['profil'] = $eleve->getProfil();
     //   $this->data['idProfil'] = $idProfil;
//        $this->data['longueurCompte'] = $longueurCompte;
        $this->data['idEleve'] = $idEleve;
        $this->data['locale'] = $locale;
        $this->data['isAdmin'] = $sessionData['isUser'];

        return $this->render($this->scolariteBundle . 'Eleve:afficherCarteEleve.html.twig', $this->data, $this->response);
    }
    /*
     * Afficher les informations  d'un élève
     * 
     * @param type $idProfil
     * @param type $idEleve
     * @return type
     */

    public function listeEleveParentAction(Request $request) {
        /**
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /**
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage des informations d'un élève";
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
//        if (!$loginManager->getOrSetActions(Module::MOD_ELEVE, Module::MOD_ELEVE_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
//            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
//            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder aux informations des élèves");
//            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
//        }


        $em = $this->getDoctrine()->getManager();
        /*
         * On recupère l'eleve
         */
        $idannee= $sessionData['etablissement'];
        $listeEleve = $em->getRepository($this->scolariteBundle . 'EstParent')->getEleveTuteur($sessionData['id']);
        
        
        
        if ($listeEleve == NULL) {
              $this->get('session')->getFlashBag()->add('ina', "Vous avez n'avez pas d'élève");
              return $this->redirect($this->generateUrl('app_admin_eleves'));
        }



        //Recuperation de la classe actuel 

      
;
        $this->data['listeeleve'] = $listeEleve;
        $this->data['locale'] = $locale;
        $this->data['isAdmin'] = $sessionData['isUser'];

        return $this->render($this->scolariteBundle . 'Eleve:listeEleveParent.html.twig', $this->data, $this->response);
    }

    /*
     * Génère un nouvel code de base par ajax
     * @return Response
     */

    public function getNewCodeBaseEleveAction(Request $request) {
        $rep = array('etat' => FALSE, 'msg' => 'Erreur survenue lors du traitement', 'code' => '');
        /*
         * Nom de l'action en cours
         */
        $nomAction = 'getNewCodeBaseEleveAction';
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Génération d'un nouvel code de base pour un élève";
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
                $rep['msg'] = "Vous avez accusé un long moment d'inactivité. Veuillez-vous connecter de nouveau";
                $loginManager->logout(LoginManager::SESSION_DATA_NAME);
                return new Response(json_encode($rep));
            }
            /*
             * Seuls les élèves admins ont le droit d'acceder à cette action
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
        if (!$loginManager->getOrSetActions(Module::MOD_ELEVE, Module::MOD_ELEVE_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {

            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $rep['msg'] = "Vous n'avez pas le droit de génèrer un nouvel code de base pour un élève";
            return new Response(json_encode($rep));
        }

        if (($request->isXmlHttpRequest()) && ($request->getMethod() == 'POST')) {
            $codeBaseManager = $this->get('codebase_manager');
            $rep['code'] = $codeBaseManager->getNewCodeBase();
            $rep['etat'] = TRUE;
        }
        return new Response(json_encode($rep));
    }

    public function saveParentInformation(array $infoParent, $eleve, $typeParent, $traite_personne_prevenir,$profil=4,$etablissement = 1,$motDePasse=0 ) {

        $em = $this->getDoctrine()->getManager();
        if ($traite_personne_prevenir == 0) {
            $objectUtilisateur = new Utilisateur();
            //Recuperation du profil de l'élève
            $unProfil = $em->getRepository($this->userBundle . 'Profil')->find($profil);
            $unEtablissement = $em->getRepository($this->scolariteBundle . 'Etablissement')->find($etablissement);           
            if($infoParent['email'] == ""){
                $email = $infoParent['nom'].''.$infoParent['prenom'].rad2deg(1).'@lequipe.com';
            }else{
                $email = $infoParent['email'];
            }
            
            $objectUtilisateur->setPassword(md5($motDePasse.''.$eleve->getId()));
            $objectUtilisateur->setcPassword(md5($motDePasse.''.$eleve->getId()));
            $objectUtilisateur->setNom($infoParent['nom']);
            $objectUtilisateur->setPrenoms($infoParent['prenom']);
            $objectUtilisateur->setSexe($infoParent['sexe']);
            $objectUtilisateur->setEtablissement($unEtablissement);
            if($typeParent==0){
                $objectUtilisateur->setEleve($eleve);
            }
            $objectUtilisateur->setTel1($infoParent['tel']);
            $objectUtilisateur->setEmail($email);
            $objectUtilisateur->setAdresse($infoParent['adresse']);
            $objectUtilisateur->setProfil($unProfil);
            $em->persist($objectUtilisateur);
            
        } else {
            $unRecupObjetPerChoix = $em->getRepository($this->scolariteBundle . 'EstParent')->findOneBy(array('eleve' => $eleve, 'etatEstParent' => $traite_personne_prevenir));
            $objectUtilisateur = $unRecupObjetPerChoix->getUtilisateur();
        }
        if($typeParent!=0){
            //Est Parent 
            $objetEstParent = new EstParent();
            $objetEstParent->setEleve($eleve);
            $objetEstParent->setEtatEstParent($typeParent);
            $objetEstParent->setUtilisateur($objectUtilisateur);
            $em->persist($objetEstParent);
        }
        $em->flush();
        
        return 1;
    }
    
    function recuperationStatistique($etab,$nom, $prenoms,$sexe, $classe, $degre, $niveau,$datedeb, $datefin,$annee, $nbParPage , $pageActuelle){
        $em = $this->getDoctrine()->getManager();
        $tab = array();
        $eleves = $em->getRepository($this->scolariteBundle . 'Eleve')->getListeRechercheEleve($etab,$nom, $prenoms,$sexe, $classe, $degre, $niveau,$datedeb, $datefin,$annee, $nbParPage , $pageActuelle);
        $fille = $em->getRepository($this->scolariteBundle . 'Eleve')->getListeRechercheEleve($etab,$nom, $prenoms,2, $classe, $degre, $niveau,$datedeb, $datefin,$annee, $nbParPage , $pageActuelle);
        $garcon = $em->getRepository($this->scolariteBundle . 'Eleve')->getListeRechercheEleve($etab,$nom, $prenoms,1, $classe, $degre, $niveau,$datedeb, $datefin,$annee, $nbParPage , $pageActuelle);
        //recuperation nombre total
        //recuperation des dégre
        $degres = $em->getRepository($this->scolariteBundle . 'Degre')->getAllActifDegre();
        
        foreach ($degres as $unDegre){
          $tab['degre'][$unDegre->getId()] =count($em->getRepository($this->scolariteBundle . 'Eleve')->getListeRechercheEleve($etab,$nom, $prenoms,$sexe, $classe, $unDegre->getId(), $niveau,$datedeb, $datefin,$annee, $nbParPage , $pageActuelle)); 
        }
        
        $tab['nombreTotal']= count($eleves);
        $tab['fille']= count($fille);
        $tab['garcon']= count($garcon);
        
        return $tab;        
    }

    

 /**
     *  Mise en place le bulletin d'un élève
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
    public function bulletinEleveAction(Request $request, $idEleve, $idDecoupage) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage du bulletin d'un élève";
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
        if (!$loginManager->getOrSetActions(Module::MOD_ELEVE, Module::MOD_ELEVE_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder d'afficher le bulletin de l'élève ");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }
        $em = $this->getDoctrine()->getManager();
        
        $idannee = $sessionData['idannee'];
        $idEtab = $sessionData['etablissement'];
        $objetEtablissement = $em->getRepository($this->scolariteBundle . 'Etablissement')->find($idEtab);
        if($sessionData['idProfil']==5){
            $idEleve = $sessionData['idEleve'];
        }
        //Vérifier si c'est un élève | Enseignant | Administration
        //Recuperation de la classe de l'élève
        $listeEstEnseigne =array();
        
        $tabInfo = array();
        $tabMatiere = array();
        $tabMoyenneGenerale = array();
        $i=0;
        
        $objetClasseEnCours  = $em->getRepository($this->scolariteBundle . 'Eleve')->getClasseEnCours($idEleve, $idannee);
        $idClasse = $objetClasseEnCours[0]->getId();
       $listeEleveClasse = $em->getRepository($this->scolariteBundle . 'Eleve')->getListeEleveClasse($idClasse, $idannee);
        $objetClasse = $em->getRepository($this->scolariteBundle . 'Classe')->find($idClasse);
        $unEleve = $em->getRepository($this->scolariteBundle . 'Eleve')->find($idEleve);
        if($sessionData['idProfil']==4){
            $unUtil = $em->getRepository($this->userBundle . 'Utilisateur')->find($sessionData['id']);
            $siTestObjetUtl = $em->getRepository($this->scolariteBundle . 'EstParent')->findOneBy(array('eleve'=>$unEleve,'utilisateur'=>$unUtil));
            if(count($siTestObjetUtl)==0){
                return $this->redirect($this->generateUrl('app_admin_user_index'));
            }
        }elseif($sessionData['idProfil']==5){
           $siTestObjetEleve = $em->getRepository($this->userBundle . 'Utilisateur')->findOneBy(array('eleve'=>$unEleve));
            if(count($siTestObjetEleve)==0){
                return $this->redirect($this->generateUrl('app_admin_user_index'));
            }
        }
        $objetDecoupage = $em->getRepository($this->scolariteBundle . 'Decoupage')->find($idDecoupage);
        if(count($objetDecoupage)==0){
             //$this->get('session')->getFlashBag()->add('eleve.ajout.success', 'Ajout effectue avec succès');
             return $this->redirect($this->generateUrl('app_admin_user_index'));

        }
        
        $objetAnneeScolaire = $em->getRepository($this->scolariteBundle . 'AnneeScolaire')->find($idannee);
        
        $listeTypeMatiere = $em->getRepository($this->scolariteBundle . 'TypeMatiere')->getClasseTypeMatiere($objetClasse->getNiveau()->getId(), $objetClasse->getFiliere()->getId());
        
        //foreach($listeEleveClasse as $unEleve){
            
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
        

        $this->data['objetClasse'] = $objetClasse;
        $this->data['objetEtablissement'] = $objetEtablissement;
        $this->data['unEleve'] = $unEleve;
        $this->data['listeTypeMatiere'] = $listeTypeMatiere;
        $this->data['listeEleveClasse'] = $listeEleveClasse;
        $this->data['etablissement'] = $objetEtablissement;
        $this->data['listeEstEnseigne'] = $listeEstEnseigne;
        $this->data['objetDecoupage'] = $objetDecoupage;
        $this->data['tabMoyenneGenerale'] = $tabMoyenneGenerale;
        $this->data['objetAnneeScolaire'] = $objetAnneeScolaire;
        $this->data['tabMatiere'] = $tabMatiere;
        $this->data['tabInfo'] = $tabInfo;
        $this->data['idClasse'] = $idClasse;
        
        $this->data['locale'] = $locale;
        return $this->render($this->scolariteBundle . 'Eleve:bulletinEleve.html.twig', $this->data, $this->response);
    }    
    
    
    
    
}
