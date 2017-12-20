<?php

namespace ShoppingCartBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ShoppingCartBundle\Entity\Category;
use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Entity\ProductReview;
use ShoppingCartBundle\Form\ProductReviewForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class ProductController extends Controller
{
    /**
     * @Route("", name="products_list")
     * @Route("", name="homepage")
     * @Method("GET")
     *
     * @param Request $request
     * @return Response
     */

    public function viewAllAction(Request $request)
    {

        $pager = $this->get('knp_paginator');

        /** @var ArrayCollection|Product[] $products */
        $products = $pager->paginate(
            $this->getDoctrine()->getRepository(Product::class)
                ->findShopProducts(),
            $request->query->getInt('page', 1),
            9
        );
        return $this->render("product/products.html.twig", ["products"=>$products]);

    }

    /**
     * @Route("/product/{id}", name="view_product", requirements={"id"="\d+"})
     * @Method("GET")
     *
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function viewProductAction(Product $product)
    {

        return $this->render('product/product_info.html.twig', ['product' => $product,
            "reviews" => $product->getReviews()]);

    }

    /**
     * @Route("/category/{id}/", name="products_by_category", requirements={"id"="\d+"})
     * @Method("GET")
     *
     * @param Category $category
     * @param Request $request
     * @return Response
     */

    public function listCategoryAction(Category $category,Request $request)
    {
        $pager = $this->get('knp_paginator');

        /** @var ArrayCollection|Product[] $products */
        $products = $pager->paginate(
            $this->getDoctrine()->getRepository(Product::class)
                ->findAllBySubCategory($category),
            $request->query->getInt('page', 1),
            9
        );

        return $this->render("product/list_by_category.html.twig", [
            "products" => $products,
            "category" => $category
        ]);
    }

    /**
     * @Route("/products/latest", name="latest_products_list")
     * @Method("GET")
     *
     * @return Response
     */

    public function viewLastProductsAction()
    {
        $products = $this->getDoctrine()->getRepository(Product::class)
            ->findLastProducts(5);

        return $this->render("product/latest_products.html.twig", ["products" => $products]);
    }

    /**
     * @Route("/products/sales/", name="view_user_sales")
     * @Security(expression="is_granted('IS_AUTHENTICATED_FULLY')")
     * @Method("GET")
     *
     * @param Request $request
     * @return Response
     */

    public function viewUserSales(Request $request)
    {
        $pager = $this->get('knp_paginator');

        /** @var ArrayCollection|Product[] $products */
        $products = $pager->paginate(
            $this->getDoctrine()->getRepository(Product::class)
                ->findUserSales(),
            $request->query->getInt('page', 1),
            6
        );

        return $this->render("product/user_sales_products.html.twig", ["products" => $products]);
    }

    /**
     * @Route("product/{id}/review", name="product_add_review", requirements={"id"="\d+"})
     * @Security(expression="is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Product $product
     * @param Request $request
     * @return Response
     */
    public function addReviewAction(Product $product, Request $request)
    {

        $productReview = new ProductReview();

        $form = $this->createForm(ProductReviewForm::class, $productReview);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $productReview->setUser($this->getUser());
            $productReview->setProduct($product);
            $productReview->setDate(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($productReview);
            $em->flush();

            $this->addFlash("success", "Review added successfully!");
            return $this->redirectToRoute("view_product", ["id" => $product->getId()]);
        }
        return $this->render("product/add_review.html.twig", [
            "form" => $form->createView()
        ]);
    }
}
