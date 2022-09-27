<?php

namespace Payment\ValueObjects;

use Payment\Entities\Exceptions\AmountIsLessThanZeroException;
use Payment\Entities\Exceptions\InvalidCurrency;

class Money
{
    private int $amount;
    private string $currency;

    /**
     * @throws AmountIsLessThanZeroException
     * @throws InvalidCurrency
     */
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

    /**
     * @throws AmountIsLessThanZeroException
     */
    private function setAmount(int $amount): void
    {
        if ($amount < 0) {
            throw new AmountIsLessThanZeroException("Amount {$amount} must be greater or equal zero");
        }
        $this->amount = $amount;
    }

    /**
     * @throws InvalidCurrency
     */
    private function setCurrency(string $currency): void
    {
        if (!in_array($currency, ['EGP', 'SAR'])) {
            throw new InvalidCurrency("Currency {$currency} must be SAR or EGP");
        }
        $this->currency = $currency;
    }
}
