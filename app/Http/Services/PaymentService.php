<?php

namespace App\Http\Services;

use App\Http\Contracts\PaymentRequestBuilderInterface;
use App\Http\Contracts\PaymentRepositoryInterface;
use App\Http\Dtos\CallbackDto;
use App\Http\Dtos\PaymentAssemblerDto;
use App\Http\Dtos\PaymentTransactionDto;
use App\Http\Enums\PaymentGateways;
use App\Http\Factories\PaymentFactory;
use App\Models\Payment;

class PaymentService
{
    private PaymentRequestBuilderInterface $builder;
    private PaymentRepositoryInterface $repository;

    public function __construct(PaymentRequestBuilderInterface $builder, PaymentRepositoryInterface $repository)
    {
        $this->builder = $builder;
        $this->repository = $repository;
    }

    public function initTransaction(PaymentAssemblerDto $paymentDto): PaymentTransactionDto
    {
        $order = $this->builder->build($paymentDto);

        {
            $payment = new Payment();
            $payment->reference_id = $paymentDto->getOrderDto()->getReferenceId();
            $payment->status = 'created';
            $payment->user_id = $paymentDto->getCustomerDto()->getId();
            $payment->total_amount = $paymentDto->getOrderDto()->getAmount();
            $payment->payment_gateway = $paymentDto->getPaymentDto()->getPaymentGateway()->name;
            $payment->payment_method = $paymentDto->getPaymentDto()->getPaymentMethod()->name;
            $this->repository->Add($payment);
        }

        return PaymentFactory::getInstance($paymentDto->getPaymentDto()->getPaymentGateway())
            ->beginTransaction($order);
    }

    public function processResponse(array $data, PaymentGateways $paymentGateway): CallbackDto
    {
        $callback = PaymentFactory::getInstance($paymentGateway)->processedCallback($data);

        {
            $payment = $this->repository->get($callback->getReferenceId());

            if (!$payment->order_id) {
                $payment->order_id = $callback->getOrderId();
                $payment->status = $callback->getStatus()->name;
                $payment->log = $callback->getData();
                $this->repository->Add($payment);
            }
        }

        return $callback;
    }
}
