<?php
namespace App\Http\Enums;

enum OrderStatuses
{
    case succeeded;
    case voided;
    case refunded;
    case failed;
    case not_secured;
}
