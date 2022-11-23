<?php

namespace Payment\Services\Paymob;

use Exception;
use Payment\Enums\PaymentMethods;
use Payment\Services\Exceptions\UnsupportedPaymentMethod;

class PaymobValidator
{
    /**
     * @throws Exception
     */
    public static function validatePayment(PaymentMethods $paymentMethod): array
    {
        return match ($paymentMethod) {
            PaymentMethods::banktransfer   => [config('paymob.integration_id'), 563811],
            default                        => throw new UnsupportedPaymentMethod('Unsupported paymob method')
        };
    }

    public static function validateServerResponse(array $data): bool
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

    public static function validateFrontResponse(array $data): bool
    {
        ksort($data);
        $connectionString = '';

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
            'order',
            'owner',
            'pending',
            'source_data_pan',
            'source_data_sub_type',
            'source_data_type',
            'success'
        ];

        foreach($data as $key => $value)   {
            if(in_array($key, $array, TRUE)){
                $connectionString .= $value;
            }
        }

        return hash_hmac('sha512', $connectionString, config('paymob.hmac')) === $data['hmac'];
    }
}
