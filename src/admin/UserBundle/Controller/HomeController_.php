<?php

namespace admin\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Response;
use \admin\UserBundle\Types\TypeCodeProfil;


class HomeController extends Controller {

   
    public function __construct() {
        $this->response = new Response();
        $this->response->headers->addCacheControlDirective('no-cache', true);
        $this->response->headers->addCacheControlDirective('max-age', 0);
        $this->response->headers->addCacheControlDirective('must-revalidate', true);
        $this->response->headers->addCacheControlDirective('no-store', true);
        $this->logMessage = " [ HomeController ] ";
        $this->description = "Controlleur qui gère la la page d'accueil de l'espace d'administration";;
    }

    /*
     * Page d'accueil. 
     * @return type
     */
    public function homeAction() {
        $this->logMessage .= ' [ homeAction ] ';
        $loginManager = $this->get('login_manager');
        $locale = $loginManager->getLocale();
        $sessionData = $this->infosConnecte();
        $status = $loginManager->isConnecte('homeAction');

        if ($status['isConnecte']) {
            if ($status['isInnactif']) {
                $this->get('session')->getFlashBag()->add('ina', "Vous avez accusé un long moment d'inactivité");

                return ($status['isUser']) ? $this->redirect($this->generateUrl('app_admin_user_logout')) :
                        $this->redirect($this->generateUrl('app_admin_user_logout'));
            }
            if ($status['isUser']) {              
                $em = $this->getDoctrine()->getManager();
                /**
                 * REcuperer les informations pr la page d'accueil
                 */

                $abonne = $em->getRepository($this->userBundle . 'Abonne')->find($sessionData['id']);
                
                if ($sessionData['codeProfil'] == \admin\UserBundle\Types\TypeCodeProfil::MAINTENANCE) {
                    
                    $this->data['locale'] = $locale;
                    $this->data['abonne'] = $abonne;

                    $abonne_id = $abonne->getId();
                    $isAbonne = 0;
                    
                    $t1 = $loginManager->getDataTableauDeBord($abonne_id, $isAbonne, 1);
                    
                    if ( is_array($t1) && isset($t1[0]) && isset($t1[0]['tableau_de_bord']) ){                        
                        $t1 = explode('|',$t1[0]['tableau_de_bord']);
                        $this->data['dernaction']= $t1;
                    }
                    
                    $t2 = $loginManager->getDataTableauDeBord($abonne_id, $isAbonne, 2);

                    if ( is_array($t2) && isset($t2[0]) && isset($t2[0]['tableau_de_bord']) ){                        
                        $t2 = explode('|',$t2[0]['tableau_de_bord']);
                        $this->data['minmaxdur']= $t2;
                    }
                    
                    $t3 = $loginManager->getDataTableauDeBord($abonne_id, $isAbonne, 3);

                    if ( is_array($t3) && count($t3)>0 ){                        
                        $this->data['integrfic']= $t3;
                    }
                    
                    $t4 = $loginManager->getDataTableauDeBord($abonne_id, $isAbonne, 4);
                    
                    if ( is_array($t4) && count($t4)>1 ){                            
                        $t4_1 = $t4[0]['nbre'];
						$t4_2 = $t4[1]['nbre'];
						$taux = ($t4_2>0)? 100*($t4_1-$t4_2)/$t4_2 : 100;
                        $this->data['nbreutil']= array($t4_1,$t4_2,$taux);
                    }elseif (is_array($t4) && count($t4)==1){
                        $t4_1 = $t4[0]['nbre'];
						$t4_2 = 0;
						//var_dump($t4);exit;
						$taux = ($t4_2>0)? 100*($t4_1-$t4_2)/$t4_2 : 100;
                        $this->data['nbreutil']= array($t4_1,$t4_2,$taux);
					}
                    
                    $t5 = $loginManager->getDataTableauDeBord($abonne_id, $isAbonne, 5);
					//var_dump($t5);exit;
                    
                    if ( is_array($t5) && count($t5)>1 ){                            
                        $t5_1 = $t5[0]['nbre'];
						$t5_2 = $t5[1]['nbre'];
						$taux = ($t5_2>0)? 100*($t5_1-$t5_2)/$t5_2 : 100;
                        $this->data['nbreutil']= array($t5_1,$t5_2,$taux);
                    }elseif (is_array($t5) && count($t5)==1){
                        $t5_1 = $t5[0]['nbre'];
						$t5_2 = 0;
						//var_dump($t4);exit;
						$taux = ($t5_2>0)? 100*($t5_1-$t5_2)/$t5_2 : 100;
                        $this->data['nbrearch']= array($t5_1,$t5_2,$taux);
					}
                    
                    $t6 = $loginManager->getDataTableauDeBord($abonne_id, $isAbonne, 6);

                    if ( is_array($t6) && isset($t6[0]) && isset($t6[0]['tableau_de_bord']) ){                        
                        
                        $this->data['comparaison']= $t6;
                    }
                    
                    $t7 = $loginManager->getDataTableauDeBord($abonne_id, $isAbonne, 7);
					
                    if ( is_array($t7) && count($t7)>1 ){                            
                        $t7_1 = $t7[0]['nbre'];
						$t7_2 = $t7[1]['nbre'];
						$taux = ($t7_2>0)? 100*($t7_1-$t7_2)/$t7_2 : 100;
                        $this->data['nbretotal']= array($t7_1,$t7_2,$taux);
                    }elseif (is_array($t7) && count($t7) == 1){
                        $t7_1 = $t7[0]['nbre'];
						$t7_2 = 0;
						$taux = ($t7_2>0)? 100*($t7_1-$t7_2)/$t7_2 : 100;
                        $this->data['nbretotal']= array($t7_1,$t7_2,$taux);
					}
                    
                    $t8 = $loginManager->getDataTableauDeBord($abonne_id, $isAbonne, 8);
                    
                    if ( is_array($t8) && isset($t8[0]) && isset($t8[0]['tableau_de_bord']) ){                        
                        $t8= explode('|',$t8[0]['tableau_de_bord']);
                        $this->data['dercon']= $t8;
                    }
                    
					$liste = $em->getRepository($this->paramBundle . 'Fichier')->findBy(array('iduser'=>$abonne_id),array('addDate'=>'DESC'),5,0);
					$listeF = array();
					foreach( $liste as $key=> $value){
						$listeF[$key]['nomF']	= $value->getNomFile();	
						$listeF[$key]['dateadd']	=  $value->getAddDate();	
						$ut= $em->getRepository($this->userBundle . 'Abonne')->find($value->getIduser());
						( $ut != null)? $listeF[$key]['userF'] = $ut->getUsername() : $listeF[$key]['userF']	= '';								
					}
					
					unset($ut); unset($liste);
					
					$this->data['files']= $listeF;
                    return $this->render($this->userBundle . 'Home:homeMaintenance.html.twig', $this->data, $this->response);
                }
                
                var_dump('Afficher le tableau de bord du profil '.$sessionData['codeProfil'] ); exit;
                
            }
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }
        return $this->redirect($this->generateUrl('app_admin_user_logout'));
    }
    
    


}
