<?php
namespace Stock\AdministrationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityController extends Controller
{
    public function loginAction()
    {
        return $this->render('StockAdministrationBundle:Security:login.html.twig');
    }
    
    public function loginCheckAction()
    {
    }
}