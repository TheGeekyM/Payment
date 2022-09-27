<?php

namespace Payment\Entities;

use Exception;
use Payment\Entities\Exceptions\InvalidTotalOrderAmountException;
use Payment\Enums\PaymentGateways;
use Payment\Enums\PaymentMethods;
use Payment\ValueObjects\Money;

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
    private PaymentMethods $paymentMethod;

    /**
     * @var Money
     */
    private Money $shippingAmount;

    /**
     * @var Money
     */
    private Money $taxAmount;

    /**
     * @var array<OrderItem>
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

    /**
     * @var Money
     */
    private Money $discountAmount;

    /**
     * @var PaymentGateways
     */
    private PaymentGateways $paymentGateway;

    /**
     * @param string $referenceId
     * @return void
     */
    public function setReferenceId(string $referenceId): void
    {
        $this->referenceId = $referenceId;
    }

    /**
     * @param string $locale
     * @return void
     */
    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    /**
     * @param Money $amount
     * @return void
     */
    public function setAmount(Money $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @param PaymentMethods $paymentType
     * @return void
     */
    public function setPaymentMethod(PaymentMethods $paymentType): void
    {
        $this->paymentMethod = $paymentType;
    }

    /**
     * @param Money $taxAmount
     * @return void
     */
    public function setTaxAmount(Money $taxAmount): void
    {
        $this->taxAmount = $taxAmount;
    }

    /**
     * @param Money $shippingAmount
     * @return void
     */
    public function setShippingAmount(Money $shippingAmount): void
    {
        $this->shippingAmount = $shippingAmount;
    }

    /**
     * @param array $orderItemArray
     * @return void
     */
    public function setItems(array $orderItemArray): void
    {
        $this->orderItemArray = $orderItemArray;
    }

    /**
     * @param Address $billing
     * @return void
     */
    public function setBillingAddress(Address $billing): void
    {
        $this->billing = $billing;
    }

    /**
     * @param Customer $consumer
     * @return void
     */
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
    public function getPaymentMethod(): PaymentMethods
    {
        return $this->paymentMethod;
    }

    /**
     * @return PaymentGateways
     */
    public function getPaymentGateway(): PaymentGateways
    {
        return $this->paymentGateway;
    }

    /**
     * @param PaymentGateways $getPaymentMethod
     * @return void
     */
    public function setPaymentGateway(PaymentGateways $getPaymentMethod): void
    {
        $this->paymentGateway = $getPaymentMethod;
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
    public function getItems(): array
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

    public function setDiscountAmount(Money $discountAmount): void
    {
        $this->discountAmount = $discountAmount;
    }

    /**
     * @return Money
     */
    public function getDiscountAmount(): Money
    {
        return $this->discountAmount;
    }

    /**
     * @return Money
     * @throws InvalidTotalOrderAmountException
     */
    public function calculateTotalAmount(): Money
    {
        $itemsTotalAmount = array_map( static function ($item){
            return $item->getTotalAmount()->amount();
        }, $this->getItems());

        $orderAmount = $this->amount->amount();
        $itemsTotalAmount = array_sum($itemsTotalAmount);

        if($itemsTotalAmount !== $orderAmount){
            throw new InvalidTotalOrderAmountException("Items Total Amount {$itemsTotalAmount} must be equal to order sub amount {$orderAmount}");
        }

        $total = ($orderAmount + $this->shippingAmount->amount() + $this->taxAmount->amount()) - $this->discountAmount->amount();
        return new Money($total, $this->amount->currency());
    }
}
