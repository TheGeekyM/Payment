<?php

namespace App\Http\Dtos;

class PaymentAssemblerDto
{
    private PaymentDto $paymentDto;
    private OrderDto $orderDto;
    private CustomerDto $customerDto;
    private ShippingAddressDto $shippingAddressDto;

    public function __construct(PaymentDto $paymentDto, OrderDto $orderDto, CustomerDto $customerDto, ShippingAddressDto $shippingAddressDto)
    {
        $this->paymentDto = $paymentDto;
        $this->orderDto = $orderDto;
        $this->customerDto = $customerDto;
        $this->shippingAddressDto = $shippingAddressDto;
    }

    /**
     * @return PaymentDto
     */
    public function getPaymentDto(): PaymentDto
    {
        return $this->paymentDto;
    }

    /**
     * @return OrderDto
     */
    public function getOrderDto(): OrderDto
    {
        return $this->orderDto;
    }

    /**
     * @return CustomerDto
     */
    public function getCustomerDto(): CustomerDto
    {
        return $this->customerDto;
    }

    /**
     * @return ShippingAddressDto
     */
    public function getShippingAddressDto(): ShippingAddressDto
    {
        return $this->shippingAddressDto;
    }
}
