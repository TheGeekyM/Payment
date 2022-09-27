<?php

namespace Payment\Entities;

use DateTime;
use Jenssegers\Mongodb\Eloquent\Model;

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
    private ?string $order_id = NULL;

    /**
     * @var array|null
     */
    private ?array $log = NULL;

    /**
     * @var DateTime
     */
    private DateTime $updated_at;

    /**
     * @var DateTime
     */
    private DateTime $created_at;

    /**
     * @param string $reference_id
     */
    public function setReferenceId(string $reference_id): void
    {
        $this->reference_id = $reference_id;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @param string $user_id
     */
    public function setUserId(string $user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @param string $total_amount
     */
    public function setTotalAmount(string $total_amount): void
    {
        $this->total_amount = $total_amount;
    }


    /**
     * @return string
     */
    public function getPaymentMethod(): string
    {
        return $this->payment_method;
    }


    /**
     * @param string $payment_method
     */
    public function setPaymentMethod(string $payment_method): void
    {
        $this->payment_method = $payment_method;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->created_at;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updated_at;
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
     * @param string $payment_gateway
     */
    public function setPaymentGateway(string $payment_gateway): void
    {
        $this->payment_gateway = $payment_gateway;
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
        $this->order_id = $order_id;
    }

    /**
     * @return string|null
     */
    public function getOrderId(): ?string
    {
        return $this->order_id;
    }

    public function setLog(array $log)
    {
        $this->log = $log;
    }

    /**
     * @return array|null
     */
    public function getLog(): ?array
    {
        return $this->log;
    }


}
