<?php

namespace App\Http\Services\Tabby;

use App\Http\Contracts\HttpClientInterface;
use App\Http\Contracts\PaymentStrategyInterface;
use App\Http\Dtos\CallbackDto;
use App\Http\Dtos\PaymentAssemblerDto;
use App\Http\Dtos\PaymentTransactionDto;
use App\Http\Enums\OrderStatuses;
use App\Http\Services\Tabby\Exceptions\InvalidPaymentId;
use Illuminate\Http\Client\RequestException;
use Exception;

class TabbyStrategy implements PaymentStrategyInterface
{
    private HttpClientInterface $client;

    private TabbyRequestBuilder $builder;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
        $this->builder = new TabbyRequestBuilder();
    }

    /**
     * @throws Exception
     */
    public function beginTransaction(PaymentAssemblerDto $paymentAssemblerDto): PaymentTransactionDto
    {
        $auth = $this->createSession($paymentAssemblerDto);
        return new PaymentTransactionDto($auth['configuration']['available_products']['installments'][0]['web_url'], []);
    }

    /**
     * @throws RequestException
     */
    private function createSession(PaymentAssemblerDto $paymentAssemblerDto): array
    {
        return $this->client->sendAuthorizedRequest('https://api.tabby.ai/api/v2/checkout',
            config('tabby.public_key'), 'post', $this->builder->build($paymentAssemblerDto));
    }

    /**
     * @throws RequestException
     * @throws Exception
     */
    public function processedCallback(array $data): CallbackDto
    {
        $response = $this->client->sendAuthorizedRequest("https://api.tabby.ai/api/v2/payments/{$data['payment_id']}",
            config('tabby.private_key'), 'get');

        if (isset($data['status']) && $response['status'] === 'AUTHORIZED') {
            $response = $this->captureAmount($response['id'], $response['amount']);
        }

        if (isset($response['status']) && ($response['status'] === 'CLOSED' || $data['status'] === 'AUTHORIZED')) {
            return new CallbackDto(OrderStatuses::succeeded, $response, $response['id']);
        }

        throw new InvalidPaymentId('Invalid Payment Id');
    }

    /**
     * @throws RequestException
     */
    private function captureAmount(string $paymentId, string $amount): array
    {
        return $this->client->sendAuthorizedRequest("https://api.tabby.ai/api/v1/payments/{$paymentId}/captures",
            config('tabby.private_key'), 'post', ['amount' => $amount]);
    }
}
