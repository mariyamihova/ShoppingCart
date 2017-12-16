<?php

namespace ShoppingCartBundle\Repository;


/**
 * ProductOrderRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
use ShoppingCartBundle\Entity\User;
class ProductOrderRepository extends \Doctrine\ORM\EntityRepository
{

    public function getOrdersByUser(User $user)
    {
        return $this->createQueryBuilder('productOrder')
            ->where("productOrder.user=:user")
            ->setParameter("user",$user)
            ->orderBy("productOrder.date","desc")
            ->getQuery()
            ->getResult();
    }
    public function getAllPendingOrders()
    {
        return $this->createQueryBuilder('productOrder')
            ->where("productOrder.verified=0")
            ->orderBy("productOrder.date","desc")
            ->getQuery()
            ->getResult();
    }
}