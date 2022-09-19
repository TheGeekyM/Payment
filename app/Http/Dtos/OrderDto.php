<?php

namespace App\Http\Dtos;

class OrderDto
{
    private float $amount;
    private int $id;
    private string $currency;
    private string $referenceId;
    private array $items;

    /**
     * @param int $id
     * @param float $amount
     * @param string $currency
     * @param string $referenceId
     * @param ItemDto[] $items
     */
    public function __construct(int $id, float $amount, string $currency, string $referenceId, array $items)
    {
        $this->id = $id;
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
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
