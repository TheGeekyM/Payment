<?php

namespace Payment\Contracts;

interface HttpClientInterface
{
    public function sendRequest(string $endpoint, array $params, string $method = 'post', ?array $headers = []): array;

    public function sendAuthorizedRequest(string $endpoint, string $authorizationKey, string $method = 'post', ?array $params = [],): array;
}
