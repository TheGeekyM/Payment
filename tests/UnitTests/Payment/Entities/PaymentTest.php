<?php

namespace UnitTests\Payment\Entities;

use Payment\Entities\Payment;
use Payment\Enums\PaymentGateways;
use Payment\Enums\PaymentMethods;
use PHPUnit\Framework\TestCase;

class PaymentTest  extends TestCase
{
    /**
     * @throws \Exception
     */
    public function test_if_values_set_correctly_in_payment_entity(): void
    {
        $payment = new Payment();

        $now = date_create();
        $payment->setReferenceId('445ewf');
        $payment->setTotalAmount(100);
        $payment->setOrderId(123);
        $payment->setPaymentMethod(PaymentMethods::AllBanksPaymob);
        $payment->setPaymentGateway(PaymentGateways::paymob);
        $payment->setUserId('dw55');
        $payment->setStatus('created');
        $payment->setCurrency('EGP');
        $payment->setLog(['data' => 'aaa']);

        $this->assertEquals('445ewf', $payment->getReferenceId());
        $this->assertEquals(100, $payment->getTotalAmount());
        $this->assertEquals(123, $payment->getOrderId());
        $this->assertEquals(PaymentMethods::AllBanksPaymob, $payment->getPaymentMethod());
        $this->assertEquals(PaymentGateways::paymob, $payment->getPaymentGateway());
        $this->assertEquals('dw55', $payment->getUserId());
        $this->assertEquals('created', $payment->getStatus());
        $this->assertEquals('EGP', $payment->getCurrency());
        $this->assertEquals(['data' => 'aaa'], $payment->getLog());
    }
}
