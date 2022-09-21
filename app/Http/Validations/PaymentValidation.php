<?php

namespace App\Http\Validations;

class PaymentValidation
{
    public static function rules(): array
    {
        return [
            'payment' => 'required',
            'payment.gateway' => 'required',
            'payment.method' => 'required',

            'order' => 'required',
            'order.reference_id' => 'required',
            'order.amount' => 'required|string',
            'order.currency' => 'required|in:SAR,EGP',
            'order.items' => 'required|array',
            'order.items.*.title' => 'required|string',
            'order.items.*.sku' => 'required|string',
            'order.items.*.quantity' => 'required|integer',
            'order.items.*.price' => 'required|string',
            'order.items.*.category' => 'required|string',

            'customer' => 'required',
            'customer.id' => 'required|integer',
            'customer.ip' => 'required|string',
            'customer.phone' => 'required|string',
            'customer.name' => 'required|string',
            'customer.email' => 'required|email',
            'customer.language' => 'required|in:ar,en',

            'shipping_address' => 'required',
            'shipping_address.country' => 'required|string',
            'shipping_address.city' => 'required|string',
            'shipping_address.address' => 'required|string',
            'shipping_address.zip' => 'required|string',
        ];
    }
}
