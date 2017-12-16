<?php

namespace ShoppingCartBundle\Controller;


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
     * @Route("/products", name="user_owned_products")
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function viewAllAction()
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        $products = $this->getDoctrine()->getRepository(Product::class)
            ->findProductsBySeller($currentUser);

        return $this->render("user/owner.html.twig", ["products" => $products]);

    }

    /**
     * @Route("/sell/{id}", name="sell_user_product")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Product $product
     * @return Response
     */
    public function sellProductAction(Request $request, Product $product)
    {

        $newProduct = new Product();
        $newProduct->setName($product->getName());
        $newProduct->setCategory($product->getCategory());
        $newProduct->setDescription($product->getDescription());
        $newProduct->setQuantity(1);
        $newProduct->setPriority(1);
        $newProduct->setPrice($product->getPrice());
        $newProduct->setUpdatedAt(new \DateTime());
        $newProduct->setImageUrl($product->getImageUrl());
        $newProduct->setSeller($this->getUser());

        $form = $this->createForm(ProductSellType::class, $newProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

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
}
