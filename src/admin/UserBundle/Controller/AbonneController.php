<?php

namespace admin\UserBundle\Controller;

use admin\ParamBundle\Types\TypeParametre;
use admin\UserBundle\Entity\Abonne;
use admin\UserBundle\Entity\CodeBase;
use admin\UserBundle\Entity\Module;
use admin\UserBundle\Form\AbonneType;
use admin\UserBundle\Form\ModifierAbonneType;
use admin\UserBundle\Types\TypeEtat;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use \admin\UserBundle\Services\LoginManager;
use \admin\UserBundle\Types\TypeProfil;
use \Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;

class AbonneController extends Controller {

    
     use \admin\UserBundle\ControllerModel\paramUtilTrait;

    public function __construct() {
        $this->response = new Response();
        $this->response->headers->addCacheControlDirective('no-cache', true);
        $this->response->headers->addCacheControlDirective('max-age', 0);
        $this->response->headers->addCacheControlDirective('must-revalidate', true);
        $this->response->headers->addCacheControlDirective('no-store', true);
        $this->logMessage = " [ AbonneController ] ";
        $this->description =  "Controlleur qui gère les abonnés";
    }

    /*
     * Affiche la liste des abonnes par parfil. si $idProfil = 0 alors on affiche les abonnes de ts les profils
     * @param int $idProfil
     * @return type
     */
    public function listeAbonneByProfilAction(Request $request, $idProfil, $nbParPage, $pageActuelle) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Affichage de la liste des Utilisateurs";
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
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'accéder à la liste des abonnés");
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
        $queryResult = $em->getRepository($this->userBundle . 'Abonne')->getAllAbonne($idProfil, $nbParPage, $pageActuelle, $sessionData['id']);



        $nbParPage = $queryResult['nbParPage'];
        $pageActuelle = $queryResult['pageActuelle'];
        $nbTotal = $queryResult['nbTotal'];
        $nbTotalPage = $queryResult['nbTotalPage'];

        $abonnes = $queryResult['data'];


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

        $this->data['abonnes'] = $abonnes;
        $this->data['locale'] = $locale;
        $this->data['idProfil'] = $idProfil;
        $this->data['profil'] = $profil;

