<?php

namespace Payment\Repository;

use App\Models\Payment;
use Payment\Contracts\PaymentRepositoryInterface;

class PaymentRepository implements PaymentRepositoryInterface
{
    public function Add(Payment $payment): Payment
    {
        $payment->save();
        return $payment;
    }

    public function get(string $id): Payment
    {
        return Payment::where('reference_id', $id)->firstOrFail();
    }

    public function getAll(): array
    {
        // TODO: Implement getAll() method.
    }

    public function delete(int $id): void
    {
        // TODO: Implement delete() method.
    }
}
