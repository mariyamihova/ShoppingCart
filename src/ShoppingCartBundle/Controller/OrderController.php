<?php

namespace ShoppingCartBundle\Controller;


use Doctrine\Common\Collections\ArrayCollection;
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
     * @param Request $request
     * @return Response
     *
     */

    public function indexAction(Request $request)
    {
        $currentUser=$this->getUser();

        $pager = $this->get('knp_paginator');

        /** @var ArrayCollection|ProductOrder[] $orders */
        $orders = $pager->paginate(
            $this->getDoctrine()->getRepository(ProductOrder::class)
                ->getOrdersByUser($currentUser),
            $request->query->getInt('page', 1),
            5
        );

        return $this->render("orders/user_orders.html.twig", ["orders" => $orders]);
    }
}
