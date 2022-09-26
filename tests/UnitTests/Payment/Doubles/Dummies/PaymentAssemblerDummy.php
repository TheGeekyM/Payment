<?php

namespace UnitTests\Payment\Doubles\Dummies;

use Payment\Dtos\CustomerDto;
use Payment\Dtos\ItemDto;
use Payment\Dtos\OrderDto;
use Payment\Dtos\PaymentAssemblerDto;
use Payment\Dtos\PaymentDto;
use Payment\Dtos\ShippingAddressDto;
use Payment\Enums\PaymentGateways;
use Payment\Enums\PaymentMethods;

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
