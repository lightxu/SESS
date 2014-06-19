<?php
namespace Stock\AdministrationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends Controller
{
    public function indexAction(Request $request)
    {
        $notice = $request->query->get("notice");
        $admin = $this->getUser();
        return $this->render('StockAdministrationBundle:Index:index.html.twig', array("username" => $admin->getUsername(), "bankname" => $admin->getBankname()));
    }
}