<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Content Security Policy (CSP)
    |--------------------------------------------------------------------------
    |
    | When report_only is true, Content-Security-Policy-Report-Only is sent,
    | allowing observation of violations without blocking resources.
    | When false, the policy is enforced.
    |
    */
    'csp' => [
        'report_only' => (bool) env('CSP_REPORT_ONLY', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | HTTP Strict Transport Security (HSTS)
    |--------------------------------------------------------------------------
    |
    | RFC 6797: Strict-Transport-Security must only be sent over HTTPS.
    | For subdomain deployments on shared cPanel hosting, include_subdomains
    | and preload must remain disabled by default to prevent affecting sibling
    | subdomains or the apex domain.
    |
    */
    'hsts' => [
        'enabled' => (bool) env('HSTS_ENABLED', true),
        'max_age' => (int) env('HSTS_MAX_AGE', 31536000),
        'include_subdomains' => (bool) env('HSTS_INCLUDE_SUBDOMAINS', false),
        'preload' => (bool) env('HSTS_PRELOAD', false),
    ],
];
