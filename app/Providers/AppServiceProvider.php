<?php

namespace App\Providers;

use App\Http\Contracts\PaymentRepositoryInterface;
use App\Http\Contracts\PaymentRequestBuilderInterface;
use App\Http\Repository\PaymentRepository;
use App\Http\Services\PaymentRequestBuilder;
use Illuminate\Support\ServiceProvider;

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
