<?php

use afiqiqmal\ESolat\ESolat;
use Carbon\Carbon;

define('SOLAT_METHOD_POST', 'POST');
define('SOLAT_METHOD_GET', 'GET');
define('SOLAT_METHOD_PATCH', 'PATCH');
define('SOLAT_METHOD_DELETE', 'DELETE');
define('SOLAT_LANGUAGE_EN', 'en');

if (! function_exists('esolat')) {
    function esolat()
    {
        return new ESolat();
    }
}

if (! function_exists('trim_spaces')) {
    function trim_spaces($text)
    {
        return trim(preg_replace('/\s+/', ' ', $text));
    }
}

if (! function_exists('error_response')) {

    function error_response($message = "Something Went Wrong")
    {
        http_response_code(400);
        return [
            'code' => 400,
            'error' => true,
            'message' => $message
        ];
    }
}

if (! function_exists('solat_response')) {

    function solat_response($result, $status_code = 200)
    {
        http_response_code($status_code);
        return [
            'code' => $status_code,
            'error' => $status_code >= 300 ? true : false,
            'data' => $result,
            'generated_at' => Carbon::now()->toDateTimeString(),
            'footer' => [
                'source' => 'http://www.e-solat.gov.my/web/',
                'host' => 'JAKIM',
                'developer' => [
                    "name" => "Hafiq",
                    "homepage" => "https://github.com/afiqiqmal"
                ]
            ]
        ];
    }
}