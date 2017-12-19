<?php

namespace ShoppingCartBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Entity\Promotion;
use ShoppingCartBundle\Service\PromotionService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CartController
 * @package ShoppingCartBundle\Controller
 *
 * @Route("/promotion")
 * @Security(expression="is_granted('IS_AUTHENTICATED_FULLY')")
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
     * @Route("/{id}", name="view_promotion_products")
     * @Method("GET")
     * @param Promotion $promotion
     *
     * @return Response
     */
    public function viewPromotionProducts(Promotion $promotion)
    {
        $allProducts = $promotion->getProducts();
        return $this->render("promotions/all_products.html.twig", ["products" => $allProducts]);

    }
}
