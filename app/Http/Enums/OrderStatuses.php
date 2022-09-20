<?php
namespace App\Http\Enums;

enum OrderStatuses
{
    case created;
    case succeeded;
    case captured;
    case voided;
    case refunded;
    case failed;
    case not_secured;
}
