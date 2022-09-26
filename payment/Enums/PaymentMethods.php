<?php

namespace Payment\Enums;

enum PaymentMethods
{
    case visa;
    case credit;
    case mada;
    case banktransfer;
    case valu;
    case vfcash;
    case cibinstallment;
    case bminstallment;
    case ShahryPaymob;
    case SymplPaymob;
    case SouhoolaPaymob;
    case AudiPaymob;
    case BDCPaymob;
    case NBKPaymob;
    case NBDPaymob;
    case MashreqPaymob;
    case AllBanksPaymob;
    case PAY_BY_INSTALMENTS;
}
