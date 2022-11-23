<?php

namespace Payment\Services;

use Payment\Contracts\PaymentRequestBuilderInterface;
use Payment\Dtos\PaymentAssemblerDto;
use Payment\Entities\Address;
use Payment\Entities\Customer;
use Payment\Entities\Exceptions\AmountIsLessThanZeroException;
use Payment\Entities\Exceptions\InvalidCurrency;
use Payment\Entities\Exceptions\InvalidNameProvided;
use Payment\Entities\Order;
use Payment\Entities\OrderItem;
use Payment\ValueObjects\Money;

class PaymentRequestBuilder implements PaymentRequestBuilderInterface
{
    private PaymentAssemblerDto $paymentAssemblerDto;

    /**
     * @throws InvalidNameProvided
     * @throws InvalidCurrency
     * @throws AmountIsLessThanZeroException
     */
    public function build(PaymentAssemblerDto $paymentAssemblerDto): Order
    {
        $this->paymentAssemblerDto = $paymentAssemblerDto;

        # order items
        $orderItemArray = $this->buildOrderItems();

        # billing address
        $billing = $this->buildBillingAddress();

        # consumer
        $consumer = $this->buildCustomer();

        # order
        return $this->buildOrder($consumer, $billing, $orderItemArray);
    }

    /**
     * @throws InvalidCurrency
     * @throws AmountIsLessThanZeroException
     */
    private function buildOrder(Customer $consumer, Address $billing, array $orderItemArray): Order
    {
        $order = new Order();
        $order->setReferenceId($this->paymentAssemblerDto->getOrderDto()->getReferenceId());
        $order->setLocale($this->paymentAssemblerDto->getCustomerDto()->getLanguage());
        {
            $order->setAmount(new Money($this->paymentAssemblerDto->getOrderDto()->getAmount(), $this->paymentAssemblerDto->getOrderDto()->getCurrency()));
            $order->setTaxAmount(new Money(0.00, $this->paymentAssemblerDto->getOrderDto()->getCurrency()));
            $order->setShippingAmount(new Money(0.00, $this->paymentAssemblerDto->getOrderDto()->getCurrency()));
            $order->setDiscountAmount(new Money(0.00, $this->paymentAssemblerDto->getOrderDto()->getCurrency()));
        }
        $order->setPaymentMethod($this->paymentAssemblerDto->getPaymentDto()->getPaymentMethod());
        $order->setPaymentGateway($this->paymentAssemblerDto->getPaymentDto()->getPaymentGateway());
        $order->setConsumer($consumer);
        $order->setBillingAddress($billing);
        $order->setItems($orderItemArray);

        return $order;
    }

    /**
     * @throws InvalidNameProvided
     */
    private function buildCustomer(): Customer
    {
        $consumer = new Customer();
        $consumer->setId($this->paymentAssemblerDto->getCustomerDto()->getId());
        $consumer->setIp($this->paymentAssemblerDto->getCustomerDto()->getIp());
        $consumer->setFirstName($this->paymentAssemblerDto->getCustomerDto()->getName());
        $consumer->setLastName($this->paymentAssemblerDto->getCustomerDto()->getName());
        $consumer->setEmail($this->paymentAssemblerDto->getCustomerDto()->getEmail());
        $consumer->setPhoneNumber($this->paymentAssemblerDto->getCustomerDto()->getPhone());

        return $consumer;
    }

    /**
     * @throws InvalidCurrency
     * @throws AmountIsLessThanZeroException
     */
    private function buildOrderItems(): array
    {
        $orderItemArray = [];
        foreach ($this->paymentAssemblerDto->getOrderDto()->getItems() as $item) {
            $orderItem = new OrderItem();
            $orderItem->setName($item->getTitle());
            $orderItem->setQuantity(1);
            $orderItem->setCategory($item->getCategory());
            $orderItem->setSku($item->getSku());
            $orderItem->setTotalAmount(new Money($item->getPrice(), $this->paymentAssemblerDto->getOrderDto()->getCurrency()));
            $orderItem->setReferenceId($item->getReferenceId());
            $orderItemArray[] = $orderItem;
        }

        return $orderItemArray;
    }

    /**
     * @throws InvalidNameProvided
     */
    private function buildBillingAddress(): Address
    {
        $billing = new Address();
        $billing->setFirstName($this->paymentAssemblerDto->getCustomerDto()->getName());
        $billing->setLastName($this->paymentAssemblerDto->getCustomerDto()->getName());
        $billing->setAddress($this->paymentAssemblerDto->getShippingAddressDto()->getAddress());
        $billing->setCity($this->paymentAssemblerDto->getShippingAddressDto()->getCity());
        $billing->setPhoneNumber($this->paymentAssemblerDto->getCustomerDto()->getPhone());
        $billing->setCountryCode($this->paymentAssemblerDto->getShippingAddressDto()->getCountry());
        $billing->setCountry($this->paymentAssemblerDto->getShippingAddressDto()->getCountry());
        $billing->setZipCode($this->paymentAssemblerDto->getShippingAddressDto()->getZip());

        return $billing;
    }
}
