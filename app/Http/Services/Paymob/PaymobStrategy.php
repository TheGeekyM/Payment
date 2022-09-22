<?php

namespace App\Http\Services\Paymob;

use App\Http\Contracts\HttpClientInterface;
use App\Http\Contracts\PaymentStrategyInterface;
use App\Http\Dtos\CallbackDto;
use App\Http\Dtos\PaymentTransactionDto;
use App\Http\Entities\Order;
use App\Http\Enums\OrderStatuses;
use App\Http\Services\Exceptions\UnsecureCallback;
use Exception;

class PaymobStrategy implements PaymentStrategyInterface
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
        [$integrationId, $iframe] = PaymobValidator::validatePayment($order->getPaymentType());

        $auth = $this->authenticationRequest();
        $registeredOrder = $this->registerOrder($order, $auth['token']);
        $paymentKey = $this->requestPaymentKey($order, $auth['token'], $registeredOrder['id'], $integrationId);

        return new PaymentTransactionDto(
            config('paymob.url') . "acceptance/iframes/{$iframe}?payment_token={$paymentKey['token']}", []
        );
    }

    private function authenticationRequest(): array
    {
        return $this->client->sendRequest(config('paymob.url') . 'auth/tokens',
            ['api_key' => config('paymob.api_key')]);
    }

    private function registerOrder(Order $order, string $token): array
    {
        $request = PaymobRequestMapper::buildOrderRegistration($order, $token);
        return $this->client->sendRequest(config('paymob.url') . 'ecommerce/orders', $request);
    }

    private function requestPaymentKey(Order $order, string $token, int $orderId, int $integrationId): array
    {
        $request = PaymobRequestMapper::buildPaymentKeyRequest($order, $token, $orderId, $integrationId);
        return $this->client->sendRequest(config('paymob.url') . 'acceptance/payment_keys', $request);
    }

    /**
     * @throws UnsecureCallback
     */
    public function processedCallback(array $data): CallbackDto
    {
        $obj = $data['obj'];
        $order = $obj['order'];

        if (!PaymobValidator::validateResponse($data)) {
            throw new UnsecureCallback('Invalid response received');
        }

        if ($obj['success'] && !$obj['is_voided'] && !$obj['is_refunded']) {
            return new CallbackDto(OrderStatuses::succeeded, $data, $order['merchant_order_id'], $order['id']);
        }

        if ($obj['success'] && $obj['is_voided']) {
            return new CallbackDto(OrderStatuses::voided, $data, $order['merchant_order_id'], $order['id']);
        }

        if ($obj['success'] && $obj['is_refunded']) {
            return new CallbackDto(OrderStatuses::refunded, $data, $order['merchant_order_id'], $order['id']);
        }

        return new CallbackDto(OrderStatuses::failed, $data, $order['merchant_order_id'], $order['id']);
    }
}
