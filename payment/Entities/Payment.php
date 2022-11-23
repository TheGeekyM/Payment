<?php

namespace Payment\Entities;

use Jenssegers\Mongodb\Eloquent\Model;
use Payment\Enums\PaymentGateways;
use Payment\Enums\PaymentMethods;

class Payment extends Model
{
    /**
     * @var string
     */
    private string $reference_id;

    /**
     * @var string
     */
    private string $status;

    /**
     * @var string
     */
    private string $user_id;

    /**
     * @var string
     */
    private string $total_amount;

    /**
     * @var string
     */
    private string $payment_gateway;


    /**
     * @var string
     */
    private string $payment_method;

    /**
     * @var string|null
     */
    public ?string $order_id = NULL;

    /**
     * @var array|null
     */
    private ?array $log = NULL;


    /**
     * @var string
     */
    private string $currency;

    /**
     * @var mixed
     */
    private mixed $service_name;

    /**
     * @param string $reference_id
     */
    public function setReferenceId(string $reference_id): void
    {
        $this->attributes['reference_id'] = $reference_id;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->attributes['status'] = $status;
    }

    /**
     * @param string $user_id
     */
    public function setUserId(string $user_id): void
    {
        $this->attributes['user_id'] = $user_id;
    }

    /**
     * @param string $total_amount
     */
    public function setTotalAmount(string $total_amount): void
    {
        $this->attributes['total_amount'] = $total_amount;
    }


    /**
     * @return string
     */
    public function getPaymentMethod(): string
    {
        return $this->payment_method;
    }


    /**
     * @param PaymentMethods $payment_method
     */
    public function setPaymentMethod(PaymentMethods $payment_method): void
    {
        $this->attributes['payment_method'] = $payment_method->name;
    }

    /**
     * @return string
     */
    public function getReferenceId(): string
    {
        return $this->reference_id;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->user_id;
    }

    /**
     * @return string
     */
    public function getTotalAmount(): string
    {
        return $this->total_amount;
    }

    /**
     * @param PaymentGateways $payment_gateway
     */
    public function setPaymentGateway(PaymentGateways $payment_gateway): void
    {
        $this->attributes['payment_gateway'] = $payment_gateway->name;
    }

    /**
     * @return string
     */
    public function getPaymentGateway(): string
    {
        return $this->payment_gateway;
    }

    /**
     * @param string|null $order_id
     */
    public function setOrderId(?string $order_id): void
    {
        $this->attributes['order_id'] = $order_id;
    }

    /**
     * @return string|null
     */
    public function getOrderId(): ?string
    {
        return $this->order_id;
    }

    /**
     * @param array $log
     * @return void
     */
    public function setLog(array $log): void
    {
        $this->attributes['log'] = $log;
    }

    /**
     * @return array|null
     */
    public function getLog(): ?array
    {
        return $this->log;
    }

    /**
     * @param string $currency
     * @return void
     */
    public function setCurrency(string $currency): void
    {
        $this->attributes['currency'] = $currency;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $serviceName
     * @return void
     */
    public function setServiceName(string $serviceName): void
    {
        $this->attributes['service_name'] = $serviceName;
    }

    /**
     * @param string $serviceName
     * @return string
     */
    public function gtServiceName(string $serviceName): string
    {
        return $this->service_name;
    }
}
