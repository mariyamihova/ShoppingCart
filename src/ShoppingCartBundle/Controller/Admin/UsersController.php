<?php

namespace ShoppingCartBundle\Controller\Admin;

use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ShoppingCartBundle\Entity\User;
use ShoppingCartBundle\Form\AddEditUserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UsersController
 * @package ShoppingCartBundle\Controller\Admin
 *
 * @Route("/admin")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class UsersController extends Controller
{
    /**
     * @Route("/users", name="admin_list_users")
     * @Method("GET")
     *
     * @param Request $request
     * @return Response
     */

    public function listUsersAction(Request $request)
    {
        $pager = $this->get('knp_paginator');

        /** @var ArrayCollection|User[] $users */
        $users = $pager->paginate(
            $this->getDoctrine()->getRepository(User::class)->findAll(),
            $request->query->getInt('page', 1),
            9
        );

        return $this->render("admin/users/list.html.twig", ["users"=>$users]);
    }

    /**
     * @Route("/users/add", name="admin_add_user")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @return Response
     */
    public function addUserAction(Request $request)
    {
        $user=new User();
        $form=$this->createForm(AddEditUserType::class,$user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash("success", "user {$user->getEmail()} added successfully.");
            return $this->redirectToRoute("admin_list_users");
        }

        return $this->render("admin/users/add.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/users/edit/{id}", name="admin_edit_user", requirements={"id"="\d+"})
     * @Method({"GET", "POST"})
     *
     * @param User $user
     * @param Request $request
     * @return Response
     */
    public function editUserAction(Request $request, User $user)
    {

        $form=$this->createForm(AddEditUserType::class,$user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {

            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash("success", "user {$user->getEmail()} edited successfully.");
            return $this->redirectToRoute("admin_list_users");
        }

        return $this->render("admin/users/edit.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/users/delete/{id}", name="admin_delete_user", requirements={"id"="\d+"})
     * @Method("POST")
     *
     * @param User $user
     * @return Response
     */

    public function deleteUserAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        $this->addFlash("success", "user {$user->getEmail()} was deleted successfully.");
        return $this->redirectToRoute("admin_list_users");
    }
}
