<?php

namespace App\Http\Services\Tabby;

use App\Http\Contracts\HttpClientInterface;
use App\Http\Contracts\PaymentStrategyInterface;
use App\Http\Dtos\CallbackDto;
use App\Http\Dtos\PaymentTransactionDto;
use App\Http\Entities\Order;
use App\Http\Enums\OrderStatuses;
use App\Http\Services\Tabby\Exceptions\InvalidPaymentId;
use Exception;

class TabbyStrategy implements PaymentStrategyInterface
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @throws Exception
     */
    public function beginTransaction(Order $order): PaymentTransactionDto
    {
        $request = TabbyRequestMapper::createCheckoutRequest($order);
        $auth = $this->createSession($request);

        return new PaymentTransactionDto($auth['configuration']['available_products']['installments'][0]['web_url'], []);
    }

    /**
     */
    private function createSession(array $request): array
    {
        return $this->client->sendAuthorizedRequest(config('tabby.url_v2'). '/checkout',
            config('tabby.public_key'), 'post', $request);
    }

    /**
     * @throws Exception
     */
    public function processedCallback(array $data): CallbackDto
    {
        $response = $this->client->sendAuthorizedRequest(config('tabby.url_v2'). "/payments/{$data['payment_id']}",
            config('tabby.private_key'), 'get');

        if (isset($response['status']) && $response['status'] === 'AUTHORIZED') {
            $response = $this->captureAmount($response['id'], $response['amount']);
        }

        if (isset($response['status']) && ($response['status'] === 'CLOSED' || $data['status'] === 'AUTHORIZED')) {
            return new CallbackDto(OrderStatuses::succeeded, $response, $response['order']['reference_id'], $response['id']);
        }

        throw new InvalidPaymentId('Payment Id is Invalid', 400);
    }

    private function captureAmount(string $paymentId, string $amount): array
    {
        return $this->client->sendAuthorizedRequest(config('tabby.url_v2') . "/payments/{$paymentId}/captures",
            config('tabby.private_key'), 'post', ['amount' => $amount]);
    }
}
