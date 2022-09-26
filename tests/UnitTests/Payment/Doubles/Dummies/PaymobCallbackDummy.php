<?php

namespace UnitTests\Payment\Doubles\Dummies;

class PaymobCallbackDummy
{
    public static function successCallbackData(): array
    {
        $callback = self::callbackData();
        $callback['hmac'] = 'd3e769478d112b4e38e03c407cf2d26acd5db514bee7cd97940b55abc6e1b91e73a962a1464284647981b14b140915bccf980d900a7e235a462ae508b77616ae';
        $callback['obj']['success'] = true;
        return $callback;
    }

    public static function voidCallbackData(): array
    {
        $callback = self::callbackData();
        $callback['hmac'] = 'ab884ebec619a50e2a89a00de8bb2753531985e2eeb85c08ce6654a0a615cd911fc8a817770ad0059b7632ce8ce77914c664bb84a9822c8257ff7d4a4ac53a88';
        $callback['obj']['success'] = true;
        $callback['obj']['is_voided'] = true;
        return $callback;
    }

    public static function refundCallbackData(): array
    {
        $callback = self::callbackData();
        $callback['hmac'] = 'a15926174f31499d06099072d08e6d12957db57a5cce3a5c2db6b42548cfc6cf0bdb82066ec7f89a6299b95f53f606753d046a153f801344355954b70841bb6d';
        $callback['obj']['success'] = true;
        $callback['obj']['is_refunded'] = true;
        return $callback;
    }

