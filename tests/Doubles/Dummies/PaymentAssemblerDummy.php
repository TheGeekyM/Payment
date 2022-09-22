<?php

namespace Doubles\Dummies;

use App\Http\Dtos\CreditDto;
use App\Http\Dtos\CustomerDto;
use App\Http\Dtos\ItemDto;
use App\Http\Dtos\OrderDto;
use App\Http\Dtos\PaymentAssemblerDto;
use App\Http\Dtos\PaymentDto;
use App\Http\Dtos\ShippingAddressDto;
use App\Http\Enums\PaymentGateways;
use App\Http\Enums\PaymentMethods;

class PaymentAssemblerDummy
{
    /**
     * @throws \Exception
     */
    public static function buildDummyObject(PaymentGateways $paymentGateway, PaymentMethods $paymentMethod): PaymentAssemblerDto
    {
        $customerDto = new CustomerDto(1, "Mohamed Emad", "user@user.com", '127.0.0.1', 'en', '01033633939');
        $shippingAddressDto = new ShippingAddressDto('egypt', 'cairo', 'address', 1234);

        $items = [
            ['reference_id' => '12-as5', 'title' => 'item 1', 'price' => 50],
            ['reference_id' => '1125-as5', 'title' => 'item 2', 'price' => 50],
            ['reference_id' => '784-as5', 'title' => 'item 3', 'price' => 50],
        ];

        $items = collect($items)->transform(function ($item) {
            return new ItemDto($item['reference_id'], $item['title'], $item['price']);
        })->toArray();

        $OrderDto = new OrderDto( 1000, 'EGP',500 - time(), $items);
        $paymentDto = new PaymentDto($paymentGateway, $paymentMethod);

        return new PaymentAssemblerDto($paymentDto, $OrderDto, $customerDto, $shippingAddressDto);
    }
}
