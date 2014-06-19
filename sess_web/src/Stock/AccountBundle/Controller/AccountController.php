<?php
namespace Stock\AccountBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Stock\AccountBundle\Entity\NaturalCustomer;
use Stock\AccountBundle\Entity\CompanyCheck;
use Stock\AccountBundle\Entity\CompanyCustomer;
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
        //change the information
        if (strcmp($type, 'edit') == 0 || strcmp($type, 'reopen') == 0)
        {
            //find the customer
            $customer_id = $request->query->get('customer_id');
            if ($customer_id == null)
                throw $this->createNotFoundException('No natural customer found for customer_id' . $customer_id);
            $customer = $this->showNaturalCustomerAction($customer_id);
            $this->removeNaturalCustomerAction($customer_id);
            //open the according page
            return $this->render('StockAccountBundle:Account:OpenPerson.html.twig', array("is_open" => false, "customer" => $customer));
        }
        else
            return $this->render('StockAccountBundle:Account:OpenPerson.html.twig', array("is_open" => true));
    }
    //show the web page of opening an account of a company
    public function openCompanyAction(Request $request)
    {
        //get the type
        $type = $request->query->get('type');
        if ($type == null)
            $type = 'open';
        //the following is the same as opening natural 
        if (strcmp($type, 'edit') == 0 || strcmp($type, 'reopen') == 0)
        {
            $customer_id = $request->query->get('customer_id');
            if ($customer_id == null)
                throw $this->createNotFoundException('No company customer found for customer_id' . $customer_id);
            $customer = $this->showCompanyCustomerAction($customer_id);
            $this->removeCompanyCustomerAction($customer_id);
            return $this->render('StockAccountBundle:Account:OpenCompany.html.twig', array("is_open" => false, "customer" => $customer));
        }
        else
            return $this->render('StockAccountBundle:Account:OpenCompany.html.twig', array("is_open" => true));
    }
    //show the web page of confirming the information of a person
    public function confirmPersonalAction(Request $request)
    {
        $customer_id = $request->query->get('customer_id');
        //no such is, report the error
        if ($customer_id == null)
            return $this->makeResponse(STATUS_ARGUMENT_ERROR);
        //show the information of customer
        $customer = $this->showNaturalCustomerAction($customer_id);
        // return new Response(var_dump($customer));
        return $this->render('StockAccountBundle:Account:ConfirmPerson.html.twig', $customer);
    }
    
    //show the web page of confirming the information of a company
    public function confirmCompanyAction(Request $request)
    {
        $customer_id = $request->query->get('customer_id');
        if ($customer_id == null)
            return $this->makeResponse(STATUS_ACCOUNT_ERROR);
        $customer = $this->showCompanyCustomerAction($customer_id);
        return $this->render('StockAccountBundle:Account:ConfirmCompany.html.twig', $customer);
    }
    //show the web page of reporting the loss of the account
    public function reportLossAction(Request $request)
    {
        $request->query->get('id');
        
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
    
    //function used to report of the status
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
            return $this->redirect($this->generateUrl('openPersonal_page') . "?notice=从业人员不能开设账户");
            
        // Check age
        $birthdate = substr($customer['id_number'], 6, 4);
        $intbirth = intval($birthdata);
        if (2014 - $intbirth <= 18)
            return $this->redirect($this->generateUrl('openPersonal_page') . "?notice=小于18岁不能开设账户");
        // TODO
        
        //check same idnumber
        if ($this->findNaturalCustomerAction($customer['id_number']))
            return $this->redirect($this->generateUrl('openPersonal_page') . "?notice=该身份证已创建过账户");

            //create the natural customer
        $db_error = $this->createNaturalCustomerAction($customer);
        if ($db_error)
            return $this->makeResponse(self::STATUS_DB_ERROR);
        //return $this->redirect($this->generateUrl('openPersonal_page') . "?notice=从业人员不能开设账户");
            
        //return $this->redirect($this->generateUrl('confirmPersonal_page') . '?customer_id=' . $customer['id']);
    }
    
    //the api for opening an account for a company
    public function openCompanyApiAction(Request $request)
    {
        $customer = array();
        $customer['id'] = "C" . time();
        while ($this->checkCompanyCustomerAction($customer['id']))
            $customer['id'] = "C" . time();
        $customer['name']  = $request->request->get('name');
        $customer['phone'] = $request->request->get('phone');
        $customer['address'] = $request->request->get('address');
        $customer['id_number'] = $request->request->get('id_number');
        $customer['register_id'] = $request->request->get('register_id');
        $customer['license_id'] = $request->request->get('license_id');
        $customer['auth_name'] = $request->request->get('auth_name');
        $customer['auth_id'] = $request->request->get('auth_id');
        $customer['auth_phone'] = $request->request->get('auth_phone');
        $customer['auth_address'] = $request->request->get('auth_address');
        // TODO: get agent information
        $customer['agent_id'] = '1';
        $customer['bank'] = 'Laohe Branch';
        // Check arguments existence
        foreach ($customer as $key => $value)
            if ($value == null)
                return $this->makeResponse(self::STATUS_FORMAT_ERROR . " " . $key);
        
        // Check personnel
        if ($this->checkPersonnel($customer['auth_id']) || $this->checkPersonnel($customer['id_number']))
            return $this->redirect($this->generateUrl('openCompany_page') . "?notice=从业人员不能开设账户");
        
        //create the company customer
        $db_error = $this->createCompanyCustomerAction($customer);
        if ($db_error)
            return $this->makeResponse(self::STATUS_DB_ERROR);
        
        return $this->redirect($this->generateUrl('confirmCompany_page') . '?customer_id=' . $customer['id']);
    }
    
    //the api for confirming the information of the person customer
    public function confirmPersonalApiAction(Request $request)
    {
        $customer_id = $request->query->get('customer_id');
        $confirm = $request->query->get('confirm');
        //confirm create the account
        if ($confirm == 1)
        {
            $this->updateNaturalCustomerAction($customer_id, false);
            return $this->redirect($this->generateUrl('index') . "?notice=个人证券帐户创建成功，id为" . $customer_id);
        }
        //return to change the information
        else
            return $this->redirect($this->generateUrl('openPersonal_page') . '?type=edit&customer_id=' . $customer_id);
    }
    
    //the api for confirming the information of the company customer
    public function confirmCompanyApiAction(Request $request)
    {
        $customer_id = $request->query->get('customer_id');
        $confirm = $request->query->get('confirm');
        //confirm the create
        if ($confirm == 1)
        {
            $this->updateCompanyCustomerAction($customer_id, false);
            return $this->redirect($this->generateUrl('index') . "?notice=公司证券帐户创建成功，id为" . $customer_id);
        }
        // return to change the information
        else
            return $this->redirect($this->generateUrl('openCompany_page') . '?type=edit&customer_id=' . $customer_id);
    }
    
    
    //the api for reporting the loss of the account
    public function reportLossApiAction(Request $request)
    {
        $id = $request->request->get('id');
        if (($find = $this->findNaturalCustomerAction($id)) != null)
        {
            $customer_id = $find->getCustomerId();
            $this->updateNaturalCustomerAction($customer_id, true);
            return $this->redirect($this->generateUrl('index') . "?notice=个人证券帐户挂失成功，id为" . $customer_id);
        }
        else if ($this->checkCompanyCustomerAction($id))
        {
            $this->updateCompanyCustomerAction($id, true);
            return $this->redirect($this->generateUrl('index') . "?notice=企业证券帐户挂失成功，id为" . $id);
        }
        else
            return $this->redirect($this->generateUrl('reportLoss_page') . "?notice=该身份账号不存在");
    }
    
    //the api for register the account
    public function postRegisterApiAction(Request $request)
    {
        $id = $request->request->get('id');
        if (($find = $this->findNaturalCustomerAction($id)) != null)
        {
            $customer_id = $find->getCustomerId();
            $this->updateNaturalCustomerAction($customer_id, false);
            return $this->redirect($this->generateUrl('index') . "?notice=个人证券帐户补办成功，id为" . $customer_id);
        }
        else if ($this->checkCompanyCustomerAction($id))
        {
            $this->updateCompanyCustomerAction($id, true);
            return $this->redirect($this->generateUrl('index') . "?notice=企业证券帐户补办成功，id为" . $id);
        }
        else
            return $this->redirect($this->generateUrl('postRegister_page') . "?notice=该身份账号不存在");
    }
    
    //the api for cancelling an account
    public function cancelApiAction(Request $request)
    {
        $id = $request->request->get('id');
        if (($find = $this->findNaturalCustomerAction($id)) != null)
        {
            $customer_id = $find->getCustomerId();
            $this->removeNaturalCustomerAction($customer_id);
            return $this->redirect($this->generateUrl('index') . "?notice=个人证券帐户销户成功，id为" . $customer_id);
        }
        else if ($this->checkCompanyCustomerAction($id))
        {
            $this->removeCompanyCustomerAction($id);
            return $this->redirect($this->generateUrl('index') . "?notice=企业证券帐户销户成功，id为" . $id);
        }
        else
            return $this->redirect($this->generateUrl('cancel_page') . "?notice=该身份账号不存在");
    }
    
    //the function used to check whether the person is a personnel
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
        $natural_customer->setAssetsNumber('');
        $natural_customer->setFrozen(true);
		
		//instantiate database query
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($natural_customer);
        $em->flush();

        return false;
    
    }
    
    //check the information of the natural customer
    private function checkNaturalCustomerAction($customer_id)
    {
		$natural_customer = $this->getDoctrine()
                 ->getRepository('StockAccountBundle:NaturalCustomer')
                 ->find($customer_id);
        return $natural_customer != null;
    }
    
    private function findNaturalCustomerAction($id)
    {
        $natural_customer = $this->getDoctrine()
                 ->getRepository('StockAccountBundle:NaturalCustomer')
                 ->findOneBy(
                    array('id_number' => $id)
                 );
        if ($natural_customer != null)
            return $natural_customer;
        else
            return null;
    }
    
    private function findCompanyCustomerAction($id)
    {
        $company_customer = $this->getDoctrine()
                 ->getRepository('StockAccountBundle:CompanyCustomer')
                 ->findBy($id);
        if ($company_customer != null)
            return $company_customer;
        else
            return null;
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
        $customer['assets_number'] = $natural_customer->getAssetsNumber();
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
    }

    //create a company customer
    private function createCompanyCustomerAction($customer)
    {
		//check for company_check table
		// $company_customer = $this->getDoctrine()
                 // ->getRepository('StockAccountBundle:CompanyCheck')
                 // ->find($company_register_number);
        // if((!$company_customer)
			// ||($name != $company_customer->name)
			// ||($id_number != $company_customer->id_number)
			// ||($license != $company_customer->license)){
            // throw $this->createNotFoundException('The customer qualification is not company for company_register_number ' .$company_register_number);
		// }
		//pass parameters
		$company_customer = new CompanyCustomer();
        $company_customer->setCustomerId($customer['id']);
        $company_customer->setName($customer['name']);
        $company_customer->setPhone($customer['phone']);
        $company_customer->setAddress($customer['address']);
        $company_customer->setIdNumber($customer['id_number']);
        $company_customer->setRegisterId($customer['register_id']);
        $company_customer->setLicense($customer['license_id']);
        $company_customer->setAuthName($customer['auth_name']);
        $company_customer->setAuthId($customer['auth_id']);
        $company_customer->setAuthAddress($customer['auth_address']);
        $company_customer->setAuthPhone($customer['auth_phone']);
        $company_customer->setBank($customer['bank']);
        $company_customer->setAgentId($customer['agent_id']);
        $company_customer->setAssetsNumber('');
        $company_customer->setFrozen(true);
    
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($company_customer);
        $em->flush();
    }

    //check the information of the company customer
    private function checkCompanyCustomerAction($customer_id)
    {
        $company_customer = $this->getDoctrine()
                  ->getRepository('StockAccountBundle:CompanyCustomer')
                  ->find($customer_id);
        return $company_customer != null;
    }
    
    //query for company customer
    private function showCompanyCustomerAction($customer_id)
    {
        //query
		$company_customer = $this->getDoctrine()
                 ->getRepository('StockAccountBundle:CompanyCustomer')
                 ->find($customer_id);
		
		//response if not found
        if(!$company_customer)
            throw $this->createNotFoundException('No company customer found for customer_id' . $customer_id);
        // return get_object_vars($company_customer);
        $customer = array();
        $customer['customer_id'] = $company_customer->getCustomerId();
        $customer['name']  = $company_customer->getName();
        $customer['phone'] = $company_customer->getPhone();
        $customer['address'] = $company_customer->getAddress();
        $customer['id_number'] = $company_customer->getIdNumber();
        $customer['register_id'] = $company_customer->getRegisterId();
        $customer['license_id'] = $company_customer->getLicense();
        $customer['auth_name'] = $company_customer->getAuthName();
        $customer['auth_id'] = $company_customer->getAuthId();
        $customer['auth_address'] = $company_customer->getAuthAddress();
        $customer['auth_phone'] = $company_customer->getAuthPhone();
        $customer['agent_id'] = $company_customer->getAgentId();
        $customer['bank'] = $company_customer->getBank();
        $customer['assets_number'] = $company_customer->getAssetsNumber();
        $customer['frozen'] = $company_customer->getFrozen();
        return $customer;
    }
    
    //update for company customer, mainly used for forzen action now
    private function updateCompanyCustomerAction($customer_id, $frozen)
    {
        $company_customer = $this->getDoctrine()
                    ->getRepository('StockAccountBundle:CompanyCustomer')
                    ->find($customer_id);
    
        if(!$company_customer){
                throw $this->createNotFoundException('No company customer found for customer_id ' .$customer_id);
            }
        $company_customer->setFrozen($frozen);

        $em = $this->getDoctrine()->getEntityManager();
        $em->flush();
    }

    //delete a company customer
    private function removeCompanyCustomerAction($customer_id)
    {
        $company_customer = $this->getDoctrine()
                    ->getRepository('StockAccountBundle:CompanyCustomer')
                    ->find($customer_id);
    
        if(!$company_customer){
                throw $this->createNotFoundException('No company customer found for customer_id ' .$customer_id);
            }
    
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($company_customer);
        $em->flush();
    }


}