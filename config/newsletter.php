<?php

use Spatie\Newsletter\Drivers\MailChimpDriver;

return [

    'driver' => MailChimpDriver::class,

    'driver_arguments' => [
        'api_key' => env('NEWSLETTER_API_KEY'),
        'endpoint' => env('NEWSLETTER_ENDPOINT'),
    ],

    'default_list_name' => 'subscribers',

    'lists' => [
        'subscribers' => [
            'id' => env('NEWSLETTER_LIST_ID'),
        ],
    ],

];
