<?php

namespace ShoppingCartBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="security_login")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function loginAction()
    {

        return $this->render('security/login.html.twig');
    }

    /**
     * This is the route the user can use to logout.
     *
     * But, this will never be executed. Symfony will intercept this first
     * and handle the logout automatically. See logout in app/config/security.yml
     *
     * @Route("/logout", name="security_logout")
     */

    public function logoutAction()
    {
        $this->addFlash("notice", "Logging out successfully completed!");
        return $this->redirectToRoute("homepage");
        //throw new \Exception('This should never be reached!');
    }
}
