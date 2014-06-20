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
     * @var string
     */
    private $stockId;

    /**
     * @var string
     */
    private $totalAmount;

    /**
     * @var string
     */
    private $frozenAmount;

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
     * @param string $stockId
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
     * @return string 
     */
    public function getStockId()
    {
        return $this->stockId;
    }

    /**
     * Set totalAmount
     *
     * @param string $totalAmount
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
     * @return string 
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    /**
     * Set frozenAmount
     *
     * @param string $frozenAmount
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
     * @return string 
     */
    public function getFrozenAmount()
    {
        return $this->frozenAmount;
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
