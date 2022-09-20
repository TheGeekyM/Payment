<?php

namespace App\Http\Dtos;

use App\Http\Enums\OrderStatuses;

class CallbackDto
{
    private OrderStatuses $status;
    private array $data;
    private string $orderId;
    private string $referenceId;

    public function __construct(OrderStatuses $status, array $data, string $referenceId, string $orderId)
    {
        $this->status = $status;
        $this->data = $data;
        $this->referenceId = $referenceId;
        $this->orderId = $orderId;
    }

    /**
     * @return OrderStatuses
     */
    public function getStatus(): OrderStatuses
    {
        return $this->status;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return int
     */
    public function getOrderId(): string
    {
        return $this->orderId;
    }

    /**
     * @return string
     */
    public function getReferenceId(): string
    {
        return $this->referenceId;
    }
}
