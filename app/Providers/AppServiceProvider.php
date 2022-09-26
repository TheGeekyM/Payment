<?php

namespace App\Providers;

use Payment\Contracts\PaymentRepositoryInterface;
use Payment\Contracts\PaymentRequestBuilderInterface;
use Illuminate\Support\ServiceProvider;
use Payment\Repository\PaymentRepository;
use Payment\Services\PaymentRequestBuilder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PaymentRequestBuilderInterface::class, PaymentRequestBuilder::class);
        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);
    }
}
