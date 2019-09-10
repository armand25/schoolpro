<?php

namespace admin\ScolariteBundle\Entity;
use \admin\UserBundle\Types\TypeEtat;

/**
 * ChapitreRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ChapitreRepository extends \Doctrine\ORM\EntityRepository
{
    use \admin\UserBundle\ControllerModel\paramUtilTrait;
    /**
     * Retourne un ou tous les chapitres si $idChapitre = 0
     * @param int $idChapitre
     * @return array
     */
    public function getAllOrOneChapitre($idChapitre) {
        $idChapitreQuery = (int) $idChapitre;
        $qb = $this->createQueryBuilder('p')
                ->where('p.etatChapitre != ' . TypeEtat::SUPPRIME)
                ;
        if($idChapitre > 0){
            $qb->andWhere('p.id =:idChapitre')
                    ->setParameter('idChapitre', $idChapitreQuery);
            
        }
        return $qb->getQuery()->getResult();
    }
    
    /*
     * Retourne tous les chapitres
     * @return type
     */
    //    public function getAllChapitre($estEns) {
    //        $qb = $this->createQueryBuilder('e')
    //                ->where('e.etatChapitre != ' . TypeEtat::SUPPRIME);
    //               // ->orderBy('e.typeChapitre', 'ASC');
    //        return $qb->getQuery()->getResult();
    //    }
    public function getAllChapitre($estEns) {
        $dql = "SELECT c FROM " . $this->scolariteBundle . "Chapitre c INNER JOIN c.estenseigne e "
                . " WHERE e.id =:idens ";

        $query = $this->_em->createQuery($dql);
        $query->setParameter('idens', $estEns);

        return $query->getResult();
    } 
    
    
    /*
     * Retourne tous les chapitres
     * @return type
     */
    public function getAllActifChapitre() {
        $qb = $this->createQueryBuilder('e')
                ->where('e.etatChapitre = ' . TypeEtat::ACTIF);
               // ->orderBy('e.typeChapitre', 'ASC');
        return $qb->getQuery()->getResult();
    }
    
}
