<?php

namespace App\Http\Contracts;

interface HttpClientInterface
{
    public function sendRequest(string $endpoint, array $params): array;
}
