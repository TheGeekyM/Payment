<?php

namespace App\Http\Entities;

use App\Http\ValueObjects\Money;

class Order
{
    /**
     * @var string
     */
    private string $orderReferenceId;

    /**
     * @var string
     */
    private string $locale;

    /**
     * @var Money
     */
    private Money $amount;

    /**
     * @var string
     */
    private string $countryCode;

    /**
     * @var string
     */
    private string $paymentType;

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
     * @var MerchantUrl
     */
    private MerchantUrl $merchantUrl;

    /**
     * @var Customer
     */
    private Customer $consumer;

    /**
     * @var Address
     */
    private Address $billing;

    public function setOrderReferenceId(string $referenceId): void
    {
        $this->orderReferenceId = $referenceId;
    }

    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }


    public function setAmount(Money $amount): void
    {
        $this->amount = $amount;
    }

    public function setCountryCode(string $countryCode): void
    {
        $this->countryCode = strtoupper(substr($countryCode, 0, 2));
    }

    public function setPaymentType(string $paymentType): void
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

    public function setMerchantUrl(MerchantUrl $merchantUrl): void
    {
        $this->merchantUrl = $merchantUrl;
    }

    /**
     * @return int
     */
    public function getOrderReferenceId(): string
    {
        return $this->orderReferenceId;
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
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    /**
     * @return string
     */
    public function getPaymentType(): string
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
     * @return MerchantUrl
     */
    public function getMerchantUrl(): MerchantUrl
    {
        return $this->merchantUrl;
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
