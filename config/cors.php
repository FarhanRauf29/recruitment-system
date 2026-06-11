<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Paths that should be CORS enabled
    |--------------------------------------------------------------------------
    |
    | You can enable CORS on specific paths or groups of paths.
    | Example: 'api/*' will enable CORS on all API routes.
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    /*
    |--------------------------------------------------------------------------
    | Allowed HTTP methods
    |--------------------------------------------------------------------------
    |
    | You can set this to ['*'] to allow all HTTP verbs or specify your own.
    |
    */

    'allowed_methods' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Allowed origins
    |--------------------------------------------------------------------------
    |
    | Specify the domains or IPs that are allowed to access your API.
    | Use ['*'] to allow all origins (not recommended for production).
    |
    */

    'allowed_origins' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Allowed origin patterns
    |--------------------------------------------------------------------------
    |
    | Patterns to match allowed origins (wildcards).
    |
    */

    'allowed_origins_patterns' => [],

    /*
    |--------------------------------------------------------------------------
    | Allowed headers
    |--------------------------------------------------------------------------
    |
    | Specify the allowed HTTP headers.
    | Use ['*'] to allow all headers.
    |
    */

    'allowed_headers' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Exposed headers
    |--------------------------------------------------------------------------
    |
    | Specify the headers that should be exposed to the browser.
    |
    */

    'exposed_headers' => [],

    /*
    |--------------------------------------------------------------------------
    | Max age
    |--------------------------------------------------------------------------
    |
    | How long (in seconds) the results of a preflight request can be cached.
    |
    */

    'max_age' => 0,

    /*
    |--------------------------------------------------------------------------
    | Supports credentials
    |--------------------------------------------------------------------------
    |
    | Whether to support cookies and authentication headers.
    |
    */

    'supports_credentials' => false,

];
