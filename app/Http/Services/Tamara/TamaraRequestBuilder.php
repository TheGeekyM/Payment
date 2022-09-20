<?php

namespace App\Http\Services\Tamara;

use App\Http\Dtos\PaymentAssemblerDto;
use App\Http\Entities\Address;
use App\Http\Entities\Customer;
use App\Http\Entities\MerchantUrl;
use App\Http\Entities\Order;
use App\Http\Entities\OrderItem;
use App\Http\ValueObjects\Money;

class TamaraRequestBuilder
{
    public function build(PaymentAssemblerDto $paymentAssemblerDto): array
    {
        # order items
        $orderItemArray = [];
        foreach ($paymentAssemblerDto->getOrderDto()->getItems() as $item) {
            $orderItem = new OrderItem();
            $orderItem->setName($item->getTitle());
            $orderItem->setQuantity(1);
            $orderItem->setType('product');
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
        $consumer->setFirstName($paymentAssemblerDto->getCustomerDto()->getName());
        $consumer->setLastName($paymentAssemblerDto->getCustomerDto()->getName());
        $consumer->setEmail($paymentAssemblerDto->getCustomerDto()->getEmail());
        $consumer->setPhoneNumber($paymentAssemblerDto->getCustomerDto()->getPhone());

        # merchant urls
        $merchantUrl = new MerchantUrl();
        $merchantUrl->setSuccessUrl(url(config('tamara.callback_url')));
        $merchantUrl->setFailureUrl(url(config('tamara.callback_url')));
        $merchantUrl->setCancelUrl(url(config('tamara.callback_url')));
        $merchantUrl->setNotificationUrl(url(config('tamara.callback_url')));

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

    private function createCheckoutRequest(Order $order): array
    {
        return [
            "order_reference_id" => $order->getOrderReferenceId(),
            "order_number" => $order->getOrderReferenceId(),
            "total_amount" => [
                "amount" => $order->getAmount()->amount(),
                "currency" => $order->getAmount()->currency()
            ],
            "description" => $order->getDescription(),
            "country_code" => $order->getCountryCode(),
            "payment_type" => $order->getPaymentType(),
            "instalments" => NULL,
            "locale" => $order->getLocale(),
            "items" => [
                [
                    "reference_id" => "123456",
                    "type" => "Digital",
                    "name" => "Lego City 8601",
                    "sku" => "SA-12436",
                    "image_url" => "https://www.example.com/product.jpg",
                    "quantity" => 1,
                    "unit_price" => [
                        "amount" => "50.00",
                        "currency" => "SAR"
                    ],
                    "discount_amount" => [
                        "amount" => "50.00",
                        "currency" => "SAR"
                    ],
                    "tax_amount" => [
                        "amount" => "50.00",
                        "currency" => "SAR"
                    ],
                    "total_amount" => [
                        "amount" => "50.00",
                        "currency" => "SAR"
                    ]
                ]
            ],
            "consumer" => [
                "first_name" => $order->getConsumer()->getFirstName(),
                "last_name" => $order->getConsumer()->getLastName(),
                "phone_number" => $order->getConsumer()->getPhoneNumber(),
                "email" => $order->getConsumer()->getEmail()
            ],
            "shipping_address" => [
                "first_name" => $order->getBilling()->getFirstName(),
                "last_name" => $order->getBilling()->getLastName(),
                "line1" => $order->getBilling()->getLine1(),
                "postal_code" => $order->getBilling()->getCountryCode(), //neeeds to be changed
                "city" => $order->getBilling()->getCity(),
                "country_code" => $order->getBilling()->getCountryCode(),
                "phone_number" => $order->getConsumer()->getPhoneNumber()
            ],
            "tax_amount" => [
                "amount" => "00.00",
                "currency" => "SAR",
            ],
            "shipping_amount" => [
                "amount" => "00.00",
                "currency" => "SAR",
            ],
            "merchant_url" => [
                "success" => $order->getMerchantUrl()->getSuccessUrl(),
                "failure" => $order->getMerchantUrl()->getFailureUrl(),
                "cancel" => $order->getMerchantUrl()->getCancelUrl(),
                "notification" => $order->getMerchantUrl()->getNotificationUrl()
            ],
            "expires_in_minutes" => 60,
        ];
    }
}
