<?php

namespace ShoppingCartBundle\Controller\Admin;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Form\AddEditProductForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class ProductsController
 * @package ShoppingCartBundle\Controller\Admin
 *
 * @Route("/admin")
 * @Security("is_granted(['ROLE_EDITOR','ROLE_ADMIN'])")
 */
class ProductsController extends Controller
{
    /**
     * @Route("/products", name="admin_list_products")
     * @Method("GET")
     *
     * @return Response
     */
    public function listProductsAction()
    {

        $products = $this->getDoctrine()->getRepository(Product::class)
                ->findAll();
        return $this->render("admin/products/list.html.twig",
            ["products" =>$products]);
    }

    /**
     * @Route("/products/add", name="admin_add_product")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @return Response
     */
    public function addProductAction(Request $request)
    {
        $product=new Product();

        $form = $this->createForm(AddEditProductForm::class,$product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {

            $product->setUpdatedAt(new \DateTime());
            $product->setPromotionalPrice(0.00);

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash("success", "Product {$product->getName()} added successfully.");
            return $this->redirectToRoute("admin_list_products");
        }

        return $this->render("admin/products/add.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/products/edit/{id}", name="admin_edit_product")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Product $product
     * @return Response
     */

    public function editProductAction(Request $request, Product $product)
    {
        $form = $this->createForm(AddEditProductForm::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash("success", "Product {$product->getName()} edited successfully.");
            return $this->redirectToRoute("admin_list_products");
        }

        return $this->render("admin/products/edit.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/products/delete/{id}", name="admin_delete_product")
     * @Method("POST")
     *
     * @param Product $product
     * @return Response
     */
    public function deleteProductAction(Product $product)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        $this->addFlash("success", "Product {$product->getName()} was deleted successfully.");
        return $this->redirectToRoute("admin_list_products");
    }
}
