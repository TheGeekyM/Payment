<?php

namespace App\Http\Dtos;

class CreditDto
{
    private string $creditNumber;
    private int $expiryDate;
    private int $cardSecurityCode;

    public function __construct(string $creditNumber, int $expiryDate, int $cardSecurityCode)
    {

        $this->creditNumber = $creditNumber;
        $this->expiryDate = $expiryDate;
        $this->cardSecurityCode = $cardSecurityCode;
    }

    /**
     * @return string
     */
    public function getCreditNumber(): string
    {
        return $this->creditNumber;
    }

    /**
     * @return int
     */
    public function getExpiryDate(): int
    {
        return $this->expiryDate;
    }

    /**
     * @return int
     */
    public function getCardSecurityCode(): int
    {
        return $this->cardSecurityCode;
    }

}
