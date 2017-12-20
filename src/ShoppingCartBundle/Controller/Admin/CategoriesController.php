<?php

namespace ShoppingCartBundle\Controller\Admin;

use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ShoppingCartBundle\Entity\Category;
use ShoppingCartBundle\Form\AddEditCategoryForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CategoriesController
 * @package ShoppingCartBundle\Controller\Admin
 *
 * @Route("/admin")
 * @Security("is_granted(['ROLE_EDITOR','ROLE_ADMIN'])")
 *
 */
class CategoriesController extends Controller
{
    /**
     * @Route("/categories", name="admin_view_categories")
     * @Method("GET")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function viewAllAction(Request $request)
    {
        $pager = $this->get('knp_paginator');

        /** @var ArrayCollection|Category[] $categories */
        $categories = $pager->paginate(
            $this->getDoctrine()->getRepository(Category::class)->findAll(),
            $request->query->getInt('page', 1),
            9
        );

        return $this->render("admin/categories/list.html.twig",["categories"=>$categories]);
    }

    /**
     * @Route("/categories/add", name="admin_add_category")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function addCategoryAction(Request $request)
    {

        $category=new Category();


        $form=$this->createForm(AddEditCategoryForm::class,$category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->addFlash("success", "Category {$category->getName()} was added!");
            return $this->redirectToRoute("admin_view_categories");
        }

        return $this->render("admin/categories/add.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/categories/edit/{id}", name="admin_edit_category", requirements={"id"="\d+"})
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Category $category
     * @return Response
     */

    public function editCategoryAction(Request $request,Category $category)
    {

        $form = $this->createForm(AddEditCategoryForm::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->addFlash("success", "Category {$category->getName()} was updated!");
            return $this->redirectToRoute("admin_view_categories");
        }

        return $this->render("admin/categories/edit.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/categories/delete/{id}", name="admin_delete_category",requirements={"id"="\d+"})
     * @Method("POST")
     *
     * @param Category $category
     * @return Response
     */

    public function deleteCategoryAction(Category $category)
    {
        if ($category->getProducts()->count() > 0)
        {

            $this->addFlash("danger", "Category with products in it cannot be deleted.");
            return $this->redirectToRoute("admin_view_categories");
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();

        $this->addFlash("success", "Category {$category->getName()} was deleted!");
        return $this->redirectToRoute("admin_view_categories");
    }
}
