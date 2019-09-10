<?php

/**
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ApplicationManager
 *
 * @author Edmond
 */

namespace admin\ScolariteBundle\Services;
use \Doctrine\ORM\EntityManager;
use \PDO;
class NoteManager {
    
    private $em;
    
    
    public function __construct(EntityManager $em) {
        $this->em = $em;
    }
 /*
     * Retourne la moyenne des notes d'un eleve par type examen
     * @return type
     */

    public function getMoyenneNote($idtypeexam,$idseTrouver, $iddecoup, $idmatiere) {
        $sql = '
                SELECT AVG(note) as moyenne FROM note c 
                WHERE  c.setrouver_id = :setrouver and typeexamen_id = :typeexamen and matiere_id = :matiere and decoupage_id = :decoupage';
        $stmt = $this->em->getConnection()->prepare($sql);

      //  var_dump($sql,$idtypeexam,$idseTrouver, $iddecoup, $idmatiere);
       // exit;
        try {
            $stmt->bindValue(':setrouver', $idseTrouver, PDO::PARAM_INT);
            $stmt->bindValue(':typeexamen', $idtypeexam, PDO::PARAM_STR);
            $stmt->bindValue(':matiere', $idmatiere, PDO::PARAM_STR);
            $stmt->bindValue(':decoupage', $iddecoup, PDO::PARAM_STR);
          //  var_dump(1);exit;
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            //var_dump($e);
            var_dump('faux');
            exit;
        }
        
       
        return $result;
    }

}
