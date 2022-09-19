<?php

namespace App\Http\Services\Tabby;

use App\Http\Dtos\PaymentAssemblerDto;
use http\Exception\InvalidArgumentException;

class TabbyRequestBuilder
{
    public function build(PaymentAssemblerDto $paymentAssemblerDto): array
    {
        return [
            "payment" => [
                "amount" => $this->setAmount($paymentAssemblerDto->getOrderDto()->getAmount()),
                "currency" => $this->setCurrency($paymentAssemblerDto->getOrderDto()->getCurrency()),
                'description' => '',
                "buyer" => [
                    "phone" => $paymentAssemblerDto->getCustomerDto()->getPhone(),
                    "email" => $paymentAssemblerDto->getCustomerDto()->getEmail(),
                    "name" => $paymentAssemblerDto->getCustomerDto()->getName(),
                ],
                "shipping_address" => [
                    "city" => $paymentAssemblerDto->getShippingAddressDto()->getCity(),
                    "address" => $paymentAssemblerDto->getShippingAddressDto()->getAddress(),
                    "zip" => $paymentAssemblerDto->getShippingAddressDto()->getZip()
                ],
                "order" => [
                    "reference_id" => $paymentAssemblerDto->getOrderDto()->getReferenceId(),
                    "items" => $paymentAssemblerDto->getOrderDto()->getItems()
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
            "lang" => $paymentAssemblerDto->getCustomerDto()->getLanguage(),
            "merchant_code" => config('tabby.merchant_code'),
            "merchant_urls" => [
                "success" => url(config('tabby.callback_url')),
                "cancel" => url(config('tabby.callback_url')),
                "failure" => url(config('tabby.callback_url'))
            ]];
    }

    private function setAmount(float $amount): float
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException("Amount must be greater than zero");
        }
        return $amount;
    }

    private function setCurrency(string $currency): string
    {
        if (!in_array($currency, ['EGP', 'SAR'])) {
            throw new InvalidArgumentException("Currency must be SAR or EGP");
        }
        return $currency;
    }
}
