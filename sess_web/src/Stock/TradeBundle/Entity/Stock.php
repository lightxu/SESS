<?php

namespace Stock\TradeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stock
 */
class Stock
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $stockId;

    /**
     * @var integer
     */
    private $totalAmount;

    /**
     * @var integer
     */
    private $frozenAmount;

    /**
     * @var float
     */
    private $holdCost;

    /**
     * @var string
     */
    private $accountId;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set stockId
     *
     * @param integer $stockId
     * @return Stock
     */
    public function setStockId($stockId)
    {
        $this->stockId = $stockId;

        return $this;
    }

    /**
     * Get stockId
     *
     * @return integer 
     */
    public function getStockId()
    {
        return $this->stockId;
    }

    /**
     * Set totalAmount
     *
     * @param integer $totalAmount
     * @return Stock
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

    /**
     * Set frozenAmount
     *
     * @param integer $frozenAmount
     * @return Stock
     */
    public function setFrozenAmount($frozenAmount)
    {
        $this->frozenAmount = $frozenAmount;

        return $this;
    }

    /**
     * Get frozenAmount
     *
     * @return integer 
     */
    public function getFrozenAmount()
    {
        return $this->frozenAmount;
    }

    /**
     * Set holdCost
     *
     * @param float $holdCost
     * @return Stock
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
     * Set accountId
     *
     * @param string $accountId
     * @return Stock
     */
    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;

        return $this;
    }

    /**
     * Get accountId
     *
     * @return string 
     */
    public function getAccountId()
    {
        return $this->accountId;
    }
}
