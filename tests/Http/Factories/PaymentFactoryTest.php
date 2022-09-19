<?php

namespace Http\Factories;

use App\Http\Enums\PaymentGateways;
use App\Http\Factories\PaymentFactory;
use App\Http\Services\Payfort\PayFortStrategy;
use App\Http\Services\Paymob\PaymobStrategy;
use App\Http\Strategies\ApplePayStrategy;

class PaymentFactoryTest extends \TestCase
{
    public function testGetPayfortInstanceIfPaymentMethodIsPayfort(): void
    {
        $payfortPaymentStrategy = PaymentFactory::getInstance(PaymentGateways::payfort);
        $this->assertInstanceOf(PayFortStrategy::class, $payfortPaymentStrategy);
    }

    public function testGetPaymobInstanceIfPaymentMethodIsPaymob(): void
    {
        $paymobPaymentStrategy = PaymentFactory::getInstance(PaymentGateways::paymob);
        $this->assertInstanceOf(PaymobStrategy::class, $paymobPaymentStrategy);
    }

    public function testGetApplePayInstanceIfPaymentMethodIsApplePay(): void
    {
        $applePayPaymentStrategy = PaymentFactory::getInstance(PaymentGateways::applePay);
        $this->assertInstanceOf(ApplePayStrategy::class, $applePayPaymentStrategy);
    }
}
