<?php

namespace Libs;

use Payment\Libs\Encrypter;

class EncrypterTest extends \TestCase
{
    /**
     * @throws \Exception
     */
    public function test_can_encrypt_and_decrypt_data(): void
    {
        $data = [
            "payment" => [
                "gateway" => "paymob",
                "method" => "banktransfer"
            ],
            "order" => [
                "reference_id" => "1234243-22Z",
                "amount" => "10000.00",
                "currency" => "EGP",
                "items" => [
                    [
                        "reference_id" => "5484-dd",
                        "sku" => "111-222abc",
                        "title" => "string",
                        "quantity" => 1,
                        "price" => "0.00",
                        "category" => "string"
                    ],
                    [
                        "reference_id" => "eed5",
                        "sku" => "111-222abc",
                        "title" => "string",
                        "quantity" => 1,
                        "price" => "0.00",
                        "category" => "string"
                    ]
                ]
            ],
            "customer" => [
                "id" => 1,
                "ip" => "127.0.0.1",
                "phone" => "500000001",
                "name" => "Mohamed Emad",
                "email" => "successful.payment@tabby.ai",
                "language" => "en"
            ],
            "shipping_address" => [
                "country" => "saudi arabia",
                "city" => "cairo",
                "address" => "Maadi",
                "zip" => "1234"
            ]
        ];

        $encrypter = new Encrypter(config('encryption.key'), 'aes-256-gcm');
        $encryptedData = $encrypter->encrypt($data);
        $decryptedData = $encrypter->decrypt($encryptedData);

        $this->assertStringContainsString('eyJpdiI6', $encryptedData);
        $this->assertIsArray($decryptedData);

        $resp = $this->call('get', 'api/pay',[]);
    }
}
