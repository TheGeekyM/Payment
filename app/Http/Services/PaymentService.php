<?php

namespace App\Http\Services;

use App\Http\Dtos\PaymentAssemblerDto;
use App\Http\Dtos\PaymentTransactionDto;
use App\Http\Enums\PaymentGateways;
use App\Http\Enums\PaymentMethods;
use App\Http\Factories\PaymentFactory;

class PaymentService
{
    public function __construct() {}

    public function initTransaction(PaymentAssemblerDto $paymentDto): PaymentTransactionDto
    {
        return PaymentFactory::getInstance($paymentDto->getPaymentDto()->getPaymentGateway())
            ->beginTransaction($paymentDto);
    }

    public function processResponse(array $data, PaymentGateways $paymentGateway): bool
    {
        $callback = PaymentFactory::getInstance($paymentGateway)->processedCallback($data);

        return true;
    }
}
