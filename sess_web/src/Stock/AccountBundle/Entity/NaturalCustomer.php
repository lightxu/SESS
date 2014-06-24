<?php
namespace Stock\AccountBundle\Entity;

class NaturalCustomer
{

    protected $customer_id;

    protected $name;

    protected $id_number;
    protected $register_date;
    protected $gender;
    protected $address;
    protected $occupation;
    protected $educational_background;
    protected $company_or_organization;
    protected $tel;
    protected $agent_id;
    protected $bank;
    protected $frozen;

    /**
     * Set customer_id
     *
     * @param string $customerId
     * @return NaturalCustomer
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
     * @return NaturalCustomer
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
     * Set id_number
     *
     * @param string $idNumber
     * @return NaturalCustomer
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
     * Set register_date
     *
     * @param \DateTime $registerDate
     * @return NaturalCustomer
     */
    public function setRegisterDate($registerDate)
    {
        $this->register_date = $registerDate;

        return $this;
    }

    /**
     * Get register_date
     *
     * @return \DateTime 
     */
    public function getRegisterDate()
    {
        return $this->register_date;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return NaturalCustomer
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return NaturalCustomer
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set occupation
     *
     * @param string $occupation
     * @return NaturalCustomer
     */
    public function setOccupation($occupation)
    {
        $this->occupation = $occupation;

        return $this;
    }

    /**
     * Get occupation
     *
     * @return string 
     */
    public function getOccupation()
    {
        return $this->occupation;
    }

    /**
     * Set educational_background
     *
     * @param string $educationalBackground
     * @return NaturalCustomer
     */
    public function setEducationalBackground($educationalBackground)
    {
        $this->educational_background = $educationalBackground;

        return $this;
    }

    /**
     * Get educational_background
     *
     * @return string 
     */
    public function getEducationalBackground()
    {
        return $this->educational_background;
    }

    /**
     * Set company_or_organization
     *
     * @param string $companyOrOrganization
     * @return NaturalCustomer
     */
    public function setCompanyOrOrganization($companyOrOrganization)
    {
        $this->company_or_organization = $companyOrOrganization;

        return $this;
    }

    /**
     * Get company_or_organization
     *
     * @return string 
     */
    public function getCompanyOrOrganization()
    {
        return $this->company_or_organization;
    }

    /**
     * Set tel
     *
     * @param string $tel
     * @return NaturalCustomer
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string 
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set agent_id
     *
     * @param string $agentId
     * @return NaturalCustomer
     */
    public function setAgentId($agentId)
    {
        $this->agent_id = $agentId;

        return $this;
    }

    /**
     * Get agent_id
     *
     * @return string 
     */
    public function getAgentId()
    {
        return $this->agent_id;
    }

    /**
     * Set bank
     *
     * @param string $bank
     * @return NaturalCustomer
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
     * @return NaturalCustomer
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
     * @return NaturalCustomer
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
