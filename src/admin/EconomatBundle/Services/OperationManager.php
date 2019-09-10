<?php

/*
 * Description of LoginManager
 * Service de gestion des connexions et des droits des utilisateurs.
 *
 * @author Jerome
 */

namespace admin\EconomatBundle\Services;
use \Doctrine\ORM\EntityManager;
use \PDO;
/**
 * ConfigManager
 * 
 * Service de gestion des configurations de l'application
 * 
 */
class OperationManager extends \Twig_Extension {

    use \admin\UserBundle\ControllerModel\paramUtilTrait;
    /*
     *
     * @var \Doctrine\ORM\EntityManager  $em : gestionnaire d'entité
     * 
     */
    private $em;
   
    /*
     * le constructeur qui initialise les attributs
     * 
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function __construct(EntityManager $em) {
        $this->em = $em;       
    }

    
 /*
     * Retourne un nom par defaut lors de la création d'un nouveau param
     * @return type
     */
    public function getInfoSoldeEleve($ideleve=0,$anneescolaire=0 ,$typeoperation=0, $compte='0') {
            $sql = "
                    SELECT  e.id, e.nom_eleve, e.prenoms_eleve,   sum(case 
                            when o.sensoperation = 'D' then montant 
                            when o.sensoperation = 'C' then (-1)*montant 
                            end) as solde 
                            FROM eleve e 
                             JOIN operation o on e.id = o.eleve_id                             
                              ";
            
            $sql .= '  where  1=1 ';
            if($anneescolaire!=0){
               $sql .= ' and  o.anneescolaire_id = :anneescolaire  '; 
            }
            if($typeoperation!=0){
               $sql .= ' and  o.idtypeoperation = :typeoperation '; 
            }
            
            if($compte!='0'){
               
               $sql .= ' and  o.compte = :compte '; 
            }
            
            if($ideleve !=0){
               $sql .= ' and  o.eleve_id = :eleve '; 
            }
            $stmt = $this->em->getConnection()->prepare($sql);
          //  var_dump($sql,$anneescolaire,$typeoperation,$ideleve,$compte)   ;exit;
            try {
                if($anneescolaire!=0){
               $stmt->bindValue(':anneescolaire', $anneescolaire, PDO::PARAM_INT);
            }
            if($typeoperation!=0){
               $stmt->bindValue(':typeoperation', $typeoperation, PDO::PARAM_INT); 
            }
            
            if($ideleve !=0){
               $stmt->bindValue(':eleve', $ideleve, PDO::PARAM_INT);  
            }
            if($compte !='0'){
               $stmt->bindValue(':compte', $compte, PDO::PARAM_STR);  
            }
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (\Exception $e) {
                var_dump($e);
                exit;
            }
            return $result ;
        
    }  
 /*
     * Retourne un nom par defaut lors de la création d'un nouveau param
     * @return type
     */
    public function getInfoSoldeClasse($idclasse=0,$anneescolaire=0 ,$typeoperation=0, $compte='0',$idetablissement=0) {
            $sql = "
                    SELECT  c.id, c.libelle_classe,   sum(case 
                            when o.sensoperation = 'D' then montant 
                            when o.sensoperation = 'C' then (-1)*montant 
                            end) as solde 
                            FROM classe c 
                             JOIN operation o on c.id = o.classe_id                             
                              ";
            
            $sql .= '  where  1=1 ';
            if($anneescolaire!=0){
               $sql .= ' and  o.anneescolaire_id = :anneescolaire  '; 
            }
            if($typeoperation!=0){
               $sql .= ' and  o.idtypeoperation = :typeoperation '; 
            }
            if($idetablissement!=0){
               $sql .= ' and  o.etablissement_id = :idetabl '; 
            }
            
            if($compte!='0'){
               
               $sql .= ' and  o.compte = :compte '; 
            }
            
            if($idclasse !=0){
               $sql .= ' and  o.classe_id = :classe '; 
            }
            $stmt = $this->em->getConnection()->prepare($sql);
          //  var_dump($sql,$anneescolaire,$typeoperation,$ideleve,$compte)   ;exit;
            try {
                if($anneescolaire!=0){
               $stmt->bindValue(':anneescolaire', $anneescolaire, PDO::PARAM_INT);
            }
            if($typeoperation!=0){
               $stmt->bindValue(':typeoperation', $typeoperation, PDO::PARAM_INT); 
            }
            if($idetablissement!=0){
               $stmt->bindValue(':idetabl', $idetablissement, PDO::PARAM_INT); 
            }
            
            if($idclasse !=0){
               $stmt->bindValue(':classe', $idclasse, PDO::PARAM_INT);  
            }
            if($compte !='0'){
               $stmt->bindValue(':compte', $compte, PDO::PARAM_STR);  
            }
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (\Exception $e) {
                var_dump($e);
                exit;
            }
            return $result ;        
    }  
 /*
     * Retourne un nom par defaut lors de la création d'un nouveau param
     * @return type
     */
    public function getListeOperationScolariteEleve($ideleve=0,$anneescolaire=0 ,$typeoperation=0, $compte='0') {
        
            $sql = "
                    SELECT  o.id, o.num_mvt, o.nom_deposant,  case 
                            when o.sensoperation = 'D' then montant 
                            when o.sensoperation = 'C' then (-1)*montant 
                            end as montant 
                            FROM eleve e 
                             JOIN operation o on e.id = o.eleve_id                             
                              ";
            
            $sql .= '  where  1=1 ';
            if($anneescolaire!=0){
               $sql .= ' and  o.anneescolaire_id = :anneescolaire  '; 
            }
            if($typeoperation!=0){
               $sql .= ' and  o.idtypeoperation = :typeoperation '; 
            }
            
            if($compte!='0'){
               
               $sql .= ' and  o.compte = :compte '; 
            }
            
            if($ideleve !=0){
               $sql .= ' and  o.eleve_id = :eleve '; 
            }
            $stmt = $this->em->getConnection()->prepare($sql);
          //  var_dump($sql,$anneescolaire,$typeoperation,$ideleve,$compte)   ;exit;
            try {
                if($anneescolaire!=0){
               $stmt->bindValue(':anneescolaire', $anneescolaire, PDO::PARAM_INT);
            }
            if($typeoperation!=0){
               $stmt->bindValue(':typeoperation', $typeoperation, PDO::PARAM_INT); 
            }
            
            if($ideleve !=0){
               $stmt->bindValue(':eleve', $ideleve, PDO::PARAM_INT);  
            }
            if($compte !='0'){
               $stmt->bindValue(':compte', $compte, PDO::PARAM_STR);  
            }
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (\Exception $e) {
                var_dump($e);
                exit;
            }
            return $result ;
        
    }  

    
/*
     * Retourne un nom par defaut lors de la création d'un nouveau param
     * @return type
     */
    public function geneLigneOperation($commande_id=0,$caisse_id=0,$montant=0, $nom_deposant=0, $ref_facture) {
        
        $date_operation= '2013-03-26';
        $date_valeur= '2013-03-26';
        $comptabilise=0;
        $piece_operation = 0;
        
      
        
        $sql = "
                INSERT  INTO  operation(commande_id,caisse_id, date_operation, 
                                        date_valeur, piece_operation, montant, 
                                        comptabilise, nom_deposant,etat_operation,ref_facture)
                              values(:commande_id,:caisse_id,:date_operation,
                                    :date_valeur,:piece_operation,:montant,
                                    :comptabilise,:nom_deposant,1, :ref_facture) ";
        $stmt = $this->em->getConnection()->prepare($sql);
        
        try {
            $stmt->bindParam(':caisse_id', $caisse_id, PDO::PARAM_STR);
            $stmt->bindParam(':commande_id', $commande_id, PDO::PARAM_STR);
            
            $stmt->bindParam(':date_operation', $date_operation, PDO::PARAM_STR);
            
            $stmt->bindParam(':piece_operation', $piece_operation, PDO::PARAM_INT);
            
            $stmt->bindParam(':date_valeur', $date_valeur, PDO::PARAM_STR);
            $stmt->bindParam(':montant', $montant, PDO::PARAM_STR);
            $stmt->bindParam(':comptabilise', $comptabilise, PDO::PARAM_STR);
            $stmt->bindParam(':nom_deposant', $nom_deposant, PDO::PARAM_STR);
            $stmt->bindParam(':ref_facture', $ref_facture, PDO::PARAM_STR);
             //var_dump($commande_id,$caisse_id,$date_operation,$piece_operation,$date_valeur,$montant,$comptabilise,$nom_deposant,$ref_facture); exit;             
            $stmt->execute();                       

        } catch (\Exception $e) {
            var_dump($e);
            return false ;
        }
        return true ;
        
    }    
    
    
  /*
     * Retourne un nom par defaut lors de la création d'un nouveau param
     * @return type
     */

    public function geneLigneOperationComptable($eleve_id = 0, $caisse_id = 0, $montant , $nom_deposant = 0,$tel_deposant='', $ref_facture,$num_cheque=0, $compte_cheque=0, $idTypeOp = 0,$piece_operation =0,$compte_client='',$type_paye='',$classe_id='',$compte_auxi='',$datechoix='',$anneescolaire_id, $etablissement_id) {
             
        // requete d'insertion de l'ecrirure comptable   dans table tmp 
        //var_dump($eleve_id);exit;
        $res = 0;
        $comptabilise = 0;                           
        $sqltypeOp = "";
        $sqltypeOp .= " select t.idtypeoperation , t.libtypeoperation , s.sens, s.compte, t.codeoperation, p.libelle
                                  from schemacomptable s inner join typeoperation t
                                       on t.idtypeoperation = s.idtypeoperation
                                    inner join plancomptable p
                                       on s.compte = p.compte
                                  where t.idtypeoperation = :idtypeop   
                                  order by s.sens desc ";
        $stmttypeop = $this->em->getConnection()->prepare($sqltypeOp);
        $stmttypeop->bindParam(':idtypeop', $idTypeOp, PDO::PARAM_INT);
        try {
            $stmttypeop->execute();
            $listetypeOp = $stmttypeop->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            //var_dump($e);
            exit;
        }
     

        if (is_array($listetypeOp) && count($listetypeOp) > 0) {

            $sql = " INSERT  INTO  operation
                                        ( eleve_id,caisse_id, date_operation, 
                                        date_valeur, piece_operation, montant, 
                                        comptabilise, nom_deposant,tel_deposant,
                                        etat_operation,ref_facture, compte, cheque,
                                        sensoperation,compte_client, idtypeoperation,num_mvt, lib_operation,
                                        type_traite_operation, classe_id, compte_auxiliaire, anneescolaire_id, etablissement_id)
                              values
                                        (:eleve_id,:caisse_id,:date_operation,
                                        :date_valeur,:piece_operation,:montant,
                                        :comptabilise,:nom_deposant,:tel_deposant,
                                        1, :ref_facture,:compte,:cheque,
                                        :sensoperation, :compte_client, :idtypeoperation, :num_mvt, :lib_operation
                                        ,:type_traite_operation, :classe_id, :compte_auxiliaire, :anneescolaire_id, :etablissement_id) ";
            // exit;
            
            $stmt = $this->em->getConnection()->prepare($sql);
            //if($idCaisse != 0)

           // $datechoix = implode('/', array_reverse( explode('/',$datechoix) ) ) ;
            $dateAutor = new \DateTime($datechoix);
             
            //$dateAutor->setDate((int) substr($dateo, 0, 4), (int) substr($dateo, 5, 2), (int) substr($dateo, 8, 2));
            $dateAutor = $dateAutor->format('Y-m-d H:i:s');
          
           // $dateAutor->setDate((int) substr($dateo, 0, 4), (int) substr($dateo, 5, 2), (int) substr($dateo, 8, 2));
           $an=substr($dateAutor, 0, 4);
           $m=substr($dateAutor, 5, 2);
            $datev = $dateAutor;
            $idUser=1;
            $idCaisse=1;
            $util = $this->em->getRepository("adminUserBundle:Utilisateur")->find($idUser);
            $cash = $this->em->getRepository("adminEconomatBundle:Caisse")->find($idCaisse);

            $j=0;
            foreach ($listetypeOp as $key => $value) {
                $i = 1;
               
                $datevaleur = $datev;
                $lib = '' ;//$this->em->getRepository("adminStockBundle:TypeOperation")->find($idTypeOp);
                //$annule = $lib->getId();
                if($lib == ''){
                    $lib = ''.$nom_deposant;
                }else{
                    $lib='-';
                }
                $cle = "";
//                        if (strtolower($value['codeoperation']) == 'ccc') $comptabilise = 2;                                               
                $datej = 111;//$this->GetDatJulienne($this->em);
                $numMvt = $this->getNumMvt($this->em, 1, $an, $m, $entite = 'LIVRER', $taille = 5);;
//                if ($idCaisse != 0)
//                    $numMvt = $this->GetNumeroMvt($this->em, $datej, $idCaisse);
//                else
//                    $numMvt = $this->GetNumeroMvtVir($this->em, $datej);
//                $numCheque = '';

//                if ($i == 1 && strlen($numMvt) > 5) {
//                    $numoperation = substr($numMvt, -5); //substr($numMvt,strlen($numMvt)-5,5);                               
//                    $numCheque = $numCheq;
//                    //: $numCheque = '' ;
//                }
                //$mttrecu = 0;
                //$mtt = 0;
//                ($montant[$value['compte'] . $value['sens']]) ? $mtt = $montant[$value['compte'] . $value['sens']] : $mtt = 0;
//                ($montantrecu[$value['compte'] . $value['sens']]) ? $mttrecu = $montantrecu[$value['compte'] . $value['sens']] : $mttrecu = 0;
//                ($comptetablo[$value['compte'] . $value['sens']]) ? $compte = $comptetablo[$value['compte'] . $value['sens']] : $compte = '';
//                ($compteauxi[$value['compte'] . $value['sens']]) ? $auxi = $compteauxi[$value['compte'] . $value['sens']] : $auxi = '';
//                $sens = $value['sens'];
               
                $leComptePlanC = $value['compte'];//$compte_cheque[$j];
                $leMontantPaye =$montant[$j];
               // var_dump($montant);exit;
                //var_dump('------------------------------------------------');
                
                                
                if ($i == 1) {
                    $stmt->bindParam(':caisse_id', $caisse_id, PDO::PARAM_STR); //var_dump($caisse_id);
                    $stmt->bindParam(':eleve_id', $eleve_id, PDO::PARAM_STR);//var_dump($commande_id);
                    $stmt->bindParam(':date_operation', $dateAutor, PDO::PARAM_STR);//var_dump($dateAutor);
                    $stmt->bindParam(':piece_operation', $piece_operation, PDO::PARAM_INT);//var_dump($piece_operation);
                    $stmt->bindParam(':sensoperation', $value['sens'], PDO::PARAM_STR);//var_dump($value['sens']);
                    $stmt->bindParam(':date_valeur', $datev, PDO::PARAM_STR);//var_dump($datev);
                    $stmt->bindParam(':montant', $leMontantPaye, PDO::PARAM_STR);//var_dump($leMontantPaye);
                    $stmt->bindParam(':comptabilise', $comptabilise, PDO::PARAM_STR);//var_dump($comptabilise);
                    $stmt->bindParam(':nom_deposant', $nom_deposant, PDO::PARAM_STR);//var_dump($nom_deposant);
                    $stmt->bindParam(':tel_deposant', $tel_deposant, PDO::PARAM_STR);//var_dump($tel_deposant);
                    $stmt->bindParam(':compte', $leComptePlanC, PDO::PARAM_STR);//var_dump($leComptePlanC);
                    $stmt->bindParam(':compte_client', $compte_client, PDO::PARAM_STR);//var_dump($compte_client);
                    $stmt->bindParam(':cheque', $num_cheque, PDO::PARAM_STR);//var_dump($num_cheque);
                    $stmt->bindParam(':idtypeoperation', $idTypeOp, PDO::PARAM_STR);//var_dump($idTypeOp);
                    $stmt->bindParam(':ref_facture', $ref_facture, PDO::PARAM_STR);//var_dump($ref_facture);
                    $stmt->bindParam(':num_mvt', $numMvt, PDO::PARAM_STR);//var_dump($numMvt);
                    $stmt->bindParam(':lib_operation', $lib, PDO::PARAM_STR);//var_dump($lib);
                    $stmt->bindParam(':type_traite_operation', $type_paye, PDO::PARAM_STR);//var_dump($lib);
                    $stmt->bindParam(':classe_id', $classe_id, PDO::PARAM_INT);//var_dump($lib);
                    $stmt->bindParam(':anneescolaire_id', $anneescolaire_id, PDO::PARAM_INT);//var_dump($lib);
                    $stmt->bindParam(':etablissement_id', $etablissement_id, PDO::PARAM_INT);//var_dump($lib);
                    $stmt->bindParam(':compte_auxiliaire', $compte_auxi, PDO::PARAM_INT);//var_dump($lib);
                    //
                 //  var_dump($sql);
                   //var_dump($caisse_id,$eleve_id,$dateAutor,$piece_operation,$value['sens'],$datev,$leMontantPaye,$comptabilise,$nom_deposant,$tel_deposant,$leComptePlanC,$leComptePlanC,$compte_client,$num_cheque,$idTypeOp,$ref_facture,$numMvt,$lib,$type_paye,$id_abonne,$compte_auxi);exit;
                }
               
                try {
                  $stmt->execute();
                } catch (\Exception $e) {
                    $res = 1;
                    //var_dump($e);
                    exit;
                }
                 
               // var_dump($numMvt);exit;
                $i++;
                $j++;
            }
        }
        //exit;
        // retourner le numero de piece pour l'impression de l'ecriture
        if ($res == 0)
            $resulat = 1;//$numpiece;
        else
            $resulat = null;
        return $resulat;
    }

    
public function soldeCaisseDate(\Doctrine\ORM\EntityManager $emc,$idCaisse,$date,$idprod=0,$idab=0,$compte='0'){                 
    
        // requete d'insertion de l'ecrirure comptable
        $sql =  " select c.id ,".
                "    sum(case ".
                "            when sensoperation = 'C' then montant ".
                "            when sensoperation = 'D' then (-1)*montant ".
                "        end) as solde ".
                " from operation o inner join caisse c ".
                "    on c.id =o.caisse_id  ".
                "    ".
                " where 1=1 ";       

        ( $idCaisse == 0 )? $sql.= '' : $sql.= ' and c.id =:idcaisse';
        ( $compte == '0' )? $sql.= '' : $sql.= ' and o.compte =:compte';
        if($idab !=0){
        $sql.= ' and o.id_abonne =:idab';
        }else{
            ( $idprod == 0 )? $sql.= '' : $sql.= ' and o.produit_id =:idprod';
        }
        
        ($date != null && $date != '')? $sql.=" AND  o.date_operation <= " . " '" . $date . "' " :  $sql.= '' ;
        
        $sql .=' group by c.id ';

        $liste = null;
        
        
        // on ne passe les ecritures que si l'entity manager et le numero de piece sont pas null
        if ( $this->em != null ) {
              // recuperation de la connection 
              $stmt = $this->em->getConnection()->prepare($sql);              
              ( $idCaisse == 0 )? $sql.= '' : $stmt->bindValue(':idcaisse',$idCaisse, PDO::PARAM_INT); 
              ( $compte == '0' )? $sql.= '' : $stmt->bindValue(':compte',$compte, PDO::PARAM_INT); 
              if($idab !=0){
                 $stmt->bindValue(':idab',$idab, PDO::PARAM_INT); 
                }else{
                   ( $idprod == 0 )? $sql.= '' : $stmt->bindValue(':idprod',$idprod, PDO::PARAM_INT); 
                }             
              try{
                $stmt->execute(); 
                $liste = $stmt->fetchAll(PDO::FETCH_ASSOC);
              }catch ( \Exception $e){
                 //var_dump(substr($e->getMessage(),600,800));exit;  
              }             
        }  
        
        return $liste ; 
    }
    
    
public function soldeCaisseParSercice(\Doctrine\ORM\EntityManager $emc,$idCaisse,$date,$idprod=0,$idab=0,$compte='0'){                 
         $date = implode('-', array_reverse( explode('-',$date) ) ) ;
        // requete d'insertion de l'ecrirure comptable
        $sql =  " select p.nom_produit ,".
                "    sum(case ".
                "            when sensoperation = 'C' then montant ".
                "            when sensoperation = 'D' then (-1)*montant ".
                "        end) as solde ".
                " from operation o inner join caisse c ".
                "    on c.id =o.caisse_id inner join produit p on p.id =o.produit_id  ".
                "    ".
                " where  p.etat_produit !=6 and 1=1";       

        ( $idCaisse == 0 )? $sql.= '' : $sql.= ' and c.id =:idcaisse';
        ( $compte == '0' )? $sql.= '' : $sql.= ' and o.compte =:compte';
        if($idab !=0){
        $sql.= ' and o.id_abonne =:idab';
        }else{
            ( $idprod == 0 )? $sql.= '' : $sql.= ' and o.produit_id =:idprod';
        }
        
        ( $date != '')? $sql.=" AND  o.date_operation <= " . " '" . $date . "' " :  $sql.= '' ;
        
        $sql .=' group by p.nom_produit';

        $liste = null;
        
        // on ne passe les ecritures que si l'entity manager et le numero de piece sont pas null
        if ( $this->em != null ) {
              // recuperation de la connection 
              $stmt = $this->em->getConnection()->prepare($sql);              
              ( $idCaisse == 0 )? $sql.= '' : $stmt->bindValue(':idcaisse',$idCaisse, PDO::PARAM_INT); 
              ( $compte == '0' )? $sql.= '' : $stmt->bindValue(':compte',$compte, PDO::PARAM_INT); 
              if($idab !=0){
                 $stmt->bindValue(':idab',$idab, PDO::PARAM_INT); 
                }else{
                   ( $idprod == 0 )? $sql.= '' : $stmt->bindValue(':idprod',$idprod, PDO::PARAM_INT); 
                }             
              try{
                $stmt->execute(); 
                $liste = $stmt->fetchAll(PDO::FETCH_ASSOC);
              }catch ( \Exception $e){
                 //var_dump(substr($e->getMessage(),600,800));exit;  
              }             
        }  
        
        return $liste ; 
    }    

    
public function soldeCaisseDesSercice(\Doctrine\ORM\EntityManager $emc,$idCaisse,$date,$idprod=0,$idab=0,$compte='0'){                 
         $date = implode('-', array_reverse( explode('-',$date) ) ) ;
        // requete d'insertion de l'ecrirure comptable
        $sql =  " select p.nom_produit ,".
                "    sum(case ".
                "            when sensoperation = 'C' then montant ".
                "        end) as soldecredit, ".
                "    sum(case ".
                "            when sensoperation = 'D' then montant ".
                "        end) as soldedebit ".
                " from operation o inner join caisse c ".
                "    on c.id =o.caisse_id inner join produit p on p.id =o.produit_id  ".
                "    ".
                " where  p.etat_produit !=6 and 1=1 ";       

        ( $idCaisse == 0 )? $sql.= '' : $sql.= ' and c.id =:idcaisse';
        ( $compte == '0' )? $sql.= '' : $sql.= ' and o.compte =:compte';
        if($idab !=0){
        $sql.= ' and o.id_abonne =:idab';
        }else{
            ( $idprod == 0 )? $sql.= '' : $sql.= ' and o.produit_id =:idprod';
        }
        
        ( $date != '')? $sql.=" AND  o.date_operation = " . " '" . $date . "' " :  $sql.= '' ;
        
        $sql .=' group by p.nom_produit';

        
      
        $liste = null;
        
        // on ne passe les ecritures que si l'entity manager et le numero de piece sont pas null
        if ( $this->em != null ) {
              // recuperation de la connection 
              $stmt = $this->em->getConnection()->prepare($sql);              
              ( $idCaisse == 0 )? $sql.= '' : $stmt->bindValue(':idcaisse',$idCaisse, PDO::PARAM_INT); 
              ( $compte == '0' )? $sql.= '' : $stmt->bindValue(':compte',$compte, PDO::PARAM_INT); 
              if($idab !=0){
                 $stmt->bindValue(':idab',$idab, PDO::PARAM_INT); 
                }else{
                   ( $idprod == 0 )? $sql.= '' : $stmt->bindValue(':idprod',$idprod, PDO::PARAM_INT); 
                }             
              try{
                $stmt->execute(); 
                $liste = $stmt->fetchAll(PDO::FETCH_ASSOC);
              }catch ( \Exception $e){
                 //var_dump(substr($e->getMessage(),600,800));exit;  
              }             
        }  
        
        return $liste ; 
    }    
public function soldeCaisseDesSerciceAutres(\Doctrine\ORM\EntityManager $emc,$idCaisse,$date,$idprod=0,$idab=0,$compte='0'){                 
         $date = implode('-', array_reverse( explode('-',$date) ) ) ;
        // requete d'insertion de l'ecrirure comptable
        $sql =  " select p.nom_produit , o.lib_operation as detail, ".
                "  o.montant, o.sensoperation ".
                " from operation o inner join caisse c ".
                "    on c.id =o.caisse_id inner join produit p on p.id =o.produit_id  ".
                "    ".
                " where  p.etat_produit =6 and 1=1 ";       

        ( $idCaisse == 0 )? $sql.= '' : $sql.= ' and c.id =:idcaisse';
        ( $compte == '0' )? $sql.= '' : $sql.= ' and o.compte =:compte';
        if($idab !=0){
        $sql.= ' and o.id_abonne =:idab';
        }else{
            ( $idprod == 0 )? $sql.= '' : $sql.= ' and o.produit_id =:idprod';
        }
        
        ( $date != '')? $sql.=" AND  o.date_operation = " . " '" . $date . "' " :  $sql.= '' ;
        
        //$sql .=' group by p.nom_produit';

      
      
        $liste = null;
        
        // on ne passe les ecritures que si l'entity manager et le numero de piece sont pas null
        if ( $this->em != null ) {
              // recuperation de la connection 
              $stmt = $this->em->getConnection()->prepare($sql);              
              ( $idCaisse == 0 )? $sql.= '' : $stmt->bindValue(':idcaisse',$idCaisse, PDO::PARAM_INT); 
              ( $compte == '0' )? $sql.= '' : $stmt->bindValue(':compte',$compte, PDO::PARAM_INT); 
              if($idab !=0){
                 $stmt->bindValue(':idab',$idab, PDO::PARAM_INT); 
                }else{
                   ( $idprod == 0 )? $sql.= '' : $stmt->bindValue(':idprod',$idprod, PDO::PARAM_INT); 
                }             
              try{
                $stmt->execute(); 
                $liste = $stmt->fetchAll(PDO::FETCH_ASSOC);
              }catch ( \Exception $e){
                 //var_dump(substr($e->getMessage(),600,800));exit;  
              }             
        }  
        
        return $liste ; 
    }    
    
 public function somEntreeSortieDate(\Doctrine\ORM\EntityManager $emc,$idCaisse,$date){                 
        $sql =  " select c.id, 
                    sum(case 
                            when operation = 'D' then montant
                            when operation = 'C' then 0
                        end) as sommedebit,
                    sum(case 
                            when operation = 'D' then 0
                            when operation = 'C' then montant
                        end) as sommecredit
                  from operation o inner join caisse c
                    on c.compte = o.compte
                    ";       

        ( $idCaisse == 0 )? $sql.= '' : $sql.= ' and c.idcaisse =:idcaisse';
       // ( trim($date)=='' || $date != null)? $sql.= '' : $sql.=" AND  o.dateOperation = " . " '" . $date . "' ";
        ($date != null && trim($date) != '')? $sql.=" AND  o.dateOperation = " . " '" . $date . "' " :  $sql.= '' ;
        $sql .= " ";
        $sql .= " group by c.id";
        
        $liste = null;
        // on ne passe les ecritures que si l'entity manager et le numero de piece sont pas null
        if ( $this->em != null ) {
              // recuperation de la connection 
              $stmt = $this->em->getConnection()->prepare($sql);              
              ( $idCaisse == 0 )? $sql.= '' : $stmt->bindValue(':idcaisse',$idCaisse, PDO::PARAM_INT);  
             
              try{
                $stmt->execute(); 
                $liste = $stmt->fetchAll(PDO::FETCH_ASSOC);
              }catch ( \Exception $e){
                 //var_dump($e);exit;  
              }             
        }  
       // //var_dump($liste); exit ;
        return $liste ; 
    }
    
    public function soldeCaisseEncaisseTheo(\Doctrine\ORM\EntityManager $emc,$idCaisse,$idagence){                 
        
        // requete d'insertion de l'ecrirure comptable
         $sql =  " select c.idcaisse,c.compte,c.codecaisse,c.libcaisse,a.idagence,a.libagence,cpte.libelle as libellecompte, 
                    sum(case 
                            when o.operation = 'D' then o.montant
                            when o.operation = 'C' then (-1)*(o.montant)
                        end) as solde
                  from caisse c left join operation o
                    on o.idcaisse = c.idcaisse inner join agence a
                    on a.idagence = c.idagence
                    inner join plancomptable cpte on cpte.compte = c.compte
                  where c.compte = o.compte and ifnull(trim(o.numconversion),'0')  not in ('1','3') ";       

            ( $idCaisse == 0 )? $sql.= '' : $sql.= " and c.idcaisse =:idcaisse";

            ( $idagence == 0 )? $sql.= '' : $sql.= " and a.idagence =:idagence";
        
        $sql .=" group by c.idcaisse,c.codecaisse,c.libcaisse,a.idagence,a.libagence ";
        
        // on ne passe les ecritures que si l'entity manager et le numero de piece sont pas null
        if ( $this->em != null ) {        $liste = null;

              // recuperation de la connection 
              $stmt = $this->em->getConnection()->prepare($sql);              
              ( $idCaisse == 0 )? $sql.= '' : $stmt->bindValue(':idcaisse',$idCaisse, PDO::PARAM_INT);  
              ( $idagence == 0 )? $sql.= '' : $stmt->bindValue(':idagence',$idagence, PDO::PARAM_INT); 
              try{
                $stmt->execute(); 
                $liste = $stmt->fetchAll(PDO::FETCH_ASSOC);
              }catch ( \Exception $e){
                 //var_dump(substr($e->getMessage(),600,800));exit;  
              }             
        }
        ////var_dump($idCaisse) ;exit ;
        if(($idCaisse != 0) and (count($liste) == 0)){
        //    //var_dump("le jeune") ;exit ;
            
            $sql =  " select c.idcaisse,c.compte,c.codecaisse,c.libcaisse,a.idagence,a.libagence,cpte.libelle as libellecompte
                  from caisse c inner join agence a
                    on a.idagence = c.idagence
                    inner join plancomptable cpte on cpte.compte = c.compte
                  where  c.idcaisse =:idcaisse";    
            
            // on ne passe les ecritures que si l'entity manager et le numero de piece sont pas null
            if ( $this->em != null ) {
                  // recuperation de la connection 
                  $stmt = $this->em->getConnection()->prepare($sql);              
                  $stmt->bindValue(':idcaisse',$idCaisse, PDO::PARAM_INT);  
                   
                  try{
                    $stmt->execute(); 
                    $liste = $stmt->fetchAll(PDO::FETCH_ASSOC);
                  }catch ( \Exception $e){
                     //var_dump(substr($e->getMessage(),600,800));exit;  
                  }
                
                  $liste[0]['solde'] = '0' ;
                    ////var_dump($liste); exit ;
                  //return $liste ;
            }
        
        
            
            
            
        }
        return $liste ; 
    }        
    
    public function getName() {
        return 'OperationManager';
    }    
   
}
