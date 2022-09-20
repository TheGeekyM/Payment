<?php

namespace App\Http\Contracts;

use App\Http\Entities\Order;

interface PaymentRepositoryInterface
{
    public function get(int $id): Order;

    public function getAll(): array;

    public function Add(Order $order): void;

    public function delete(int $id): void;
}
