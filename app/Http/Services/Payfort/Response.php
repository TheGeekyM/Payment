<?php

namespace App\Http\Services\Payfort;

use App\Http\Contracts\PaymentStrategyInterface;
use JetBrains\PhpStorm\NoReturn;

class Response
{
   public function processResponse(array $data)
   {
           $params        = $data;
           unset($params['r'], $params['signature'], $params['integration_type']);
           $success       = true;
           $reason        = '';

           if ($$data['response_signature'] != $data['signature']) {
               $success = false;
               $reason  = 'Invalid signature.';
           }
           else {
               $response_code    = $params['response_code'];
               $response_message = $params['response_message'];
               if (substr($response_code, 2) != '000') {
                   $success = false;
                   $reason  = $response_message;
               }
           }

       if(!$success) {
           $p = $params;
           $p['error_msg'] = $reason;
           return false;
       }

       return true;
   }
}
