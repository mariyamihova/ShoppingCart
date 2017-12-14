<?php

namespace ShoppingCartBundle\Controller;

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
     * @Route("/category/{id}", name="subcategories_by_category")
     * @Method("GET")
     *
     * @param Category $category
     * @return Response
     */

    public function viewSubcategoriesAction(Category $category)
    {
        $subcategories = $this->getDoctrine()->getRepository(Category::class)
            ->findAllSubCategories($category);

        return $this->render("category/subcategories.html.twig",["subcategories"=>$subcategories,
            "category"=>$category]);
    }

}
