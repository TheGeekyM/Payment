<?php

namespace Http\Services;

use App\Http\Dtos\CreditDto;
use App\Http\Dtos\CustomerDto;
use App\Http\Dtos\ItemDto;
use App\Http\Dtos\OrderDto;
use App\Http\Dtos\PaymentAssemblerDto;
use App\Http\Dtos\PaymentDto;
use App\Http\Dtos\ShippingAddressDto;
use App\Http\Enums\PaymentGateways;
use App\Http\Enums\PaymentMethods;
use App\Http\Services\PaymentService;
use Doubles\Dummies\PaymentAssemblerDummy;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class PaymentServiceTest extends \TestCase
{
    /**
     * @throws \JsonException
     */
    public function testPayFortPaymentGatway()
    {
        $paymentAssemblerDto = PaymentAssemblerDummy::buildDummyObject(PaymentGateways::payfort, PaymentMethods::visa);
        $payment = (new PaymentService())->initTransaction($paymentAssemblerDto);

        $crawler = $this->submitPaymentPage($payment['gateway_url'], $payment['params']);

        $text = $crawler->first('script')->text();

        $re = "/returnUrlParams\\s*=\\s*(.*);/";

        preg_match_all($re, $text, $matches);

        $this->assertEquals('Success', json_decode($matches[1][0], FALSE, 512, JSON_THROW_ON_ERROR)->response_message);
    }

    private function submitPaymentPage(string $gatewayUrl, array $params): Crawler
    {
        $html = "<html'>\n<head></head>\n<body>\n";
        $html .= "<form action='$gatewayUrl' method='post' name='payfort_payment_form' id='payfort_payment_form'>\n";
        foreach ($params as $a => $b) {
            $html .= "\t<input  name='" . htmlentities($a) . "' value='" . htmlentities($b) . "'>\n";
        }
        $html .= '<input type="submit" id="submit">';

        $html .= "</form>\n</body>\n</html>";

        $crawler = new Crawler($html, $gatewayUrl);

        $form = $crawler->filter('form')->form();

        return (new Client())->submit($form);
    }
}
