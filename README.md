<div align="center">
  <img alt="Payment logo" src="https://w7.pngwing.com/pngs/519/586/png-transparent-split-payment-credit-card-computer-icons-payment-system-credit-card-angle-hand-payment.png" width="600px" />

# Homzmart Payment

[![Build Status](https://travis-ci.org/laravel/lumen-framework.svg)]()
[![Docker Build Status](https://img.shields.io/docker/build/redocly/redoc.svg)]()
[![License](https://img.shields.io/packagist/l/laravel/framework)](https://packagist.org/packages/laravel/lumen-framework)
</div>

## Payment Model

![Payment demo](https://github.com/geekyHomz/payment/blob/main/modeling.png)

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
- [tabby](https://tabby.ai/) :dancer: Tabby is split in 4 payment method, Which means you can buy anything and pay it as
  a 4-month installment.
- [tamara](https://docs.tamara.co/) :dancer:  is a payment method that gives you the ability to split your order
  payment on several payments with zero interest.

## How To Use

* Add your payment in `Payment\Enums\PaymentGateways Enum`
* Add your payment in `Payment\Factories\PaymentFactory`
* Add your payment domain model in `App\Http\Services\[payment-dir]` with at minimum one class
  ex: `{Payment}Startegy.php` implementing `App\Http\Contracts\PayFortStrategy`
* Add your payment config in config dir

###### Enums

| Gatewa Name    |   Payment Method	   | 	order statuse	 
|:---------------|:-------------------:| :----------------: 
| paymob         |        visa         |    created    
| payfort        |       credit        |  succeeded   
| tabby          |        mada         |   captured     
| tamara         |    banktransfer     |   voided   
| -------------- |        valu         |  refunded 
| -------------- |       vfcash        |   failed  
| -------------- |   cibinstallment    | not_secured 
| -------------- |    bminstallment    | -------------- 
| -------------- |    ShahryPaymob     | -------------- 
| -------------- |     SymplPaymob     | -------------- 
| -------------- |   SouhoolaPaymob    | -------------- 
| -------------- |     AudiPaymob      | -------------- 
| -------------- |      BDCPaymob      | -------------- 
| -------------- |      NBKPaymob      | -------------- 
| -------------- |      NBDPaymob      | -------------- 
| -------------- |    MashreqPaymob    | -------------- 
| -------------- |   AllBanksPaymob    | -------------- 
| -------------- | PAY_BY_INSTALMENTS  | -------------- 

<strong>Pay Request</strong>

```bash
The optimal request that will be accepted with all payments gateways

GET /api/pay HTTP/1.1
Host: xxxx.xxxx
Accept: application/json
Content-Type: application/json
{
    "payment": {
        "gateway": "gateway_name", //see gateway name in enums table
        "method": "payment_method_name",//see gateway method in enums table
    },
    "order": {
        "id": 1420000,
        "reference_id": "123423-22Z",
        "amount": "1000",
        "currency": "SAR",
        "items": [
            {
                "id": 5,
                "title": "string",
                "quantity": 1,
                "unit_price": "0.00",
                "category": "string"
            }
        ]
    },
    "customer": {
        "id": 1,
        "ip": "127.0.0.1",
        "phone": "500000001",
        "name": "Mohamed Emad",
        "email": "email@domain.com",
        "language": "en"
    },
    "shipping_address": {
        "country": "egypt",
        "city": "cairo",
        "address": "Maadi",
        "zip": "1234"
    }
}
```

<strong>Response 200</strong>

```bash
{
    "data": {
        "url": "iframe_url",
        "params": {
        }
    }
}
```

<strong>Callback Response 200</strong>

```bash
{
  "data": {
    "status": "payment_status", //see order status in enums table
    "reference_id": "123423-22Z",
    "order_id": "47ebdba5-4ae8-4a8f-acce-a04ff1467e63",
    "data": {}
    }
}
```

## Error Responses

>500: Something bad happened. We're notified.

>```json{
> {
>    "message": "string."
> }
 
> 400: failure.
> ```json{ 
> {
>    "message": "string."
> }

> 422: validation error. One of the required fields is missing or request is not formatted correctly.
> ```json{ 
> {
>    "message": "The given data was invalid.",
>    "errors": [
>         "The xxxx field is required.",
>         "The xxxx field is required.",
>    ]
> }
> ```

## Test

``` bash
$ vendor/bin/phpunit
```

## License

The Payment is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
