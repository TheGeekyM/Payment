<?php

namespace Payment\ValueObjects;

use http\Exception\InvalidArgumentException;

class Money
{
    private int $amount;
    private string $currency;

    public function __construct(int $amount, string $currency)
    {
        $this->setAmount($amount);
        $this->setCurrency($currency);
    }

    public function equals(Money $money): bool
    {
        return $money->amount() === $this->amount();
    }

    public function amount(): int
    {
        return $this->amount;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    private function setAmount(int $amount): void
    {
        if ($amount < 0) {
            throw new InvalidArgumentException("Amount {$amount} must be greater or equal zero");
        }
        $this->amount = $amount;
    }

    private function setCurrency(string $currency): void
    {
        if (!in_array($currency, ['EGP', 'SAR'])) {
            throw new InvalidArgumentException("Currency {$currency} must be SAR or EGP");
        }
        $this->currency = $currency;
    }
}
