<?php

namespace admin\SiteBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SiteController extends Controller
{
    
      
    use \admin\UserBundle\ControllerModel\paramUtilTrait; //bibliothèque contenant la fonction pour recuperer les informations de la personne connectée
    
    private $data;
    public function indexAction()
    {
        //Liste des bannières
        
        $em = $this->getDoctrine()->getManager();

        $banniere = $em->getRepository('adminCmsBundle:Rubrique')->getAllOrOneRubriqueArticleActif(2);
        
        //Recupération des activités
        
        $activites = $em->getRepository('adminCmsBundle:Rubrique')->getAllOrOneRubriqueSousActif(3);
        
        $this->data['activites'] = $activites;
        $this->data['banniere'] = $banniere;
        return $this->render('adminSiteBundle:Site:index.html.twig', $this->data);
    }
    

    //private $datas;
    public function detailsRubriqueAction(Request $request, $id,$idEtablissement)
    {
	    $codeEtablissement = $idEtablissement;
        $em = $this->getDoctrine()->getManager();
        $idEtablissement = $this->getIdEtablissement($em,$idEtablissement);
        
        //var_dump('tester');exit;
 //recuperation de l'objet classe par rapport  
        $unEtablissement = $em->getRepository($this->scolariteBundle . 'Etablissement')->find($idEtablissement);
        if($unEtablissement != NULL){
            $template = $unEtablissement->getTemplate();
        }else{
            $template = $em->getRepository($this->cmsBundle . 'Template')->find(3);
        }
        $getInfoPage = $em->getRepository($this->cmsBundle . 'Page')->findOneBy(array('template'=>$template, 'siPageAccueil'=>  \admin\UserBundle\Types\TypeEtat::INACTIF));
            //recuperation et traitement des informations sur les zones
            
                $rubrique = $em->getRepository('adminCmsBundle:Rubrique')->find($id);
           
         //traitement des menus
         $listeMenu = $this->creerTabMenu($em,$idEtablissement);
        //$rubriques = $em->getRepository('adminCmsBundle:Rubrique')->findAll();        
        // var_dump($tabTraiteZone[2]['information']);exit;
        if($template->getNomDossierTemplate()==''){
            $nomTemplate = "Default";
        }else{
            $nomTemplate = $template->getNomDossierTemplate();
        }
//        if(count($getInfoPage)!=0){
//            if($getInfoPage->getTwigPage()==''){
//                $nomTemplate = "detailRubrique.html.twig";
//            }else{
//                var_dump(3);
//                $nomTwig = $getInfoPage->getTwigPage();
//            }
//        }else{
            $nomTwig = "detailsRubrique.html.twig";
//        }
        //Recupération des activités
        
        $etablissements = $em->getRepository($this->scolariteBundle . 'Etablissement')->getAllActifEtablissementAccueil();
        //$rubriques = $em->getRepository('adminCmsBundle:Rubrique')->findAll();
        $this->data['rubrique'] = $rubrique;
        $this->data['listeEtablissement'] = $etablissements;       
        $this->data['unEtablissement'] = $unEtablissement;       
        $this->data['listeMenu'] = $listeMenu;
        $this->data['idEtablissement'] = $codeEtablissement;
        //$this->datas['rubriques'] = $rubriques;
       // var_dump('adminSiteBundle'.':'.$nomTemplate.':'.$nomTwig);exit;
        return $this->render('adminSiteBundle'.':'.$nomTemplate.':'.$nomTwig, $this->data);
    }
    
    
    public function detailsArticleAction(Request $request, $id, $idEtablissement)
    {
	    $codeEtablissement = $idEtablissement;
        $em = $this->getDoctrine()->getManager();
        $idEtablissement = $this->getIdEtablissement($em,$idEtablissement);
        //recuperation de l'objet classe par rapport  
        $unEtablissement = $em->getRepository($this->scolariteBundle . 'Etablissement')->find($idEtablissement);
        if($unEtablissement != NULL){
            $template = $unEtablissement->getTemplate();
        }else{
            $template = $em->getRepository($this->cmsBundle . 'Template')->find(3);
        }
        $getInfoPage = $em->getRepository($this->cmsBundle . 'Page')->findOneBy(array('template'=>$template, 'siPageAccueil'=>  \admin\UserBundle\Types\TypeEtat::INACTIF));
            //recuperation et traitement des informations sur les zones
           // if(count($getInfoPage)!=0){
                $article = $em->getRepository('adminCmsBundle:Article')->find($id);
           // }
         //traitement des menus
         $listeMenu = $this->creerTabMenu($em,$idEtablissement);
        //$rubriques = $em->getRepository('adminCmsBundle:Rubrique')->findAll();        
         //var_dump($template->getNomDossierTemplate());exit;
        if($template->getNomDossierTemplate()==''){
            $nomTemplate = "Default";
        }else{
            $nomTemplate = $template->getNomDossierTemplate();
        }
//        if(count($getInfoPage)!=0){
//            if($getInfoPage->getTwigPage()==''){
//                $nomTemplate = "detailArticle.html.twig";
//            }else{
//                $nomTwig = $getInfoPage->getTwigPage();
//            }
//        }else{
            $nomTwig = "detailsArticle.html.twig";
//        }
        $etablissements = $em->getRepository($this->scolariteBundle . 'Etablissement')->getAllActifEtablissementAccueil();
        $this->data['listeEtablissement'] = $etablissements;
        $this->data['unEtablissement'] = $unEtablissement; 
        $this->data['article'] = $article;
        $this->data['listeMenu'] = $listeMenu;
        $this->data['idEtablissement'] = $codeEtablissement;
        return $this->render('adminSiteBundle'.':'.$nomTemplate.':'.$nomTwig, $this->data);
        
       
    }
    
    
    public function traitePageTemplateAction(Request $request, $idEtablissement, $idRubArt){
        $em = $this->getDoctrine()->getManager(); //recuperation de l"entité manager
		
		$codeEtablissement = $idEtablissement;
        $tabTraiteZone = array();
        $tabZonePage = array();
        $idEtablissement = $this->getIdEtablissement($em,$idEtablissement);
        //recuperation de l'objet classe par rapport  
        $unEtablissement = $em->getRepository($this->scolariteBundle . 'Etablissement')->find($idEtablissement);
        if($unEtablissement != NULL){            
            $template = $unEtablissement->getTemplate();
            //var_dump($template->getNomDossierTemplate());exit;
        }else{
            $idEtablissement = \admin\UserBundle\Types\TypeEtat::ACTIF;
            $template = $em->getRepository($this->cmsBundle . 'Template')->find(3);
        }
        $listeMenu = $this->creerTabMenu($em,$idEtablissement);        
        //recuperation de l'objet page d'accueil et recuperation des zones celle-ci
        if( $idRubArt == 0){ // 
            //Recuperation des informations concernant la page d'acceuil
            $getInfoPage = $em->getRepository($this->cmsBundle . 'Page')->findOneBy(array('template'=>$template, 'siPageAccueil'=>  \admin\UserBundle\Types\TypeEtat::ACTIF));
            //recuperation et traitement des informations sur les zones
            if($getInfoPage!=null){
                //$tabZonePage=$getInfoPage->getZones();                
                $listeZone = $em->getRepository($this->cmsBundle . 'Zone')->getAllZonePage($getInfoPage->getId(),$idEtablissement);                        
                $incZone = 0;
                //foreach ($getInfoPage->getZones() as $uneZone) {
                foreach ($listeZone as $uneZone) {                    
                    if($uneZone["typeElement"]== 1 ){ //Affichage d'article seul'
                        $tabTraiteZone[$incZone]['information'] =$em->getRepository($this->cmsBundle . 'Article')->getAllOrOneArticle($uneZone["pointeVers"]);
                        $tabTraiteZone[$incZone]['typeInfo'] =1;   
                          
                    } elseif($uneZone["typeElement"]== 2 ){// Affichage du détail d'une rubrique seul
                        $uneRub = $em->getRepository($this->cmsBundle . 'Rubrique')->getAllOrOneRubrique($uneZone["pointeVers"]);   
                        $tabTraiteZone[$incZone]['information'] =  $uneRub;                  
                        $tabTraiteZone[$incZone]['typeInfo'] =2;
                        if($uneRub[0]->getTypeRubrique() == \admin\UserBundle\Types\TypeEtat::APRODUIRE){
                            $tabTraiteZone[$incZone]['article'] =  $em->getRepository($this->cmsBundle . 'Article')->getAllOrOneArticleSousActif($uneRub[0]->getId());
                        }else{
                            $tabTraiteZone[$incZone]['article'] =  $em->getRepository($this->cmsBundle . 'Article')->getAllArticleActifEtablissement($idEtablissement,$uneRub[0]->getId());
                        } 
                    } elseif($uneZone["typeElement"]== 3 ){// Affichage des articles d'une rubrique
                        $tabTraiteZone[$incZone]['information'] =$em->getRepository($this->cmsBundle . 'Rubrique')->getAllOrOneRubriqueArticleActif($uneZone["pointeVers"]);                       
                        $tabTraiteZone[$incZone]['typeInfo'] =3; 
                          
                    } elseif($uneZone["typeElement"]== 4 ){// Affichage des sous rubriques d'une rubrique
                    } 
                   $incZone++;
                }
            }
        }
       
        if($template->getNomDossierTemplate()==''){
            $nomTemplate = "Default";
        }else{
            $nomTemplate = $template->getNomDossierTemplate();
        }
        if($getInfoPage!=null){
            if($getInfoPage->getTwigPage()==''){
                $nomTemplate = "index.html.twig";
            }else{
                $nomTwig = $getInfoPage->getTwigPage();
            }
        }else{
            $nomTwig = "index.html.twig";
        }
        $etablissements = $em->getRepository($this->scolariteBundle . 'Etablissement')->getAllActifEtablissementAccueil();
       // var_dump($nomTemplate,$nomTwig);exit;
        $this->data['tabTraiteZone'] = $tabTraiteZone;
        $this->data['listeEtablissement'] = $etablissements;
        $this->data['unEtablissement'] = $unEtablissement; 
        $this->data['listeZone'] = $tabZonePage;
        $this->data['idEtablissement'] = $codeEtablissement;
        $this->data['listeMenu'] = $listeMenu;
        return $this->render('adminSiteBundle'.':'.$nomTemplate.':'.$nomTwig, $this->data);
    } 
    
    
    function classerIncrZoneAfficher(){
        
    }
//    public function detailsArticleRubriqueAction(Request $request)
//    {
//        $em = $this->getDoctrine()->getManager();
//
//        //Recupération des activités
//        
//        $rubrique = $em->getRepository('adminCmsBundle:Rubrique')->findAll();
//        $this->data['rubriques'] = $rubrique;
//        return $this->render('adminSiteBundle:Site:detailsRubrique.html.twig', $this->data);
//    }
    public function creerTabMenu($em,$idEtabl){
        $listeMenu = $em->getRepository('adminCmsBundle:Menu')->getAllMenuEtablissement($idEtabl);
        $i=0;
        $tabMenu = array();
        foreach($listeMenu as $unMenu){
            $tab = explode('|', $unMenu->getContenuMenu());
            $tabMenu[$i]['lien'] = $tab[0];
            $tabMenu[$i]['id'] = $tab[1];
            $tabMenu[$i]['titre'] = $unMenu->getTitre();
            $i++;
        }
        return $tabMenu; 
    }    
    public function getIdEtablissement($em,$idEtabl){
        $infoEtab = $em->getRepository($this->scolariteBundle . 'Etablissement')->findOneBy(array('codeEtablissement'=>$idEtabl));
        if($idEtabl==1){
            return $idEtabl; 
        }
        if($infoEtab == null){
            return 1;
        }else{
            return $infoEtab->getId();
        }
        
        
    }    
    
}
