<?php

namespace App\Http\Services\Tabby;

use App\Http\Entities\Order;

class TabbyRequestMapper
{
    public static function createCheckoutRequest(Order $order): array
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
                    "address" => $order->getBilling()->getAddress(),
                    "zip" => $order->getBilling()->getZipCode()
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
                "success" => url(config('tabby.callback_url')),
                "cancel" => url(config('tabby.callback_url')),
                "failure" => url(config('tabby.callback_url'))
            ]];
    }

}
