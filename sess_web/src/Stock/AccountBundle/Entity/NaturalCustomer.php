<?php
namespace Stock\AccountBundle\Entity;

class NaturalCustomer
{

    protected $customer_id;

    protected $name;

    protected $id_number;
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $length;


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
     * Set type
     *
     * @param string $type
     * @return NaturalCustomer
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set length
     *
     * @param string $length
     * @return NaturalCustomer
     */
    public function setLength($length)
    {
        $this->length = $length;
    
        return $this;
    }

    /**
     * Get length
     *
     * @return string 
     */
    public function getLength()
    {
        return $this->length;
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
}
