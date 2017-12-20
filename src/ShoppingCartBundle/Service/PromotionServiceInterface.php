<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16.12.2017 г.
 * Time: 14:10
 */

namespace ShoppingCartBundle\Service;


use Doctrine\Common\Collections\ArrayCollection;
use ShoppingCartBundle\Entity\Category;
use ShoppingCartBundle\Entity\Promotion;

interface PromotionServiceInterface
{
    public function setPromotionToCategory(Promotion $promotion, Category $category);
    public function setPromotionToProduct(Promotion $promotion, ArrayCollection $products);
    public function setPromotionToAllProducts(Promotion $promotion, array $products);
    public function removePromotion(Promotion $promotion,array $products);
}