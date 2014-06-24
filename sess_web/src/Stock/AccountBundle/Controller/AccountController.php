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
    /********** Send request **********/
    /**
    * Send post data to a url.
    * @param string $url address
    * @param array $data the data to post
    * @return string content
    * @author DAI, Longao
    */
    function send_post($url, $data){
        // Build Post Data
        // Secret Key for Posting Verify
        $data['app_key'] = "354DD0DE1AB36DC4531B8723C34B9EFE";
        // Encode the data to json formatting
        $data = json_encode($data);

        // Set options
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' =>
                    "Content-Type: application/json\r\n" .
                    "Accept: application/json",
                'content' => $data,
                'timeout' => 15 * 60,
            ),
        );
        // Call functions to post data
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        // Check the data
        if(!$this->validate_data($result)){
            $this->common->set_global_warning("接口错误：无法通过数据完整性校验！", "system_msg");
            return FALSE;
        }

        return $result;
    }

    /********** Page Actions **********/
    //show the web page of opening an account of a person
    public function openPersonalAction(Request $request)
    {
        $type = $request->query->get('type');
        if (!isset($type))
            $type = 'open';
        $admin = $this->getUser();
        //change the information
        if (strcmp($type, 'edit') == 0 || strcmp($type, 'reopen') == 0)
        {
            //find the customer
            $customer_id = $request->query->get('customer_id');
            //no customer
            if (!isset($customer_id))
                throw $this->createNotFoundException('No natural customer found for customer_id' . $customer_id);
            $customer = $this->showNaturalCustomerAction($customer_id);
            $this->removeNaturalCustomerAction($customer_id);
            //open the according page
            return $this->render('StockAccountBundle:Account:OpenPerson.html.twig', array("is_open" => false, "customer" => $customer, "username" => $admin->getUsername(), "bankname" => $admin->getBankname()));
        }
        else
            return $this->render('StockAccountBundle:Account:OpenPerson.html.twig', array("is_open" => true, "username" => $admin->getUsername(), "bankname" => $admin->getBankname()));
    }
    //show the web page of opening an account of a company
    public function openCompanyAction(Request $request)
    {
        //get the type
        $type = $request->query->get('type');
        if (!isset($type))
            $type = 'open';
        $admin = $this->getUser();
        //the following is the same as opening natural 
        if (strcmp($type, 'edit') == 0 || strcmp($type, 'reopen') == 0)
        {
            $customer_id = $request->query->get('customer_id');
            //no customer
            if (!isset($customer_id))
                throw $this->createNotFoundException('No company customer found for customer_id' . $customer_id);
            //show the infor of the customer
            $customer = $this->showCompanyCustomerAction($customer_id);
            $this->removeCompanyCustomerAction($customer_id);
            return $this->render('StockAccountBundle:Account:OpenCompany.html.twig', array("is_open" => false, "customer" => $customer, "username" => $admin->getUsername(), "bankname" => $admin->getBankname()));
        }
        //open opencompany page
        else
            return $this->render('StockAccountBundle:Account:OpenCompany.html.twig', array("is_open" => true, "username" => $admin->getUsername(), "bankname" => $admin->getBankname()));
    }
    //show the web page of confirming the information of a person
    public function confirmPersonalAction(Request $request)
    {
        //get the customer id
        $customer_id = $request->query->get('customer_id');
        //can not find the customer
        if (!isset($customer_id))
        {
            $this->get('session')->getFlashBag()->add(
                'alert',
                '用户ID('.$customer_id.')未找到。'
            );
            return $this->redirect($this->generateUrl('index'));
        }
        $admin = $this->getUser();
        //show the information of customer
        $customer = $this->showNaturalCustomerAction($customer_id);
        //get the infomation of the bank and administrator
        $customer["username"] = $admin->getUsername();
        $customer["bankname"] = $admin->getBankname();
        // return new Response(var_dump($customer));
        return $this->render('StockAccountBundle:Account:ConfirmPerson.html.twig', $customer);
    }
    
    //show the web page of confirming the information of a company
    public function confirmCompanyAction(Request $request)
    {
        $customer_id = $request->query->get('customer_id');
        //can not find the customer
        if (!isset($customer_id))
        {
            $this->get('session')->getFlashBag()->add(
                'alert',
                '用户ID('.$customer_id.')未找到。'
            );
            return $this->redirect($this->generateUrl('index'));
        }
        //get the administrator of the bank
        $admin = $this->getUser();
        $customer = $this->showCompanyCustomerAction($customer_id);
        //get the administrator name and the bank name
        $customer["username"] = $admin->getUsername();
        $customer["bankname"] = $admin->getBankname();
        return $this->render('StockAccountBundle:Account:ConfirmCompany.html.twig', $customer);
    }
    //show the web page of reporting the loss of the account
    public function reportLossAction(Request $request)
    {
        $request->query->get('id');
        $admin = $this->getUser();
        //open the report loss page
        return $this->render('StockAccountBundle:Account:ReportLoss.html.twig', array("username" => $admin->getUsername(), "bankname" => $admin->getBankname()));
    }
    //show the web page of registering a new account
    public function postRegisterAction()
    {
        $admin = $this->getUser();
        //open the page of post register
        return $this->render('StockAccountBundle:Account:PostRegister.html.twig', array("username" => $admin->getUsername(), "bankname" => $admin->getBankname()));
    }
    //show the web page of cancelling the account
    public function cancelAction()
    {
        $admin = $this->getUser();
        return $this->render('StockAccountBundle:Account:AccountCancel.html.twig', array("username" => $admin->getUsername(), "bankname" => $admin->getBankname()));
    }
    
    //function to search the id to change information
    public function changeInformationAction(Request $request)
    {
        $request->query->get('id');
        $admin = $this->getUser();
        //open the page
        return $this->render('StockAccountBundle:Account:ChangeInformation.html.twig', array("username" => $admin->getUsername(), "bankname" => $admin->getBankname()));
    }
    
    //function to search the id to view the information
    public function viewSearchAction(Request $request)
    {
        $request->query->get('id');
        $admin = $this->getUser();
        //open the page
        return $this->render('StockAccountBundle:Account:ViewSearch.html.twig', array("username" => $admin->getUsername(), "bankname" => $admin->getBankname()));
    }
    
    /********** POST Api Actions **********/
    //the api to change the information of the customer
    public function changePersonalApiAction(Request $request)
    {
        //create the addry to record the update information
        $updateinfo = array();
        $customer_id = $request->request->get('customer_id');
        $updateinfo['address'] = $request->request->get('address');
        $updateinfo['occupation'] = $request->request->get('occupation');
        $updateinfo['educational'] = $request->request->get('educational_background');
        $updateinfo['company'] = $request->request->get('company_or_organization');
        $updateinfo['tel'] = $request->request->get('tel');
        //call the change function
        $this->changeNaturalCustomerAction($customer_id, $updateinfo);
            $this->get('session')->getFlashBag()->add(
                'notice',
                "个人信息修改成功."
            );
        //return to the main page
        return $this->redirect($this->generateUrl('index'));
    }
    
    //the api to update the information of the company customer
    public function changeCompanyApiAction(Request $request)
    {
        //create the array to record the inforamtion
        $updateinfo = array();
        $customer_id = $request->request->get('customer_id');
        //get the information from the post
        $updateinfo['phone'] = $request->request->get('phone');
        $updateinfo['address'] = $request->request->get('address');
        $updateinfo['auth_phone'] = $request->request->get('auth_phone');
        $updateinfo['auth_address'] = $request->request->get('auth_address');
        //call the function to update the infomation
        $this->changeCompanyCustomerAction($customer_id, $updateinfo);
            $this->get('session')->getFlashBag()->add(
                'notice',
                "企业信息修改成功."
            );
        //return to the main page
        return $this->redirect($this->generateUrl('index'));
    }
    
    //the api for opening an account for a person
    public function openPersonalApiAction(Request $request)
    {
        $customer = array();
        //create the id numner use the time stamp
        $customer['id'] = "P" . time();
        while ($this->checkNaturalCustomerAction($customer['id']))
            $customer['id'] = "P" . time();
        //get the information from the post
        $customer['name']  = $request->request->get('name');
        $customer['id_number'] = $request->request->get('id_number');
        $customer['register_date'] = new \DateTime();
        $customer['gender'] = $request->request->get('gender');
        $customer['address'] = $request->request->get('address');
        $customer['occupation'] = $request->request->get('occupation');
        $customer['educational_background'] = $request->request->get('educational_background');
        $customer['company_or_organization'] = $request->request->get('company_or_organization');
        $customer['tel'] = $request->request->get('tel');
        $customer['agent_id'] = $request->request->get('agent_id');
        if (!isset($customer['agent_id']))
            $customer['agent_id'] = '空';
        $admin = $this->getUser();
        //get the bank name
        $customer['bank'] = $admin->getBankname();
        
        // Check arguments existence
        foreach ($customer as $key => $value)
            if (!isset($value))
            {                
                $this->get('session')->getFlashBag()->add(
                    'alert',
                    '表单未填写完整。'
                );
                return $this->render('StockAccountBundle:Account:OpenPerson.html.twig', array("is_open" => false, "customer" => $customer, "username" => $admin->getUsername(), "bankname" => $admin->getBankname()));
            }
        // Check personnel
        if ($this->checkPersonnel($customer['id_number']))
        {
            $this->get('session')->getFlashBag()->add(
                'alert',
                '从业人员不能开设账户。'
            );
            return $this->render('StockAccountBundle:Account:OpenPerson.html.twig', array("is_open" => false, "customer" => $customer, "username" => $admin->getUsername(), "bankname" => $admin->getBankname()));
        }
        
        //check gender
        if (strcmp($customer['gender'], '男') && strcmp($customer['gender'], '女'))
        {
             $this->get('session')->getFlashBag()->add(
                'alert',
                '性别只能是男或女。'
            );
            return $this->render('StockAccountBundle:Account:OpenPerson.html.twig', array("is_open" => false, "customer" => $customer, "username" => $admin->getUsername(), "bankname" => $admin->getBankname()));
        
        }
        
        //check id card
        if (!(strlen($customer['id_number']) == 18 || strlen($customer['id_number']) == 19))
        {
            $this->get('session')->getFlashBag()->add(
                'alert',
                '身份证号码格式不正确。'
            );
            return $this->render('StockAccountBundle:Account:OpenPerson.html.twig', array("is_open" => false, "customer" => $customer, "username" => $admin->getUsername(), "bankname" => $admin->getBankname()));
        }
        //check phone
        for ($i = 0; $i < strlen($customer['tel']); $i++)
        {
            $phonum = substr($customer['tel'], $i, 1);
            if (!($phonum >= '0' && $phonum <= '9'))
            {
                $this->get('session')->getFlashBag()->add(
                'alert',
                '电话号码格式不正确。'
            );
            return $this->render('StockAccountBundle:Account:OpenPerson.html.twig', array("is_open" => false, "customer" => $customer, "username" => $admin->getUsername(), "bankname" => $admin->getBankname()));
            }
        }
        // Check age
        $agentbirth = substr($customer['agent_id'], 6, 4);
        $intagentbirth = intval($agentbirth);
        $birthdate = substr($customer['id_number'], 6, 4);
        $intbirth = intval($birthdate);
        if ((2014 - $intbirth <= 18 && strcmp($customer['agent_id'], '空') == 0) || (2014 - $intbirth <= 18 && 2014-$intagentbirth <= 18))
        {
            $this->get('session')->getFlashBag()->add(
                'alert',
                '小于18岁不能开设账户。'
            );
            return $this->render('StockAccountBundle:Account:OpenPerson.html.twig', array("is_open" => true, "username" => $admin->getUsername(), "bankname" => $admin->getBankname()));
        }
        
        //check same idnumber
        if ($this->findNaturalCustomerAction($customer['id_number']))
        {
            $this->get('session')->getFlashBag()->add(
                'alert',
                '该身份证已创建过账户。'
            );
            return $this->render('StockAccountBundle:Account:OpenPerson.html.twig', array("is_open" => true, "username" => $admin->getUsername(), "bankname" => $admin->getBankname()));
        }
        
        //create the natural customer
        $this->createNaturalCustomerAction($customer);
        
        return $this->redirect($this->generateUrl('confirmPersonal_page') . '?customer_id=' . $customer['id']);
    }
    
    //the api for opening an account for a company
    public function openCompanyApiAction(Request $request)
    {
        $customer = array();
        //create the id numner using the time stamp
        $customer['id'] = "C" . time();
        // if the id has existed, we have to recreate
        while ($this->checkCompanyCustomerAction($customer['id']))
            $customer['id'] = "C" . time();
        //get the information from the post
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
        $admin = $this->getUser();
        //get the bank name
        $customer['bank'] = $admin->getBankname();
        
        // Check arguments existence
        foreach ($customer as $key => $value)
            if (!isset($value))
            {
                $this->get('session')->getFlashBag()->add(
                    'alert',
                    '表单未填写完整。'
                );
                return $this->render('StockAccountBundle:Account:OpenCompany.html.twig', array("is_open" => false, "customer" => $customer, "username" => $admin->getUsername(), "bankname" => $admin->getBankname()));
            }

        // Check personnel
        if ($this->checkPersonnel($customer['auth_id']) || $this->checkPersonnel($customer['id_number']))
        {
            $this->get('session')->getFlashBag()->add(
                'alert',
                '从业人员不得开设证券账户。'
            );
            return $this->render('StockAccountBundle:Account:OpenCompany.html.twig', array("is_open" => true, "username" => $admin->getUsername(), "bankname" => $admin->getBankname()));
        }

        
        //create the company customer
        $this->createCompanyCustomerAction($customer);
        
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
            $this->get('session')->getFlashBag()->add(
                'notice',
                '个人证券帐户创建成功，id为' . $customer_id
            );
            //return to the main page
            return $this->redirect($this->generateUrl('index'));
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
            $this->get('session')->getFlashBag()->add(
                'notice',
                '个人证券帐户创建成功，id为' . $customer_id
            );
            return $this->redirect($this->generateUrl('index'));
        }
        // return to change the information
        else
            return $this->redirect($this->generateUrl('openCompany_page') . '?type=edit&customer_id=' . $customer_id);
    }
    
    
    //the api for reporting the loss of the account
    public function reportLossApiAction(Request $request)
    {
        $id = $request->request->get('id');
        //find the customer
        if (($find = $this->findNaturalCustomerAction($id)) != null)
        {
            //freeze the account 
            $customer_id = $find->getCustomerId();
            $this->updateNaturalCustomerAction($customer_id, true);
            $this->get('session')->getFlashBag()->add(
                'notice',
                "个人证券帐户挂失成功，id为" . $customer_id
            );
            return $this->redirect($this->generateUrl('index'));
        }
        //for company customer
        else if (($find = $this->findCompanyCustomerAction($id)) != null)
        {
            //freeze the customer
            $this->updateCompanyCustomerAction($id, true);
            $this->get('session')->getFlashBag()->add(
                'notice',
                "企业证券帐户挂失成功，id为" . $id
            );
            return $this->redirect($this->generateUrl('index'));
        }
        //can not find the id 
        else
        {
            $this->get('session')->getFlashBag()->add(
                'alert',
                "该身份账号不存在"
            );
            //report the alert and return to this page
            return $this->redirect($this->generateUrl('reportLoss_page'));
        }
    }
    
    //the api for register the account
    public function postRegisterApiAction(Request $request)
    {
        $id = $request->request->get('id');
        //find the customer
        if (($find = $this->findNaturalCustomerAction($id)) != null)
        {
            //check whether the account has been frozen
            if ($find->getFrozen() == 0)
             {
                 $this->get('session')->getFlashBag()->add(
                     'alert',
                     "该账户未曾挂失。"
                 );
                 return $this->redirect($this->generateUrl('reportLoss_page'));
             }
            $customer_id = $find->getCustomerId();
            //activate the account
            $this->updateNaturalCustomerAction($customer_id, false);
            $this->get('session')->getFlashBag()->add(
                'notice',
                "个人证券帐户补办成功，id为" . $customer_id
            );
            return $this->redirect($this->generateUrl('index'));
        }
        //for company customer
        else if (($find = $this->findCompanyCustomerAction($id)) != null)
        {
            //check if the account have been frozen
            if ($find->getFrozen() == 0)
            {
                $this->get('session')->getFlashBag()->add(
                    'alert',
                    "该账户未曾挂失。"
                );
                return $this->redirect($this->generateUrl('reportLoss_page'));
            }
            //call the function to freeze
            $this->updateCompanyCustomerAction($id, false);
            $this->get('session')->getFlashBag()->add(
                'notice',
                "企业证券帐户补办成功，id为" . $id
            );
            return $this->redirect($this->generateUrl('index'));
        }
        //can not find the customer
        else
        {
            $this->get('session')->getFlashBag()->add(
                'alert',
                "该身份账号不存在"
            );
            //report the alert
            return $this->redirect($this->generateUrl('postRegister_page'));
        }
    }
    
    //the api fot change the information of the customer
    public function changeInformationApiAction(Request $request)
    {
        $id = $request->request->get('id');
        $admin = $this->getUser();
        //find the customer
        if (($find = $this->findNaturalCustomerAction($id)) != null)
        {   
            $customer = $this->showNaturalCustomerAction($find->getCustomerId());        
            $customer['username'] = $admin->getUsername();
            $customer['bankname'] = $admin->getBankname();
            return $this->render('StockAccountBundle:Account:UpdatePerson.html.twig', $customer);
        }
        else if (($find = $this->findCompanyCustomerAction($id)) != null)
        {
            $admin = $this->getUser();
            //get the bank name and administrator name
            $customer = $this->showCompanyCustomerAction($id);
            $customer["username"] = $admin->getUsername();
            $customer["bankname"] = $admin->getBankname();
            //return to update the company
            return $this->render('StockAccountBundle:Account:UpdateCompany.html.twig', $customer);
        }
        //can not find the customer
        else
        {
            $this->get('session')->getFlashBag()->add(
                'alert',
                "该身份账号不存在"
            );
            return $this->redirect($this->generateUrl('changeInformation_page'));
        }
    }
    //seratch the customer who want to view the information
    public function viewSearchApiAction(Request $request)
    {
        $id = $request->request->get('id');
        //for natural customer
        if (($find = $this->findNaturalCustomerAction($id)) != null)
        {   
            //get the information of the bank
            $admin = $this->getUser();
            $customer = $this->showNaturalCustomerAction($find->getCustomerId());  
            $customer["username"] = $admin->getUsername();
            $customer["bankname"] = $admin->getBankname();        
            return $this->render('StockAccountBundle:Account:ViewPerson.html.twig', $customer);
        }
        //for company customer
        else if (($find = $this->findCompanyCustomerAction($id)) != null)
        {
            $admin = $this->getUser();
            //get the infomation of the bank
            $customer = $this->showCompanyCustomerAction($id);
            $customer["username"] = $admin->getUsername();
            $customer["bankname"] = $admin->getBankname();
            return $this->render('StockAccountBundle:Account:ViewCompany.html.twig', $customer);
        }
        //can not find the customer
        else
        {
            $this->get('session')->getFlashBag()->add(
                'alert',
                "该身份账号不存在"
            );
            //report the alert
            return $this->redirect($this->generateUrl('viewSearch_page'));
        }
    }
    //the api for cancelling an account
    public function cancelApiAction(Request $request)
    {
        $id = $request->request->get('id');
        //for natural customer
        if (($find = $this->findNaturalCustomerAction($id)) != null)
        {
            $customer_id = $find->getCustomerId();
            $data = send_post("g2.jiong3.cn/finance/getbind", array("stockUsername" => $customer_id));
            $asset_name = null;
            if (strcmp($data["status"], "find") == 0)
            {
                $asset_name = $data["username"];
                send_post("g2.jiong3.cn/finance/unbind", array("stockUsername" => $customer_id));
                $this->get('session')->getFlashBag()->add(
                    'notice',
                    "已与资金账户解除绑定，id为" . $asset_name
                );
            }
            
            //remove the account
            $this->removeNaturalCustomerAction($customer_id);
            $this->get('session')->getFlashBag()->add(
                'notice',
                "个人证券帐户销户成功，id为" . $customer_id
            );
            return $this->redirect($this->generateUrl('index'));
        }
        //for company customer
        else if ($this->checkCompanyCustomerAction($id))
        {
            $id = $find->getCustomerId();
            $data = send_post("g2.jiong3.cn/finance/getbind", array("stockUsername" => $id));
            $asset_name = null;
            if (strcmp($data["status"], "find") == 0)
            {
                $asset_name = $data["username"];
                send_post("g2.jiong3.cn/finance/unbind", array("stockUsername" => $id));
                $this->get('session')->getFlashBag()->add(
                    'notice',
                    "已与资金账户解除绑定，id为" . $asset_name
                );
            }
            //remove the account
            $this->removeCompanyCustomerAction($id);
            $this->get('session')->getFlashBag()->add(
                'notice',
                "企业证券帐户销户成功，id为" . $id
            );
            return $this->redirect($this->generateUrl('index'));
        }
        //can not find the customer
        else
        {
            $this->get('session')->getFlashBag()->add(
                'alert',
                "该身份账号不存在"
            );
            //report the alert
            return $this->redirect($this->generateUrl('cancel_page'));
        }
    }
    
    /********** Database Access Actions **********/
    //the function used to check whether the person is a personnel
    private function checkPersonnel($id_number)
    {
        //search the database
        $natural_customer = $this->getDoctrine()
                 ->getRepository('StockAccountBundle:Personnel')
                 ->find($id_number);
        //find
        if (isset($natural_customer))
            return true;
        //not find
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
        $natural_customer->setFrozen(true);
		
		//instantiate database query
        $em = $this->getDoctrine()->getManager();
        $em->persist($natural_customer);
        $em->flush();

        return false;
    
    }
    
    //check the information of the natural customer
    private function checkNaturalCustomerAction($customer_id)
    {
        //search the database
		$natural_customer = $this->getDoctrine()
                 ->getRepository('StockAccountBundle:NaturalCustomer')
                 ->find($customer_id);
        return $natural_customer != null;
    }
    
    private function findNaturalCustomerAction($id)
    {
        //search the database
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
    
    //the funtion to find the company customer
    private function findCompanyCustomerAction($id)
    {
        //search the database
        $company_customer = $this->getDoctrine()
                 ->getRepository('StockAccountBundle:CompanyCustomer')
                 ->find($id);
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
        if(!isset($natural_customer))
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
        if(!isset($natural_customer)){
                throw $this->createNotFoundException('No natural customer found for customer_id ' .$customer_id);
            }
		//freeze or thaw
        $natural_customer->setFrozen($frozen);

        $em = $this->getDoctrine()->getManager();
        $em->flush();
    }
    
    private function changeNaturalCustomerAction($customer_id,$updateinfo)
    {
        //query
		$natural_customer = $this->getDoctrine()
                    ->getRepository('StockAccountBundle:NaturalCustomer')
                    ->find($customer_id);
		//response if not found
        if(!isset($natural_customer)){
                throw $this->createNotFoundException('No natural customer found for customer_id ' .$customer_id);
            }
		//freeze or thaw
        $natural_customer->setEducationalBackground($updateinfo['educational']);
        $natural_customer->setAddress($updateinfo['address']);
        $natural_customer->setCompanyOrOrganization($updateinfo['company']);
        $natural_customer->setOccupation($updateinfo['occupation']);
        $natural_customer->setTel($updateinfo['tel']);
        $em = $this->getDoctrine()->getManager();
        $em->flush();
    }
    
    private function changeCompanyCustomerAction($customer_id,$updateinfo)
    {
        //query
		$company_customer = $this->getDoctrine()
                    ->getRepository('StockAccountBundle:CompanyCustomer')
                    ->find($customer_id);
		//response if not found
        if(!isset($company_customer)){
                throw $this->createNotFoundException('No company customer found for customer_id ' .$customer_id);
            }
		//freeze or thaw
        $company_customer->setPhone($updateinfo['phone']);
        $company_customer->setAddress($updateinfo['address']);
        $company_customer->setAuthPhone($updateinfo['auth_phone']);
        $company_customer->setAuthAddress($updateinfo['auth_address']);
        $em = $this->getDoctrine()->getManager();
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
        if(!isset($natural_customer)){
                throw $this->createNotFoundException('No natural customer found for customer_id ' .$customer_id);
            }
		//remove
        $em = $this->getDoctrine()->getManager();
        $em->remove($natural_customer);
        $em->flush();
    }

    //create a company customer
    private function createCompanyCustomerAction($customer)
    {
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
        $company_customer->setFrozen(true);
    
        $em = $this->getDoctrine()->getManager();
        $em->persist($company_customer);
        $em->flush();
    }

    //check the information of the company customer
    private function checkCompanyCustomerAction($customer_id)
    {
        $company_customer = $this->getDoctrine()
                  ->getRepository('StockAccountBundle:CompanyCustomer')
                  ->find($customer_id);
        if ($company_customer != null)
            return $company_customer;
        else
            return null;
    }
    
    //query for company customer
    private function showCompanyCustomerAction($customer_id)
    {
        //query
		$company_customer = $this->getDoctrine()
                 ->getRepository('StockAccountBundle:CompanyCustomer')
                 ->find($customer_id);
		
		//response if not found
        if(!isset($company_customer))
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
        $customer['bank'] = $company_customer->getBank();
        $customer['frozen'] = $company_customer->getFrozen();
        return $customer;
    }
    
    //update for company customer, mainly used for forzen action now
    private function updateCompanyCustomerAction($customer_id, $frozen)
    {
        //search the database
        $company_customer = $this->getDoctrine()
                    ->getRepository('StockAccountBundle:CompanyCustomer')
                    ->find($customer_id);
        //can not find
        if(!isset($company_customer)){
                throw $this->createNotFoundException('No company customer found for customer_id ' .$customer_id);
            }
        //set forzen
        $company_customer->setFrozen($frozen);

        $em = $this->getDoctrine()->getManager();
        $em->flush();
    }

    //delete a company customer
    private function removeCompanyCustomerAction($customer_id)
    {
        //search the database
        $company_customer = $this->getDoctrine()
                    ->getRepository('StockAccountBundle:CompanyCustomer')
                    ->find($customer_id);
        //can not find
        if(!isset($company_customer)){
                throw $this->createNotFoundException('No company customer found for customer_id ' .$customer_id);
            }
    
        $em = $this->getDoctrine()->getManager();
        $em->remove($company_customer);
        $em->flush();
    }
}