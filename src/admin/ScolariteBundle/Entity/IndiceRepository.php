<?php

namespace admin\ScolariteBundle\Entity;
use \admin\UserBundle\Types\TypeEtat;

/**
 * IndiceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class IndiceRepository extends \Doctrine\ORM\EntityRepository
{
        /*
     * Retourne un ou tous les profils si $idIndice = 0
     * @param int $idIndice
     * @return array
     */
    public function getAllOrOneIndice($idIndice) {
        $idIndiceQuery = (int) $idIndice;
        $qb = $this->createQueryBuilder('p')
                ->where('p.etatIndice != ' . TypeEtat::SUPPRIME)
                ;
        if($idIndice > 0){
            $qb->andWhere('p.id =:idIndice')
                    ->setParameter('idIndice', $idIndiceQuery);
            
        }
        return $qb->getQuery()->getResult();
    }
    
    /*
     * Retourne tous les profils
     * @return type
     */
    public function getAllIndice() {
        $qb = $this->createQueryBuilder('e')
                ->where('e.etatIndice != ' . TypeEtat::SUPPRIME);
               // ->orderBy('e.typeIndice', 'ASC');
        return $qb->getQuery()->getResult();
    }
}