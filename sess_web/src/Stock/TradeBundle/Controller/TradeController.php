<?php
namespace Stock\TradeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class TradeController extends Controller
{
    const STATUS_SUCCESS = "success";
    const STATUS_ARGUMENT_ERROR = "too few arguments";
    const STATUS_FORMAT_ERROR = "invalid arguments";
    const STATUS_UNAUTHORIZED_ERROR = "unauthorized";
    const STATUS_DB_ERROR = "database error";
    const STATUS_STOCK_ERROR = "insufficient amount";
    const STATUS_ACCOUNT_ERROR = "invalid account";
    const APP_KEY = "354DD0DE1AB36DC4531B8723C34B9EFE";
    
    private function makeResponse($status, $data=[])
    {
        $data["status"] = $status;
        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    
    public function deactivateAction(Request $request)
    {
        $account_id = $request->request->get('account_id');
        $stock_id   = $request->request->get('stock_id');
        $amount     = $request->request->get('amount');
        $token      = $request->request->get('token');
        
        if ($account_id == null || $stock_id == null ||
            $amount == null || $token == null)
            return $this->makeResponse(self::STATUS_ARGUMENT_ERROR);
        
        if (strcmp($token, self::APP_KEY) != 0)
            return $this->makeResponse(self::STATUS_UNAUTHORIZED_ERROR);
            
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
    
    public function activateAction(Request $request)
    {
        $account_id = $request->request->get('account_id');
        $stock_id   = $request->request->get('stock_id');
        $amount     = $request->request->get('amount');
        $token      = $request->request->get('token');
        
        if ($account_id == null || $stock_id == null ||
            $amount == null || $token == null)
            return $this->makeResponse(self::STATUS_ARGUMENT_ERROR);
        
        if (strcmp($token, self::APP_KEY) != 0)
            return $this->makeResponse(self::STATUS_UNAUTHORIZED_ERROR);
            
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
    
    public function tradeAction(Request $request)
    {
        $buyer_id = $request->request->get('buyer_id');
        $seller_id = $request->request->get('seller_id');
        $stock_id = $request->request->get('stock_id');
        $amount = $request->request->get('amount');
        $token  = $request->request->get('token');
        
        if ($buyer_id == null || $seller_id == null ||
            $stock_id == null || $amount == null ||
            $token == null)
            return $this->makeResponse(self::STATUS_ARGUMENT_ERROR);
        
        if (strcmp($token, self::APP_KEY) != 0)
            return $this->makeResponse(self::STATUS_UNAUTHORIZED_ERROR);
            
        // TODO: accessing db
        $operation_code = "1";
        $db_error = false;
        $stock_error = false;
        $account_error = false;
        if ($db_error)
            return $this->makeResponse(self::STATUS_DB_ERROR);
        if ($stock_error)
            return $this->makeResponse(self::STATUS_STOCK_ERROR);
        if ($account_error)
            return $this->makeResponse(self::STATUS_ACCOUNT_ERROR);
        
        return $this->makeResponse(self::STATUS_SUCCESS, array("operation_code" => $operation_code));
    }
    
    public function tradeConfirmAction(Request $request)
    {
        $operation_code = $request->request->get('operation_code');
        $token      = $request->request->get('token');
        
        if ($account_id == null || $stock_id == null ||
            $amount == null || $token == null)
            return $this->makeResponse(self::STATUS_ARGUMENT_ERROR);
        
        if (strcmp($token, self::APP_KEY) != 0)
            return $this->makeResponse(self::STATUS_UNAUTHORIZED_ERROR);
            
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
    
    public function tradeCancelAction(Request $request)
    {
        $operation_code = $request->request->get('operation_code');
        $token      = $request->request->get('token');
        
        if ($account_id == null || $stock_id == null ||
            $amount == null || $token == null)
            return $this->makeResponse(self::STATUS_ARGUMENT_ERROR);
        
        if (strcmp($token, self::APP_KEY) != 0)
            return $this->makeResponse(self::STATUS_UNAUTHORIZED_ERROR);
            
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
    
    public function stocksInfoAction(Request $request)
    {
        $account_id = $request->request->get('account_id');
        $token      = $request->request->get('token');
        
        if ($account_id == null || $token == null)
            return $this->makeResponse(self::STATUS_ARGUMENT_ERROR);
        
        if (strcmp($token, self::APP_KEY) != 0)
            return $this->makeResponse(self::STATUS_UNAUTHORIZED_ERROR);
            
        // TODO: accessing db
        $stock_info = array(array(["stock_id" => 1, "amount" => 1]));
        $stock_summary = 1;
        $db_error = false;
        $stock_error = false;
        $account_error = false;
        if ($db_error)
            return $this->makeResponse(self::STATUS_DB_ERROR);
        if ($stock_error)
            return $this->makeResponse(self::STATUS_STOCK_ERROR);
        if ($account_error)
            return $this->makeResponse(self::STATUS_ACCOUNT_ERROR);
        
        $data = array("stock_info" => $stock_info, "stock_summary" => $stock_summary);
        return $this->makeResponse(self::STATUS_SUCCESS, data);
    }
    
    public function checkAccountAction(Request $request)
    {
        $account_id = $request->request->get('account_id');
        $token      = $request->request->get('token');
        
        if ($account_id == null || $token == null)
            return $this->makeResponse(self::STATUS_ARGUMENT_ERROR);
        
        if (strcmp($token, self::APP_KEY) != 0)
            return $this->makeResponse(self::STATUS_UNAUTHORIZED_ERROR);
            
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
        
        return $this->makeResponse(self::STATUS_SUCCESS, data);
    }
    
    public function testApiAction(Request $request)
    {
        $test_arg = $request->query->get('test_arg');
        if ($test_arg != null)
            return $this->makeResponse(self::STATUS_SUCCESS, array('test_arg' => $test_arg));
        else
            return $this->render('StockTradeBundle:Trade:test_api.html.twig');
    }
}