<?php

namespace admin\EconomatBundle\Entity;

use Doctrine\ORM\EntityRepository;
use admin\UserBundle\Types\TypeEtat;
/**
 * CaisseRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CaisseRepository extends EntityRepository
{
    
    
        /**
     * Retourne tous les profils.
     *
     * @return type
     */
    public function getAllCaisse()
    {
        $qb = $this->createQueryBuilder('r')
                ->where('r.etatCaisse != '.TypeEtat::SUPPRIME)
                ->orderBy('r.id', 'ASC');

        return $qb->getQuery()->getResult();
    }
}