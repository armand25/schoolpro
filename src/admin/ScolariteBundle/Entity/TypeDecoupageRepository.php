<?php

namespace admin\ScolariteBundle\Entity;
use \admin\UserBundle\Types\TypeEtat;
/**
 * TypeDecoupageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TypeDecoupageRepository extends \Doctrine\ORM\EntityRepository
{
      /*
     * Retourne un ou tous les  type decoupages si $idTypeDecoupage = 0
     * @param int $idTypeDecoupage
     * @return array
     */
    public function getAllOrOneTypeDecoupage($idTypeDecoupage) {
        $idTypeDecoupageQuery = (int) $idTypeDecoupage;
        $qb = $this->createQueryBuilder('p')
                ->where('p.etatTypeDecoupage != ' . TypeEtat::SUPPRIME)
                ;
        if($idTypeDecoupage > 0){
            $qb->andWhere('p.id =:idTypeDecoupage')
                    ->setParameter('idTypeDecoupage', $idTypeDecoupageQuery);
            
        }
        return $qb->getQuery()->getResult();
    }
    
    /*
     * Retourne tous les  type decoupages
     * @return type
     */
    public function getAllTypeDecoupage() {
        $qb = $this->createQueryBuilder('e')
                ->where('e.etatTypeDecoupage != ' . TypeEtat::SUPPRIME);
               // ->orderBy('e.typeTypeDecoupage', 'ASC');
        return $qb->getQuery()->getResult();
    }
    /*
     * Retourne tous les  type decoupages
     * @return type
     */
    public function getAllActifTypeDecoupage() {
        $qb = $this->createQueryBuilder('e')
                ->where('e.etatTypeDecoupage != ' . TypeEtat::SUPPRIME);
               // ->orderBy('e.typeTypeDecoupage', 'ASC');
        return $qb->getQuery()->getResult();
    }
    
}