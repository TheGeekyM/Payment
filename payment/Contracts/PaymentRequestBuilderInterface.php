<?php

namespace Payment\Contracts;

use Payment\Dtos\PaymentAssemblerDto;
use Payment\Entities\Order;

interface PaymentRequestBuilderInterface
{
    public function build(PaymentAssemblerDto $paymentAssemblerDto): Order;
}
