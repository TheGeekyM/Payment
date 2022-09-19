<?php

namespace App\Http\Dtos;

use App\Http\Enums\PaymentGateways;
use App\Http\Enums\PaymentMethods;

class PaymentDto
{
    private PaymentGateways $paymentGateway;
    private ?PaymentMethods $paymentMethod;
    private ?string $token;

    public function __construct(PaymentGateways $paymentGateway, ?PaymentMethods $paymentMethod, ?string $token = null)
    {
        $this->paymentGateway = $paymentGateway;
        $this->paymentMethod = $paymentMethod;
        $this->token = $token;
    }

    /**
     * @return PaymentMethods
     */
    public function getPaymentMethod(): PaymentMethods
    {
        return $this->paymentMethod;
    }

    /**
     * @return PaymentGateways
     */
    public function getPaymentGateway(): PaymentGateways
    {
        return $this->paymentGateway;
    }
    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }
}
