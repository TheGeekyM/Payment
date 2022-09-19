<?php

namespace App\Http\Contracts;

use App\Http\Dtos\CallbackDto;
use App\Http\Dtos\PaymentAssemblerDto;
use App\Http\Dtos\PaymentTransactionDto;

interface PaymentStrategyInterface
{
    public function beginTransaction(PaymentAssemblerDto $paymentAssemblerDto): PaymentTransactionDto;

    public function processedCallback(array $data): CallbackDto;
}
