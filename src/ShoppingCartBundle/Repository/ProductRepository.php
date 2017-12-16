<?php

namespace ShoppingCartBundle\Repository;


/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
use ShoppingCartBundle\Entity\Category;
use ShoppingCartBundle\Entity\User;

class ProductRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllBySubCategory(Category $category)
    {
        return $this->createQueryBuilder("product")
            ->where("product.quantity > 0")
            ->andWhere("product.category = :cat")
            ->setParameter("cat", $category)
            ->getQuery()
            ->getResult();
    }

    public function findLastProducts($count)
    {
        return $this->createQueryBuilder("product")
            ->where("product.seller IS NULL")
            ->andWhere("product.quantity > 0")
            ->setMaxResults($count)
            ->orderBy("product.priority", "desc")
            ->getQuery()
            ->getResult();
    }

    public function findUserSales()
    {
        return $this->createQueryBuilder("product")
            ->where("product.quantity>0")
            ->andWhere("product.seller IS NOT NULL")
            ->orderBy("product.priority","desc")
            ->getQuery()
            ->getResult();
    }

    public function findShopProducts()
    {
        return $this->createQueryBuilder("product")
            ->where("product.quantity>0")
            ->andWhere("product.seller IS NULL")
            ->orderBy("product.priority","desc")
            ->getQuery()
            ->getResult();
    }
    public function findProductsBySeller(User $seller)
    {
        return $this->createQueryBuilder("product")
            ->where("product.quantity > 0")
            ->andWhere("product.seller = :seller")
            ->setParameter("seller", $seller)
            ->getQuery()
            ->getResult();
    }
}
