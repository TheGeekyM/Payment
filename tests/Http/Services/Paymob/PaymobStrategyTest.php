<?php

namespace Http\Services\Paymob;

use App\Http\Enums\PaymentGateways;
use App\Http\Enums\PaymentMethods;
use App\Http\Libs\HttpClient;
use App\Http\Services\Paymob\PaymobStrategy;
use App\Http\Services\Paymob\PaymobValidator;
use Exception;
use Doubles\Dummies\PaymentAssemblerDummy;
use Goutte\Client;
use Illuminate\Http\Client\RequestException;

class PaymobStrategyTest extends \TestCase
{
    /**
     * @throws Exception
     */
    public function test_it_should_create_a_paymob_strategy_with_default_options(): string
    {
        [, $iframe] = PaymobValidator::validatePayment(PaymentMethods::banktransfer);
        $paymentAssemblerDto = PaymentAssemblerDummy::buildDummyObject(PaymentGateways::paymob, PaymentMethods::banktransfer);
        $resp = (new PaymobStrategy(new HttpClient()))->beginTransaction($paymentAssemblerDto);

        $this->assertStringContainsString("https://accept.paymobsolutions.com/api/acceptance/iframes/{$iframe}", $resp->getUrl());

        return $resp->getUrl();
    }

    /**
     * @throws Exception
     */
    public function test_throw_duplicate_order_reference_exception(): void
    {
        $this->expectException(RequestException::class);

        $paymentAssemblerDto = PaymentAssemblerDummy::buildDummyObject(PaymentGateways::paymob, PaymentMethods::banktransfer);
        (new PaymobStrategy(new HttpClient()))->beginTransaction($paymentAssemblerDto);
        (new PaymobStrategy(new HttpClient()))->beginTransaction($paymentAssemblerDto);
    }

    /**
     * @depends test_it_should_create_a_paymob_strategy_with_default_options
     */
    public function payment_iframe(string $url): void
    {
        $client = new Client();
        $crawler = $client->request('GET', $url);
        $form = $crawler->filter("form")->form();

        $res = $client->submit($form, [
            'number' => 4005550000000001,
            'name' => 'Mohamed Emad',
            'expiry' => '05/25',
            'cvc' => 123,
        ]);
    }
}
