<?php

namespace App\Http\Services\Payfort;

use App\Http\Contracts\HttpClientInterface;
use App\Http\Contracts\PaymentStrategyInterface;
use App\Http\Dtos\CallbackDto;
use App\Http\Dtos\PaymentAssemblerDto;
use App\Http\Dtos\PaymentTransactionDto;
use JetBrains\PhpStorm\NoReturn;

class PayFortStrategy implements PaymentStrategyInterface
{
    /**
     * @var string
     */
    private string $gatewayUrl;

    /**
     * @var string
     */
    public string $operationHost;

    /**
     * @var Signature
     */
    private Signature $signature;

    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client, Signature $signature)
    {
        $this->signature = $signature;
        $this->client = $client;
        $this->gatewayUrl = config('payfort.sandbox') ? 'https://sbcheckout.payfort.com/FortAPI/paymentPage' :
            'https://checkout.payfort.com/FortAPI/paymentPage';
        $this->operationHost = config('payfort.sandbox') ? 'https://sbpaymentservices.payfort.com/FortAPI/paymentApi' :
            'https://paymentservices.payfort.com/FortAPI/paymentApi';
    }

    public function beginTransaction(PaymentAssemblerDto $paymentAssemblerDto): PaymentTransactionDto
    {
        return $paymentAssemblerDto->getPaymentDto()->getToken() ?
            $this->getRedirectionRequestParams($paymentAssemblerDto) : $this->getTokenization($paymentAssemblerDto);
    }

    private function getTokenization(PaymentAssemblerDto $paymentAssemblerDto): PaymentTransactionDto
    {
        $params = [
            'service_command' => 'TOKENIZATION',
            'merchant_identifier' => config('payfort.merchant_identifier'),
            'access_code' => config('payfort.access_code'),
            'merchant_reference' => $paymentAssemblerDto->getOrderDto()->getId(),
            'language' => $paymentAssemblerDto->getCustomerDto()->getLanguage(),
        ];

        $params['signature'] = $this->signature->calculateSignature($params);

        $params["card_number"] = $paymentAssemblerDto->getCustomerDto()->getCreditDto()->getCreditNumber();
        $params["expiry_date"] = $paymentAssemblerDto->getCustomerDto()->getCreditDto()->getExpiryDate();
        $params["card_security_code"] = $paymentAssemblerDto->getCustomerDto()->getCreditDto()->getCardSecurityCode();

        return new PaymentTransactionDto($this->gatewayUrl, $params);
    }

    private function getRedirectionRequestParams(PaymentAssemblerDto $paymentAssemblerDto): PaymentTransactionDto
    {
        $params = [
            'command' => 'PURCHASE',
            'merchant_identifier' => config('payfort.merchant_identifier'),
            'merchant_reference' => '5000_' . time(),
            'access_code' => config('payfort.access_code'),
            'return_url' => config('payfort.return_url'),
            'amount' => $paymentAssemblerDto->getOrderDto()->getAmount(),
            'currency' => $paymentAssemblerDto->getOrderDto()->getCurrency(),
            'language' => $paymentAssemblerDto->getCustomerDto()->getLanguage(),
            'customer_ip' => $paymentAssemblerDto->getCustomerDto()->getIp(),
            'customer_email' => $paymentAssemblerDto->getCustomerDto()->getEmail(),
            'customer_name' => $paymentAssemblerDto->getCustomerDto()->getName(),
            'token_name' => $paymentAssemblerDto->getPaymentDto()->getToken(),
            'merchant_extra3' => $paymentAssemblerDto->getOrderDto()->getId()
        ];

        $payment_option = $paymentAssemblerDto->getPaymentDto()->getPaymentMethod();

//        if ($payment_option === "payfortmada") {
//            $params['payment_option'] = "MADA";
//            $params['merchant_extra1'] = "payfortmada";
//        } elseif ($payment_option == "payfortcredit") {
//            $params['merchant_extra1'] = "payfortcredit";
//        } elseif ($payment_option == "payfortinstallment") {
//            $params['installments'] = "HOSTED";
//            if (isset($requestData["issuer_code"])&&!empty($requestData["issuer_code"])) {
//                $params['issuer_code'] = $requestData["issuer_code"];
//            } else {
//                return ['message' => 'Issuer code is required!', "error"=>true, 'code' => 403,"not_installment"=>true];
//            }
//            if (isset($requestData["plan_code"]) && !empty($requestData["plan_code"])) {
//                $params['plan_code'] = $requestData["plan_code"];
//            } else {
//                return ['message' => 'Plan code is required!', 'error' => true, 'code' => 403,"not_installment"=>true];
//            }
//            $params['merchant_extra1'] = "payfortinstallment";
//        } else {
//            return ['message' => 'Invalid payment option.', 'error' => true, 'response_code' => 400];
//        }

        $params['signature'] = $this->signature->calculateSignature($params);

        $this->client->sendRequest($this->gatewayUrl, $params);

        return new PaymentTransactionDto($this->gatewayUrl, $params);
    }


    #[NoReturn] private function displayPayfortPage(string $gatewayUrl, array $params): void
    {
        echo "<html xmlns='https://www.w3.org/1999/xhtml'>\n<head></head>\n<body>\n";
        echo "<form action='$gatewayUrl' method='post' name='payfort_payment_form' id='payfort_payment_form'>\n";
        foreach ($params as $a => $b) {
            echo "\t<input  name='" . htmlentities($a) . "' value='" . htmlentities($b) . "'>\n";
        }
        echo '<input type="submit" id="submit">';

        echo "</form>\n</body>\n</html>";
        die;
    }

    /**
     * @return string
     */
    #[NoReturn] public function checkOrderStatus(): string
    {
        $params = [
            'query_command' => 'CHECK_STATUS',
            'access_code' => config('payfort.access_code'),
            'merchant_identifier' => config('payfort.merchant_identifier'),
            'merchant_reference' => '_88930291',
            'language' => 'en',
            "fort_id" => "169996200006833578"
        ];

        $params['signature'] = $this->signature->calculateSignature($params);

        $ch = curl_init($this->gatewayUrl);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        $response = curl_exec($ch);

        //$response_data = array();
        //parse_str($response, $response_data);
        curl_close($ch);

        $array_result = json_decode($response, TRUE);


        dd($array_result);
    }

    public function Pay(): bool|string
    {
        // TODO: Implement Pay() method.
    }

    public function processedCallback(array $data): CallbackDto
    {
        dd($data);
        if (empty($data)) {
            return FALSE;
        }

        $data['response_signature'] = $this->signature->calculateSignature($data, 'response');
    }


    /**
     * @throws \JsonException
     */
    public function getStatusByMerchantReference()
    {
        $params = [
            'query_command' => 'CHECK_STATUS',
            'merchant_identifier' => config('payfort.merchant_identifier'),
            'access_code' => config('payfort.access_code'),
            'merchant_reference' => '5000_1662841307',
            'language' => 'en'
        ];

        $params['signature'] = $this->signature->calculateSignature($params);

        return $this->client->sendRequest($this->gatewayUrl, $params);
    }

    public function getStatusByFortID($fort_id)
    {
        $params = [
            'query_command' => 'CHECK_STATUS',
            'merchant_identifier' => config('payfort.merchant_identifier'),
            'access_code' => config('payfort.access_code'),
            'fort_id' => $fort_id,
            'language' => 'en'
        ];

        $params['signature'] = $this->signature->calculateSignature($params);

        return $this->client->sendRequest($this->gatewayUrl, $params);
    }
}
