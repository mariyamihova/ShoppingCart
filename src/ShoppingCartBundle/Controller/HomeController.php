<?php

namespace ShoppingCartBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class HomeController extends Controller
{

    public function indexAction()
    {
        return $this->redirect('products_list');
    }
}
