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
    
    const STATUS_SUCCESS = "success";
    const STATUS_ARGUMENT_ERROR = "too few arguments";
    const STATUS_FORMAT_ERROR = "invalid arguments";
    const STATUS_UNAUTHORIZED_ERROR = "unauthorized";
    const STATUS_DB_ERROR = "database error";
    const STATUS_STOCK_ERROR = "insufficient amount";
    const STATUS_ACCOUNT_ERROR = "invalid account";
    
    private function makeResponse($status, $data=[])
    {
        $data["status"] = $status;
        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    
    public function reportLossApiAction(Request $request)
    {
        $id  = $request->request->get('id');
        $type = $request->request->get('type');
        $token      = $request->request->get('token');
        
        if ($id == null || $type == null || $token == null)
            return $this->makeResponse(self::STATUS_ARGUMENT_ERROR);
        
        // Notice that token for each admin should be different
        // This will be implemented later
        // TODO: verify token
        $token_error = false;
        if ($token_error)
            return $this->makeResponse(self::STATUS_UNAUTHORIZED_ERROR);
        
        if (strcmp($type, "personal") != 0 && strcmp($type, "company") != 0)                
            return $this->makeResponse(self::STATUS_FORMAT_ERROR);
            
        // TODO: accessing db
        $db_error = false;
        $stock_error = false;
        $account_error = false;
        if ($db_error)
            return $this->makeResponse(self::STATUS_DB_ERROR);
        if ($stock_error)
            return $this->makeResponse(self::STATUS_STOCK_ERROR);
        if ($account_error)
            return $this->makeResponse(self::STATUS_ACCOUNT_ERROR);
        
        return $this->makeResponse(self::STATUS_SUCCESS);
    }
}