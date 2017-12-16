<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 6.12.2017 г.
 * Time: 10:54
 */

namespace ShoppingCartBundle\Service;


use ShoppingCartBundle\Entity\User;

interface OrderServiceInterface
{
 public function createOrder(User $user,\DateTime $date, array $products, float $total);
}