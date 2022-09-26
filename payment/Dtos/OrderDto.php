<?php

namespace Payment\Dtos;

class OrderDto
{
    private float $amount;
    private string $currency;
    private string $referenceId;
    private array $items;

    /**
     * @param float $amount
     * @param string $currency
     * @param string $referenceId
     * @param ItemDto[] $items
     */
    public function __construct(float $amount, string $currency, string $referenceId, array $items)
    {
        $this->amount = $amount;
        $this->currency = $currency;
        $this->referenceId = $referenceId;
        $this->items = $items;
    }
    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @return string
     */
    public function getReferenceId(): string
    {
        return $this->referenceId;
    }

    /**
     * @return ItemDto[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
