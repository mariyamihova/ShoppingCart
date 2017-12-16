<?php

namespace ShoppingCartBundle\Repository;


/**
 * PromotionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
use ShoppingCartBundle\Entity\Promotion;
class PromotionRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllActivePromotions()
    {
        return $this->createQueryBuilder("promotion")
            ->andWhere("promotion.endDate >= :now")
            ->andWhere("promotion.startDate <= :now")
            ->setParameter("now", new \DateTime("now"))
            ->getQuery()
            ->getResult();
    }

    public function findProductsByPromotion($promotionId)
    {
        return $this->createQueryBuilder("promotion")
            ->join("promotion.products","pp")
            ->select("pp.id")
            ->where("promotion.id=:id")
            ->setParameter("id",$promotionId)
            ->getQuery()
            ->getResult();
    }

}
