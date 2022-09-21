<?php

namespace App\Http\Dtos;

class ItemDto
{
    private float $price;
    private int $id;
    private string $title;
    private int $quantity;
    private string $category;
    private ?string $sku;

    public function __construct(int $id, string $title, string $sku = '', float $price = 0, int $quantity = 1, string $category = 'product')
    {
        $this->id = $id;
        $this->title = $title;
        $this->sku = $sku;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->category = $category;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
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
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @return string|null
     */
    public function getSku(): ?string
    {
        return $this->sku;
    }
}
