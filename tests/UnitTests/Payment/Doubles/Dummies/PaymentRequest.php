<?php

namespace UnitTests\Payment\Doubles\Dummies;

class PaymentRequest
{
    public static function getOptimalRequest(string $gateway)
    {
        return [
            "payment" => [
                "gateway" => $gateway,
                "method" => "banktransfer"
            ],
            "order" => [
                "reference_id" => "1234243-22Z",
                "amount" => "10000.00",
                "currency" => "EGP",
                "items" => [
                    [
                        "reference_id" => "5484-dd",
                        "sku" => "111-222abc",
                        "title" => "string",
                        "quantity" => 1,
                        "price" => "0.00",
                        "category" => "string"
                    ],
                    [
                        "reference_id" => "eed5",
                        "sku" => "111-222abc",
                        "title" => "string",
                        "quantity" => 1,
                        "price" => "0.00",
                        "category" => "string"
                    ]
                ]
            ],
            "customer" => [
                "id" => 1,
                "ip" => "127.0.0.1",
                "phone" => "500000001",
                "name" => "Mohamed Emad",
                "email" => "successful.payment@tabby.ai",
                "language" => "en"
            ],
            "shipping_address" => [
                "country" => "saudi arabia",
                "city" => "cairo",
                "address" => "Maadi",
                "zip" => "1234"
            ]
        ];
    }
}
