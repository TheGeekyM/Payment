<?php

namespace App\Http\Factories;

use App\Http\Contracts\PaymentStrategyInterface;
use App\Http\Enums\PaymentGateways;
use App\Http\Libs\HttpClient;
use App\Http\Services\Payfort\PayFortStrategy;
use App\Http\Services\Payfort\Signature;
use App\Http\Services\Paymob\PaymobStrategy;
use App\Http\Services\Tabby\TabbyStrategy;

class PaymentFactory
{
    public static function getInstance(PaymentGateways $getPaymentMethod): PaymentStrategyInterface
    {
        return match ($getPaymentMethod) {
            PaymentGateways::paymob   => new PaymobStrategy(new HttpClient()),
            PaymentGateways::payfort  => new PayFortStrategy(new HttpClient(), new Signature()),
            PaymentGateways::tabby    => new TabbyStrategy(new HttpClient()),
            PaymentGateways::applePay => throw new \Exception('To be implemented'),
            PaymentGateways::tamara   => throw new \Exception('To be implemented'),
        };
    }
}
