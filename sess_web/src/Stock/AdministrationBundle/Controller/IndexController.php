<?php
namespace Stock\AdministrationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        return $this->render('StockAdministrationBundle:Index:index.html.twig');
    }
}