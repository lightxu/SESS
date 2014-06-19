<?php
namespace Stock\AccountBundle\Entity;

class CompanyCustomer
{

    protected $customer_id;

    protected $register_id;
    protected $license_id;
    protected $id_number;
    protected $name;
    protected $phone;
    protected $address;
    protected $auth_name;
    protected $auth_id;
    protected $auth_address;
    protected $auth_phone;
    
    protected $bank;
    protected $assets_number;
    protected $frozen;

    /**
     * Set customer_id
     *
     * @param string $customerId
     * @return CompanyCustomer
     */
    public function setCustomerId($customerId)
    {
        $this->customer_id = $customerId;

        return $this;
    }

    /**
     * Get customer_id
     *
     * @return string 
     */
    public function getCustomerId()
    {
        return $this->customer_id;
    }

    public function setRegisterId($registerId)
    {
        $this->register_id = $registerId;
        
        return $this;
    }
    
    public function getRegisterId()
    {
        return $this->register_id;
    }
    
    public function setLicense($licenseid)
    {
        $this->license_id = $licenseid;
        return $this;
    }
    
    public function getLicense()
    {
        return $this->license_id;
    }
    public function setIdNumber($idNumber)
    {
        $this->id_number = $idNumber;
        
        return $this;
    }
    
    public function getIdNumber()
    {
        return $this->id_number;
    }
    
    /**
     * Set name
     *
     * @param string $name
     * @return CompanyCustomer
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
        
        return $this;
    }
    
    public function getPhone()
    {
        return $this->phone;
    }
    
    public function setAddress($address)
    {
        $this->address = $address;
        
        return $this;
    }
    
    public function getAddress()
    {
        return $this->address;
    }
    
    /**
     * Set auth_name
     *
     * @param string $authName
     * @return CompanyCustomer
     */
    public function setAuthName($authName)
    {
        $this->auth_name = $authName;

        return $this;
    }

    /**
     * Get auth_name
     *
     * @return string 
     */
    public function getAuthName()
    {
        return $this->auth_name;
    }

    /**
     * Set auth_id
     *
     * @param string $authId
     * @return CompanyCustomer
     */
    public function setAuthId($authId)
    {
        $this->auth_id = $authId;

        return $this;
    }

    /**
     * Get auth_id
     *
     * @return string 
     */
    public function getAuthId()
    {
        return $this->auth_id;
    }

    /**
     * Set auth_address
     *
     * @param string $authAddress
     * @return CompanyCustomer
     */
    public function setAuthAddress($authAddress)
    {
        $this->auth_address = $authAddress;

        return $this;
    }

    /**
     * Get auth_address
     *
     * @return string 
     */
    public function getAuthAddress()
    {
        return $this->auth_address;
    }

    /**
     * Set auth_phone
     *
     * @param string $authPhone
     * @return CompanyCustomer
     */
    public function setAuthPhone($authPhone)
    {
        $this->auth_phone = $authPhone;

        return $this;
    }

    /**
     * Get auth_phone
     *
     * @return string 
     */
    public function getAuthPhone()
    {
        return $this->auth_phone;
    }
    
    /**
     * Set bank
     *
     * @param string $bank
     * @return CompanyCustomer
     */
    public function setBank($bank)
    {
        $this->bank = $bank;

        return $this;
    }

    /**
     * Get bank
     *
     * @return string 
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * Set assets_number
     *
     * @param string $assetsNumber
     * @return CompanyCustomer
     */
    public function setAssetsNumber($assetsNumber)
    {
        $this->assets_number = $assetsNumber;

        return $this;
    }

    /**
     * Get assets_number
     *
     * @return string 
     */
    public function getAssetsNumber()
    {
        return $this->assets_number;
    }

    /**
     * Set frozen
     *
     * @param boolean $frozen
     * @return CompanyCustomer
     */
    public function setFrozen($frozen)
    {
        $this->frozen = $frozen;

        return $this;
    }

    /**
     * Get frozen
     *
     * @return boolean 
     */
    public function getFrozen()
    {
        return $this->frozen;
    }
}
