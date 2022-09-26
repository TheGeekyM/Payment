<?php

namespace Payment\Libs;

use Payment\Contracts\HttpClientInterface;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class HttpClient implements HttpClientInterface
{
    /**
     * @throws RequestException
     * @throws \Exception
     */
    public function sendRequest(string $endpoint, ?array $params = [], string $method = 'post', ?array $headers = []): array
    {
        $response = Http::contentType('application/json')
            ->{$method}($endpoint, $params);

        $response->throw();

        return $response->json();
    }

    /**
     * @throws RequestException
     */
    public function sendAuthorizedRequest(string $endpoint, string $authorizationKey, string $method = 'post', ?array $params = [],): array
    {
        return $this->sendRequest($endpoint, $params, $method, ['Authorization' => 'Bearer ' . $authorizationKey]);
    }
}
