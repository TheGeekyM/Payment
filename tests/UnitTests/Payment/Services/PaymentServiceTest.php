<?php

namespace UnitTests\Payment\Services;

use Goutte\Client;
use UnitTests\Payment\Doubles\Dummies\PaymentAssemblerDummy;
use Payment\Enums\PaymentGateways;
use Payment\Enums\PaymentMethods;
use Symfony\Component\DomCrawler\Crawler;

class PaymentServiceTest extends \TestCase
{
    /**
     * @throws \JsonException
     */
    public function PayFortPaymentGatway()
    {
        $paymentAssemblerDto = PaymentAssemblerDummy::buildDummyObject(PaymentGateways::payfort, PaymentMethods::visa);
        $payment = (new \Payment\Services\PaymentService())->initTransaction($paymentAssemblerDto);

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
