<div align="center">
  <img alt="Payment logo" src="https://w7.pngwing.com/pngs/519/586/png-transparent-split-payment-credit-card-computer-icons-payment-system-credit-card-angle-hand-payment.png" width="600px" />

# Homzmart Payment

[![Build Status](https://travis-ci.org/laravel/lumen-framework.svg)]()
[![Docker Build Status](https://img.shields.io/docker/build/redocly/redoc.svg)]()
[![License](https://img.shields.io/packagist/l/laravel/framework)](https://packagist.org/packages/laravel/lumen-framework)
</div>

# Standalone Payment

![Payment demo](https://raw.githubusercontent.com/geekyHomz/payment/main/modeling.png?token=GHSAT0AAAAAAB6GLQLEGIUGRAWHHRUTW6CIY62FNNA)

## How To Run The Project

``` bash
$ git clone project-url
$ composer install
$ cp .env.example .env
$ php -S localhost:8080 -t public
```

# List of available drivers

- [ ] [payfort](https://paymentservices.amazon.com/) :expressionless:
- [x] [paymob](https://paymob.com/) :dancer:
- [x] [tabby](https://tabby.ai/) :dancer: Tabby is split in 4 payment method, Which means you can buy anything and pay
  it as
  a 4-month installment.
- [x] [tamara](https://docs.tamara.co/) :dancer:  is a payment method that gives you the ability to split your order
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

###### Note: We have two requests to send one in the local environment to make it easy to test and the second it has to be encrypted, and it works in any other environment.

The optimal request that will be accepted with all payments gateways in local environments:

```bash
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
        "reference_id": "123423-22Z",
        "amount": "1000",
        "currency": "SAR",
      "items": [
            {
                "reference_id": "5484-dd",
                "sku": "111-222abc",
                "title": "string",
                "quantity": 1,
                "price": "0.00",
                "category": "string"
            },
                        {
                "reference_id": "eed5",
                "sku": "111-222abc",
                "title": "string",
                "quantity": 1,
                "price": "0.00",
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

An example of encrypted request that should work on any other environment except local

```bash
GET /api/pay HTTP/1.1
Host: xxxx.xxxx
Accept: application/json
Content-Type: application/json

{
    "data": "eyJpdiI6Im5uSGtMV1hXdmNXaGlQTTgiLCJ2YWx1ZSI6ImFtb0RwelJ0bXpsSW8vVWpCYTVRMEoxOUJjQ0N5Y0Zza05mTHZuRy9BQy9OS2NSUGZJdWFtbTRPWUxudk1PYzlVWjEycnRJbndmYUZyc1d5VVBOS0tLNGJWdzN6QkdJQk50d0hkRTVTU09tQ1RnRGowYjMzVW1kdmYrNi9Wd1NwWk9NTjdxTFlTZ1R1TGNaSWZ2a2pNUVJnVUM0d2M4TER6S2pkUWhrazFpVWw5Wi9vYitOc3NiTURuRDVsSS9XdDdJZzh3Y3RLWWZCNnhEbEx3K0lPc08vQUZlN2VtZ2xPRHUrTGNHNWJwbzFXTVovdHA2alBXYmtmNVFtUk1nTWFTa25zb1F1YlpQSU1NbXdCeURTUUtkRzNDL212T0VpSzJZZkNMaWNaTmFZZnREV25ySGNCZHA4WDMvU1lNMUJVTlZlWTB3TGJBREdydHhNOWN0cm1EY25CSWw1MGpxY05sZFFiSkMxKzRUNEJvZ1FRcmRZeGhCUFlsVDlQYk1sbmo4cU1qc2dHTk5pY1ptQXNIbjZJd0hmN3krbG8wZkh2NHdraEVEZFpIOVpRNGxOMkVLcG05WHZmMkM1NDNrcUpKZS9ScUFtTWh1cW16T1hTbXFIVjlMZCtsYnliQ1BUTWhwYjJIZVZqMGsrbUw5R2lmQytuWTFCaE84U3oxK1ZsdENlNHpOTm5nSGlVYnFYclJrOWo3UDFsQTdLUHlUcFptNzExeGFYek1rTndpMGZMSnlRUUpjd2lHQi9lVjlsYzJUd09Ndm50NC9scCsrOGFSd3Bkc1IrRkFlbEN6T3pxVXFBVG5lZTBON2kwUHdScDV1QStjamJ1aElkZ0p1Q0l3WlFDMmQ2RERSWXo5QXlIWTlVK01UUkQ1aHlIYzdCb3BhNWdIYzNiOS9FUnFLbkY4QWVWR2xUYWdvSXYyakVXUVJYUGVTZjBxVDVXbCs4SDVvZ1VvWEZMVlZpY1dNNW1jWmhPcVFjRnhYMVpGOWZ4QVgyVkpMQnQzSUhBZ0xGOHVaL0dXMlVmdTV6aWthVXNnNDlzTnVFMFBqNjRCRDBXQ1NzQkxQQzFZTW5xQklsNUp3MDl1bjBtQ1JONXdSYWpFckRRb2dub09mbC9USkxzSFFueFJBelhJT2lEMFVjN1I5STM2WkZTWk8wdWZUTG1pbzNmMmNiQXFCcWtMSUdmM09USkNnOElUUTBXbXUvQzNvall4RlRVNk5qaE9veGJWQ3ZBMHh4cS9Hb1hCb2xTZFRoZ3FtUVFaVS9MTDVPUjY2d0JWNENsN3RlYUFRaVZyZkhSajdvT3Y3VXJVcVowODBUK3IxVmMwaEg0cE91eFVydFFTOWR5SWV1dWxqcitZdjlPUTBaVUswRHZHZWc4MEViWDMrdjJZdHpOcVU2N1hQRUJ0K2w0dmo1ZWdyVms4a1NCbitOc1VjL3hIYzZOdWRkRzhINTRYYU16dWNBb3Z0WmtYa0s1MGw0Q3YwdVBKNks5dFgzZ3A4WG1YSGR6ckF0Z0ppMDZuaWd6OE1vL3RIV3NRZz09IiwibWFjIjoiIiwidGFnIjoibzdacnErTVdCdUxEN1VLYkp0dmU4dz09In0="
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

> 500: Something bad happened. We're notified.

> ```json{
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

## Request Encryption
###### If you want to call this service you should send the request encrypted, otherwise you will have an exception.
you have to use the same encrypter algorithm and the same key to be able to decrypt the data. Try to do these steps to have a successful encrypted request:
* Implement this interface [EncrypterInterface.php](payment/Contracts/EncrypterInterface.php) 
* Use this [Encrypter.php](payment/Libs/Encrypter.php) ready implemented service to decrypt the data
* Make an object from `Encrypter class` and pass the same algorithm and key in our service to be able to decrypt the encrypted request. 
* Finally, send the encrypted data by your http client. 

## Test

``` bash
$ vendor/bin/phpunit
```

## License

The Payment is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
