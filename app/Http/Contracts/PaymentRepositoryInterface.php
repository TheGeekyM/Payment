<?php

namespace App\Http\Contracts;

use App\Http\Entities\Order;
use App\Models\Payment;

interface PaymentRepositoryInterface
{
    public function get(string $id): Payment;

    public function getAll(): array;

    public function Add(Payment $payment): Payment;

    public function delete(int $id): void;
}
