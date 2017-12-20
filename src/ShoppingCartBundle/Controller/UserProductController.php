<?php

namespace ShoppingCartBundle\Controller;


use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Entity\ProductOrder;
use ShoppingCartBundle\Entity\User;
use ShoppingCartBundle\Form\ProductSellType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class UserProductController
 * @package ShoppingCartBundle\Controller
 *
 * @Security(expression="is_granted('IS_AUTHENTICATED_FULLY')")
 */
class UserProductController extends Controller
{
    /**
     * @Route("/myproducts", name="user_owned_products")
     * @Method("GET")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function viewAllAction(Request $request)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        $pager = $this->get('knp_paginator');

        /** @var ArrayCollection|Product[] $products */
        $products = $pager->paginate(
            $this->getDoctrine()->getRepository(Product::class)
                ->findProductsBySeller($currentUser),
            $request->query->getInt('page', 1),
            9
        );


        return $this->render("user/owner.html.twig", ["products" => $products]);

    }

    /**
     * @Route("/sell/{id}", name="sell_user_product", requirements={"id"="\d+"})
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Product $product
     * @return Response
     */
    public function sellProductAction(Request $request, Product $product)
    {
        $order=$this->getDoctrine()->getRepository(ProductOrder::class)
            ->getOrdersByUserAndProduct($this->getUser(),$product);
         if(!empty($order))
         {
             $newProduct = new Product();
             $newProduct->setName($product->getName());
             $newProduct->setCategory($product->getCategory());
             $newProduct->setDescription($product->getDescription());
             $newProduct->setQuantity(1);
             $newProduct->setPriority(1);
             $newProduct->setPrice($product->getPrice());
             $newProduct->setPromotionalPrice($product->getPromotionalPrice());
             $newProduct->setUpdatedAt(new \DateTime());
             $newProduct->setImageUrl($product->getImageUrl());
             $newProduct->setSeller($this->getUser());

             $form = $this->createForm(ProductSellType::class, $newProduct);
             $form->handleRequest($request);

             if ($form->isSubmitted() && $form->isValid())
             {

                 $em = $this->getDoctrine()->getManager();
                 $em->persist($newProduct);
                 $em->flush();

                 $this->addFlash("notice", "Product has been added for sale!");
                 return $this->redirectToRoute("user_owned_products");
             }


             return $this->render("product/sell_product.html.twig", [
                 "form" => $form->createView(),
                 "product" => $product
             ]);
         }
        $this->addFlash("notice", "You're not allowed to sell this product");
        return $this->redirectToRoute("user_owned_products");
    }
}
