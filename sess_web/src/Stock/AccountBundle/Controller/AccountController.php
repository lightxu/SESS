<?php
namespace Stock\AccountBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AccountController extends Controller
{
    /*public function indexAction()
    {
        return $this->render('StockAccountBundle:Account:index.html.twig');
    }*/
    //show the web page of opening an account of a person
    public function openPersonalAction()
    {
        return $this->render('StockAccountBundle:Account:OpenPerson.html.twig');
    }
    //show the web page of opening an account of a company
    public function openCompanyAction()
    {
        return $this->render('StockAccountBundle:Account:OpenCompany.html.twig');
    }
    //show the web page of confirming the information of a person
    public function confirmPersonalAction()
    {
        return $this->render('StockAccountBundle:Account:ConfirmPerson.html.twig');
    }
    //show the web page of confirming the information of a company
    public function confirmCompanyAction()
    {
        return $this->render('StockAccountBundle:Account:ConfirmCompany.html.twig');
    }
    //show the web page of reporting the loss of the account
    public function reportLossAction()
    {
        return $this->render('StockAccountBundle:Account:ReportLoss.html.twig');
    }
    //show the web page of registering a new account
    public function postRegisterAction()
    {
        return $this->render('StockAccountBundle:Account:PostRegister.html.twig');
    }
    //show the web page of cancelling the account
    public function cancelAction()
    {
        return $this->render('StockAccountBundle:Account:AccountCancel.html.twig');
    }
    
    //define the error code for later use
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
    
    //the api for opening an account for a person
    public function openPersonalApiAction(Request $request)
    {
        $type = $request->request->get('type');
        $name  = $request->request->get('name');
        $gender = $request->request->get('gender');
        $id_no = $request->request->get('id_no');
        $address = $request->request->get('address');
        $phone = $request->request->get('phone');
        $token = $request->request->get('token');
        $job = $request->request->get('job');
        $education = $request->request->get('education');
        $company = $request->request->get('company');
        $commission_id = $request->request->get('commission_id');
        
        
        //TODO: accessing db
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
    
    //the api for opening an account for a company
    public function openCompanyApiAction(Request $request)
    {
        $type = $request->request->get('type');
        $name  = $request->request->get('name');
        $gender = $request->request->get('gender');
        $id_no = $request->request->get('id_no');
        $address = $request->request->get('address');
        $phone = $request->request->get('phone');
        $token = $request->request->get('token');
        $register_id = $request->request->get('register_id');
        $license_id = $request->request->get('license_id');
        $auth_name = $request->request->get('auth_name');
        $auth_id = $request->request->get('auth_id');
        $auth_phone = $request->request->get('auth_phone');
        $auth_address = $request->request->get('auth_address');
        
        //TODO: accessing db
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
    
    //the api for confirming the information of the person
    public function confirmPersonalApiAction(Request $request)
    {
        $token = $request->request->get('token');
        //TODO: accessing db
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
    
    //the api for confirming the information of the company
    public function confirmCompanyApiAction(Request $request)
    {
        $token = $request->request->get('token');
        //TODO: accessing db
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
    
    //the api for reporting the loss of the account
    public function reportLossApiAction(Request $request)
    {
        $id  = $request->request->get('id');
        $type = $request->request->get('type');
        $token = $request->request->get('token');
        
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
    
    //the api for register the account
    public function postRegisterApiAction(Request $request)
    {
        //TODO: accessing db
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
    
    //the api for cancelling an account
    public function cancelApiAction(Request $request)
    {
        $id  = $request->request->get('id');
        $type = $request->request->get('type');
        $token = $request->request->get('token');
        //TODO: accessing db
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