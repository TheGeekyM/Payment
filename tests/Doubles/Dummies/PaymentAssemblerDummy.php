<?php

namespace Doubles\Dummies;

use App\Http\Dtos\CreditDto;
use App\Http\Dtos\CustomerDto;
use App\Http\Dtos\OrderDto;
use App\Http\Dtos\PaymentAssemblerDto;
use App\Http\Dtos\PaymentDto;
use App\Http\Enums\PaymentGateways;
use App\Http\Enums\PaymentMethods;

class PaymentAssemblerDummy
{
    public static function buildDummyObject(PaymentGateways $paymentGateway, PaymentMethods $paymentMethod): PaymentAssemblerDto
    {
        $paymentDto = new PaymentDto($paymentGateway, $paymentMethod);
        $OrderDto = new OrderDto(500 - time(), 1000, 'EGP');
        $CreditDto = new CreditDto(4005550000000001, 2505, 123);
        $customerDto = new CustomerDto(1, "Mohamed Emad", "user@user.com", '127.0.0.1', 'en', $CreditDto);

        return new PaymentAssemblerDto($paymentDto, $OrderDto, $customerDto);
    }
}
