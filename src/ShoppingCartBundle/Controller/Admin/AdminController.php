<?php

namespace ShoppingCartBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * Class AdminController
 * @package ShoppingCartBundle\Controller\Admin
 *
 * @Route("/admin")
 * @Security("is_granted(['ROLE_EDITOR','ROLE_ADMIN'])")
 */
class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin_homepage")
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render("default/index.html.twig",[]);
    }
}
