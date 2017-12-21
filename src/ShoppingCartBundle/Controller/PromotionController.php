<?php

namespace ShoppingCartBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Entity\Promotion;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CartController
 * @package ShoppingCartBundle\Controller
 *
 * @Route("/promotion")
 *
 */
class PromotionController extends Controller
{
    /**
     * @Route("", name="view_promotions")
     * @Method("GET")
     *
     * @return Response
     */
    public function indexAction()
    {

        $promotions = $this->getDoctrine()->getRepository(Promotion::class)
            ->findAllActivePromotions();

        return $this->render("promotions/list.html.twig", [
            "promotions" => $promotions
        ]);
    }

    /**
     * @Route("/{id}", name="view_promotion_products", requirements={"id"="\d+"})
     * @Method("GET")
     *
     * @param Promotion $promotion
     * @param Request $request
     * @return Response
     */
    public function viewPromotionProducts(Promotion $promotion,Request $request)
    {
        $pager = $this->get('knp_paginator');

        /** @var ArrayCollection|Product[] $products */
        $allProducts = $pager->paginate(
            $promotion->getProducts(),
            $request->query->getInt('page', 1),
            9
        );

        return $this->render("promotions/all_products.html.twig", ["products" => $allProducts]);

    }
}
