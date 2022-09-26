<?php

namespace App\Providers;

use Payment\Contracts\EncrypterInterface;
use Payment\Contracts\PaymentRepositoryInterface;
use Payment\Contracts\PaymentRequestBuilderInterface;
use Illuminate\Support\ServiceProvider;
use Payment\Libs\Encrypter;
use Payment\Repository\PaymentRepository;
use Payment\Services\PaymentRequestBuilder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(PaymentRequestBuilderInterface::class, PaymentRequestBuilder::class);
        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);

        $this->app->bind(EncrypterInterface::class, function($app){
            return new Encrypter(config('encryption.key'), config('encryption.algo'));
        });
    }
}
