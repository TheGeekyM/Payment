<?php

namespace App\Http\Services\Paymob;

use App\Http\Dtos\PaymentAssemblerDto;

class PaymobRequestBuilder
{

    /**
     * @param PaymentAssemblerDto $paymentAssemblerDto
     * @param string $token
     * @return array
     */
    public function buildOrderRegistration(PaymentAssemblerDto $paymentAssemblerDto, string $token): array
    {
        return [
            'auth_token' => $token,
            "amount_cents" => $paymentAssemblerDto->getOrderDto()->getAmount(),
            "delivery_needed" => "false",
            'currency' => $paymentAssemblerDto->getOrderDto()->getCurrency(),
            'notify_user_with_email' => TRUE,
            "items" => [
                [
                    "name" => "ASC1515",
                    "amount_cents" => $paymentAssemblerDto->getOrderDto()->getAmount(),
                    "description" => "Smart Watch",
                    "quantity" => "1"
                ]
            ],
        ];
    }

    /**
     * @param PaymentAssemblerDto $paymentAssemblerDto
     * @param string $token
     * @param int $orderId
     * @param int $integrationId
     * @return array
     */
    public function buildPaymentKeyRequest(PaymentAssemblerDto $paymentAssemblerDto, string $token, int $orderId, int $integrationId): array
    {
        return [
            'auth_token' => $token,
            "amount_cents" => $paymentAssemblerDto->getOrderDto()->getAmount(),
            'currency' => $paymentAssemblerDto->getOrderDto()->getCurrency(),
            'order_id' => $orderId,
            "expiration" => 3600,
            "integration_id" => $integrationId,
            "lock_order_when_paid" => "false",
            "billing_data" => [
                "first_name" => $paymentAssemblerDto->getCustomerDto()->getName(),
                "email" => $paymentAssemblerDto->getCustomerDto()->getEmail(),
                "apartment" => "NA",
                "floor" => "NA",
                "street" => $paymentAssemblerDto->getShippingAddressDto()->getAddress(),
                "building" => "NA",
                "phone_number" => $paymentAssemblerDto->getCustomerDto()->getPhone(),
                "shipping_method" => "NA",
                "postal_code" => $paymentAssemblerDto->getShippingAddressDto()->getZip(),
                "city" => $paymentAssemblerDto->getShippingAddressDto()->getCity(),
                "country" => "NA",
                "last_name" => "Nicolas",
                "state" => "NA"
            ]
        ];
    }
}
