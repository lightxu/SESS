<?php
namespace Stock\AdministrationBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class IndexController
{
    public function indexAction()
    {
        return $this->render('StockAdministrationBundle:Index:index.html.twig');
    }
}