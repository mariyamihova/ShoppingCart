<?php

namespace ShoppingCartBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CartController
 * @package ShoppingCartBundle\Controller
 *
 * @Route("/cart")
 * @Security(expression="is_granted('IS_AUTHENTICATED_FULLY')")
 */
class CartController extends Controller
{
    /**
     * @Route("", name="user_cart")
     * @Method("GET")
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $currentUser = $this->getUser();
        $cartService = $this->get(CartService::class);
        $total = $cartService->getProductsTotal($currentUser->getProducts()->toArray());

        $pager = $this->get('knp_paginator');

        /** @var ArrayCollection|Product[] $products */
        $cartProducts = $pager->paginate(
            $currentUser->getProducts(),
            $request->query->getInt('page', 1),
            5
        );
        return $this->render("cart/index.html.twig", [
            "cart" => $cartProducts,
            "total" => $total
        ]);
    }

    /**
     * @Route("/add/{id}", name="user_cart_add", requirements={"id"="\d+"})
     * @Method("POST")
     *
     * @param Product $product
     * @return Response
     */
    public function addToCartAction(Product $product)
    {
        $cartService = $this->get(CartService::class);

        if (!$cartService->addToCart($this->getUser(), $product))
        {
            $this->addFlash("danger", "Sorry, cannot add this product.It is out of stock!");
            return $this->redirectToRoute("homepage");
        }

        $this->addFlash("notice", "Product has been added to your cart!");
        return $this->redirectToRoute("user_cart");
    }

    /**
     * @Route("/delete/{id}", name="user_cart_update")
     * @Method("POST")
     *
     * @param Product $product
     * @return Response
     */

    public function deleteFromCartAction(Product $product)
    {
        $cartService = $this->get(CartService::class);
        $cartService->removeFromCart($this->getUser(), $product);

        $this->addFlash("notice", "Product has been removed from your cart!");
        return $this->redirectToRoute("user_cart");
    }

    /**
     * @Route("/checkout/{id}", name="user_cart_checkout", requirements={"id"="\d+"})
     * @Method("POST")
     *
     * @param Product $product
     * @return Response
     */

    public function checkoutAction(Product $product)
    {

        $cartService = $this->get(CartService::class);

        if (!$cartService->checkoutCart($this->getUser(),$product))
        {
            $this->addFlash("danger", "Sorry, cannot order this product!Check out your cash!");
            return $this->redirectToRoute("user_cart");
        }

        $this->addFlash("notice", "Congrats, product has been ordered!");
        return $this->redirectToRoute("user_orders");
    }
}
