<?php


namespace ShoppingCartBundle\Service;


use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Entity\User;

interface CartServiceInterface
{
    public function addToCart(User $user, Product $product);
    public function getProductsTotal($products);
    public function removeFromCart(User $user, Product $product);
    public function checkoutCart(User $user,Product $product);
    public function getOrderTotal(Product $product);
}