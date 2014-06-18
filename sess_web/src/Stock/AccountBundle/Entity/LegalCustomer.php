<?php
namespace Stock\AccountBundle\Entity;

class LegalCustomer
{

    protected $customer_id;

    protected $name;

    protected $id_number;
    protected $legal_register_number;
    protected $license;
    protected $executor_name;
    protected $executor_id;
    protected $executor_address;
    protected $executor_tel;
    protected $bank;
    protected $assests_number;
    protected $frozen;

    /**
     * Set customer_id
     *
     * @param string $customerId
     * @return LegalCustomer
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

    /**
     * Set name
     *
     * @param string $name
     * @return LegalCustomer
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

    /**
     * Set legal_register_number
     *
     * @param string $legalRegisterNumber
     * @return LegalCustomer
     */
    public function setLegalRegisterNumber($legalRegisterNumber)
    {
        $this->legal_register_number = $legalRegisterNumber;

        return $this;
    }

    /**
     * Get legal_register_number
     *
     * @return string 
     */
    public function getLegalRegisterNumber()
    {
        return $this->legal_register_number;
    }

    /**
     * Set id_number
     *
     * @param string $idNumber
     * @return LegalCustomer
     */
    public function setIdNumber($idNumber)
    {
        $this->id_number = $idNumber;

        return $this;
    }

    /**
     * Get id_number
     *
     * @return string 
     */
    public function getIdNumber()
    {
        return $this->id_number;
    }

    /**
     * Set license
     *
     * @param string $license
     * @return LegalCustomer
     */
    public function setLicense($license)
    {
        $this->license = $license;

        return $this;
    }

    /**
     * Get license
     *
     * @return string 
     */
    public function getLicense()
    {
        return $this->license;
    }

    /**
     * Set executor_name
     *
     * @param string $executorName
     * @return LegalCustomer
     */
    public function setExecutorName($executorName)
    {
        $this->executor_name = $executorName;

        return $this;
    }

    /**
     * Get executor_name
     *
     * @return string 
     */
    public function getExecutorName()
    {
        return $this->executor_name;
    }

    /**
     * Set executor_id
     *
     * @param string $executorId
     * @return LegalCustomer
     */
    public function setExecutorId($executorId)
    {
        $this->executor_id = $executorId;

        return $this;
    }

    /**
     * Get executor_id
     *
     * @return string 
     */
    public function getExecutorId()
    {
        return $this->executor_id;
    }

    /**
     * Set executor_address
     *
     * @param string $executorAddress
     * @return LegalCustomer
     */
    public function setExecutorAddress($executorAddress)
    {
        $this->executor_address = $executorAddress;

        return $this;
    }

    /**
     * Get executor_address
     *
     * @return string 
     */
    public function getExecutorAddress()
    {
        return $this->executor_address;
    }

    /**
     * Set executor_tel
     *
     * @param string $executorTel
     * @return LegalCustomer
     */
    public function setExecutorTel($executorTel)
    {
        $this->executor_tel = $executorTel;

        return $this;
    }

    /**
     * Get executor_tel
     *
     * @return string 
     */
    public function getExecutorTel()
    {
        return $this->executor_tel;
    }

    /**
     * Set bank
     *
     * @param string $bank
     * @return LegalCustomer
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
     * Set assests_number
     *
     * @param string $assestsNumber
     * @return LegalCustomer
     */
    public function setAssestsNumber($assestsNumber)
    {
        $this->assests_number = $assestsNumber;

        return $this;
    }

    /**
     * Get assests_number
     *
     * @return string 
     */
    public function getAssestsNumber()
    {
        return $this->assests_number;
    }

    /**
     * Set frozen
     *
     * @param boolean $frozen
     * @return LegalCustomer
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
