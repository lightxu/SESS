<?php

namespace StockAccount\IndexBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('StockAccountIndexBundle:Default:index.html.twig');
    }
}
