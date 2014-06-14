<?php
namespace Stock\AccountBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AccountController extends Controller
{
    /*public function indexAction()
    {
        return $this->render('StockAccountBundle:Account:index.html.twig');
    }*/
    public function openPersonalAction()
    {
        return $this->render('StockAccountBundle:Account:OpenPerson.html.twig');
    }
    public function openCompanyAction()
    {
        return $this->render('StockAccountBundle:Account:OpenCompany.html.twig');
    }
    public function confirmPersonalAction()
    {
        return $this->render('StockAccountBundle:Account:ConfirmPerson.html.twig');
    }
    public function confirmCompanyAction()
    {
        return $this->render('StockAccountBundle:Account:ConfirmCompany.html.twig');
    }
    public function reportLossAction()
    {
        return $this->render('StockAccountBundle:Account:ReportLoss.html.twig');
    }
    public function postRegisterAction()
    {
        return $this->render('StockAccountBundle:Account:PostRegister.html.twig');
    }
    public function cancelAction()
    {
        return $this->render('StockAccountBundle:Account:AccountCancel.html.twig');
    }
}