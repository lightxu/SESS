<?php
namespace Stock\TradeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Stock\TradeBundle\Entity\Stock;
use Stock\TradeBundle\Entity\TradeRecord;

class TradeController extends Controller
{
    const STATUS_SUCCESS = "success";
    const STATUS_ARGUMENT_ERROR = "too few arguments";
    const STATUS_FORMAT_ERROR = "invalid arguments";
    const STATUS_UNAUTHORIZED_ERROR = "unauthorized";
    const STATUS_STOCK_ERROR = "insufficient amount";
    const STATUS_ACCOUNT_ERROR = "invalid account";
    const STATUS_TRADE_ERROR = "no such trade record";
    const APP_KEY = "354DD0DE1AB36DC4531B8723C34B9EFE";
    
    private function makeResponse($status, $data=array())
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
            
        $status = updateStockFrozenAmount($account_id, $stock_id, -$amount);
        
        return $this->makeResponse($status);
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
            
        $status = updateStockFrozenAmount($account_id, $stock_id, $amount);
        
        return $this->makeResponse($status);
    }
    
    public function tradeAction(Request $request)
    {
        $buyer_id = $request->request->get('buyer_id');
        $seller_id = $request->request->get('seller_id');
        $stock_id = $request->request->get('stock_id');
        $amount = $request->request->get('amount');
        $price = $request->request->get('price');
        $token  = $request->request->get('token');
        
        if (($buyer_id == null && $seller_id == null) ||
            $stock_id == null || $price == null ||
            $amount == null || $amount <= 0 || $token == null)
            return $this->makeResponse(self::STATUS_ARGUMENT_ERROR);
            
        if (strcmp($token, self::APP_KEY) != 0)
            return $this->makeResponse(self::STATUS_UNAUTHORIZED_ERROR);
        
        if ($buyer_id != null)
        {
            $status = updateStockTotalAmountCheck($buyer_id, $stock_id, $amount);
            if (strcmp($status, self::STATUS_SUCCESS) != 0)
                return $this->makeResponse($status);
        }
        else
            $buyer_id = "";
        
        if ($seller_id != null)
        {
            $status = updateStockTotalAmountCheck($seller_id, $stock_id, -$amount);
            if (strcmp($status, self::STATUS_SUCCESS) != 0)
                return $this->makeResponse($status);
        }
        else
            $seller_id = "";
            
        $operation_code = createTradeRecord($buyer_id, $seller_id, $stock_id, $amount, $price);
        return $this->makeResponse(self::STATUS_SUCCESS, array("operation_code" => $operation_code));
    }
    
    public function tradeConfirmAction(Request $request)
    {
        $operation_code = $request->request->get('operation_code');
        $token      = $request->request->get('token');
        
        if ($operation_code == null || $token == null)
            return $this->makeResponse(self::STATUS_ARGUMENT_ERROR);
        
        if (strcmp($token, self::APP_KEY) != 0)
            return $this->makeResponse(self::STATUS_UNAUTHORIZED_ERROR);
            
        $trade_record = showTradeRecord($operation_code);
        
        if (strcmp($trade_record["status"], self::STATUS_SUCCESS) != 0)
            return $this->makeResponse($trade_record["status"]);
        
        $buyer_id = $trade_record["buyer_id"];
        $seller_id = $trade_record["seller_id"];
        $stock_id = $trade_record["stock_id"];
        $amount = $trade_record["amount"];
        $price = $trade_record["price"];
        if (strcmp($buyer_id, "") != 0)
        {
            updateStockTotalAmount($buyer_id, $stock_id, $amount);
            updateHoldCost($buyer_id, $stock_id, $amount, $price);
        }
        if (strcmp($seller_id, "") != 0)
        {
            updateStockTotalAmount($seller_id, $stock_id, -$amount, $price);
            updateHoldCost($seller_id, $stock_id, -$amount, -$price);
        }
        return $this->makeResponse(self::STATUS_SUCCESS);
    }
    
    public function tradeCancelAction(Request $request)
    {
        $operation_code = $request->request->get('operation_code');
        $token      = $request->request->get('token');
        
        $status = deleteTradeRecord($operation_code);
        return $this->makeResponse($status);
    }
    
    public function stocksInfoAction(Request $request)
    {
        $account_id = $request->request->get('account_id');
        $token      = $request->request->get('token');
        
        if ($account_id == null || $token == null)
            return $this->makeResponse(self::STATUS_ARGUMENT_ERROR);
        
        if (strcmp($token, self::APP_KEY) != 0)
            return $this->makeResponse(self::STATUS_UNAUTHORIZED_ERROR);
            
        $stock_array = showStock($account_id);
        if (strcmp($stock_array["status"], self::STATUS_SUCCESS) != 0)
            return $this->makeResponse($stock_array["status"]);
        else
            return $this->makeResponse($stock_array["status"], $stock_array);
    }
    
    public function checkAccountAction(Request $request)
    {
        $content = $request->getContent();
        if (!empty($content))
            $params = json_decode($content, true);
        else
            return $this->makeResponse(self::STATUS_ARGUMENT_ERROR);
        
        $account_id = $params['account_id'];
        $token      = $params['token'];
        
        if ($account_id == null || $token == null)
            return $this->makeResponse(self::STATUS_ARGUMENT_ERROR);
        
        if (strcmp($token, self::APP_KEY) != 0)
            return $this->makeResponse(self::STATUS_UNAUTHORIZED_ERROR);
        
        $status = $this->checkStockAccount($account_id);
        return $this->makeResponse($status);
    }
    
    public function testApiAction(Request $request)
    {
        $test_arg = $request->query->get('test_arg');
        if ($test_arg != null)
            return $this->makeResponse(self::STATUS_SUCCESS, array('test_arg' => $test_arg));
        else
            return $this->render('StockTradeBundle:Trade:test_api.html.twig');
    }
    
    // Database Functions
    public function createStock($stock)
    {
        $stock = new Stock();
        $stock->setAccountId($stock["account_id"]);
        $stock->setStockId($stock["stock_id"]);
        $stock->setTotalAmount(intval($stock["amount"]));
        $stock->setFrozenAmount(0);
        
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($stock);
        $em->flush();
    }
    
    public function showStock($account_id)
    {
        $stocks = $this->getDoctrine()
             ->getRepository('StockTradeBundle:Stock')
             ->findAllByAccountId($account_id);
        $hold_cost = $this->getDoctrine()
             ->getRepository('StockTradeBundle:HoldCost')
             ->find($account_id);
        $stock_array = array();
        $stock_array["status"] = self::STATUS_SUCCESS;
        $stock_array["hold_cost"] = $hold_cost->getHoldCost();
        $stock_array["stock_info"] = array();
        foreach ($stocks as $stock)
        {
            array_push($stock_array["stock_info"], array(
                "stock_id" => $stock->getStockId(),
                "total_amount" => $stock->getTotalAmount()
            ));
        }
        return $stock_array;
    }
    
    public function updateStockFrozenAmount($account_id, $stock_id, $frozen_amount)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $stock = $em->getRepository('StockTradeBundle:Stock')
            ->findOneBy(array(
                "accountId" => $account_id,
                "stockId" => $stock_id
            ));
        if (!$stock)
            return self::STATUS_ACCOUNT_ERROR;
            
        $current_amount = $stock->getFrozenAmount();
        $frozen_amount += $current_amount;
        if ($frozen_amount < 0 || $frozen_amount > $stock->getTotalAmount())
            return self::STATUS_STOCK_ERROR;
            
        $stock->setFrozenAmount($frozen_amount);
        $em->flush();
        return self::STATUS_SUCCESS;
    }
    
    public function updateStockTotalAmountCheck($account_id, $stock_id, $update_amount)
    {
        $status = checkStockAccount($account_id);
        if (strcmp($status, self::STATUS_SUCCESS) != 0)
            return $status;
            
        $stock = $this->getDoctrine()
            ->getRepository('StockTradeBundle:Stock')
            ->findOneBy(array(
                "accountId" => $account_id,
                "stockId" => $stock_id
            ));
            
        // update frozen amount, if it is selling.
        // as the selling requires deactivate first.
        if ($update_amount < 0)
            if (!$stock || $stock->getFrozenAmount() + $update_amount < 0)
                return self::STATUS_STOCK_ERROR;
        return self::STATUS_SUCCESS;
    }

    public function updateStockTotalAmount($account_id, $stock_id, $update_amount)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $stock = $em->getRepository('StockTradeBundle:Stock')
            ->findOneBy(array(
                "accountId" => $account_id,
                "stockId" => $stock_id
            ));
        if (!$stock)
        {
            $stock = new Stock();
            $stock->setAccountId($account_id);
            $stock->setStockId($stock_id);
            $stock->setAmount($update_amount);
            $em->persist($stock);
        }
        else
        {
            // update frozen amount, if it is selling.
            // as the selling requires deactivate first.
            // here we will not check the amount.
            if ($update_amount < 0)
            {
                $frozen_amount = $stock->getFrozenAmount();
                $frozen_amount += $update_amount;
                $stock->setFrozenAmount($frozen_amount);
            }
            $total_amount = $stock->getTotalAmount();
            $total_amount += $update_amount;
            $stock->setTotalAmount($total_amount);
            if ($total_amount == 0)
                $em->remove($stock);
        }
        $em->flush();
        return self::STATUS_SUCCESS;
    }
    
    public function createTradeRecord($buyer_id, $seller_id, $stock_id, $amount, $price)
    {
        $trade_record = new TradeRecord();
        $trade_record.setBuyerId($buyer_id);
        $trade_record.setSellerId($seller_id);
        $trade_record.setStockId($stock_id);
        $trade_record.setAmount($amount);
        $trade_record.setPrice($price);
        
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($trade_record);
        $em->flush();
        return $trade_record.getId();
    }
    
    public function showTradeRecord($id)
    {
        $trade_record = $this->getDoctrine()
            ->getRepository('StockTradeBundle:TradeRecord')
            ->find($id);
        if ($trade_record == null)
            return array("status" => self::STATUS_TRADE_ERROR);
        $record = array();
        $record["buyer_id"] = $record->getBuyerId();
        $record["seller_id"] = $record->getSellerId();
        $record["stock_id"] = $record->getStockId();
        $record["amount"] = $record->getAmount();
        $record["price"] = $record->getPrice();
        $record["status"] = self::STATUS_SUCCESS;
        return $record;
    }
    
    public function deleteTradeRecord($id)
    {
        $trade_record = $this->getDoctrine()
            ->getRepository('StockTradeBundle:TradeRecord')
            ->find($id);
        if ($trade_record == null)
            return status::STATUS_TRADE_ERROR;
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($trade_record);
        $em->flush();
        return status::STATUS_SUCCESS;
    }
    
    public function checkStockAccount($account_id)
    {
        $hold_cost = $this->getDoctrine()
            ->getRepository('StockTradeBundle:HoldCost')
            ->find($account_id);
        if ($hold_cost == null)
            return self::STATUS_ACCOUNT_ERROR;
        else
            return self::STATUS_SUCCESS;
        
    }
    
    public function updateHoldCost($account_id, $stock_id, $update_amount, $price)
    {
        $em = $this->getDoctrine();
        $hold_cost = $em->getRepository('StockTradeBundle:HoldCost')
            ->find($account_id);
        if ($customer == null)
            return self::STATUS_ACCOUNT_ERROR;
        $cost = $hold_cost->getHoldCost();
        $old_amount = $hold_cost->getTotalAmount();
        $total_amount = $old_amount + $update_amount;
        $cost = ($cost * $old_amount + $price * $total_amount) / $total_amount;
        $hold_cost->setHoldCost($cost);
        $hold_cost->setTotalAmount($total_amount);
        $em->flush();
        return status::STATUS_SUCCESS;
    }
}