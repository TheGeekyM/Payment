# Homzmart Payment

[![Build Status](https://travis-ci.org/laravel/lumen-framework.svg)](https://travis-ci.org/laravel/lumen-framework)
[![License](https://img.shields.io/packagist/l/laravel/framework)](https://packagist.org/packages/laravel/lumen-framework)

## How To Run The Project

``` bash
$ git clone project-url
$ composer install
$ cp .env.example .env
$ php -S localhost:8080 -t public
```

# List of available drivers
- [payfort](https://paymentservices.amazon.com/) :expressionless:
- [paymob](https://paymob.com/) :dancer:
- [tabby](https://tabby.ai/) :dancer:
- [tamara](https://docs.tamara.co/) :bangbang:

## How To Use
* Add your payment in `App\Http\Enums\PaymentGateways Enum`
* Add your payment domain model in `App\Http\Services\[payment-dir]` with at minimum one class ex: `{Payment}Startegy.php` implementing `App\Http\Contracts\PayFortStrategy`
* Add your payment config in config dir

## Test

``` bash
$ vendor/bin/phpunit
```

## License

The Payment is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
