<?php

namespace App\Http\Strategies;

use App\Http\Contracts\PaymentStrategyInterface;
use App\Http\Dtos\CallbackDto;
use App\Http\Dtos\PaymentAssemblerDto;
use App\Http\Dtos\PaymentTransactionDto;

class ApplePayStrategy implements PaymentStrategyInterface
{
    public function processedCallback(array $data): CallbackDto
    {
        // TODO: Implement processedCallback() method.
    }

    public function beginTransaction(PaymentAssemblerDto $paymentAssemblerDto): PaymentTransactionDto
    {
        // TODO: Implement beginTransaction() method.
    }
}
