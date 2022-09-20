<?php

namespace App\Http\Entities;

use App\Http\ValueObjects\Money;

class OrderItem
{

    /**
     * @var int
     */
    private int $referenceId;

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

    public function setReferenceId(int $referenceId): void
    {
        $this->referenceId = $referenceId;
    }
}
