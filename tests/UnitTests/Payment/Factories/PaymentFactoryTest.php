<?php

namespace UnitTests\Payment\Factories;

use Payment\Enums\PaymentGateways;
use Payment\Services\Payfort\PayFortStrategy;
use Payment\Services\Paymob\PaymobStrategy;
use Payment\Services\Tabby\TabbyStrategy;

class PaymentFactoryTest extends \TestCase
{
    public function testGetPayfortInstanceIfPaymentMethodIsPayfort(): void
    {
        $payfortPaymentStrategy = \Payment\Factories\PaymentFactory::getInstance(PaymentGateways::payfort);
        $this->assertInstanceOf(PayFortStrategy::class, $payfortPaymentStrategy);
    }

    public function testGetPaymobInstanceIfPaymentMethodIsPaymob(): void
    {
        $paymobPaymentStrategy = \Payment\Factories\PaymentFactory::getInstance(PaymentGateways::paymob);
        $this->assertInstanceOf(PaymobStrategy::class, $paymobPaymentStrategy);
    }

    public function testGetTabbyInstanceIfPaymentMethodIsApplePay(): void
    {
        $tabbyPaymentStrategy = \Payment\Factories\PaymentFactory::getInstance(PaymentGateways::tabby);
        $this->assertInstanceOf(TabbyStrategy::class, $tabbyPaymentStrategy);
    }
}
