<?php

namespace IntegrationTests\Http\Controllers;


use Payment\Libs\Encrypter;

class PaymentControllerTest extends \TestCase
{
    public function test_get_validation_error_with_errors_as_an_array(): void
    {
        $resp = $this->call('get', 'api/pay', []);

        $resp = $resp->json();
        $this->assertStringContainsString($resp['message'], 'The given data was invalid.');
        $this->assertArrayHasKey('errors', $resp);
    }
}
