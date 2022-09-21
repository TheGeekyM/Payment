<?php

namespace App\Http\Entities;

use App\Http\Enums\PaymentMethods;
use App\Http\ValueObjects\Money;

class Order
{
    /**
     * @var string
     */
    private string $referenceId;

    /**
     * @var string
     */
    private string $locale;

    /**
     * @var Money
     */
    private Money $amount;

    /**
     * @var PaymentMethods
     */
    private PaymentMethods $paymentType;

    /**
     * @var ?string
     */
    private ?string $platform;

    /**
     * @var string
     */
    private string $description;

    /**
     * @var Money
     */
    private Money $shippingAmount;

    /**
     * @var Money
     */
    private Money $taxAmount;

    /**
     * @var array
     */
    private array $orderItemArray;

    /**
     * @var Customer
     */
    private Customer $consumer;

    /**
     * @var Address
     */
    private Address $billing;

    public function setReferenceId(string $referenceId): void
    {
        $this->referenceId = $referenceId;
    }

    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }


    public function setAmount(Money $amount): void
    {
        $this->amount = $amount;
    }

    public function setPaymentType(PaymentMethods $paymentType): void
    {
        $this->paymentType = $paymentType;
    }

    public function setPlatform(string $platform): void
    {
        $this->platform = $platform;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setTaxAmount(Money $taxAmount): void
    {
        $this->taxAmount = $taxAmount;
    }

    public function setShippingAmount(Money $shippingAmount): void
    {
        $this->shippingAmount = $shippingAmount;
    }

    public function getItems(): array
    {
        return $this->orderItemArray;
    }

    public function setItems(array $orderItemArray): void
    {
        $this->orderItemArray = $orderItemArray;
    }

    public function setBillingAddress(Address $billing): void
    {
        $this->billing = $billing;
    }

    public function setConsumer(Customer $consumer): void
    {
        $this->consumer = $consumer;
    }

    /**
     * @return string
     */
    public function getReferenceId(): string
    {
        return $this->referenceId;
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @return Money
     */
    public function getAmount(): Money
    {
        return $this->amount;
    }

    /**
     * @return PaymentMethods
     */
    public function getPaymentType(): PaymentMethods
    {
        return $this->paymentType;
    }

    /**
     * @return string
     */
    public function getPlatform(): string
    {
        return $this->platform;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return Money
     */
    public function getShippingAmount(): Money
    {
        return $this->shippingAmount;
    }

    /**
     * @return Money
     */
    public function getTaxAmount(): Money
    {
        return $this->taxAmount;
    }

    /**
     * @return array
     */
    public function getOrderItemArray(): array
    {
        return $this->orderItemArray;
    }

    /**
     * @return Customer
     */
    public function getConsumer(): Customer
    {
        return $this->consumer;
    }

    /**
     * @return Address
     */
    public function getBilling(): Address
    {
        return $this->billing;
    }
}
