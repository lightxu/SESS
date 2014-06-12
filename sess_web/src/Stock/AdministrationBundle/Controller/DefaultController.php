<?php

namespace Stock\AdministrationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('StockAdministrationBundle:Default:index.html.twig', array('name' => $name));
    }
}
