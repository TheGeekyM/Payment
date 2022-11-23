<?php

namespace App\Listeners;

use App\Events\OrderCreatedEvent;
use Payment\Contracts\EncrypterInterface;
use Payment\Contracts\HttpClientInterface;

class CallBackFrontServiceListener
{
    private HttpClientInterface $httpClient;
    private EncrypterInterface $encrypter;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(HttpClientInterface $httpClient, EncrypterInterface $encrypter)
    {
        $this->httpClient = $httpClient;
        $this->encrypter = $encrypter;
    }

    /**
     * @param OrderCreatedEvent $event
     * @return void
     */
    public function handle(OrderCreatedEvent $event)
    {
        $encryptedData = $this->encrypter->encrypt($event->payment->toArray());
        $resp = $this->httpClient->sendRequest(config('payment.callback_url'), ['data' => $encryptedData]);
        dd($resp);
    }
}
