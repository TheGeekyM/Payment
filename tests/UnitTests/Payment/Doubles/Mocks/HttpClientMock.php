<?php

namespace UnitTests\Payment\Doubles\Mocks;

use Payment\Contracts\HttpClientInterface;

class HttpClientMock extends \TestCase
{
    public function getMock(): HttpClientInterface
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('sendRequest')->willReturn(true);
        return $httpClient;
    }
}
