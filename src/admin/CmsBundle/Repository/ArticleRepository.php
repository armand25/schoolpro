<?php

namespace admin\CmsBundle\Repository;
use \admin\UserBundle\Types\TypeEtat;


/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleRepository extends \Doctrine\ORM\EntityRepository
{
       /*
     * Retourne un ou tous les profils si $idArticle = 0
     * @param int $idArticle
     * @return array
     */
  public function getAllOrOneArticle($idArticle) {
        $idArticleQuery = (int) $idArticle;
        $qb = $this->createQueryBuilder('p')
                ->where('p.actifArticle != ' . TypeEtat::SUPPRIME)
                ;
        if($idArticle > 0){
            $qb->andWhere('p.id =:idArticle')
                    ->setParameter('idArticle', $idArticleQuery);
            
        }
        return $qb->getQuery()->getResult();
    }
    
       /*
     * Retourne un ou tous les profils si $idArticle = 0
     * @param int $idArticle
     * @return array
     */
    public function getAllOrOneArticleActif($idArticle) {
        $idArticleQuery = (int) $idArticle;
        $qb = $this->createQueryBuilder('p')               
                ->where('p.actifArticle = ' . TypeEtat::ACTIF)                
                ->andWhere('r.actifArticle = ' . TypeEtat::ACTIF)
                ;
        if($idArticle > 0){
            $qb->andWhere('p.id =:idArticle')
            
                    ->setParameter('idArticle', $idArticleQuery);
            
        }
        //var_dump($qb->getDQL());exit;
       
        return $qb->getQuery()->getResult();
    }
    /*
     * Retourne un ou tous les profils si $idArticle = 0
     * @param int $idArticle
     * @return array
     */
    public function getAllOrOneArticleSousActif($idRub) {
        
        $dql = "SELECT p FROM admin\CmsBundle\Entity\Article p LEFT JOIN p.rubrique r "
                . "WHERE p.actifArticle = :etat AND r.actifRubrique = :etat2 AND r.id =:idRub";

        $query = $this->_em->createQuery($dql);
        $query->setParameter('etat', TypeEtat::ACTIF);
        $query->setParameter('etat2', TypeEtat::ACTIF);
        $query->setParameter('idRub', $idRub);
       // var_dump($query->getResult());exit;
        return $query->getResult();
        
    }
    
       /*
     * Retourne un ou tous les profils si $idArticle = 0
     * @param int $idArticle
     * @return array
     */
    public function getAllOrOneArticleArticleActif($idArticle) {
        $idArticleQuery = (int) $idArticle;
        $qb = $this->createQueryBuilder('p')
                ->leftJoin('p.articles', 'a')
                ->where('p.actifArticle = ' . TypeEtat::ACTIF)                
                ;
        if($idArticle > 0){
            $qb->andWhere('p.id =:idArticle')
            
                    ->setParameter('idArticle', $idArticleQuery);
            
        }
       
        return $qb->getQuery()->getResult();
    }
    
    /*
     * Retourne tous les profils
     * @return type
     */
    public function getAllArticle() {
        $qb = $this->createQueryBuilder('e')
                ->where('e.actifArticle != ' . TypeEtat::SUPPRIME);
               // ->orderBy('e.typeArticle', 'ASC');
        return $qb->getQuery()->getResult();
    }
    
    /*
     * Retourne tous les profils
     * @return type
     */
    public function getAllArticleEtablissement($idEtabl,$idPr) {
        if($idPr==1 || $idPr==3) {
           $idPr=0; 
        }
       
        $qb = $this->createQueryBuilder('a')
                ->innerJoin('a.etablissement', 'e')
                ->where('a.actifArticle != ' . TypeEtat::SUPPRIME)
                ->andWhere('e.id = '.$idEtabl)               
                ->andWhere('a.typeArticle = '.$idPr);

               // ->orderBy('e.typeArticle', 'ASC');
        return $qb->getQuery()->getResult();
    }
    /*
     * Retourne tous les profils
     * @return type
     */
  public function getAllArticleActifEtablissement($idEtabl,$idPr) {
   // public function getAllArticleActifEtablissement($idPr) {
        if($idPr==1 || $idPr==3) {
           $idPr=0; 
        }
       //var_dump($idPr);exit;
        $qb = $this->createQueryBuilder('a')
                ->innerJoin('a.etablissement', 'e')
                ->innerJoin('a.rubrique', 'r')
                ->where('a.actifArticle = ' . TypeEtat::ACTIF);
                if($idPr !=0 ){                  
                    $qb->andWhere('r.id = '.$idPr);
                }
                if($idEtabl !=1 ){
                    $qb ->andWhere('e.id = '.$idEtabl) ;
                }
              
                // ->orderBy('e.typeArticle', 'ASC');
        return $qb->getQuery()->getResult();
    }
    
    
    /*
     * Retourne tous les profils
     * @return type
     */
    public function getAllArticleActif() {
        $qb = $this->createQueryBuilder('e')
                ->where('e.actifArticle = ' . TypeEtat::ACTIF);
               // ->orderBy('e.typeArticle', 'ASC');
        return $qb->getQuery()->getResult();
    }
    
    


    
    

    
}
