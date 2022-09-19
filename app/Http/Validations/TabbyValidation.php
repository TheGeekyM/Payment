<?php

namespace App\Http\Validations;

class TabbyValidation
{
    public static function rules(): array
    {
        return [
            'order' => 'required',
            'order.id' => 'required',
            'order.reference_id' => 'required',
            'order.amount' => 'required|string',
            'order.currency' => 'required|in:SAR,EGP',
            'order.items' => 'required|array',
            'order.items.*.title' => 'required|string',
            'order.items.*.quantity' => 'required|integer',
            'order.items.*.unit_price' => 'required|string',
            'order.items.*.category' => 'required|string',

            'payment' => 'required',
            'payment.gateway' => 'required',
            'payment.method' => 'required',

            'customer' => 'required',
            'customer.phone' => 'required|string',
            'customer.name' => 'required|string',
            'customer.email' => 'required|email',
            'customer.language' => 'required|in:ar,en',

            'shipping_address' => 'required',
            'shipping_address.city' => 'required|string',
            'shipping_address.address' => 'required|string',
            'shipping_address.zip' => 'required|string',
        ];
    }
}
