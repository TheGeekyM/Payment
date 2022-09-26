<?php

namespace Payment\Repository;

use Payment\Entities\Order;

abstract class InMemoryRepository
{
    public array $data = [];

    public function get(int $id): Order
    {
        if (!isset($data[$id])) {
            throw new \InvalidArgumentException('Id is not exists');
        }
        return $this->data[$id][0];
    }

    public function getAll(): array
    {
        return $this->data;
    }

    public function Add(Order $order): void
    {
        $this->data[$order->getId()][] = $order;
    }

    public function delete(int $id): void
    {
        unset($this->data[$id]);
    }
}
