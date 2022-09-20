<?php

namespace App\Http\Services\Tamara;

use App\Http\Contracts\HttpClientInterface;
use App\Http\Contracts\PaymentStrategyInterface;
use App\Http\Dtos\CallbackDto;
use App\Http\Dtos\PaymentTransactionDto;
use App\Http\Entities\Order;
use App\Http\Enums\OrderStatuses;
use App\Http\Services\Tabby\Exceptions\InvalidPaymentId;
use Illuminate\Http\Client\RequestException;
use Exception;

class TamaraStrategy implements PaymentStrategyInterface
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
        $request = TamaraRequestMapper::map($order);

        $this->checkIfValidAmount($request);
        $session = $this->createSession($request);

        return new PaymentTransactionDto($session['checkout_url'], ['checkout_id' => $session['checkout_id'],
            'order_id' => $session['order_id']
        ]);
    }

    /**
     * @throws RequestException
     */
    private function checkIfValidAmount(array $data): void
    {
        $check = $this->client->sendAuthorizedRequest(config('tamara.url') . '/checkout/payment-options-pre-check',
            config('tamara.jwt_token'), 'post', $data);

        if (!$check['has_available_payment_options']) {
            throw new RequestException('There are any available payment options for customer with the given order value');
        }
    }

    /**
     * @throws RequestException
     */
    private function createSession(array $request): array
    {
        return $this->client->sendAuthorizedRequest(config('tamara.url') . '/checkout',
            config('tamara.jwt_token'), 'post', $this->builder->build($request));
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

        throw new InvalidPaymentId('Payment Id Id Invalid');
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
