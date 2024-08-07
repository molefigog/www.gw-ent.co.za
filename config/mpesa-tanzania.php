<?php

return [
    'environment' => env('MPESA_ENVIRONMENT', 'sandbox'),
    'api_key' => env('MPESA_API_KEY', ''),
    'public_key' => env('MPESA_PUBLIC_KEY', ''),
    'base_endpoint' => env('MPESA_BASE_ENDPOINT', 'https://openapi.m-pesa.com'), // Adjust this based on your environment
    'country' => env('MPESA_COUNTRY', 'TZ'),
    'currency' => env('MPESA_CURRENCY', 'TZS'),
];
