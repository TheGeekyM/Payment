<?php

namespace Payment\Repository;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Payment\Contracts\PaymentRepositoryInterface;
use Payment\Entities\Order;
use Payment\Entities\Payment;

class PaymentRepository implements PaymentRepositoryInterface
{
    public function Add(Payment $payment): Payment
    {
        $payment->save();
        return $payment;
    }

    public function find(string $id): Payment
    {
        return Payment::find($id)->first();
    }

    public function findBy(string $column, mixed $value): Payment
    {
        return Payment::where($column, $value)->first();
    }

    public function getAll(): array
    {
        return Payment::all()->toArray();
    }

    public function delete(int $id): void
    {
        Payment::destroy($id);
    }
}
