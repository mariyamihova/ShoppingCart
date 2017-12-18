<?php

namespace ShoppingCartBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ShoppingCartBundle\Entity\ProductReview;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function viewAllAction()
    {
        $reviews=$this->getDoctrine()->getRepository(ProductReview::class)->findAll();

        return $this->render("admin/reviews/list.html.twig",["reviews"=>$reviews]);
    }

    /**
     * @Route("/review/delete/{id}", name="admin_delete_review")
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
