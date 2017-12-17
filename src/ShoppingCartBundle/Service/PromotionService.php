<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16.12.2017 г.
 * Time: 14:11
 */

namespace ShoppingCartBundle\Service;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use ShoppingCartBundle\Entity\Category;
use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Entity\Promotion;

class PromotionService implements PromotionServiceInterface
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ManagerRegistry
     */
    private $repoManager;

    public function __construct(EntityManagerInterface $entityManager,ManagerRegistry $repoManager)
    {
        $this->entityManager = $entityManager;
        $this->repoManager=$repoManager;
    }


    public function getPromotionalProducts(array $productIds)
    {
        $products=[];
        foreach ($productIds as $id)
        {
            $prId=$id["id"];
            $product=$this->repoManager->getRepository(Product::class)->find($prId);
            $products[]=$product;
        }
        return $products;
    }


    public function setPromotionToCategory(Promotion $promotion, Category $category)
    {
        $products=$category->getProducts();

        foreach ($products as $product)
        {
            if($product->getPromotions()->contains($promotion))
            {
                continue;
            }
            $product->setPromotion($promotion);
        }
        $this->entityManager->persist($category);
        $this->entityManager->flush();
    }

    public function setPromotionToProduct(Promotion $promotion, ArrayCollection $products)
    {

        foreach ($products as $product)
        {
            if($product->getPromotions()->contains($promotion))
            {
                continue;
            }
            $product->setPromotion($promotion);
            $this->entityManager->persist($product);
            $this->entityManager->flush();
        }

    }

    public function setPromotionToAllProducts(Promotion $promotion, array $products)
    {

        foreach ($products as $product)
        {
            if ($product->getPromotions()->contains($promotion))
            {
                continue;
            }

            $product->setPromotion($promotion);
        }

        $this->entityManager->persist($promotion);
        $this->entityManager->flush();

    }
    public function removePromotion(Promotion $promotion,array $products)
    {


        foreach ($products as $product)
        {
            if (!$product->getPromotions()->contains($promotion)) {
                continue;
            }

            $product->unsetPromotion($promotion);
        }

        $this->entityManager->persist($promotion);
        $this->entityManager->flush();


    }
}