        return $this->render($this->userBundle . 'Abonne:listeAbonne.html.twig', $this->data, $this->response);
    }

    /*
     * Formulaire d'ajout d'abonne
     * 
     * @param type $idProfil
     * @return type
     */
    public function ajoutAbonneAction(Request $request, $idProfil) {
        /*
         * Nom de l'action en cours
         */
        $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Ajout d'un nouvel Utilisateur";
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
        
        $originalImages = new ArrayCollection();
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
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit d'ajouter un utilisateur");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }

$locale ='fr';

        $em = $this->getDoctrine()->getManager();
        $codeBaseManager = $this->get('codebase_manager');
        /*
         * On recupère le profil si un est défini
         */
        $abonne = new Abonne();
        $profil = $em->getRepository($this->userBundle . 'Profil')->findOneBy(array('id' => $idProfil, 'typeProfil' => TypeProfil::PROFIL_ABONNE));

        if ($profil != NULL) {
            $abonne->setProfil($profil);
        }


        $form = $this->createForm('admin\UserBundle\Form\AbonneType', $abonne);

        $codeBase = $codeBaseManager->getNewCodeBase();

        /*
         * Le mot de passe ne doit pas être vide
         */
        $parametreManager = $this->get('parametre_manager');
        $minLengthPassword = $parametreManager->getValeurParametre(TypeParametre::LONGUEUR_MIN_PASSWORD_INT);
//        $longueurCompte = $parametreManager->getValeurParametre(TypeParametre::LONGUEUR_COMPTE_INT);
        $longueurNumeroTel = $parametreManager->getValeurParametre(TypeParametre::LONGUEUR_NUM_TEL_INT);
        
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            //            $numeroCompte = $request->request->get('numeroCompte');
            //            $typeCompteSelected = (int) $request->request->get('typeCompte');
            //$codeBase = $request->request->get('codeBase');            
            if ($form->isValid()) {
                
                // Create an ArrayCollection of the current image
            
            if ($abonne->getImagepers() != null) {
            foreach ($abonne->getImagepers() as $image) {
                $originalImages->add($image);
            } 
            
            }

                if ($minLengthPassword == NULL) {
                    $minLengthPassword = 5;
                }
                if ($longueurNumeroTel == NULL) {
                    $longueurNumeroTel = 8;
                }
                
                
                //var_dump($longueurNumeroTel);exit;
                if (strlen($abonne->getPassword()) < $minLengthPassword) {
                    $this->get('session')->getFlashBag()->add('abonne.ajout.error', "Vous devez indiquer un mot de passe d'au moins " . $minLengthPassword . " caractères");
                } else if (strlen($abonne->getTel1()) != $longueurNumeroTel) {
                    $this->get('session')->getFlashBag()->add('abonne.ajout.error', "Le numéro de téléphone doit contenir exactement " . $longueurNumeroTel . " caractères");
                } else {
                    /*
                     * Les mots de passe doivent être identiques 
                     */
                    if ($abonne->getPassword() != $abonne->getCPassword()) {
                        $this->get('session')->getFlashBag()->add('abonne.ajout.error', 'Les mots de passe doivent être identiques');
                    } else {
                        $abonne->setPassword(md5($abonne->getPassword()));
                        $abonne->setCPassword($abonne->getPassword());
                        $criteria = array('nom' => $abonne->getNom(), 'prenoms' => $abonne->getPrenoms());
                        $oldAbonne = $em->getRepository($this->userBundle . 'Abonne')->findOneBy($criteria);
                        /*
                         * Aucun user ne possede le mm non et ls mm prénoms
                         */
                        if ($oldAbonne == NULL) {
                            $oldUserByEmail = $em->getRepository($this->userBundle . 'Abonne')->findOneByEmail($abonne->getEmail());
                            /*
                             * Aucun user ne possede l'adresse email renseigne
                             */
                            
                            if ($oldUserByEmail == NULL) {
                                /*
                                 * On vérifie enfin si le numéro de tel1 n'existe pas deja
                                 */
                                $abonneWithTel1 = $em->getRepository($this->userBundle . 'Abonne')->findOneByTel1($abonne->getTel1());
                                if ($abonneWithTel1 == NULL) {
                                    
                                    foreach ($originalImages as $image) {
                                        $image->setAbonne($abonne);
                                    }                            
                                    
                                    
                                    
                                    
                                    if ($abonne->getImagepers() != null) {
                                       $abonne->addImageper($abonne->getImagepers());
                                    }
                                    
                                    $objetCodeBase = new CodeBase($codeBase);
                                    $em->persist($objetCodeBase);
                                    $em->flush();
                                    $abonne->setCodeBase($codeBase);

                                    $em->persist($abonne);
                                    $em->flush();


                                    $this->get('session')->getFlashBag()->add('abonne.ajout.success', 'Ajout effectue avec succès');
                                    return $this->redirect($this->generateUrl('app_admin_abonnes', array('idProfil' => $idProfil)));
                                } else {
                                    $this->get('session')->getFlashBag()->add('abonne.ajout.error', "Un utilisateur est déja ajouté avec le numéro de téléphone  " . $abonne->getTel1() . " ( " . $abonneWithTel1->getNom() . " " . $abonneWithTel1->getPrenoms() . ")");
                                }
                            } else {
                                $this->get('session')->getFlashBag()->add('abonne.ajout.error', "L'adresse email (" . $abonne->getEmail() . ") existe déjà");
                            }
                        } else {
                            /*
                             * Utilisateur avec mm nom et prenoms existe deja et est supprime
                             */
                            if ($oldAbonne->getEtat() == TypeEtat::SUPPRIME) {
                                $oldUserByEmail = $em->getRepository($this->userBundle . 'Abonne')->findOneByEmail($abonne->getEmail());
                                /*
                                 * Aucun user ne possede l'adresse email renseigne
                                 */
                                if ($oldUserByEmail == NULL) {
                                    /*
                                     * On vérifie enfin si le numéro de tel1 n'existe pas deja
                                     */
                                    $abonneWithTel1 = $em->getRepository($this->userBundle . 'Abonne')->findOneByTel1($abonne->getTel1());
                                    if ($abonneWithTel1 == NULL) {
                                        
                                    foreach ($originalImages as $image) {
                                        $image->setAbonne($abonne);
                                    }                            
                                    
                                    if ($abonne->getImagepers() != null) {
                                       $abonne->addImageper($unProduit->getImagepers()[0]);
                                    }
                                        
                                        $objetCodeBase = new CodeBase($codeBase);
                                        $em->persist($objetCodeBase);
                                        $em->flush();
                                        $abonne->setCodeBase($codeBase);

                                        $em->persist($abonne);
                                        $em->flush();
                                        $this->get('session')->getFlashBag()->add('abonne.ajout.success', 'Ajout effectue avec succès');
                                        return $this->redirect($this->generateUrl('app_admin_abonnes', array('idProfil' => $idProfil)));
                                    } else {
                                        $this->get('session')->getFlashBag()->add('abonne.ajout.error', "Un abonné est déja ajouté avec le numéro de téléphone  " . $abonne->getTel1() . " ( " . $abonneWithTel1->getNom() . " " . $abonneWithTel1->getPrenoms() . ")");
                                    }
                                } else {
                                    $this->get('session')->getFlashBag()->add('abonne.ajout.error', "L'adresse email (" . $abonne->getEmail() . ") existe déjà");
                                }
                            } else {
                                /*
                                 * Utilisateur avec mm nom et prenoms existe deja et est non supprime
                                 */
                                $this->get('session')->getFlashBag()->add('abonne.ajout.already.exist', "L'utilisateur " . $abonne->getNom() . " " . $abonne->getPrenoms() . " existe déjà");
                            }
                        }
                    }
                }
            } else {
//                var_dump($form->getErrors()); exit;
                $this->get('session')->getFlashBag()->add('abonne.ajout.error', 'Formulaire invalide');
            }
        }

        $this->data['formuView'] = $form->createView();
        $this->data['abonne'] = $abonne;
        $this->data['profil'] = $profil;
        $this->data['idProfil'] = $idProfil;
