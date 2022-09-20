<?php

namespace App\Http\Contracts;

use App\Http\Dtos\CallbackDto;
use App\Http\Dtos\PaymentAssemblerDto;
use App\Http\Dtos\PaymentTransactionDto;
use App\Http\Entities\Order;

interface PaymentStrategyInterface
{
    public function beginTransaction(Order $order): PaymentTransactionDto;

    public function processedCallback(array $data): CallbackDto;
}
