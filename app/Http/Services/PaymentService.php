<?php

namespace App\Http\Services;

use App\Http\Contracts\PaymentRequestBuilderInterface;
use App\Http\Dtos\PaymentAssemblerDto;
use App\Http\Dtos\PaymentTransactionDto;
use App\Http\Enums\PaymentGateways;
use App\Http\Factories\PaymentFactory;

class PaymentService
{
    private PaymentRequestBuilderInterface $builder;

    public function __construct(PaymentRequestBuilderInterface $builder) {
        $this->builder = $builder;
    }

    public function initTransaction(PaymentAssemblerDto $paymentDto): PaymentTransactionDto
    {
        return PaymentFactory::getInstance($paymentDto->getPaymentDto()->getPaymentGateway())
            ->beginTransaction($this->builder->build($paymentDto));
    }

    public function processResponse(array $data, PaymentGateways $paymentGateway): bool
    {
        $callback = PaymentFactory::getInstance($paymentGateway)->processedCallback($data);

        return true;
    }
}
