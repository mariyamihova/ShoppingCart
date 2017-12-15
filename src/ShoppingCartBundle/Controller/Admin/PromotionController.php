<?php

namespace ShoppingCartBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ShoppingCartBundle\Entity\Promotion;
use ShoppingCartBundle\Form\AddEditPromotionType;
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
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function viewAllAction()
    {
        $promotions=$this->getDoctrine()->getRepository(Promotion::class)->findAll();

        return $this->render("admin/promotions/list.html.twig",["promotions"=>$promotions]);
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

        if ($form->isSubmitted() && $form->isValid()) {
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
     * @Route("/promotion/edit/{id}", name="admin_edit_promotion")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Promotion $promotion
     * @return Response
     */

    public function editCategoryAction(Request $request,Promotion $promotion)
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
     * @Route("/promotion/delete/{id}", name="admin_delete_promotion")
     * @Method("POST")
     *
     * @param Promotion $promotion
     * @return Response
     */

    public function deleteCategoryAction(Promotion $promotion)
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($promotion);
        $em->flush();

        $this->addFlash("success", "Promotion {$promotion->getName()} was deleted!");
        return $this->redirectToRoute("admin_view_promotions");
    }
}
