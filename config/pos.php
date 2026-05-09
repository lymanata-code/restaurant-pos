<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Restaurant POS configuration
    |--------------------------------------------------------------------------
    */

    'locales' => ['en', 'km'],

    'currency' => env('POS_CURRENCY', 'USD'),
    'currency_symbol' => env('POS_CURRENCY_SYMBOL', '$'),
    'date_format' => env('POS_DATE_FORMAT', 'Y-m-d'),
    'datetime_format' => env('POS_DATETIME_FORMAT', 'Y-m-d H:i'),
];
