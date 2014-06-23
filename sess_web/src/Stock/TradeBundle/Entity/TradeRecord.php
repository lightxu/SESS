<?php

namespace Stock\TradeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TradeRecord
 */
class TradeRecord
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $buyerId;

    /**
     * @var string
     */
    private $sellerId;

    /**
     * @var string
     */
    private $stockId;

    /**
     * @var integer
     */
    private $amount;

    /**
     * @var float
     */
    private $price;

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
     * Set buyerId
     *
     * @param string $buyerId
     * @return TradeRecord
     */
    public function setBuyerId($buyerId)
    {
        $this->buyerId = $buyerId;

        return $this;
    }

    /**
     * Get buyerId
     *
     * @return string 
     */
    public function getBuyerId()
    {
        return $this->buyerId;
    }

    /**
     * Set sellerId
     *
     * @param string $sellerId
     * @return TradeRecord
     */
    public function setSellerId($sellerId)
    {
        $this->sellerId = $sellerId;

        return $this;
    }

    /**
     * Get sellerId
     *
     * @return string 
     */
    public function getSellerId()
    {
        return $this->sellerId;
    }

    /**
     * Set stockId
     *
     * @param string $stockId
     * @return TradeRecord
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
     * Set amount
     *
     * @param integer $amount
     * @return TradeRecord
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return integer 
     */
    public function getAmount()
    {
        return $this->amount;
    }
    
    /**
     * Set price
     *
     * @param float $price
     * @return TradeRecord
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return integer 
     */
    public function getPrice()
    {
        return $this->price;
    }
}
