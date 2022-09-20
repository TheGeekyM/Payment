<?php

namespace App\Http\Contracts;

use App\Http\Dtos\PaymentAssemblerDto;
use App\Http\Entities\Order;

interface PaymentRequestBuilderInterface
{
    public function build(PaymentAssemblerDto $paymentAssemblerDto): Order;
}
