<?php

namespace Payment\Entities;

use Payment\ValueObjects\Money;

class OrderItem
{

    /**
     * @var string
     */
    private string $referenceId;


    /**
     * @var string
     */
    private string $sku;

    /**
     * @var string
     */
    private string $category;

    /**
     * @var int
     */
    private int $quantity;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var Money
     */
    private Money $totalAmount;

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param int $quantity
     * @return void
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param string $category
     * @return void
     */
    public function setCategory(string $category): void
    {
        $this->category = $category;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @param string $sku
     * @return void
     */
    public function setSku(string $sku): void
    {
        $this->sku = $sku;
    }

    /**
     * @return string
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * @param Money $totalAmount
     * @return void
     */
    public function setTotalAmount(Money $totalAmount): void
    {
        $this->totalAmount = $totalAmount;
    }

    /**
     * @return Money
     */
    public function getTotalAmount(): Money
    {
        return $this->totalAmount;
    }

    public function setReferenceId(string $referenceId): void
    {
        $this->referenceId = $referenceId;
    }

    /**
     * @return string
     */
    public function getReferenceId(): string
    {
        return $this->referenceId;
    }
}
