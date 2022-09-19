<?php
namespace App\Http\Enums;

enum PaymentGateways
{
    case paymob;
    case payfort;
    case applePay;
    case tabby;
    case tamara;
}
