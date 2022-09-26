<?php

namespace Payment\Dtos;

class PaymentTransactionDto
{
    private string $url;
    private array $params;

    public function __construct(string $url, array $params)
    {

        $this->url = $url;
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }
}
