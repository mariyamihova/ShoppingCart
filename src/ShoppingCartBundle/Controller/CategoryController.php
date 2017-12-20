<?php

namespace ShoppingCartBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ShoppingCartBundle\Entity\Category;
use ShoppingCartBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{

    public function viewAllAction()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)
            ->findAllMainCategories();

        return $this->render("category/categories.html.twig", ["categories" => $categories]);
    }

    /**
     * @Route("/category/{id}", name="subcategories_by_category", requirements={"id"="\d+"})
     * @Method("GET")
     *
     * @param Category $category
     * @param Request $request
     * @return Response
     */

    public function viewSubcategoriesAction(Category $category,Request $request)
    {
        $subcategories = $this->getDoctrine()->getRepository(Category::class)
            ->findAllSubCategories($category);

        $pager = $this->get('knp_paginator');

        /** @var ArrayCollection|Product[] $products */
        $productsInMainCat = $pager->paginate(
            $category->getProducts(),
            $request->query->getInt('page', 1),
            9
        );

        return $this->render("category/subcategories.html.twig",["subcategories"=>$subcategories,
            "products"=>$productsInMainCat,
            "category"=>$category]);
    }

}
