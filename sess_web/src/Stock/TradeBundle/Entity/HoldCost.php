<?php

namespace Stock\TradeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HoldCost
 */
class HoldCost
{
    /**
     * @var string
     */
    private $customerId;

    /**
     * @var float
     */
    private $holdCost;
    
    /**
     * @var integer
     */
    private $totalAmount;

    /**
     * Set customerId
     *
     * @param string $customerId
     * @return HoldCost
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;

        return $this;
    }

    /**
     * Get customerId
     *
     * @return string 
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * Set holdCost
     *
     * @param float $holdCost
     * @return HoldCost
     */
    public function setHoldCost($holdCost)
    {
        $this->holdCost = $holdCost;

        return $this;
    }

    /**
     * Get holdCost
     *
     * @return float 
     */
    public function getHoldCost()
    {
        return $this->holdCost;
    }
    
    /**
     * Set totalAmount
     *
     * @param integer $totalAmount
     * @return TotalAmount
     */
    public function setTotalAmount($totalAmount)
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    /**
     * Get totalAmount
     *
     * @return integer 
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }
}
