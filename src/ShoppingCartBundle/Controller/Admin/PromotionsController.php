<?php

namespace ShoppingCartBundle\Controller\Admin;

use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Entity\Promotion;
use ShoppingCartBundle\Form\AddEditPromotionType;
use ShoppingCartBundle\Form\PromotionCategoryForm;
use ShoppingCartBundle\Form\PromotionProductType;
use ShoppingCartBundle\Form\PromotionType;
use ShoppingCartBundle\Service\PromotionService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PromotionsController
 * @package ShoppingCartBundle\Controller\Admin
 *
 * @Route("/admin")
 * @Security("is_granted('ROLE_EDITOR')")
 *
 */
class PromotionsController extends Controller
{
    /**
     * @Route("/promotions", name="admin_view_promotions")
     * @Method("GET")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function viewAllAction(Request $request)
    {
        $pager = $this->get('knp_paginator');

        /** @var ArrayCollection|Promotion[] $promotions */
        $promotions = $pager->paginate(
            $this->getDoctrine()->getRepository(Promotion::class)->findAll(),
            $request->query->getInt('page', 1),
            9
        );

        return $this->render("admin/promotions/list.html.twig", ["promotions" => $promotions]);
    }

    /**
     * @Route("/promotion/add", name="admin_add_promotion")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function addPromotionAction(Request $request)
    {

        $promotion = new Promotion();

        $form = $this->createForm(AddEditPromotionType::class, $promotion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($promotion);
            $em->flush();

            $this->addFlash("success", "Promotion {$promotion->getName()} was added!");
            return $this->redirectToRoute("admin_view_promotions");
        }
        return $this->render("admin/promotions/add.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/promotion/edit/{id}", name="admin_edit_promotion", requirements={"id"="\d+"})
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Promotion $promotion
     * @return Response
     */

    public function editPromotionAction(Request $request, Promotion $promotion)
    {

        $form = $this->createForm(AddEditPromotionType::class, $promotion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {

            $em = $this->getDoctrine()->getManager();
            $em->persist($promotion);
            $em->flush();

            $this->addFlash("success", "Promotion {$promotion->getName()} was updated!");
            return $this->redirectToRoute("admin_view_promotions");
        }

        return $this->render("admin/promotions/edit.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/promotion/delete/{id}", name="admin_delete_promotion", requirements={"id"="\d+"})
     * @Method("POST")
     *
     * @param Promotion $promotion
     * @return Response
     */

    public function deletePromotionAction(Promotion $promotion)
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($promotion);
        $em->flush();

        $this->addFlash("success", "Promotion {$promotion->getName()} was deleted!");
        return $this->redirectToRoute("admin_view_promotions");
    }

    /**
     * @Route("/promotion/set/category", name="admin_set_promotion_category")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @return Response
     */

    public function setPromotionToCategoryAction(Request $request)
    {
        $form = $this->createForm(PromotionCategoryForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $promotion = $form->get("promotion")->getData();
            $category = $form->get("category")->getData();

            $promotionService = $this->get(PromotionService::class);
            $promotionService->setPromotionToCategory($promotion, $category);

            $this->addFlash("success", "Promotion was set to the selected category");
            return $this->redirectToRoute("admin_view_promotions");
        }

        return $this->render("admin/promotions/category.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/promotion/set/product", name="admin_set_promotion_product")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @return Response
     */

    public function setPromotionToCertainProductsAction(Request $request)
    {
        $form = $this->createForm(PromotionProductType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $promotion = $form->get("promotion")->getData();
            $products = $form->get("product")->getData();

            $promotionService = $this->get(PromotionService::class);
            $promotionService->setPromotionToProduct($promotion, $products);

            $this->addFlash("success", "Promotion was set to the selected product/products");
            return $this->redirectToRoute("admin_view_promotions");

        }

        return $this->render("admin/promotions/single_product.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/promotion/set/all", name="admin_set_promotion_all_products")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @return Response
     */

    public function setPromotionToAllProductsAction(Request $request)
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();
        $form = $this->createForm(PromotionType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $promotion = $form->get("promotion")->getData();

            $promotionService = $this->get(PromotionService::class);
            $promotionService->setPromotionToAllProducts($promotion, $products);

            $this->addFlash("success", "Promotion was set to all products");
            return $this->redirectToRoute("admin_view_promotions");


        }

        return $this->render("admin/promotions/all_product.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/promotion/delete", name="admin_delete_promotion_products")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @return Response
     */

    public function deletePromotionFromProductsAction(Request $request)
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();

        $form = $this->createForm(PromotionType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $promotion = $form->get("promotion")->getData();

            $promotionService = $this->get(PromotionService::class);
            $promotionService->removePromotion($promotion,$products);

            $this->addFlash("success", "Promotion was deleted successfully");
            return $this->redirectToRoute("admin_view_promotions");

        }

        return $this->render("admin/promotions/all_product.html.twig", [
            "form" => $form->createView()
        ]);
    }
}
