<?php

namespace Payment\Services\Tamara;

use Payment\Contracts\HttpClientInterface;
use Payment\Contracts\PaymentStrategyInterface;
use Exception;
use Payment\Dtos\CallbackDto;
use Payment\Dtos\PaymentTransactionDto;
use Payment\Entities\Order;
use Payment\Enums\OrderStatuses;
use Payment\Services\Tabby\Exceptions\InvalidPaymentId;
use Payment\Services\Tamara\Exceptions\NoAvailablePaymentOption;

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

    /**
     * @throws \Payment\Services\Tamara\Exceptions\NoAvailablePaymentOption
     */
    private function checkIfValidAmount(Order $order): void
    {
        $check = $this->client->sendAuthorizedRequest(config('tamara.url') . '/checkout/payment-options-pre-check',
            config('tamara.jwt_token'), 'post', TamaraRequestMapper::mapToPaymentMethodRequest($order));

        if (!$check['has_available_payment_options']) {
            throw new NoAvailablePaymentOption('There are any available payment options for customer with the given order value', 400);
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
        if ($data['paymentStatus'] === 'approved') {
            $this->authorizePayment($data['orderId']);
        }

        $order = $this->getOrder($data['orderId']);

        if ($order['status'] === 'declined') {
            return new CallbackDto(OrderStatuses::failed, $order, $order['order_reference_id'], $order['order_id']);
        }

        if ($order['status'] === 'fully_captured' || $order['status'] === 'partially_captured') {
            return new CallbackDto(OrderStatuses::captured, $order, $order['order_reference_id'], $order['order_id']);
        }

        if ($order['status'] === 'approved') {
            return new CallbackDto(OrderStatuses::succeeded, $order, $order['order_reference_id'], $order['order_id']);
        }

        throw new InvalidPaymentId('Payment Id Id Invalid');
    }

    private function getOrder(string $paymentId): array
    {
        return $this->client->sendAuthorizedRequest(config('tamara.url') . "/orders/{$paymentId}",
            config('tamara.jwt_token'), 'get');
    }

    private function authorizePayment(string $paymentId): void
    {
        $this->client->sendAuthorizedRequest(config('tamara.url') . "/orders/{$paymentId}/authorise",
            config('tamara.jwt_token'));
    }
}
