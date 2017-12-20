<?php

namespace ShoppingCartBundle\Repository;



use ShoppingCartBundle\Entity\Product;
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
    public function getOrdersByUserAndProduct(User $user, Product $product)
    {
        return $this->createQueryBuilder('productOrder')
            ->where("productOrder.verified=1")
            ->andWhere("productOrder.user=:user")
            ->andWhere("productOrder.product=:product")
            ->setParameter("user",$user)
            ->setParameter("product",$product)
            ->getQuery()
            ->getResult();
    }
}
