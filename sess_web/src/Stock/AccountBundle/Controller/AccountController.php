<?php
namespace Stock\AccountBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Stock\AccountBundle\Entity\NaturalCustomer;
use Stock\AccountBundle\Entity\LegalCheck;
use Stock\AccounteBundle\Entity\LegalCustomer;
use Stock\AccountBundle\Entity\Personnel;

use Stock\AccountBundle\Entity;

class AccountController extends Controller
{
    //show the web page of opening an account of a person
    public function openPersonalAction(Request $request)
    {
        $type = $request->query->get('type');
        if ($type == null)
            $type = 'open';
        if (strcmp($type, 'edit') == 0 || strcmp($type, 'reopen') == 0)
        {
            $customer_id = $request->query->get('customer_id');
            if ($customer_id == null)
                throw $this->createNotFoundException('No natural customer found for customer_id' . $customer_id);
            $customer = $this->showNaturalCustomerAction($customer_id);
            $this->removeNaturalCustomerAction($customer_id);
            return $this->render('StockAccountBundle:Account:OpenPerson.html.twig', array("is_open" => false, "customer" => $customer));
        }
        else
            return $this->render('StockAccountBundle:Account:OpenPerson.html.twig', array("is_open" => true));
    }
    //show the web page of opening an account of a company
    public function openCompanyAction()
    {
        return $this->render('StockAccountBundle:Account:OpenCompany.html.twig');
    }
    //show the web page of confirming the information of a person
    public function confirmPersonalAction(Request $request)
    {
        $customer_id = $request->query->get('customer_id');
        if ($customer_id == null)
            return $this->makeResponse(STATUS_ARGUMENT_ERROR);
        $customer = $this->showNaturalCustomerAction($customer_id);
        // return new Response(var_dump($customer));
        return $this->render('StockAccountBundle:Account:ConfirmPerson.html.twig', $customer);
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
    const STATUS_DB_ERROR = "database error";
    const STATUS_STOCK_ERROR = "insufficient amount";
    const STATUS_ACCOUNT_ERROR = "invalid account";
    
    private function makeResponse($status)
    {
        $data["status"] = $status;
        return new Response('<h1>'.$status.'</h1>');
    }
    
    //the api for opening an account for a person
    public function openPersonalApiAction(Request $request)
    {
        $customer = array();
        $customer['id'] = "P" . time();
        while ($this->checkNaturalCustomerAction($customer['id']))
            $customer['id'] = "P" . time();
        
        $customer['name']  = $request->request->get('name');
        $customer['id_number'] = $request->request->get('id_number');
        $customer['register_date'] = new \DateTime();
        $customer['gender'] = $request->request->get('gender');
        $customer['address'] = $request->request->get('address');
        $customer['occupation'] = $request->request->get('occupation');
        $customer['educational_background'] = $request->request->get('educational_background');
        $customer['company_or_organization'] = $request->request->get('company_or_organization');
        $customer['tel'] = $request->request->get('tel');
        // TODO: get agent information
        $customer['agent_id'] = '1';
        $customer['bank'] = 'Laohe Branch';
        
        // Check arguments existence
        foreach ($customer as $key => $value)
            if ($value == null)
                return $this->makeResponse(self::STATUS_FORMAT_ERROR . " " . $key);
        
        // Check personnel
        if ($this->checkPersonnel($customer['id_number']))
            return $this->makeResponse(self::STATUS_ACCOUNT_ERROR);
            
        // Check age
        // TODO
        
        $db_error = $this->createNaturalCustomerAction($customer);
        if ($db_error)
            return $this->makeResponse(self::STATUS_DB_ERROR);
        
        return $this->redirect($this->generateUrl('confirmPersonal_page') . '?customer_id=' . $customer['id']);
    }
    
    //the api for opening an account for a company
    public function openCompanyApiAction(Request $request)
    {
        $customer = array();
        $customer['type'] = $request->request->get('type');
        $customer['name']  = $request->request->get('name');
        $customer['gender'] = $request->request->get('gender');
        $customer['id_no'] = $request->request->get('id_no');
        $customer['address'] = $request->request->get('address');
        $customer['phone'] = $request->request->get('phone');
        $customer['token'] = $request->request->get('token');
        $customer['register_id'] = $request->request->get('register_id');
        $customer['license_id'] = $request->request->get('license_id');
        $customer['auth_name'] = $request->request->get('auth_name');
        $customer['auth_id'] = $request->request->get('auth_id');
        $customer['auth_phone'] = $request->request->get('auth_phone');
        $customer['auth_address'] = $request->request->get('auth_address');
        $this->createNaturalCustomer($customer);
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
    public function confirmPersonalApiAction(Request $request)
    {
        $customer_id = $request->query->get('customer_id');
        $confirm = $request->query->get('confirm');
        if ($confirm == 1)
        {
            $this->updateNaturalCustomerAction($customer_id, false);
            return $this->redirect($this->generateUrl('index') . "?notice=个人证券帐户创建成功，id为" . $customer_id);
        }
        else
            return $this->redirect($this->generateUrl('openPersonal_page') . '?type=edit&customer_id=' . $customer_id);
    }
    
    //the api for confirming the information of the person
    public function confirmCompanyApiAction(Request $request)
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
    
    private function checkPersonnel($id_number)
    {
        $natural_customer = $this->getDoctrine()
                 ->getRepository('StockAccountBundle:Personnel')
                 ->find($id_number);
        if ($natural_customer)
            return true;
        else
            return false;
    }
    
    //database operations
    //create a natural customer
    private function createNaturalCustomerAction($customer)
    {
		//pass parameters
		$natural_customer = new NaturalCustomer();
        $natural_customer->setCustomerId($customer['id']);
        $natural_customer->setName($customer['name']);
        $natural_customer->setIdNumber($customer['id_number']);
        $natural_customer->setRegisterDate($customer['register_date']);
        $natural_customer->setGender($customer['gender']);
        $natural_customer->setAddress($customer['address']);
        $natural_customer->setOccupation($customer['occupation']);
        $natural_customer->setEducationalBackground($customer['educational_background']);
        $natural_customer->setCompanyOrOrganization($customer['company_or_organization']);
        $natural_customer->setTel($customer['tel']);
        $natural_customer->setAgentId($customer['agent_id']);
        $natural_customer->setBank($customer['bank']);
        $natural_customer->setAssestsNumber('');
        $natural_customer->setFrozen(true);
		
		//instantiate database query
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($natural_customer);
        $em->flush();

        return false;
    
    }
    
    private function checkNaturalCustomerAction($customer_id)
    {
		$natural_customer = $this->getDoctrine()
                 ->getRepository('StockAccountBundle:NaturalCustomer')
                 ->find($customer_id);
        return $natural_customer != null;
    }
    
    //query for natural customer
    private function showNaturalCustomerAction($customer_id)
    {
        //query
		$natural_customer = $this->getDoctrine()
                 ->getRepository('StockAccountBundle:NaturalCustomer')
                 ->find($customer_id);
		
		//response if not found
        if(!$natural_customer)
            throw $this->createNotFoundException('No natural customer found for customer_id' . $customer_id);
        // return get_object_vars($natural_customer);
        $customer = array();
        $customer['customer_id'] = $natural_customer->getCustomerId();
        $customer['name']  = $natural_customer->getName();
        $customer['id_number'] = $natural_customer->getIdNumber();
        $customer['register_date'] = $natural_customer->getRegisterDate();
        $customer['gender'] = $natural_customer->getGender();
        $customer['address'] = $natural_customer->getAddress();
        $customer['occupation'] = $natural_customer->getOccupation();
        $customer['educational_background'] = $natural_customer->getEducationalBackground();
        $customer['company_or_organization'] = $natural_customer->getCompanyOrOrganization();
        $customer['tel'] = $natural_customer->getTel();
        // TODO: get agent information
        $customer['agent_id'] = $natural_customer->getAgentId();
        $customer['bank'] = $natural_customer->getBank();
        $customer['assets_number'] = $natural_customer->getAssestsNumber();
        $customer['frozen'] = $natural_customer->getFrozen();
        return $customer;
    }
    
    //update for natural customer, mainly used for frozen action now
    private function updateNaturalCustomerAction($customer_id, $frozen)
    {
        //query
		$natural_customer = $this->getDoctrine()
                    ->getRepository('StockAccountBundle:NaturalCustomer')
                    ->find($customer_id);
		//response if not found
        if(!$natural_customer){
                throw $this->createNotFoundException('No natural customer found for customer_id ' .$customer_id);
            }
		//freeze or thaw
        $natural_customer->setFrozen($frozen);

        $em = $this->getDoctrine()->getEntityManager();
        $em->flush();

        return new Response();
    }

    //delete a natural customer
    private function removeNaturalCustomerAction($customer_id)
    {
        //query
		$natural_customer = $this->getDoctrine()
                    ->getRepository('StockAccountBundle:NaturalCustomer')
                    ->find($customer_id);
		//response if not found
        if(!$natural_customer){
                throw $this->createNotFoundException('No natural customer found for customer_id ' .$customer_id);
            }
		//remove
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($natural_customer);
        $em->flush();

        return new Response();
    }

    //create a legal customer
    private function createLegalCustomerAction()
    {
        //check for personnel table
		$legal_customer = $this->getDoctrine()
                 ->getRepository('StockAccountBundle:Personnel')
                 ->find($id_number);
        if($legal_customer){
            throw $this->createNotFoundException('Personnel found for id_number ' .$id_number);
		}
		//check for legal_check table
		$legal_customer = $this->getDoctrine()
                 ->getRepository('StockAccountBundle:LegalCheck')
                 ->find($legal_register_number);
        if((!$legal_customer)
			||($name != $legal_customer->name)
			||($id_number != $legal_customer->id_number)
			||($license != $legal_customer->license)){
            throw $this->createNotFoundException('The customer qualification is not legal for legal_register_number ' .$legal_register_number);
		}
		//pass parameters
		$legal_customer = new LegalCustomer();
        $legal_customer->setCustomerId($customerId);
        $legal_customer->setName($name);
        $legal_customer->setIdNumber($id_number);
        $legal_customer->setLegalRegisternumber($legal_register_number);
        $legal_customer->setLicense($license);
        $legal_customer->setExecutorName($executor_name);
        $legal_customer->setExecutorId($executor_id);
        $legal_customer->setExecutorAddress($executor_address);
        $legal_customer->setExecutorTel($executor_tel);
        $legal_customer->setBank($bank);
        $legal_customer->setAssestsNumber($assestsNumber);
        $legal_customer->setFrozen($frozen);
    
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($legal_customer);
        $em->flush();

        return new Response();
    
    }

    //query for legal customer
    private function showLegalCustomerAction($customer_id)
    {
        $legal_customer = $this->getDoctrine()
                 ->getRepository('StockAccountBundle:LegalCustomer')
                 ->find($customer_id);
        if(!$legal_customer){
             throw $this->createNotFoundException('No legal customer found for customer_id ' .$customer_id);
        }
        return $legal_customer;
    }
    
    //update for legal customer, mainly used for forzen action now
    private function updateLegalCustomerAction($customer_id, $frozen)
    {
        $legal_customer = $this->getDoctrine()
                    ->getRepository('StockAccountBundle:LegalCustomer')
                    ->find($customer_id);
    
        if(!$legal_customer){
                throw $this->createNotFoundException('No legal customer found for customer_id ' .$customer_id);
            }
        $legal_customer->setFrozen($frozen);

        $em = $this->getDoctrine()->getEntityManager();
        $em->flush();

        return new Response();
    }

    //delete a legal customer
    private function removeLegalCustomerAction($customer_id)
    {
        $legal_customer = $this->getDoctrine()
                    ->getRepository('StockAccountBundle:LegalCustomer')
                    ->find($customer_id);
    
        if(!$legal_customer){
                throw $this->createNotFoundException('No legal customer found for customer_id ' .$customer_id);
            }
    
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($legal_customer);
        $em->flush();

        return new Response();
    }


}