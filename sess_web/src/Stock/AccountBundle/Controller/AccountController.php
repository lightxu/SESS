<?php
namespace Stock\AccountBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Stock\AccountBundle\Entity;

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
    
    
    
    //database operations
    //create a natural customer
    public function createNaturalCustomerAction()
    {
        //check for personnel
		$natural_customer = $this->getDoctrine()
                 ->getRepository('StockAccountBundle:Personnel')
                 ->find($id_number);
        if($natural_customer){
            throw $this->createNotFoundException('Personnel found for id_number ' .$id_number);
		}
		//pass parameters
		$natural_customer = new NaturalCustomer();
        $natural_customer->setCustomerId($customerId);
        $natural_customer->setName($name);
        $natural_customer->setIdNumber($id_number);
        $natural_customer->setRegisterDate($register_date);
        $natural_customer->setGender($gender);
        $natural_customer->setAddress($address);
        $natural_customer->setOccupation($occupation);
        $natural_customer->setEducationalBackground($educational_background);
        $natural_customer->setCompanyOrOrganization($company_or_organization);
        $natural_customer->setTel($tel);
        $natural_customer->setAgentId($agent_id);
        $natural_customer->setBank($bank);
        $natural_customer->setAssestsNumber($assestsNumber);
        $natural_customer->setFrozen($frozen);
		
		//instantiate database query
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($natural_customer);
        $em->flush();

        return new Response();
    
    }

    //query for natural customer
    public function showNaturalCustomerAction($customer_id)
    {
        //query
		$natural_customer = $this->getDoctrine()
                 ->getRepository('StockAccountBundle:NaturalCustomer')
                 ->find($customer_id);
		
		//response if not found
        if(!$natural_customer){
             throw $this->createNotFoundException('No natural customer found for customer_id ' .$customer_id);
        }
        return new Response();
    }
    
    //update for natural customer, mainly used for forzen action now
    public function updateNaturalCustomerAction($customer_id, $frozen)
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
    public function removeNaturalCustomerAction($customer_id)
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
    public function createLegalCustomerAction()
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
    public function showLegalCustomerAction($customer_id)
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
    public function updateLegalCustomerAction($customer_id, $frozen)
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
    public function removeLegalCustomerAction($customer_id)
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