<?php

namespace ShoppingCartBundle\Controller\Admin;

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
 * @package ShoppingCartBundle\Controller\Admin
 *
 * @Route("/admin/orders")
 * @Security("is_granted('ROLE_ADMIN')")
 *
 */
class OrdersController extends Controller
{
    /**
     * @Route("", name="admin_list_orders")
     * @Method("GET")
     * @param Request $request
     * @return Response
     */

    public function listOrdersAction(Request $request)
    {
        $pager = $this->get('knp_paginator');

        /** @var ArrayCollection|ProductOrder[] $orders */
        $orders = $pager->paginate(
            $this->getDoctrine()->getRepository(ProductOrder::class)
                ->getAllPendingOrders(),
            $request->query->getInt('page', 1),
            9
        );


        return $this->render("admin/orders/list.html.twig", ["orders" => $orders]);
    }

    /**
     * @Route("/approve/{id}", name="admin_verify_order", requirements={"id"="\d+"})
     * @Method("POST")
     *
     * @param ProductOrder $order
     * @return Response
     *
     *
     */
    public function approveOrder(ProductOrder $order)
    {
        if ($order->getVerified() == true)
        {
            $this->addFlash("danger", "This order is already verified.");
            return $this->redirectToRoute("admin_list_orders");
        }

        $order->setVerified(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($order);
        $em->flush();

        $this->addFlash("success", "Order was verified successfully.");
        return $this->redirectToRoute("admin_list_orders");
    }

    /**
     * @Route("/delete/{id}", name="admin_delete_order", requirements={"id"="\d+"})
     * @Method("POST")
     *
     * @param ProductOrder $order
     * @return Response
     *
     *
     */
    public function deleteOrder(ProductOrder $order)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($order);
        $em->flush();

        $this->addFlash("success", "Order was deleted!");

        return $this->redirectToRoute("admin_list_orders");
    }
}
