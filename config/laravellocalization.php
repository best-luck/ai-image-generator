<?php

return [

    'supportedLocales' => [
        'en' => ['name' => 'en'],
    ],

    'useAcceptLanguageHeader' => false,

    'hideDefaultLocaleInURL' => false,

    'localesOrder' => [],

    'localesMapping' => [],

    'utf8suffix' => env('LARAVELLOCALIZATION_UTF8SUFFIX', '.UTF-8'),

    'urlsIgnored' => ['/admin'],

    'httpMethodsIgnored' => ['POST', 'PUT', 'PATCH', 'DELETE'],
];
