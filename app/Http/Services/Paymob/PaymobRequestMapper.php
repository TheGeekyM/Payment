<?php

namespace App\Http\Services\Paymob;

use App\Http\Entities\Order;

class PaymobRequestMapper
{
    public static function buildOrderRegistration(Order $order, string $token): array
    {
        return [
            'auth_token' => $token,
            "amount_cents" => $order->getAmount()->amount(),
            "delivery_needed" => "false",
            'currency' => $order->getAmount()->currency(),
            'notify_user_with_email' => TRUE,
            "items" => [
                [
                    "name" => "ASC1515",
                    "amount_cents" => 100,
                    "description" => "Smart Watch",
                    "quantity" => "1"
                ]
            ],
        ];
    }

    public static function buildPaymentKeyRequest(Order $order, string $token, int $orderId, int $integrationId): array
    {
        return [
            'auth_token' => $token,
            "amount_cents" => $order->getAmount()->amount(),
            'currency' => $order->getAmount()->currency(),
            'order_id' => $orderId,
            "expiration" => 3600,
            "integration_id" => $integrationId,
            "lock_order_when_paid" => "false",
            "billing_data" => [
                "first_name" => $order->getConsumer()->getFirstName(),
                "last_name" => $order->getConsumer()->getLastName(),
                "email" => $order->getConsumer()->getEmail(),
                "apartment" => "NA",
                "floor" => "NA",
                "street" => $order->getBilling()->getAddress(),
                "building" => "NA",
                "phone_number" => $order->getConsumer()->getPhoneNumber(),
                "shipping_method" => "NA",
                "postal_code" => $order->getBilling()->getZipCode(), //neeeds to be changed
                "city" => $order->getBilling()->getCity(),
                "country" => $order->getBilling()->getCountry(),
                "state" => "NA"
            ]
        ];
    }
}
