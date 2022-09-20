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

        return new PaymentTransactionDto("https://accept.paymobsolutions.com/api/acceptance/iframes/{$iframe}?payment_token={$paymentKey['token']}", []);
    }
    private function authenticationRequest(): array
    {
        return $this->client->sendRequest(config('paymob.auth_url'), ['api_key' => config('paymob.api_key')]);
    }

    private function registerOrder(Order $order, string $token): array
    {
        $request = PaymobRequestMapper::buildOrderRegistration($order, $token);
        return $this->client->sendRequest(config('paymob.order_registration_url'), $request);
    }

    private function requestPaymentKey(Order $order, string $token, int $orderId, int $integrationId): array
    {
        $request = PaymobRequestMapper::buildPaymentKeyRequest($order, $token, $orderId, $integrationId);
        return $this->client->sendRequest(config('paymob.payment_key_url'),$request);
    }

    /**
     * @throws UnsecureCallback
     */
    public function processedCallback(array $data): CallbackDto
    {
        if (!PaymobValidator::validateResponse($data)) {
            throw new UnsecureCallback('Invalid response received');
        }

        if ($data['success'] && !$data['is_voided'] && !$data['is_refunded']) {
            return new CallbackDto(OrderStatuses::succeeded, $data, $data['id']);
        }

        if ($data['success'] && $data['is_voided']) {
            return new CallbackDto(OrderStatuses::voided, $data, $data['id']);
        }

        if ($data['success'] && $data['is_refunded']) {
            return new CallbackDto(OrderStatuses::refunded, $data, $data['id']);
        }

        return new CallbackDto(OrderStatuses::failed, $data, $data['id']);
    }
}
