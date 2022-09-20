<?php

namespace App\Http\Services\Tamara;

use App\Http\Entities\Order;

class TamaraRequestMapper
{
    public static function map(Order $order): array
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
                        "currency" => $order->getAmount()->currency()
                    ],
                    "discount_amount" => [
                        "amount" => "50.00",
                        "currency" => $order->getAmount()->currency()
                    ],
                    "tax_amount" => [
                        "amount" => "50.00",
                        "currency" => $order->getAmount()->currency()
                    ],
                    "total_amount" => [
                        "amount" => "50.00",
                        "currency" => $order->getAmount()->currency()
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
                "currency" => $order->getAmount()->currency(),
            ],
            "shipping_amount" => [
                "amount" => "00.00",
                "currency" => $order->getAmount()->currency(),
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
