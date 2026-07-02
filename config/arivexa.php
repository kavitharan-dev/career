<?php

return [

    'sms' => [
        'enabled' => env('SMS_ENABLED', false),
        'driver' => env('SMS_DRIVER', 'textlk'),
        'default_country_code' => env('SMS_DEFAULT_COUNTRY_CODE', '94'),
        'timezone' => env('APP_TIMEZONE', 'Asia/Colombo'),
    ],

];
