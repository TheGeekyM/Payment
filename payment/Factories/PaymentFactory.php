<?php

namespace Payment\Factories;

use Payment\Contracts\PaymentStrategyInterface;
use Payment\Enums\PaymentGateways;
use Payment\Libs\HttpClient;
use Payment\Services\Payfort\PayFortStrategy;
use Payment\Services\Payfort\Signature;
use Payment\Services\Paymob\PaymobStrategy;
use Payment\Services\Tabby\TabbyStrategy;
use Payment\Services\Tamara\TamaraStrategy;

class PaymentFactory
{
    public static function getInstance(PaymentGateways $getPaymentMethod): PaymentStrategyInterface
    {
        return match ($getPaymentMethod) {
            PaymentGateways::paymob   => new PaymobStrategy(new HttpClient()),
            PaymentGateways::payfort  => new PayFortStrategy(new HttpClient(), new Signature()),
            PaymentGateways::tabby    => new TabbyStrategy(new HttpClient()),
            PaymentGateways::tamara   => new TamaraStrategy(new HttpClient()),
        };
    }
}
