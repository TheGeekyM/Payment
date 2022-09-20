<?php

namespace App\Http\Services\Tabby;

use App\Http\Dtos\PaymentAssemblerDto;
use App\Http\Entities\Address;
use App\Http\Entities\Customer;
use App\Http\Entities\MerchantUrl;
use App\Http\Entities\Order;
use App\Http\Entities\OrderItem;
use App\Http\ValueObjects\Money;
use http\Exception\InvalidArgumentException;

class TabbyRequestBuilder
{
    public function build(PaymentAssemblerDto $paymentAssemblerDto): array
    {
        # order items
        $orderItemArray = [];
        foreach ($paymentAssemblerDto->getOrderDto()->getItems() as $item) {
            $orderItem = new OrderItem();
            $orderItem->setName($item->getTitle());
            $orderItem->setQuantity(1);
            $orderItem->setType($item->getCategory());
            $orderItem->setSku('SKU-123');
            $orderItem->setTotalAmount(new Money($item->getPrice(),  $paymentAssemblerDto->getOrderDto()->getCurrency()));
            $orderItem->setReferenceId($item->getId());
            $orderItemArray[] = $orderItem;
        }

        # billing address
        $billing = new Address();
        $billing->setFirstName($paymentAssemblerDto->getCustomerDto()->getName());
        $billing->setLastName($paymentAssemblerDto->getCustomerDto()->getName());
        $billing->setLine1($paymentAssemblerDto->getShippingAddressDto()->getAddress());
        $billing->setCity($paymentAssemblerDto->getShippingAddressDto()->getCity());
        $billing->setPhoneNumber($paymentAssemblerDto->getCustomerDto()->getPhone());
        $billing->setCountryCode('SA');

        # consumer
        $consumer = new Customer();
        $consumer->setFullName($paymentAssemblerDto->getCustomerDto()->getName());
        $consumer->setFirstName($paymentAssemblerDto->getCustomerDto()->getName());
        $consumer->setLastName($paymentAssemblerDto->getCustomerDto()->getName());
        $consumer->setEmail($paymentAssemblerDto->getCustomerDto()->getEmail());
        $consumer->setPhoneNumber($paymentAssemblerDto->getCustomerDto()->getPhone());

        # merchant urls
        $merchantUrl = new MerchantUrl();
        $merchantUrl->setSuccessUrl(url(config('tabby.callback_url')));
        $merchantUrl->setFailureUrl(url(config('tabby.callback_url')));
        $merchantUrl->setCancelUrl(url(config('tabby.callback_url')));
        $merchantUrl->setNotificationUrl(url(config('tabby.callback_url')));

        $order = new Order();
        $order->setOrderReferenceId($paymentAssemblerDto->getOrderDto()->getReferenceId());
        $order->setLocale($paymentAssemblerDto->getCustomerDto()->getLanguage());
        $order->setAmount(new Money($paymentAssemblerDto->getOrderDto()->getAmount(), $paymentAssemblerDto->getOrderDto()->getCurrency()));
        $order->setCountryCode("SA");
        $order->setPaymentType('PAY_BY_INSTALMENTS');
        $order->setDescription('order description');
        $order->setTaxAmount(new Money(0.00, $paymentAssemblerDto->getOrderDto()->getCurrency()));
        $order->setShippingAmount(new Money(0.00, $paymentAssemblerDto->getOrderDto()->getCurrency()));
        $order->setMerchantUrl($merchantUrl);
        $order->setConsumer($consumer);
        $order->setBillingAddress($billing);
        $order->setItems($orderItemArray);

        return $this->createCheckoutRequest($order);
    }

    public function createCheckoutRequest(Order $order): array
    {
        return [
            "payment" => [
                "amount" =>  $order->getAmount()->amount(),
                "currency" => $order->getAmount()->currency(),
                'description' => $order->getDescription(),
                "buyer" => [
                    "phone" => $order->getConsumer()->getPhoneNumber(),
                    "email" => $order->getConsumer()->getEmail(),
                    "name" => $order->getConsumer()->getFullName(),
                ],
                "shipping_address" => [
                    "city" => $order->getBilling()->getCity(),
                    "address" => $order->getBilling()->getLine1(),
                    "zip" => $order->getBilling()->getCountryCode() //neeeds to be changed
                ],
                "order" => [
                    "reference_id" => $order->getOrderReferenceId(),
                    "items" => $order->getOrderItemArray()
                ],
                "buyer_history" => [
                    "registered_since" => "2019-08-24T14:15:22Z",
                    "loyalty_level" => 0,
                ],
                "order_history" => [
                    [
                        "purchased_at" => "2019-08-24T14:15:22Z",
                        "amount" => "00.00",
                        "payment_method" => "card",
                        "status" => "new",
                    ]
                ],
            ],
            "lang" => $order->getLocale(),
            "merchant_code" => config('tabby.merchant_code'),
            "merchant_urls" => [
                "success" => $order->getMerchantUrl()->getSuccessUrl(),
                "cancel" => $order->getMerchantUrl()->getCancelUrl(),
                "failure" => $order->getMerchantUrl()->getFailureUrl()
            ]];
    }

}
