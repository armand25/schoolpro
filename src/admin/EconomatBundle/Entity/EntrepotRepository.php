<?php

namespace admin\EconomatBundle\Entity;

use admin\UserBundle\Types\TypeEtat;

/**
 * EntrepotRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EntrepotRepository extends \Doctrine\ORM\EntityRepository
{
    
        /**
     * Retourne tous les profils.
     *
     * @return type
     */
    public function getAllEntrepot()
    {
        $qb = $this->createQueryBuilder('r')
                ->where('r.etatEntrepot != '.TypeEtat::SUPPRIME)
                ->orderBy('r.id', 'ASC');

        return $qb->getQuery()->getResult();
    }
}