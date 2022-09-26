<?php

namespace Payment\Services\Tamara;

use Payment\Entities\Order;
use Payment\Entities\OrderItem;

class TamaraRequestMapper
{
    public static function map(Order $order): array
    {
        return [
            "order_reference_id" => $order->getReferenceId(),
            "order_number" => $order->getReferenceId(),
            "total_amount" => [
                "amount" => $order->getAmount()->amount(),
                "currency" => $order->getAmount()->currency()
            ],
            "description" => $order->getDescription(),
            "country_code" => $order->getBilling()->getCountryCode(),
            "payment_type" => $order->getPaymentType()->name,
            "instalments" => NULL,
            "locale" => $order->getLocale(),
            "items" => array_map('self::mapItems', $order->getOrderItemArray()),
            "consumer" => [
                "first_name" => $order->getConsumer()->getFirstName(),
                "last_name" => $order->getConsumer()->getLastName(),
                "phone_number" => $order->getConsumer()->getPhoneNumber(),
                "email" => $order->getConsumer()->getEmail()
            ],
            "shipping_address" => [
                "first_name" => $order->getBilling()->getFirstName(),
                "last_name" => $order->getBilling()->getLastName(),
                "line1" => $order->getBilling()->getAddress(),
                "postal_code" => $order->getBilling()->getZipCode(),
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
                "success" => url(config('tamara.callback_url')),
                "failure" => url(config('tamara.callback_url')),
                "cancel" => url(config('tamara.callback_url')),
                "notification" => url(config('tamara.callback_url'))
            ],
            "expires_in_minutes" => 60,
        ];
    }

    public static function mapToPaymentMethodRequest(Order $order): array
    {
        return [
            "country" => $order->getBilling()->getCountryCode(),
            "order_value" => [
                "amount" => $order->getAmount()->amount(),
                "currency" => $order->getAmount()->currency()
            ],
            "phone_number" => $order->getConsumer()->getPhoneNumber(),
            "is_vip" => FALSE
        ];
    }

    public static function mapItems(OrderItem $item): array
    {
        return [
            "reference_id" => $item->getReferenceId(),
            "type" => $item->getType(),
            "name" => $item->getName(),
            "sku" => $item->getSku(),
            "quantity" => $item->getQuantity(),
            "unit_price" => [
                "amount" => "00.00",
                "currency" => $item->getTotalAmount()->currency()
            ],
            "discount_amount" => [
                "amount" => "00.00",
                "currency" => $item->getTotalAmount()->currency()
            ],
            "tax_amount" => [
                "amount" => "00.00",
                "currency" => $item->getTotalAmount()->currency()
            ],
            "total_amount" => [
                "amount" => $item->getTotalAmount()->amount(),
                "currency" => $item->getTotalAmount()->currency()
            ]
        ];
    }
}
