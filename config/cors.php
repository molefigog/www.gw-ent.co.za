<?php

// return [

//     'paths' => ['api/*', 'sanctum/csrf-cookie'],

//     'allowed_methods' => ['*'],

//     'allowed_origins' => ['*'],

//     'allowed_origins_patterns' => [],

//     'allowed_headers' => ['*'],

//     'exposed_headers' => [],

//     'max_age' => 0,

//     'supports_credentials' => false,

// ];
return [
    'paths' => ['api/*', 'storage/*', '/*'], // This ensures CORS is applied to API and storage routes
    'allowed_methods' => ['*'], // Allow any HTTP method (GET, POST, PUT, DELETE)
    'allowed_origins' => ['*', 'http://localhost:9000', 'https://www.gw-ent.co.za'], // Allow local and production origins
    'allowed_headers' => ['*'], // Allow any headers
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
