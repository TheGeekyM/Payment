<?php

namespace App\Http\Entities;

class MerchantUrl
{
    /**
     * @var string
     */
    private string $successUrl;

    /**
     * @var string
     */
    private string $failureUrl;

    /**
     * @var string
     */
    private string $cancelUrl;

    /**
     * @var string
     */
    private string $notificationUrl;

    public function setSuccessUrl(string $successUrl): void
    {
        $this->successUrl = $successUrl;
    }

    public function setFailureUrl(string $failureUrl): void
    {
        $this->failureUrl = $failureUrl;
    }

    public function setCancelUrl(string $cancelUrl): void
    {
        $this->cancelUrl = $cancelUrl;
    }

    public function setNotificationUrl(string $notificationUrl): void
    {
        $this->notificationUrl = $notificationUrl;
    }

    /**
     * @return string
     */
    public function getSuccessUrl(): string
    {
        return $this->successUrl;
    }

    /**
     * @return string
     */
    public function getFailureUrl(): string
    {
        return $this->failureUrl;
    }

    /**
     * @return string
     */
    public function getCancelUrl(): string
    {
        return $this->cancelUrl;
    }

    /**
     * @return string
     */
    public function getNotificationUrl(): string
    {
        return $this->notificationUrl;
    }
}