    public static function callbackData(bool $validHmac = TRUE): array
    {
        return [
            'hmac' => $validHmac ?
                '2429cd290ab5d4fb4eb0b78fda1a732f672d278af11410b0778a77ac0d07960da8ab24bec359a4df2cf602c705b35e07543b3017dfeca08fec01082449cfb1da' : 'dummy',
            "type" => "TRANSACTION",
            "issuer_bank" => NULL,
            "transaction_processed_callback_responses" => "",
            "obj" => [
                "id" => 58576976,
                "pending" => FALSE,
                "amount_cents" => 10000,
                "success" => FALSE,
                "is_auth" => FALSE,
                "is_capture" => FALSE,
                "is_standalone_payment" => TRUE,
                "is_voided" => FALSE,
                "is_refunded" => FALSE,
                "is_3d_secure" => TRUE,
                "integration_id" => 2732714,
                "profile_id" => 310176,
                "has_parent_transaction" => FALSE,
                "order" => [
                    "id" => 69771203,
                    "created_at" => "2022-09-21T17:59:42.179989",
                    "delivery_needed" => FALSE,
                    "merchant" => [
                        "id" => 310176,
                        "created_at" => "2022-08-13T13:42:39.031390",
                        "phones" => [
                            "+201033633939"
                        ],
                        "company_emails" => [
                            "thejeeky@gmail.com"
                        ],
                        "company_name" => "Personal",
                        "state" => "",
                        "country" => "EGY",
                        "city" => "Cairo",
                        "postal_code" => "",
                        "street" => ""
                    ],
                    "collector" => NULL,
                    "amount_cents" => 10000,
                    "shipping_data" => [
                        "id" => 36943990,
                        "first_name" => "Mohamed Emad",
                        "last_name" => "Mohamed Emad",
                        "street" => "Maadi",
                        "building" => "NA",
                        "floor" => "NA",
                        "apartment" => "NA",
                        "city" => "cairo",
                        "state" => "NA",
                        "country" => "saudi arabia",
                        "email" => "successful.payment@tabby.ai",
                        "phone_number" => "500000001",
                        "postal_code" => "1234",
                        "extra_description" => "",
                        "shipping_method" => "UNK",
                        "order_id" => 69771203,
                        "order" => 69771203
                    ],
                    "currency" => "EGP",
                    "is_payment_locked" => FALSE,
                    "is_return" => FALSE,
                    "is_cancel" => FALSE,
                    "is_returned" => FALSE,
                    "is_canceled" => FALSE,
                    "merchant_order_id" => "1234243-22Z",
                    "wallet_notification" => NULL,
                    "paid_amount_cents" => 0,
                    "notify_user_with_email" => TRUE,
                    "items" => [
                        [
                            "name" => "string",
                            "description" => "",
                            "amount_cents" => 0,
                            "quantity" => 1
                        ],
                        [
                            "name" => "string",
                            "description" => "",
                            "amount_cents" => 0,
                            "quantity" => 1
                        ]
                    ],
                    "order_url" => "https://accept.paymobsolutions.com/standalone?ref=i_LRR2UWh0L1dPUDlVU1JCNW9Hck1JMlROdz09X0pqUmlvYnB5T2NDWDJ4UTJMMXdaUEE9PQ",
                    "commission_fees" => 0,
                    "delivery_fees_cents" => 0,
                    "delivery_vat_cents" => 0,
                    "payment_method" => "tbc",
                    "merchant_staff_tag" => NULL,
                    "api_source" => "OTHER",
                    "data" => []
                ],
                "created_at" => "2022-09-21T18:00:19.891924",
                "transaction_processed_callback_responses" => [],
                "currency" => "EGP",
                "source_data" => [
                    "type" => "card",
                    "tenure" => NULL,
                    "sub_type" => "MasterCard",
                    "pan" => "2346"
                ],
                "api_source" => "IFRAME",
                "terminal_id" => NULL,
                "merchant_commission" => 0,
                "installment" => NULL,
                "is_void" => FALSE,
                "is_refund" => FALSE,
                "data" => [
                    "authorised_amount" => 0.0,
                    "authorize_id" => NULL,
                    "refunded_amount" => 0.0,
                    "amount" => 10000.0,
                    "captured_amount" => 0.0,
                    "created_at" => "2022-09-21T16:00:33.608484",
                    "txn_response_code" => "BLOCKED",
                    "merchant_txn_ref" => "58576976",
                    "message" => "BLOCKED",
                    "avs_result_code" => "",
                    "merchant" => "TEST770000",
                    "currency" => "EGP",
                    "gateway_integration_pk" => 2732714,
                    "order_info" => "69771203",
                    "secure_hash" => "",
                    "transaction_no" => "",
                    "avs_acq_response_code" => "",
                    "migs_result" => "FAILURE",
                    "batch_no" => "",
                    "card_num" => "512345xxxxxx2346",
                    "receipt_no" => "",
                    "acq_response_code" => "",
                    "klass" => "MigsPayment",
                    "card_type" => "MASTERCARD",
                    "migs_transaction" => [
                        "id" => "58576976",
                        "currency" => "EGP",
                        "acquirer" => [
                            "id" => "CIB_S2I",
                            "merchantId" => "770000"
                        ],
                        "amount" => 100.0,
                        "type" => "PAYMENT",
                        "source" => "INTERNET",
                        "frequency" => "SINGLE"
                    ],
                    "migs_order" => [
                        "id" => "69771203",
                        "status" => "FAILED",
                        "currency" => "EGP",
                        "amount" => 100.0,
                        "acceptPartialAmount" => FALSE,
                        "totalRefundedAmount" => 0.0,
                        "totalAuthorizedAmount" => 0.0,
                        "creationTime" => "2022-09-21T16:00:33.247Z",
                        "totalCapturedAmount" => 0.0
                    ]
                ],
                "is_hidden" => FALSE,
                "payment_key_claims" => [
                    "amount_cents" => 10000,
                    "extra" => [],
                    "lock_order_when_paid" => FALSE,
                    "integration_id" => 2732714,
                    "pmk_ip" => "105.37.74.96",
                    "currency" => "EGP",
                    "order_id" => 69771203,
                    "billing_data" => [
                        "country" => "saudi arabia",
                        "apartment" => "NA",
                        "building" => "NA",
                        "first_name" => "Mohamed Emad",
                        "email" => "successful.payment@tabby.ai",
                        "city" => "cairo",
                        "floor" => "NA",
                        "postal_code" => "1234",
                        "extra_description" => "NA",
                        "phone_number" => "500000001",
                        "street" => "Maadi",
                        "state" => "NA",
                        "last_name" => "Mohamed Emad"
                    ],
                    "exp" => 1663779583,
                    "user_id" => 541032
                ],
                "error_occured" => FALSE,
                "is_live" => FALSE,
                "other_endpoint_reference" => NULL,
                "refunded_amount_cents" => 0,
                "source_id" => -1,
                "is_captured" => FALSE,
                "captured_amount" => 0,
                "merchant_staff_tag" => NULL,
                "updated_at" => "2022-09-21T18:00:34.066055",
                "owner" => 541032,
                "parent_transaction" => NULL
            ]
        ];
    }
}
