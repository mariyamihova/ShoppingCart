<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 6.12.2017 г.
 * Time: 10:38
 */

namespace ShoppingCartBundle\Service;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Entity\User;


class CartService implements CartServiceInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var OrderServiceInterface
     */
    private $orderService;


    public function __construct(EntityManagerInterface $entityManager, OrderServiceInterface $orderService)
    {
        $this->entityManager = $entityManager;
        $this->orderService = $orderService;

    }

    public function addToCart(User $user, Product $product)
    {
        if ($product->getQuantity() < 1) {
            return false;
        }
        $user->getProducts()->add($product);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return true;
    }


    public function removeFromCart(User $user, Product $product)
    {
        if ($user->getProducts()->removeElement($product)) {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return true;
        }
        return false;
    }

    public function checkoutCart(User $user, Product $product)
    {

        $userCash = $user->getMoney();
        $orderTotal = $this->getOrderTotal($product);


        if ($orderTotal > $userCash || $product->getQuantity() < 1) {
            return false;
        }
        $product->setQuantity($product->getQuantity() - 1);

        $user->getProducts()->removeElement($product);


        /** @var User $seller */
        $seller = $product->getSeller();

        if ($seller) {
            $seller->setMoney($seller->getMoney() + $product->getPrice());
            $product->setSeller(null);

            $this->entityManager->persist($seller);
            $this->entityManager->persist($product);
        }


        $user->setMoney($userCash - $orderTotal);

        $order = $this->orderService->createOrder($user, new \DateTime(), $product, $orderTotal);
        $this->entityManager->persist($order);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return true;
    }


    public function getProductsTotal(array $products)
    {

        $total = 0;
        foreach ($products as $product) {
            if ($product->getPromotionalPrice() != 0.00) {
                $total += $product->getPromotionalPrice();
            } else {
                $total += $product->getPrice();
            }
        }

        return number_format($total, 2, '.', '');
    }

    public function getOrderTotal(Product $product)
    {

        $total = 0;

        if ($product->getPromotionalPrice() != 0.00) {
            $total += $product->getPromotionalPrice();
        } else {
            $total += $product->getPrice();
        }


        return number_format($total, 2, '.', '');
    }
}