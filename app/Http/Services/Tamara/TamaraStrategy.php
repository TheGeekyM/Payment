<?php

namespace App\Http\Services\Tamara;

use App\Http\Contracts\HttpClientInterface;
use App\Http\Contracts\PaymentStrategyInterface;
use App\Http\Dtos\CallbackDto;
use App\Http\Dtos\PaymentTransactionDto;
use App\Http\Entities\Order;
use App\Http\Enums\OrderStatuses;
use App\Http\Services\Tabby\Exceptions\InvalidPaymentId;
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
        $this->checkIfValidAmount($order);
        $session = $this->createSession($order);

        return new PaymentTransactionDto($session['checkout_url'], ['checkout_id' => $session['checkout_id'],
            'order_id' => $session['order_id']
        ]);
    }

    private function checkIfValidAmount(Order $order): void
    {
        $check = $this->client->sendAuthorizedRequest(config('tamara.url') . '/checkout/payment-options-pre-check',
            config('tamara.jwt_token'), 'post', TamaraRequestMapper::mapToPaymentMethodRequest($order));

        if (!$check['has_available_payment_options']) {
            throw new \RuntimeException('There are any available payment options for customer with the given order value');
        }
    }

    private function createSession(Order $order): array
    {
        return $this->client->sendAuthorizedRequest(config('tamara.url') . '/checkout',
            config('tamara.jwt_token'), 'post', TamaraRequestMapper::map($order));
    }

    /**
     * @throws Exception
     */
    public function processedCallback(array $data): CallbackDto
    {
        $response = $this->authorizePayment($data['paymentStatus']);

        if (isset($data['status']) && ($response['status'] === 'fully_captured' || $response['status'] === 'partially_captured') ) {
            return new CallbackDto(OrderStatuses::succeeded, $response, $response['order_id']);
        }

        throw new InvalidPaymentId('Payment Id Id Invalid');
    }

    private function authorizePayment(string $paymentId): array
    {
        return $this->client->sendAuthorizedRequest(config('tamara.url') . "/orders/{$paymentId}/authorise",
            config('tamara.jwt_token'));
    }
}
