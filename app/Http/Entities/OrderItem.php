<?php

namespace App\Http\Entities;

use App\Http\ValueObjects\Money;

class OrderItem
{

    /**
     * @var string
     */
    private string $referenceId;

    /**
     * @var Money
     */
    private Money $taxAmount;

    /**
     * @var Money
     */
    private Money $totalAmount;

    /**
     * @var string
     */
    private string $sku;

    /**
     * @var string
     */
    private string $type;

    /**
     * @var Money
     */
    private Money $unitPrice;

    /**
     * @var int
     */
    private int $quantity;

    /**
     * @var string
     */
    private string $name;

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }


    public function setUnitPrice(Money $unitPrice): void
    {
        $this->unitPrice = $unitPrice;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function setSku(string $sku): void
    {
        $this->sku = $sku;
    }

    public function setTotalAmount(Money $totalAmount): void
    {
        $this->totalAmount = $totalAmount;
    }

    public function setTaxAmount(Money $taxAmount): void
    {
        $this->taxAmount = $taxAmount;
    }

    public function setDiscountAmount(Money $discountAmount): void
    {
        $this->DdscountAmount = $discountAmount;
    }

    public function setReferenceId(string $referenceId): void
    {
        $this->referenceId = $referenceId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return Money
     */
    public function getUnitPrice(): Money
    {
        return $this->unitPrice;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * @return Money
     */
    public function getTotalAmount(): Money
    {
        return $this->totalAmount;
    }

    /**
     * @return Money
     */
    public function getTaxAmount(): Money
    {
        return $this->taxAmount;
    }

    /**
     * @return string
     */
    public function getReferenceId(): string
    {
        return $this->referenceId;
    }
}
