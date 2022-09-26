<?php

namespace UnitTests\Payment\Libs;

use Exception;
use Payment\Libs\Encrypter;
use UnitTests\Payment\Doubles\Dummies\PaymentRequest;

class EncrypterTest extends \TestCase
{
    /**
     * @throws Exception
     */
    public function test_can_encrypt_and_decrypt_data(): void
    {
        $data = PaymentRequest::getOptimalRequest('paymob');

        $encrypter = new Encrypter(config('encryption.key'), 'aes-256-gcm');
        $encryptedData = $encrypter->encrypt($data);
        $decryptedData = $encrypter->decrypt($encryptedData);

        $this->assertStringContainsString('eyJpdiI6', $encryptedData);
        $this->assertIsArray($decryptedData);

        $resp = $this->call('get', 'api/pay',[]);
    }
}
