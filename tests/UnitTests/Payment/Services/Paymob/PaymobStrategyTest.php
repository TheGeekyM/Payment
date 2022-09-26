<?php

namespace UnitTests\Payment\Services\Paymob;

use Exception;
use Goutte\Client;
use Illuminate\Http\Client\RequestException;
use Payment\Services\Paymob\PaymobStrategy;
use Payment\Services\Paymob\PaymobValidator;
use UnitTests\Payment\Doubles\Dummies\PaymentAssemblerDummy;
use UnitTests\Payment\Doubles\Dummies\PaymobCallbackDummy;
use Payment\Enums\OrderStatuses;
use Payment\Enums\PaymentGateways;
use Payment\Enums\PaymentMethods;
use Payment\Libs\HttpClient;
use Payment\Services\Exceptions\UnsecureCallback;
use Payment\Services\PaymentRequestBuilder;

class PaymobStrategyTest extends \TestCase
{
    /**
     * @throws Exception
     */
    public function test_get_iframe_url(): string
    {
        [, $iframe] = PaymobValidator::validatePayment(PaymentMethods::banktransfer);
        $paymentAssemblerDto = PaymentAssemblerDummy::buildDummyObject(PaymentGateways::paymob, PaymentMethods::banktransfer);
        $order = (new PaymentRequestBuilder())->build($paymentAssemblerDto);

        $resp = (new PaymobStrategy(new HttpClient()))->beginTransaction($order);

        $this->assertStringContainsString("https://accept.paymob.com/api/acceptance/iframes/{$iframe}", $resp->getUrl());

        return $resp->getUrl();
    }

    /**
     * @throws Exception
     */
    public function throw_duplicate_order_reference_exception(): void
    {
        $this->expectException(RequestException::class);

        $paymentAssemblerDto = PaymentAssemblerDummy::buildDummyObject(PaymentGateways::paymob, PaymentMethods::banktransfer);
        $order = (new PaymentRequestBuilder())->build($paymentAssemblerDto);

        (new PaymobStrategy(new HttpClient()))->beginTransaction($order);
        (new PaymobStrategy(new HttpClient()))->beginTransaction($order);
    }

    /**
     * @depends test_get_iframe_url
     */
    public function test_if_payment_iframe_is_valid(string $url): void
    {
        $client = new Client();
        $crawler = $client->request('GET', $url);
        $form = $crawler->filter("form")->form();

        $values = $form->getValues();

        $this->assertArrayHasKey('number', $values);
        $this->assertArrayHasKey('name', $values);
        $this->assertArrayHasKey('expiry', $values);
        $this->assertArrayHasKey('cvc', $values);
    }

    /**
     * @throws UnsecureCallback
     */
    public function test_if_callback_has_valid_data(): void {

        $callback = PaymobCallbackDummy::callbackData();

        $resp = (new PaymobStrategy(new HttpClient()))->processedCallback($callback);

        $this->assertNotEmpty($resp);
        $this->assertEquals(OrderStatuses::failed, $resp->getStatus());
        $this->assertEquals('1234243-22Z', $resp->getReferenceId());
        $this->assertEquals('69771203', $resp->getOrderId());
        $this->assertEquals($callback, $resp->getData());
    }

    /**
     * @throws UnsecureCallback
     */
    public function test_callback_with_failed_status(): void {

        $callback = PaymobCallbackDummy::callbackData();

        $resp = (new PaymobStrategy(new HttpClient()))->processedCallback($callback);

        $this->assertEquals(OrderStatuses::failed, $resp->getStatus());
    }

    /**
     * @throws UnsecureCallback
     */
    public function test_callback_with_success_status(): void {

        $callback = PaymobCallbackDummy::successCallbackData();

        $resp = (new PaymobStrategy(new HttpClient()))->processedCallback($callback);

        $this->assertEquals(OrderStatuses::succeeded, $resp->getStatus());
    }

    /**
     * @throws UnsecureCallback
     */
    public function test_callback_with_void_status(): void {

        $callback = PaymobCallbackDummy::voidCallbackData();

        $resp = (new PaymobStrategy(new HttpClient()))->processedCallback($callback);

        $this->assertEquals(OrderStatuses::voided, $resp->getStatus());
    }

    /**
     * @throws UnsecureCallback
     */
    public function test_callback_with_refund_status(): void {

        $callback = PaymobCallbackDummy::refundCallbackData();

        $resp = (new PaymobStrategy(new HttpClient()))->processedCallback($callback);

        $this->assertEquals(OrderStatuses::refunded, $resp->getStatus());
    }

    /**
     * @throws UnsecureCallback
     */
    public function test_throw_exception_if_not_valid_or_malformed_callback(): void {

        $this->expectException(UnsecureCallback::class);
        $callback = PaymobCallbackDummy::callbackData(false);

        (new PaymobStrategy(new HttpClient()))->processedCallback($callback);
    }
}
