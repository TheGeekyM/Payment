<?php

namespace App\Http\Services\Paymob;

use App\Http\Enums\PaymentMethods;
use App\Http\Services\Exceptions\UnsupportedPaymentMethod;
use Exception;

class PaymobValidator
{
    /**
     * @throws Exception
     */
    public static function validatePayment(PaymentMethods $paymentMethod): array
    {
        return match ($paymentMethod) {
            PaymentMethods::banktransfer   => [config('paymob.integration_id'), 563811],
            PaymentMethods::valu           => [config('paymob.integration_id'), 5638110],
            PaymentMethods::vfcash         => [config('paymob.integration_id'), 5638112],
            PaymentMethods::cibinstallment => [config('paymob.integration_id'), 5638115],
            PaymentMethods::bminstallment  => [config('paymob.integration_id'), 5638114],
            PaymentMethods::ShahryPaymob   => [config('paymob.integration_id'), 45],
            PaymentMethods::SymplPaymob    => [config('paymob.integration_id'), 5638116],
            PaymentMethods::SouhoolaPaymob => [config('paymob.integration_id'), 5638117],
            PaymentMethods::AudiPaymob     => [config('paymob.integration_id'), 56385],
            PaymentMethods::BDCPaymob      => [config('paymob.integration_id'), 56341],
            PaymentMethods::NBKPaymob      => [config('paymob.integration_id'), 56611],
            PaymentMethods::NBDPaymob      => [config('paymob.integration_id'), 7811],
            PaymentMethods::MashreqPaymob  => [config('paymob.integration_id'), 5511],
            PaymentMethods::AllBanksPaymob => [config('paymob.integration_id'), 5661],
            default                        => throw new UnsupportedPaymentMethod('Unsupported paymob method')
        };
    }

    public static function validateResponse(array $data): bool
    {
        $connectionString = '';
        $hmac = $data['hmac'];
        $data = $data['obj'];

        $array = [
            'amount_cents',
            'created_at',
            'currency',
            'error_occured',
            'has_parent_transaction',
            'id',
            'integration_id',
            'is_3d_secure',
            'is_auth',
            'is_capture',
            'is_refunded',
            'is_standalone_payment',
            'is_voided',
            'order.id',
            'owner',
            'pending',
            'source_data.pan',
            'source_data.sub_type',
            'source_data.type',
            'success'
        ];

        foreach ($array as $key) {
            if (str_contains($key, '.')) {
                $keys = explode('.', $key);
                $connectionString .= $data[$keys[0]][$keys[1]];
                continue;
            }

            if (is_bool($data[$key])) {
                $connectionString .= $data[$key] ? 'true' : 'false';
                continue;
            }

            $connectionString .= $data[$key];
        }

        return hash_hmac('sha512', $connectionString, config('paymob.hmac')) === $hmac;
    }
}
