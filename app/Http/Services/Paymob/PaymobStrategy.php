<?php

namespace App\Http\Services\Paymob;

use App\Http\Contracts\HttpClientInterface;
use App\Http\Contracts\PaymentStrategyInterface;
use App\Http\Dtos\CallbackDto;
use App\Http\Dtos\PaymentAssemblerDto;
use App\Http\Dtos\PaymentTransactionDto;
use App\Http\Enums\OrderStatuses;
use App\Http\Services\Exceptions\UnsecureCallback;
use Exception;

class PaymobStrategy implements PaymentStrategyInterface
{
    private HttpClientInterface $client;
    private PaymobRequestBuilder $builder;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
        $this->builder = new PaymobRequestBuilder();
    }

    /**
     * @throws Exception
     */
    public function beginTransaction(PaymentAssemblerDto $paymentAssemblerDto): PaymentTransactionDto
    {
        [$integrationId, $iframe] = PaymobValidator::validatePayment($paymentAssemblerDto->getPaymentDto()->getPaymentMethod());

        $auth = $this->authenticationRequest();
        $order = $this->registerOrder($paymentAssemblerDto, $auth['token']);
        $paymentKey = $this->requestPaymentKey($paymentAssemblerDto, $auth['token'], $order['id'], $integrationId);

        return new PaymentTransactionDto("https://accept.paymobsolutions.com/api/acceptance/iframes/{$iframe}?payment_token={$paymentKey['token']}", []);
    }
    private function authenticationRequest(): array
    {
        return $this->client->sendRequest(config('paymob.auth_url'), ['api_key' => config('paymob.api_key')]);
    }

    private function registerOrder(PaymentAssemblerDto $paymentAssemblerDto, string $token): array
    {
        return $this->client->sendRequest(config('paymob.order_registration_url'),
            $this->builder->buildOrderRegistration($paymentAssemblerDto, $token));
    }

    private function requestPaymentKey(PaymentAssemblerDto $paymentAssemblerDto, string $token, int $orderId, int $integrationId): array
    {
        return $this->client->sendRequest(config('paymob.payment_key_url'),
            $this->builder->buildPaymentKeyRequest($paymentAssemblerDto, $token, $orderId, $integrationId)
         );
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
