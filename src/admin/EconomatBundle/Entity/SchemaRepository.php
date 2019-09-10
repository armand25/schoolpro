<?php

namespace admin\EconomatBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * SchemaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SchemaRepository extends EntityRepository
{
    
    
    //  Fonction pour chercher par par type operation
    
    public function findByTypeOperation($idTypeoperation = 0){
	
       // $manager = $this->getDoctrine()->getManager() ;
         $queryBuilder = $this->_em->createQueryBuilder();
         $queryBuilder->select("f,g")
                       ->from("adminEconomatBundle:Schema","f")
                        ->leftJoin("f.typeoperation","g") ;
         
         if($idTypeoperation > 0){
             $queryBuilder->where("g.id = :idTypeoperation")->setParameter("idTypeoperation", (int)$idTypeoperation) ;
             
         }
        
        return $queryBuilder->getQuery()->getResult() ;
    }
    
    
    
   // public function getSiSchemaExiste($id, $idtypeoperation) {
    public function getSiSchemaExiste($id) {
        //Make a Select query

        $count = 0;

        $sql = 'SELECT count(p.id) 
                FROM adminEconomatBundle:Schema p
                ';
        ($id == 0) ? $sql .= '' : $sql .= ' AND p.id != :idschema';
        $query = null;
        $query = $this->_em->createQuery($sql);
        $query->setParameter("idschema", $id);
        /*($id == 0) ?
                        $query->setParameters(array('idtypeoperation' => $idtypeoperation)) :
                        $query->setParameters(array('idtypeoperation' => $idtypeoperation, 'idschema' => $idtypeoperation));*/

        try {
            $count = $query->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            $count = 0;
        }

        return $count;
    } 
    
    
     public function getListeSchema($page, $articles_per_page)
    {        
        $tableau = null;
        $sqltype = "SELECT t.id, t.libTypeOperation
                    FROM adminEconomatBundle:TypeOperation t
                    where t.suppr= 0 and t.etatTypeOperation = 0";

        $sqltype.=' ORDER BY t.id';

        $q1 = $this->_em->createQuery($sqltype);
        $t1 = $q1->getResult();

        foreach ($t1 as $value){            
            if ($value != null && $value != 0){
                $sql = "SELECT DISTINCT t.codeOperation,  a.id, a.sens, t.id as idType, p.compte, p.libelle
                        FROM adminEconomatBundle:Schema a inner join a.typeoperation t
                        inner join a.plancomptable p 
                        where t.suppr= 0 and t.etatTypeOperation = 0 and t.id = :id ";

                $sql.=' ORDER BY t.id ';

                $query = $this->_em->createQuery($sql);
                $query->setParameter('id',$value['id'] );
                $query->setFirstResult(($page * $articles_per_page) - $articles_per_page);
                $query->setMaxResults($articles_per_page);
                $t2 = $query->getResult();                
            }
            $tableau[$value['id'].'&'.$value['libTypeOperation']][] = $t2;
        }
        
        return $tableau;
   }
}