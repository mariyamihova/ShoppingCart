<?php

namespace ShoppingCartBundle\Repository;

use ShoppingCartBundle\Entity\Category;
class CategoryRepository extends \Doctrine\ORM\EntityRepository
{

    public function findAllSubCategories(Category $category)
    {
        return $this->createQueryBuilder("category")
            ->where("category.parent=:cat")
            ->setParameter("cat", $category)
            ->getQuery()
            ->getResult();
    }
    public function findAllMainCategories()
    {
        return $this->createQueryBuilder("category")
            ->where("category.parent IS NULL")
            ->getQuery()
            ->getResult();
    }
}
