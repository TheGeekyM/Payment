<?php

namespace Payment\Contracts;

use Payment\Dtos\CallbackDto;
use Payment\Dtos\PaymentTransactionDto;
use Payment\Entities\Order;

interface PaymentStrategyInterface
{
    public function beginTransaction(Order $order): PaymentTransactionDto;

    public function processedServerCallback(array $data): CallbackDto;

    public function processedClientCallback(array $data): string;
}
