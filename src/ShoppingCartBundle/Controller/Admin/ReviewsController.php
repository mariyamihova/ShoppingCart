<?php

namespace ShoppingCartBundle\Controller\Admin;

use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ShoppingCartBundle\Entity\ProductReview;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class ReviewsController
 * @package ShoppingCartBundle\Controller\Admin
 *
 * @Route("/admin")
 * @Security("is_granted('ROLE_EDITOR')")
 *
 */
class ReviewsController extends Controller
{
    /**
     * @Route("/review/list", name="admin_view_reviews")
     * @Method("GET")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function viewAllAction(Request $request)
    {
        $pager = $this->get('knp_paginator');

        /** @var ArrayCollection|ProductReview[] $reviews */
        $reviews = $pager->paginate(
            $this->getDoctrine()->getRepository(ProductReview::class)->findAll(),
            $request->query->getInt('page', 1),
            9
        );


        return $this->render("admin/reviews/list.html.twig",["reviews"=>$reviews]);
    }

    /**
     * @Route("/review/delete/{id}", name="admin_delete_review", requirements={"id"="\d+"})
     * @Method("POST")
     *
     * @param ProductReview $review
     * @return Response
     */

    public function deleteReviewAction(ProductReview $review)
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($review);
        $em->flush();

        $this->addFlash("success", "Review was deleted!");
        return $this->redirectToRoute("admin_view_reviews");
    }

}
