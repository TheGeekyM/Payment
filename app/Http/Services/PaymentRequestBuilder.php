<?php

namespace App\Http\Services;

use App\Http\Contracts\PaymentRequestBuilderInterface;
use App\Http\Dtos\PaymentAssemblerDto;
use App\Http\Entities\Address;
use App\Http\Entities\Customer;
use App\Http\Entities\MerchantUrl;
use App\Http\Entities\Order;
use App\Http\Entities\OrderItem;
use App\Http\ValueObjects\Money;

class PaymentRequestBuilder implements PaymentRequestBuilderInterface
{
    private PaymentAssemblerDto $paymentAssemblerDto;

    public function build(PaymentAssemblerDto $paymentAssemblerDto): Order
    {
        $this->paymentAssemblerDto = $paymentAssemblerDto;

        # order items
        $orderItemArray = $this->buildOrderItems();

        # billing address
        $billing = $this->buildBillingAddress();

        # consumer
        $consumer = $this->buildCustomer();

        # merchant urls
        $merchantUrl = $this->buildMerchantUrl();

        # order
        return $this->buildOrder($merchantUrl, $consumer, $billing, $orderItemArray);
    }

    private function buildOrderItems(): array
    {
        $orderItemArray = [];
        foreach ($this->paymentAssemblerDto->getOrderDto()->getItems() as $item) {
            $orderItem = new OrderItem();
            $orderItem->setName($item->getTitle());
            $orderItem->setQuantity(1);
            $orderItem->setType($item->getCategory());
            $orderItem->setSku('SKU-123');
            $orderItem->setTotalAmount(new Money($item->getPrice(), $this->paymentAssemblerDto->getOrderDto()->getCurrency()));
            $orderItem->setReferenceId($item->getId());
            $orderItemArray[] = $orderItem;
        }

        return $orderItemArray;
    }

    private function buildBillingAddress(): Address
    {
        $billing = new Address();
        $billing->setFirstName($this->paymentAssemblerDto->getCustomerDto()->getName());
        $billing->setLastName($this->paymentAssemblerDto->getCustomerDto()->getName());
        $billing->setLine1($this->paymentAssemblerDto->getShippingAddressDto()->getAddress());
        $billing->setCity($this->paymentAssemblerDto->getShippingAddressDto()->getCity());
        $billing->setPhoneNumber($this->paymentAssemblerDto->getCustomerDto()->getPhone());
        $billing->setCountryCode($this->paymentAssemblerDto->getOrderDto()->getCurrency());

        return $billing;
    }

    private function buildCustomer(): Customer
    {
        $consumer = new Customer();
        $consumer->setFullName($this->paymentAssemblerDto->getCustomerDto()->getName());
        $consumer->setFirstName($this->paymentAssemblerDto->getCustomerDto()->getName());
        $consumer->setLastName($this->paymentAssemblerDto->getCustomerDto()->getName());
        $consumer->setEmail($this->paymentAssemblerDto->getCustomerDto()->getEmail());
        $consumer->setPhoneNumber($this->paymentAssemblerDto->getCustomerDto()->getPhone());

        return $consumer;
    }

    private function buildMerchantUrl(): MerchantUrl
    {
        $merchantUrl = new MerchantUrl();
        $merchantUrl->setSuccessUrl(url(config('tamara.callback_url')));
        $merchantUrl->setFailureUrl(url(config('tamara.callback_url')));
        $merchantUrl->setCancelUrl(url(config('tamara.callback_url')));
        $merchantUrl->setNotificationUrl(url(config('tamara.callback_url')));

        return $merchantUrl;
    }

    private function buildOrder(MerchantUrl $merchantUrl, Customer $consumer, Address $billing, array $orderItemArray): Order
    {
        $order = new Order();
        $order->setOrderReferenceId($this->paymentAssemblerDto->getOrderDto()->getReferenceId());
        $order->setLocale($this->paymentAssemblerDto->getCustomerDto()->getLanguage());
        $order->setAmount(new Money($this->paymentAssemblerDto->getOrderDto()->getAmount(), $this->paymentAssemblerDto->getOrderDto()->getCurrency()));
        $order->setCountryCode($this->paymentAssemblerDto->getOrderDto()->getCurrency());
        $order->setPaymentType($this->paymentAssemblerDto->getPaymentDto()->getPaymentMethod());
        $order->setDescription('order description');
        $order->setTaxAmount(new Money(0.00, $this->paymentAssemblerDto->getOrderDto()->getCurrency()));
        $order->setShippingAmount(new Money(0.00, $this->paymentAssemblerDto->getOrderDto()->getCurrency()));
        $order->setMerchantUrl($merchantUrl);
        $order->setConsumer($consumer);
        $order->setBillingAddress($billing);
        $order->setItems($orderItemArray);

        return $order;
    }

}
