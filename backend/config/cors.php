<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://localhost:5174', 'http://localhost:4000', 'http://127.0.0.1:5174', 'http://127.0.0.1:4000'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => ['*'],
    'max_age' => 86400,  // Cache preflight for 24 hours
    'supports_credentials' => true,
];
