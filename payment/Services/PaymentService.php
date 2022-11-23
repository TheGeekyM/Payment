<?php

namespace Payment\Services;

use App\Events\OrderCreatedEvent;
use Payment\Contracts\HttpClientInterface;
use Payment\Contracts\PaymentRepositoryInterface;
use Payment\Contracts\PaymentRequestBuilderInterface;
use Payment\Dtos\CallbackDto;
use Payment\Dtos\PaymentAssemblerDto;
use Payment\Dtos\PaymentTransactionDto;
use Payment\Entities\Exceptions\InvalidTotalOrderAmountException;
use Payment\Entities\Payment;
use Payment\Enums\PaymentGateways;
use Payment\Factories\PaymentFactory;

class PaymentService
{
    private PaymentRequestBuilderInterface $builder;
    private PaymentRepositoryInterface $repository;

    public function __construct(PaymentRequestBuilderInterface $builder, PaymentRepositoryInterface $repository)
    {
        $this->builder = $builder;
        $this->repository = $repository;
    }

    /**
     * @throws InvalidTotalOrderAmountException
     */
    public function initTransaction(PaymentAssemblerDto $paymentDto): PaymentTransactionDto
    {
        $order = $this->builder->build($paymentDto);

        {
            $payment = new Payment();
            $payment->setServiceName(config('payment.service_name'));
            $payment->setReferenceId($order->getReferenceId());
            $payment->setStatus('created');
            $payment->setUserId($order->getConsumer()->getId());
            $payment->setTotalAmount($order->calculateTotalAmount()->amount());
            $payment->setCurrency($order->calculateTotalAmount()->currency());
            $payment->setPaymentGateway($order->getPaymentGateway());
            $payment->setPaymentMethod($order->getPaymentMethod());
            $this->repository->Add($payment);
        }

        $this->repository->Add($payment);

        return PaymentFactory::getInstance($paymentDto->getPaymentDto()->getPaymentGateway())
            ->beginTransaction($order);
    }

    public function processResponse(array $data, PaymentGateways $paymentGateway): CallbackDto
    {
        $callback = PaymentFactory::getInstance($paymentGateway)->processedCallback($data);

        {
            $payment = $this->repository->findBy('reference_id', $callback->getReferenceId());

            if (!$payment->getOrderId()) {
                $payment->setOrderId( $callback->getOrderId());
                $payment->setStatus($callback->getStatus()->name);
                $payment->setLog($callback->getData());
                $this->repository->Add($payment);
            }
        }

        event(new OrderCreatedEvent($payment));

        return $callback;
    }
}
