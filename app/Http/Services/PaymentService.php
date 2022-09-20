<?php

namespace App\Http\Services;

use App\Http\Contracts\PaymentRequestBuilderInterface;
use App\Http\Contracts\PaymentRepositoryInterface;
use App\Http\Dtos\PaymentAssemblerDto;
use App\Http\Dtos\PaymentTransactionDto;
use App\Http\Enums\PaymentGateways;
use App\Http\Factories\PaymentFactory;

class PaymentService
{
    private PaymentRequestBuilderInterface $builder;
    private PaymentRepositoryInterface $repository;

    public function __construct(PaymentRequestBuilderInterface $builder, PaymentRepositoryInterface $repository) {
        $this->builder = $builder;
        $this->repository = $repository;
    }

    public function initTransaction(PaymentAssemblerDto $paymentDto): PaymentTransactionDto
    {
        $order = $this->builder->build($paymentDto);

        $this->repository->Add($order);

        return PaymentFactory::getInstance($paymentDto->getPaymentDto()->getPaymentGateway())
            ->beginTransaction($order);
    }

    public function processResponse(array $data, PaymentGateways $paymentGateway): bool
    {
        $callback = PaymentFactory::getInstance($paymentGateway)->processedCallback($data);

        return true;
    }
}
