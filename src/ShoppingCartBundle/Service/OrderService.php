<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 6.12.2017 г.
 * Time: 10:55
 */

namespace ShoppingCartBundle\Service;


use Doctrine\ORM\EntityManagerInterface;
use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Entity\ProductOrder;
use ShoppingCartBundle\Entity\User;

class OrderService implements OrderServiceInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function createOrder(User $user, \DateTime $date, Product $product, float $total)
    {
        $order=new ProductOrder();
        $order->setUser($user);
        $order->setDate($date);
        $order->setProduct($product);
        $order->setTotal($total);
        $order->setVerified(false);

        return $order;
    }

}