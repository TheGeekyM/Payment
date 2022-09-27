<?php

namespace Payment\Contracts;

use Payment\Entities\Order;
use Payment\Entities\Payment;

interface PaymentRepositoryInterface
{
    public function find(string $id): Payment;

    public function findBy(string $column, mixed $value): Payment;

    public function getAll(): array;

    public function Add(Payment $payment): Payment;

    public function delete(int $id): void;
}
