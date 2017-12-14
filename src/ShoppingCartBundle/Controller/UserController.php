<?php

namespace ShoppingCartBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ShoppingCartBundle\Entity\Role;
use ShoppingCartBundle\Entity\User;
use ShoppingCartBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;



class UserController extends Controller
{
    /**
     * @Route("user/register", name="user_register")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid())
        {
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $roleRepo = $this->getDoctrine()->getRepository(Role::class);
            $userRole = $roleRepo->findOneBy(['name' => 'ROLE_USER']);
            $user->addRole($userRole);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash("notice", "Registration successfully completed!");
            return $this->redirectToRoute("security_login");

        }
        return $this->render('user/register.html.twig',
            ['form' => $form->createView(),
                "user"=>$user]);
    }

    /**
     * @Route("user/profile", name="user_profile")
     * @Method("GET")
     *
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     */

    public function viewProfileAction()
    {
        $user = $this->getUser();
        return $this->render("user/profile.html.twig", ['user' => $user]);
    }

    /**
     * @Route("user/profile/edit", name="user_profile_edit")
     * @Method({"GET", "POST"})
     * @Security(expression="is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Request $request
     * @return Response
     */

    public function editProfileAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash("notice", "Profile has been edited successfully!");
            return $this->redirectToRoute('user_profile');
        }

        return $this->render("user/edit.html.twig", [
            "form" => $form->createView(),
            "user" => $user]);
    }
}
