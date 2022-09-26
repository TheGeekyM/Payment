<?php

namespace Payment\Services\Payfort;

class Signature
{
    public function calculateSignature(array $arrData, string $signType = 'request'): string
    {
        $shaString = '';
        ksort($arrData);

        foreach ($arrData as $key => $value) {
            $shaString .= "$key=$value";
        }

        $shaString = $signType === 'request' ?
            config('payfort.sha_request_phrase') . $shaString . config('payfort.sha_request_phrase') :
            config('payfort.sha_response_phrase') . $shaString . config('payfort.sha_response_phrase');

        return hash(config('payfort.sha_type'), $shaString);
    }
}
