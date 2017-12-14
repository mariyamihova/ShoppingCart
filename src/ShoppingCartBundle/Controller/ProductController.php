<?php

namespace ShoppingCartBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ShoppingCartBundle\Entity\Category;
use ShoppingCartBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class ProductController extends Controller
{
    /**
     * @Route("", name="products_list")
     * @Method("GET")
     *
     * @return Response
     */

    public function viewAllAction()
    {
        $products=$this->getDoctrine()->getRepository(Product::class)->findShopProducts();

        return $this->render("product/products.html.twig",["products"=>$products]);
    }

    /**
     * @Route("/product/{id}", name="view_product")
     * @Method("GET")
     *
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function viewProductAction(Product $product)
    {

        return $this->render('product/product_info.html.twig', ['product' => $product]);

    }

    /**
     * @Route("/category/{id}/", name="products_by_category")
     * @Method("GET")
     *
     * @param Category $category
     * @return Response
     */

    public function listCategoryAction(Category $category)
    {

        $products = $this->getDoctrine()->getRepository(Product::class)
                ->findAllBySubCategory($category);

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
        $products=$this->getDoctrine()->getRepository(Product::class)
            ->findLastProducts(5);

        return $this->render("product/latest_products.html.twig",["products"=>$products]);
    }


}
