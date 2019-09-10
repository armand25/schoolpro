<?php

namespace admin\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Response;
use \admin\UserBundle\Types\TypeCodeProfil;

use \Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller {

   
use \admin\UserBundle\ControllerModel\paramUtilTrait;

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
        $request = new Request(); 
        $loginManager = $this->get('login_manager');
        $locale = $loginManager->getLocale();
        $sessionData = $this->infosConnecte($request);
        
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

                $abonne = $em->getRepository($this->userBundle . 'Utilisateur')->find($sessionData['id']);
                
                //if ($sessionData['codeProfil'] == \admin\UserBundle\Types\TypeCodeProfil::MAINTENANCE) {
                    
                    $this->data['locale'] = $locale;
                    $this->data['abonne'] = $abonne;
                         //var_dump($solde);exit;
                        //$this->data['files']= $listeF;
                        //$this->data['solde']= $solde;
                    return $this->render($this->userBundle . 'Home:homeAccueil.html.twig', $this->data, $this->response);
               // }
                
                //var_dump('Afficher le tableau de bord du profil '.$sessionData['codeProfil'] ); exit;
                
            }
            return $this->redirect($this->generateUrl('app_admin_user_logout'));
        }
        return $this->redirect($this->generateUrl('app_admin_user_logout'));
    }
    
    


}