//        $this->data['longueurCompte'] = $longueurCompte;
        $this->data['locale'] = $locale;
//        $this->data['numeroCompte'] = $numeroCompte;
//        $this->data['typeCompteSelected'] = $typeCompteSelected;
//        $this->data['typesCompte'] = $typesCompte;
        $this->data['codeBase'] = $codeBase;

        return $this->render($this->userBundle . 'Abonne:ajouterAbonne.html.twig', $this->data, $this->response);
    }
    
    /*
     * Modifie un abonné
     * 
     * @param type $idProfil
     * @param type $idAbonne
     * @return type
     */
    public function modifierAbonneAction(Request $request,  $idProfil, $idAbonne) {
        /*
         * Nom de l'action en cours
         */
       $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Modification d'un utilisateur";
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
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit de modifier un utilisateur");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }


        $em = $this->getDoctrine()->getManager();
        /*
         * On recupère l'abonne
         */
        $abonne = $em->getRepository($this->userBundle . 'Abonne')->find($idAbonne);
        if ($abonne == NULL) {
            return $this->redirect($this->generateUrl('app_admin_abonnes', array('idProfil' => $idProfil)));
        }
        
        $form = $this->createForm(ModifierAbonneType::class, $abonne);


        /*
         * Le mot de passe ne doit pas être vide
         */
        $parametreManager = $this->get('parametre_manager');
        $minLengthPassword = $parametreManager->getValeurParametre(TypeParametre::LONGUEUR_MIN_PASSWORD_INT);
        $longueurCompte = $parametreManager->getValeurParametre(TypeParametre::LONGUEUR_COMPTE_INT);
        $longueurNumeroTel = $parametreManager->getValeurParametre(TypeParametre::LONGUEUR_NUM_TEL_INT);


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

                if (strlen($abonne->getPassword()) < $minLengthPassword) {
                    $this->get('session')->getFlashBag()->add('abonne.modifier.error', "Vous devez indiquer un mot de passe d'au moins 5 caractères");
                } else {
                    $formOk = TRUE;
                    
                    if (strlen($abonne->getTel1()) != $longueurNumeroTel) {
                        $formOk = FALSE;
                        $this->get('session')->getFlashBag()->add('abonne.modifier.error', "Le numéro de téléphone doit contenir exactement " . $longueurNumeroTel . " caractères");
                    }

                    if ($formOk) {
                        /*
                         * On vérifie si le numéro de tel n'est pas encor utilisé
                         */
                        $abonneWithTel1 = $em->getRepository($this->userBundle . 'Abonne')->findOneByTel1($abonne->getTel1());
                        if ($abonneWithTel1 != NULL) {
                            if ($abonne->getId() != $abonneWithTel1->getId()) {
                                $formOk = FALSE;
                                $this->get('session')->getFlashBag()->add('abonne.modifier.error', "Un abonné existe déjà avec le numéro de téléphone " . $abonne->getTel1() . " ( " . $abonneWithTel1->getNom() . " " . $abonneWithTel1->getPrenoms() . "  )");
                            }
                        }
                    }

                    /*
                     * Les mots de passe doivent être identiques 
                     */
//                    if ($abonne->getPassword() != $abonne->getCPassword()) {
//                        $this->get('session')->getFlashBag()->add('abonne.modifier.error', 'Les mots de passe doivent être identiques');
//                    } else if ($formOk) {
                    if ($formOk) {
                        /*
                         * A ce stade le code abonné et le tel1 sont uniques
                         */
                        $criteria = array('nom' => $abonne->getNom(), 'prenoms' => $abonne->getPrenoms());
                        $oldAbonne = $em->getRepository($this->userBundle . 'Abonne')->findOneBy($criteria);
                        /*
                         * Aucun user ne possede le mm non et ls mm prénoms
                         */
                        if ($oldAbonne == NULL) {
                            $oldUserByEmail = $em->getRepository($this->userBundle . 'Abonne')->findOneByEmail($abonne->getEmail());
                            /*
                             * Aucun user ne possede l'adresse email renseigne
                             */
                            if ($oldUserByEmail == NULL) {
//                                $em->persist($abonne);
                                $em->flush();

                                $this->get('session')->getFlashBag()->add('abonne.modifier.success', 'Modifications effectuées avec succès');
                                return $this->redirect($this->generateUrl('app_admin_abonnes', array('idProfil' => $idProfil)));
                            } else {
                                if ($oldUserByEmail->getId() == $abonne->getId()) {
                                    /*
                                     * Il sagit du mm abonné
                                     */
                                    $em->flush();

                                    $this->get('session')->getFlashBag()->add('abonne.modifier.success', 'Modifications effectuées avec succès');
                                    return $this->redirect($this->generateUrl('app_admin_abonnes', array('idProfil' => $idProfil)));
                                } else {
                                    $this->get('session')->getFlashBag()->add('abonne.modifier.error', "L'adresse email (" . $abonne->getEmail() . ") existe déjà");
                                }
                            }
                        } else {
                            /*
                             * Utilisateur avec mm nom et prenoms existe deja et est supprime
                             */
                            if ($oldAbonne->getEtat() == TypeEtat::SUPPRIME) {
                                $oldUserByEmail = $em->getRepository($this->userBundle . 'Abonne')->findOneByEmail($abonne->getEmail());
                                /*
                                 * Aucun user ne possede l'adresse email renseigne
                                 */
                                if ($oldUserByEmail == NULL) {
//                                    $em->persist($abonne);
                                    $em->flush();

                                    $this->get('session')->getFlashBag()->add('abonne.modifier.success', 'Modifications effectuées avec succès');
                                    return $this->redirect($this->generateUrl('app_admin_abonnes', array('idProfil' => $idProfil)));
                                } else {
                                    /*
                                     * Un abonne avec la mm adresse email exist
                                     */
                                    if ($oldUserByEmail->getId() == $abonne->getId()) {
                                        /*
                                         * Il sagit du mm abonne
                                         */
//                                        $em->persist($abonne);
                                        $em->flush();

                                        $this->get('session')->getFlashBag()->add('abonne.modifier.success', 'Modifications effectuées avec succès');
                                        return $this->redirect($this->generateUrl('app_admin_abonnes', array('idProfil' => $idProfil)));
                                    } else {
                                        $this->get('session')->getFlashBag()->add('abonne.modifier.already.exist', "L'utilisateur " . $abonne->getNom() . " " . $abonne->getPrenoms() . " existe déjà");
                                    }
                                }
                            } else {
                                /*
                                 * Utilisateur avec mm nom et prenoms existe deja et est non supprime
                                 */
                                if ($oldAbonne->getId() == $abonne->getId()) {
                                    /*
                                     * Il sagit du mm abonne
                                     */
                                    $em->flush();

                                    $this->get('session')->getFlashBag()->add('abonne.modifier.success', 'Modifications effectuées avec succès');
                                    return $this->redirect($this->generateUrl('app_admin_abonnes', array('idProfil' => $idProfil)));
                                } else {
                                    $this->get('session')->getFlashBag()->add('abonne.modifier.already.exist', "L'utilisateur " . $abonne->getNom() . " " . $abonne->getPrenoms() . " existe déjà");
                                }
                            }
                        }
                    }
                }
            } else {
                $this->get('session')->getFlashBag()->add('abonne.modifier.error', 'Formulaire invalide');
            }
        }

        $this->data['formuView'] = $form->createView();
        $this->data['abonne'] = $abonne;
        $this->data['profil'] = $abonne->getProfil();
        $this->data['idProfil'] = $idProfil;
        $this->data['idAbonne'] = $idAbonne;
        $this->data['locale'] = $locale;

        return $this->render($this->userBundle . 'Abonne:modifierAbonne.html.twig', $this->data, $this->response);
    }

     /*
     * Déconnecte un abonne
     * @return Response
     */
    public function adminLogoutAbonneAction(Request $request) {
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_ABONNE, Module::MOD_GEST_ABONNE_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $rep['msg'] = "Vous n'avez pas le droit de déconnecter un utilisateur";
            return new Response(json_encode($rep));
        }

        $em = $this->getDoctrine()->getManager();
        if (($request->isXmlHttpRequest()) && ($request->getMethod() == 'POST')) {
            $idUser = (int) $request->request->get('idUser');
            $user = $em->getRepository($this->userBundle . 'Abonne')->find($idUser);
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
                $this->get('session')->getFlashBag()->add('abonne.modifier.success', 'Utilisateur ' . $user->getNom() . ' ' . $user->getPrenoms() . ' déconnecté avec succès');
            }
            return new Response(json_encode($rep));
        }
        return new Response(json_encode($rep));
    }
    
    /*
     * Activation, suppression, désactivation d'un abonné
     * @return Response
     */
    public function changerEtatAbonneAction(Request $request) {
        $rep = array('etat' => FALSE, 'msg' => 'Erreur survenue lors du traitement');
        /*
         * Nom de l'action en cours
         */
         $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Activation, suppression, désactivation d'un utilisateur";
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_ABONNE, Module::MOD_GEST_ABONNE_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {
            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $rep['msg'] = "Vous n'avez pas le droit d'activer, de supprimer ou de désactiver un utilisateur";
            return new Response(json_encode($rep));
        }

        $em = $this->getDoctrine()->getManager();
        if (($request->isXmlHttpRequest()) && ($request->getMethod() == 'POST')) {
            $tempEtat = (int) $request->request->get('etat');
            $tempIds = $request->request->get('sId');

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
                $user = $em->getRepository($this->userBundle . 'Abonne')->find((int) $idS);
                if ($user != NULL) {
                    $user->setEtat($etat);
                    $em->flush();
                    $oneOk = TRUE;
                }
            }
            if ($oneOk) {
                $this->get('session')->getFlashBag()->add('abonne.modifier.success', 'Opération éffectuée avec succès');
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
     * @param type $idProfil
     * @param type $idAbonne
     * @return type
     */
    public function infosAbonneAction(Request $request, $idProfil, $idAbonne) {
        /*
         * Nom de l'action en cours
         */
         $nomAction = __FUNCTION__;
        /*
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
         * On recupère l'abonne
         */
        $abonne = $em->getRepository($this->userBundle . 'Abonne')->find($idAbonne);
        if ($abonne == NULL) {
            return $this->redirect($this->generateUrl('app_admin_abonnes', array('idProfil' => $idProfil)));
        }
        
        $form = $this->createForm(new ModifierAbonneType(), $abonne);


        /*
         * Le mot de passe ne doit pas être vide
         */
//        $parametreManager = $this->get('parametre_manager');
//        $minLengthPassword = $parametreManager->getValeurParametre(TypeParametre::LONGUEUR_MIN_PASSWORD_INT);
//        $longueurCompte = $parametreManager->getValeurParametre(TypeParametre::LONGUEUR_COMPTE_INT);
//        $longueurNumeroTel = $parametreManager->getValeurParametre(TypeParametre::LONGUEUR_NUM_TEL_INT);


        $this->data['formuView'] = $form->createView();
        $this->data['abonne'] = $abonne;
        $this->data['profil'] = $abonne->getProfil();
        $this->data['idProfil'] = $idProfil;
//        $this->data['longueurCompte'] = $longueurCompte;
        $this->data['idAbonne'] = $idAbonne;
        $this->data['locale'] = $locale;
        $this->data['isAdmin'] = $sessionData['isUser'];

        return $this->render($this->userBundle . 'Abonne:afficherinfosAbonne.html.twig', $this->data, $this->response);
    }
    
    /*
     * Modification du mot de passe d'un abonne par l'administrateur
     * 
     * @param type $idAbonne
     * @param type $idProfil
     * @return type
     */
    public function modifierPasswordAbonneByAdminAction(Request $request, $idAbonne, $idProfil) {
        /*
         * Nom de l'action en cours
         */
         $nomAction = __FUNCTION__;
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Modification du mot de passe d'un utilisateur par l'administrateur";
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
            $this->get('session')->getFlashBag()->add('access', "Vous n'avez pas le droit de modifier le mot de passe d'un abonne");
            return $this->redirect($this->generateUrl('app_admin_user_access_refuse'));
        }


        $em = $this->getDoctrine()->getManager();
        /*
         * On recupère l'abonne
         */
        $abonne = $em->getRepository($this->userBundle . 'Abonne')->find($idAbonne);
        if ($abonne == NULL) {
            return $this->redirect($this->generateUrl('app_admin_abonnes', array('idProfil' => $idProfil)));
        }

        $parametreManager = $this->get('parametre_manager');
        $minLengthPassword = $parametreManager->getValeurParametre(TypeParametre::LONGUEUR_MIN_PASSWORD_INT);
        if ($minLengthPassword == NULL) {
            $minLengthPassword = 5;
        }

        $pass1 = '';
        $pass2 = '';
        
        if ($request->isMethod('POST')) {
            $pass1 = $request->request->get('pass1');
            $pass2 = $request->request->get('pass2');
            if (($pass1 == NULL) || ($pass2 == NULL) || (empty($pass1)) || (empty($pass2))) {
                $this->get('session')->getFlashBag()->add('abonne.modifier.error', 'Vous devez remplire tous les champs');
            } else {
                if (strlen($pass1) < $minLengthPassword) {
                    $this->get('session')->getFlashBag()->add('abonne.modifier.error', 'Les mots de passe doivent contenir au moins ' . $minLengthPassword . ' caractères');
                } else {
                    if ($pass1 != $pass2) {
                        $this->get('session')->getFlashBag()->add('abonne.modifier.error', 'Les mots de passe doivent être identiques');
                    } else {
                        $abonne->setPassword(md5($pass1));
                        $abonne->setCPassword(md5($pass1));
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('abonne.modifier.password.success', 'Mot de passe modifié avec succès');
                        return $this->redirect($this->generateUrl('app_admin_abonne_infos', array('idProfil' => $idProfil, 'idAbonne' => $idAbonne)));
                    }
                }
            }
        }

        $this->data['pattern'] = ".{" . $minLengthPassword . ",}";
        $this->data['pass1'] = $pass1;
        $this->data['pass2'] = $pass2;
        $this->data['abonne'] = $abonne;
        $this->data['profil'] = $abonne->getProfil();
        $this->data['idProfil'] = $idProfil;
        $this->data['minLengthPassword'] = $minLengthPassword;
        $this->data['idAbonne'] = $idAbonne;
        $this->data['locale'] = $locale;

        return $this->render($this->userBundle . 'Abonne:modifierPasswordAbonneByAdmin.html.twig', $this->data, $this->response);
    }

    /*
     * Génère un nouvel code de base par ajax
     * @return Response
     */
    public function getNewCodeBaseAbonneAction(Request $request) {
        $rep = array('etat' => FALSE, 'msg' => 'Erreur survenue lors du traitement', 'code' => '');
        /*
         * Nom de l'action en cours
         */
        $nomAction = 'getNewCodeBaseAbonneAction';
        /*
         * Description de l'action de l'action en cours
         */
        $descAction = "Génération d'un nouvel code de base pour un utilisateur";
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
        if (!$loginManager->getOrSetActions(Module::MOD_GEST_ABONNE, Module::MOD_GEST_ABONNE_DESC, $this->getNomClassRun(__CLASS__), $this->description, $nomAction, $descAction, $sessionData['idProfil'])) {

            $this->logMessage .= ' [ TENTATIVE DE VIOLATION DE DROITS ] ';
            $rep['msg'] = "Vous n'avez pas le droit de génèrer un nouvel code de base pour un utilisateur";
            return new Response(json_encode($rep));
        }

        if (($request->isXmlHttpRequest()) && ($request->getMethod() == 'POST')) {
            $codeBaseManager = $this->get('codebase_manager');
            $rep['code'] = $codeBaseManager->getNewCodeBase();
            $rep['etat'] = TRUE;
        }
        return new Response(json_encode($rep));
    }

    

}
