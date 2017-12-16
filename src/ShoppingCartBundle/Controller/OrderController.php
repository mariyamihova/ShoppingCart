<?php

namespace ShoppingCartBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ShoppingCartBundle\Entity\ProductOrder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class OrdersController
 * @package ShoppingCartBundle\Controller
 *
 * @Route("/orders")
 * @Security(expression="is_granted('IS_AUTHENTICATED_FULLY')")
 */

class OrderController extends Controller
{
    /**
     * @Route("", name="user_orders")
     * @Method("GET")
     *
     * @return Response
     *
     */

    public function indexAction()
    {
        $currentUser=$this->getUser();

        $orders=$this->getDoctrine()->getRepository(ProductOrder::class)
            ->getOrdersByUser($currentUser);

        return $this->render("orders/user_orders.html.twig", ["orders" => $orders]);
    }
}
