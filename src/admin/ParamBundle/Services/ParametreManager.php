<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * Description of ParametreManager
 *
 * @author Edmond
 */

namespace admin\ParamBundle\Services;

use \Doctrine\ORM\EntityManager;
use \admin\ParamBundle\Types\TypeDonnees;
use \admin\ParamBundle\Entity\Param;
use admin\ParamBundle\Entity\LoadProcess;
use \PDO;

class ParametreManager {

    private $em;

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

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }
    

    /*
     * retoutne la valeur d'un parametre
     * @param \ParamBundle\Entity\Param $paramtre
     * @param int $objetDate Si $objet = 1, on retournera un objet, si non un string
     * @param type $int
     */
    public function getValeurParametre($paramtreName, $objetDate = 1) {
        $valeur = NULL;
        $objetParam = $this->givenParam($paramtreName);
        if ($objetParam != NULL) {
            $typeDonnee = $objetParam->getTypeDonnee();
            $valeur = $objetParam->getValeur();
            if ($typeDonnee == TypeDonnees::INT) {
                $valeur = intval($valeur);
            } else if ($typeDonnee == TypeDonnees::DATE) {
                if($objetDate == 0){
                    return $valeur;
                }
                $dateTemp = new \DateTime();
                $dateFormat = 'Y-m-d';
                $valeur = $dateTemp->createFromFormat($dateFormat, $valeur);
            } else if ($typeDonnee == TypeDonnees::DATETIME) {
                if($objetDate == 0){
                    return $valeur;
                }
                $dateTemp = new \DateTime();
                $dateFormat = 'Y-m-d H:i:s';
                if(strlen($valeur) == 8){
                    $valeur = date('Y-m-d').' '.$valeur;
                }
                $valeur = $dateTemp->createFromFormat($dateFormat, $valeur);
                
                
            } else if ($typeDonnee == TypeDonnees::BOOLEAN) {
                $valeur = ($valeur == '1') ? TRUE : FALSE;
            }
        }
        return $valeur;
    }

    /*
     * Met à jour la valeur d'un parametre
     * 
     * @param type $paramtreName
     * @param type $valeur
     * @param type $description
     * @param type $typeDonnee
     * @return boolean
     */
    public function setParametre($paramtreName, $valeur, $typeDonnee = 1, $libelle = '', $description = '',$typeParam=0) {
        $rep = FALSE;
        if (!empty($paramtreName)) {
            if ($typeDonnee == TypeDonnees::BOOLEAN) {
                $valeur = ($valeur == 1) ? '1' : '0';
            }
            $o = $this->givenParam($paramtreName);
            if ($o != NULL) {
                $o->setValeur($valeur);
                $o->setLibelle($libelle);
                $o->setDescription($description);
                $this->em->flush();
            } else {
                $p = new Param($paramtreName, $typeDonnee, $valeur, $description);
                $p->setNom($paramtreName);
                $p->setValeur($valeur);
                $p->setTypeDonnee($typeDonnee);
                $p->setLibelle($libelle);
                $p->setTypeParam($typeParam);
                $p->setDescription($description);

                $this->em->persist($p);
                $this->em->flush();
            }
            $rep = TRUE;
        }

        return $rep;
    }

    /*
     * retoutne un objet parametre à partir de son nom ($paramtreName)
     * 
     * @param type $paramtreName
     * @param type $int
     * @return type
     */
    public function getParametre($paramtreName) {
        return $this->givenParam($paramtreName);
    }

    /*
     * Retourn un objet Parametre
     * @param type $paramtreName
     * @return null
     */
    public function givenParam($paramtreName) {
        if (!empty($paramtreName)) {
            $t = $this->em->getRepository($this->paramBundle . 'Param')->findOneByNom($paramtreName);
            return $t;
        }
        return NULL;
    }

    public function getAllParametres() {
        return   $this->em->getRepository($this->paramBundle . 'Param')->findBy(array('affiche'=>1));
    }

    /*
     * Retourne un nom par defaut lors de la création d'un nouveau param
     * @return type
     */
    public function getDefaultNewName() {
        $name = 0;

        $sql = 'SELECT MAX(nom) as max_nom FROM param';
        $stmt = $this->em->getConnection()->prepare($sql);
        
        try {
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (is_array($result) && (count($result) > 0)) {
                $temp = $result[0];
                $name = (int) $temp['max_nom'];
            }
        } catch (\Exception $e) {
            var_dump($e);
            exit;
        }

        return $name + 1;
    }

    
    /*
     * Enrégitrement des informations concernant le loadProcess
     * 
     * @param type $paramtreName
     * @param type $valeur
     * @param type $description
     * @param type $typeDonnee
     * @return boolean
     */
    public function addloadProcess($idUser) {        
        $rep = FALSE;        
        if ($idUser !=0) {
                $processLoad = new LoadProcess();
                $processLoad->setIdUser($idUser);
                $this->em->persist($processLoad);
                $this->em->flush();
        }
            $rep = TRUE;       
        return $rep;
    }    
    
    /*
     * Retourne un nom par defaut lors de la création d'un nouveau param
     * @return type
     */
    public function getCurrentLoadProcess($idUser=0) {
        $name = 0;

        $sql = 'SELECT MAX(idprocessload) as id FROM load_process where iduser =  :iduser';
        $stmt = $this->em->getConnection()->prepare($sql);
        
        try {
            $stmt->bindValue(':iduser', $idUser, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            
        } catch (\Exception $e) {
            var_dump($e);
            exit;
        }
        return $result ;
    }   
    
    
      
    
}
