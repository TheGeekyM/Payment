<?php

namespace Payment\Contracts;

use App\Models\Payment;

interface PaymentRepositoryInterface
{
    public function get(string $id): Payment;

    public function getAll(): array;

    public function Add(Payment $payment): Payment;

    public function delete(int $id): void;
}
